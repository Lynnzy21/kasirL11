<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Latest compiled JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body onload="print()">
        <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                <div class="card text-primary bg-light mb-3">
                    <div class="card-body">
                        <h4 class="card-title">Laporan Transaksi</h4>
                        <a href="">Cetak</a>
                        <table class="table table-bordered table-striped table-hover" id="laporan">
                            <thead>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>No Inv.</th>
                                <th>Total</th>
                            </thead>
                            <tbody>
@foreach ($semuaTransaksi as $transaksi)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transaksi->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $transaksi->kode }}</td>
                                    <td>{{ $transaksi->total }}</td>
                                    <td>Rp. {{ number_format($transaksi->total, 0, ',', '.') }}</td>
                                </tr>
    
@endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</body>
</html>