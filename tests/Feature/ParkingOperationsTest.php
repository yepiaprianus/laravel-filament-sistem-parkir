<?php

namespace Tests\Feature;

use App\Models\AreaParkir;
use App\Models\Tarif;
use App\Models\Transaksi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParkingOperationsTest extends TestCase
{
    use RefreshDatabase;

    protected User $petugas;
    protected AreaParkir $area;
    protected Tarif $tarifMotor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->petugas = User::where('role', 'petugas')->first();
        $this->area = AreaParkir::first();
        $this->tarifMotor = Tarif::where('jenis_kendaraan', 'motor')->first();
    }

    public function test_petugas_can_view_masuk_form_with_registered_vehicles(): void
    {
        $response = $this->actingAs($this->petugas)->get('/petugas/transaksi/masuk');
        $response->assertStatus(200);
        $response->assertViewHas('registeredVehicles');
        $response->assertViewHas('areas');
        $response->assertSee('Identifikasi Kendaraan');
        $response->assertSee('Pilih Dari Master Kendaraan (Cepat)');
    }

    public function test_petugas_can_process_vehicle_entry(): void
    {
        $response = $this->actingAs($this->petugas)->post('/petugas/transaksi/masuk', [
            'plat_nomor' => 'B 9999 XYZ',
            'jenis_kendaraan' => 'motor',
            'id_area' => $this->area->id_area,
            'warna' => 'Hitam',
            'pemilik' => 'Pak Budi',
        ]);

        $response->assertRedirect(route('petugas.transaksi.index', ['status' => 'masuk']));
        $this->assertDatabaseHas('tb_kendaraan', ['plat_nomor' => 'B 9999 XYZ']);
        $this->assertDatabaseHas('tb_transaksi', [
            'id_area' => $this->area->id_area,
            'status' => 'masuk',
        ]);

        $this->area->refresh();
        $this->assertEquals(1, $this->area->terisi);
    }

    public function test_cannot_enter_same_vehicle_twice_when_already_parked(): void
    {
        // First entry
        $this->actingAs($this->petugas)->post('/petugas/transaksi/masuk', [
            'plat_nomor' => 'B 1111 ABC',
            'jenis_kendaraan' => 'motor',
            'id_area' => $this->area->id_area,
        ]);

        // Second entry attempt
        $response = $this->actingAs($this->petugas)->post('/petugas/transaksi/masuk', [
            'plat_nomor' => 'B 1111 ABC',
            'jenis_kendaraan' => 'motor',
            'id_area' => $this->area->id_area,
        ]);

        $response->assertSessionHas('error');
    }

    public function test_entry_fails_when_area_is_full(): void
    {
        $this->area->update(['kapasitas' => 1, 'terisi' => 1]);

        $response = $this->actingAs($this->petugas)->post('/petugas/transaksi/masuk', [
            'plat_nomor' => 'B 2222 DEF',
            'jenis_kendaraan' => 'motor',
            'id_area' => $this->area->id_area,
        ]);

        $response->assertSessionHas('error');
    }

    public function test_checkout_calculates_fee_and_decrements_occupancy(): void
    {
        // Create entry 2.5 hours ago -> should be rounded up to 3 hours
        $this->actingAs($this->petugas)->post('/petugas/transaksi/masuk', [
            'plat_nomor' => 'B 3333 GHI',
            'jenis_kendaraan' => 'motor',
            'id_area' => $this->area->id_area,
        ]);

        $trx = Transaksi::latest('id_parkir')->first();
        // Manipulate waktu masuk to 2.5 hours ago
        $trx->update(['waktu_masuk' => Carbon::now()->subMinutes(150)]);

        // Checkout
        $response = $this->actingAs($this->petugas)->post("/petugas/transaksi/{$trx->id_parkir}/checkout");

        $response->assertRedirect(route('petugas.transaksi.index', ['status' => 'keluar']));

        $trx->refresh();
        $this->assertEquals('keluar', $trx->status);
        $this->assertEquals(3, $trx->durasi_jam); // 150 mins / 60 ceil = 3
        $this->assertEquals(3 * (float) $this->tarifMotor->tarif_per_jam, $trx->biaya_total);

        $this->area->refresh();
        $this->assertEquals(0, $this->area->terisi);
    }

    public function test_receipt_views_render_successfully(): void
    {
        $this->actingAs($this->petugas)->post('/petugas/transaksi/masuk', [
            'plat_nomor' => 'B 4444 JKL',
            'jenis_kendaraan' => 'motor',
            'id_area' => $this->area->id_area,
        ]);

        $trx = Transaksi::latest('id_parkir')->first();

        // Struk tiket masuk
        $responseMasuk = $this->actingAs($this->petugas)->get("/petugas/transaksi/{$trx->id_parkir}/struk-masuk");
        $responseMasuk->assertStatus(200);
        $responseMasuk->assertSee('TIKET PARKIR MASUK');

        // Checkout
        $this->actingAs($this->petugas)->post("/petugas/transaksi/{$trx->id_parkir}/checkout");

        // Struk keluar
        $responseKeluar = $this->actingAs($this->petugas)->get("/petugas/transaksi/{$trx->id_parkir}/struk-keluar");
        $responseKeluar->assertStatus(200);
        $responseKeluar->assertSee('STRUK PARKIR');
    }
}
