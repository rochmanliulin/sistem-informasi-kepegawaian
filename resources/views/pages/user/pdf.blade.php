<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Gaji Lembur</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

  <style type="text/css">
    table tr td, table tr th, p {
      font-size: 9pt;
    }
  </style>
</head>

<body>
  <table class="table table-borderless">
    <tr>
      <td style="width: 1px;">
        <img src="../public/img/pgs-icon.png" alt="Logo PGS" width="auto" height="50px">
      </td>
      <td>
        <h4 style="margin-top: 10px; margin-left: -20px;">Pusat Grosir Sidoarjo</h4>
      </td>
      <td class="text-right">
        <h2 style="color: #364968;">INVOICE</h2>
      </td>
    </tr>
    <tr>
      <td colspan="2">Ritzgate Industrial Park Blok BF-1,<br>
        Jl. Muncul, Bohar Timur, Kec. Gedangan, <br>
        Kabupaten Sidoarjo, Jawa Timur <br>
        61257</td>
      <td class="text-right" style="line-height: 21px;">
        TANGGAL : <span class="p-1" style="border: 1px solid #344767;">{{ $data[0]->tgl_terbit }}</span>  <br>
        INVOICE <span class="p-1" style="border: 1px solid #344767;">#{{ rand(100000, 999999) }}</span> <br>
      </td>
    </tr>
  </table>
  <table class="mt-4">
    <thead>
      <tr>
        <th class="text-white" style="background-color: #364968;">DETAIL LEMBUR</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>NIP : {{ $data[0]->nip }}</td>
      </tr>
      <tr>
        <td>Nama : {{ $data[0]->employee->nama }}</td>
      </tr>
      <tr>
        <td>Tidak Finger Istirahat : {{ $data[0]->tidak_istirahat }}</td>
      </tr>
      <tr>
        <td> Tidak Finger Kembali Istirahat : {{ $data[0]->tidak_istirahat_kembali }}</td>
      </tr>
      <tr>
        <td>Waktu Lebih Istirahat : {{ $data[0]->lebih_istirahat }} Menit</td>
      </tr>
      <tr>
        <td>Terlambat :  {{ $data[0]->hari_terlambat }} hari  / {{ $data[0]->total_terlambat }} menit</td>
      </tr>
    </tbody>
  </table>

  <table class="table table-bordered table-striped table-sm pt-3">
    <thead>
      <tr class="text-center text-white" style="background-color: #364968;">
        <th>KETERANGAN</th>
        <th style="width: 20%;">JUMLAH</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Gaji Lembur</td>
        <td class="text-right">Rp. {{ $data[0]->total_uang_lembur }}</td>
      </tr>
      <tr>
        <td>Do'a</td>
        <td class="text-right">Rp. {{ $data[0]->doa }}</td>
      </tr>
      <tr>
        <td>Premi</td>
        <td class="text-right">Rp. {{ $data[0]->premi }}</td>
      </tr>
      <tr>
        <td>Gaji</td>
        <td class="text-right">Rp. {{ $data[0]->gaji }}</td>
      </tr>
      <tr>
        <td>Kopi</td>
        <td class="text-right">Rp. {{ $data[0]->total_uang_kopi }}</td>
      </tr>
      <tr>
        <td>Lembur Hari Minggu</td>
        <td class="text-right">Rp. {{ $data[0]->total_uang_lembur_minggu }}</td>
      </tr>
      <tr>
        <td>Uang Makan</td>
        <td class="text-right">Rp. {{ $data[0]->total_uang_makan }}</td>
      </tr>
      <tr>
        <th class="text-center">TOTAL</th>
        <th class="text-right">Rp. {{ $data[0]->total }}</th>
      </tr>
    </tbody>
  </table>
  <span class="font-weight-bold" style="font-size: 13px;">*Catatan : </span><p class="d-inline">Jika ditemukan kesalahan dalam perhitungan harap menghubungi sdri Dwi Ata Yulia atau Rizki Amalia</p>

  <div class="text-center" style="line-height: 4px; position:absolute; left: 50%; transform:translateX(-50%); bottom:0;">
    <p>Jika ada pertanyaan mengenai sistem, silahkan hubungi</p>
    <p class="text-monospace font-weight-bold"><a href="mailto:rochmanliulin@pusatgrosirsidoarjo.com" style="color: #364968;">rochmanliulin@pusatgrosirsidoarjo.com</a></p>
    <p class="font-weight-bold">TERIMA KASIH!</p>
  </div>
</body>

</html>