<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <style>
            /* Reset total agar tidak ada margin dari browser */
            @page {
                size: 58mm 80mm;
                margin: 0;
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Courier New', Courier, monospace;
                width: 58mm;
                padding: 4mm;
                font-size: 9pt;
                line-height: 1.3;
                color: #000;
            }

            .text-center {
                text-align: center;
                width: 100%;
            }

            .bold {
                font-weight: bold;
            }

            .line {
                border-top: 1px dashed #000;
                margin: 5px 0;
                width: 100%;
            }

            /* Container untuk baris data */
            .item-row {
                position: relative;
                width: 100%;
                height: 14pt;
                /* Kunci tinggi baris agar tidak pecah */
            }

            .label {
                position: absolute;
                left: 0;
            }

            .value {
                position: absolute;
                right: 0;
                text-align: right;
            }

            .footer {
                margin-top: 15px;
                font-size: 7pt;
            }
        </style>
    </head>

    <body onload="window.print(); window.onafterprint = function(){ window.close(); }">

        <div class="text-center header">
            <div class="bold" style="font-size: 11pt;">SCAFFOLD PARKING</div>
            <div style="font-size: 8pt;">Jl. Stadion No. 2, Salatiga</div>
        </div>

        <div class="line"></div>

        <div class="item-row">
            <span class="label">ID</span>
            <span class="value">#{{ $record->ticket_number }}</span>
        </div>
        <div class="item-row">
            <span class="label">Plat</span>
            <span class="value bold">{{ $record->vehicle->plate_number }}</span>
        </div>
        <div class="item-row">
            <span class="label">Jenis</span>
            <span class="value">{{ $record->vehicle->vehicleType->name }}</span>
        </div>

        <div class="line"></div>

        <div class="item-row">
            <span class="label">Masuk</span>
            <span class="value">{{ $record->entry_time->format('d/m H:i:s') }}</span>
        </div>
        <div class="item-row">
            <span class="label">Keluar</span>
            <span class="value">{{ $record->exit_time->format('d/m H:i:s') }}</span>
        </div>

        <div class="line"></div>

        <div class="item-row bold" style="font-size: 10pt;">
            <span class="label">TOTAL</span>
            <span class="value">IDR {{ number_format($record->total_price, 0, ',', '.') }}</span>
        </div>

        <div class="line"></div>

        <div class="text-center footer">
            Terima kasih atas kunjungan Anda.<br>
            Semoga selamat sampai tujuan.
            <div style="font-size: 7pt; margin-top: 5px; font-style: italic;">
                {{ $record->exit_time->format('d/m/Y H:i:s') }}
            </div>
        </div>

    </body>

</html>