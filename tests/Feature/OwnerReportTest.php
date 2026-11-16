<?php

namespace Tests\Feature;

use App\Models\AreaParkir;
use App\Models\Tarif;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerReportTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();

        $this->owner = User::where('role', 'owner')->first();
    }

    public function test_owner_can_view_laporan_page_with_date_filters(): void
    {
        $response = $this->actingAs($this->owner)->get('/owner/laporan');
        $response->assertStatus(200);
        $response->assertSee('Rekapitulasi Laporan Transaksi Parkir');
    }

    public function test_owner_can_view_cetak_laporan(): void
    {
        $response = $this->actingAs($this->owner)->get('/owner/laporan/cetak');
        $response->assertStatus(200);
        $response->assertSee('LAPORAN REKAPITULASI TRANSAKSI PARKIR');
    }
}
