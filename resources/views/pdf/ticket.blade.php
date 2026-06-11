<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tiket Parkir {{ $transaction->no_tiket }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            margin-bottom: 20px;
        }
        .title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .address {
            font-size: 10px;
        }
        .ticket-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 5px;
        }
        .info {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .details {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 40px;
        }
        .footer {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">SIJA PARKING</div>
        <div class="address">
            Jl. Raya Karadenan No.7, Karadenan,<br>
            Kec. Cibinong, Kabupaten Bogor, Jawa<br>
            Barat 16111
        </div>
    </div>

    <div class="ticket-title">TIKET PARKIR</div>
    <div class="info">
        {{ $transaction->location->location_name }}<br>
        @php
            $jenisLabel = ['motorcycle' => 'Motor', 'car' => 'Mobil', 'other' => 'Lainnya'];
        @endphp
        {{ $jenisLabel[$transaction->vehicleType->jenis] ?? ucfirst($transaction->vehicleType->jenis) }}
    </div>

    <div class="details">
        No Tiket : {{ $transaction->no_tiket }}<br>
        Tanggal : {{ $transaction->masuk->format('Y-m-d H:i:s') }}
    </div>

    <div class="footer">
        JANGAN MENINGGALKAN TIKET DAN BARANG<br>
        BERHARGA DI DALAM KENDARAAN
    </div>
</body>
</html>
