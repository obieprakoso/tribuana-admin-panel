<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan - Ringkasan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .info {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: right;
            margin-top: 50px;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .total-row td {
            border-top: 2px solid #000;
        }
        .summary-box {
            border: 1px solid #000;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }
        .summary-box h3 {
            margin-top: 0;
            margin-bottom: 10px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .summary-item:last-child {
            margin-bottom: 0;
            padding-top: 5px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Keuangan - Ringkasan</h2>
        <p>Tanggal: {{ $date }}</p>
    </div>

    <div class="info">
        <p><strong>Periode:</strong> {{ $start_date }} - {{ $end_date }}</p>
    </div>

    <div class="summary-box">
        <h3>Ringkasan Keuangan</h3>
        <div class="summary-item">
            <span>Total Uang Masuk:</span>
            <span>Rp. {{ $total_in }}</span>
        </div>
        <div class="summary-item">
            <span>Total Uang Masuk dari Penduduk:</span>
            <span>Rp. {{ $total_in_from_residents }}</span>
        </div>
        <div class="summary-item">
            <span>Total Uang Keluar:</span>
            <span>Rp. {{ $total_out }}</span>
        </div>
        <div class="summary-item">
            <span><strong>Saldo:</strong></span>
            <span><strong>Rp. {{ $balance }}</strong></span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tipe Transaksi</th>
                <th>Uang Masuk</th>
                <th>Uang Masuk dari Penduduk</th>
                <th>Uang Keluar</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row['name'] }}</td>
                <td>Rp. {{ number_format($row['in_amount'], 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($row['in_from_residents'], 0, ',', '.') }}</td>
                <td>Rp. {{ number_format($row['out_amount'], 0, ',', '.') }}</td>
                <td>Rp. {{ $row['total'] }}</td>
            </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="2">Total Keseluruhan</td>
                <td>Rp. {{ $total_in }}</td>
                <td>Rp. {{ $total_in_from_residents }}</td>
                <td>Rp. {{ $total_out }}</td>
                <td>Rp. {{ $grand_total }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html> 