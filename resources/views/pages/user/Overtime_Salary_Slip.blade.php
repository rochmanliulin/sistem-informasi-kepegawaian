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
        TANGGAL : <span class="p-1" style="border: 1px solid #344767;">{{ $data->tgl_terbit }}</span>  <br><br>
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
        <td>NIP : {{ $data->nip }}</td>
      </tr>
      <tr>
        <td>Nama : {{ $data->employee->nama }}</td>
      </tr>
      <tr>
        <td>Tidak Finger Istirahat : {{ $data->tidak_istirahat }}</td>
      </tr>
      <tr>
        <td>Tidak Finger Kembali Istirahat : {{ $data->tidak_istirahat_kembali }}</td>
      </tr>
      <tr>
        <td>Waktu Lebih Istirahat : {{ $data->lebih_istirahat }} Menit</td>
      </tr>
      <tr>
        <td>Terlambat :  {{ $data->hari_terlambat }} hari  / {{ $data->total_terlambat }} menit</td>
      </tr>
      <tr>
        <td>Jumlah Hari Kerja : {{ $data->hari_kerja }}</td>
      </tr>
      <tr>
        <td>Jumlah Hari Aktif : {{ $data->hari_aktif }}</td>
      </tr>
      <tr>
        <td>
        Total Jam Lembur:
        @if($data->total_jam_lembur !== null)
            @php
                $menitTotal = (int) $data->total_jam_lembur;
                $jam = intdiv($menitTotal, 60);
                $menit = $menitTotal % 60;

                // Jika menitnya 0, tampilkan hanya jam
                if ($menit === 0) {
                    echo "{$jam} jam";
                } else {
                    echo "{$jam} jam {$menit} menit";
                }
            @endphp
        @else
            -
        @endif
        </td>
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
            <td>Uang Lembur</td>
            <td class="text-right">
            {{ $data->total_uang_lembur > 0 ? 'Rp. ' . number_format($data->total_uang_lembur, 0, ',', '.') : '-' }}
            </td>
        </tr>
        <tr>
            <td>Do'a</td>
            <td class="text-right">
            {{ $data->doa > 0 ? 'Rp. ' . number_format($data->doa, 0, ',', '.') : '-' }}
            </td>
        </tr>
        <tr>
            <td>Premi Hadir</td>
            <td class="text-right">
            {{ $data->premi_hadir > 0 ? 'Rp. ' . number_format($data->premi_hadir, 0, ',', '.') : '-' }}
            </td>
        </tr>
        <tr>
            <td>Premi Lembur</td>
            <td class="text-right">
            {{ $data->premi_lembur > 0 ? 'Rp. ' . number_format($data->premi_lembur, 0, ',', '.') : '-' }}
            </td>
        </tr>
        @if(strtolower($data->employee->status) == 'pegawai harian')
            <tr>
            <td>Gaji</td>
            <td class="text-right">
                {{ $data->gaji > 0 ? 'Rp. ' . number_format($data->gaji, 0, ',', '.') : '-' }}
            </td>
            </tr>
        @endif
        @if(strtolower($data->employee->departemen) == 'packing')
            <tr>
            <td>Kopi</td>
            <td class="text-right">
                {{ $data->total_uang_kopi > 0 ? 'Rp. ' . number_format($data->total_uang_kopi, 0, ',', '.') : '-' }}
            </td>
            </tr>
        @endif
        <tr>
            <td>Lembur Hari Minggu</td>
            <td class="text-right">
            {{ $data->total_uang_lembur_minggu > 0 ? 'Rp. ' . number_format($data->total_uang_lembur_minggu, 0, ',', '.') : '-' }}
            </td>
        </tr>
        <tr>
            <td>Uang Makan</td>
            <td class="text-right">
            {{ $data->total_uang_makan > 0 ? 'Rp. ' . number_format($data->total_uang_makan, 0, ',', '.') : '-' }}
            </td>
        </tr>
        <tr>
            <th class="text-center">TOTAL</th>
            <th class="text-right">
            {{ $data->total > 0 ? 'Rp. ' . number_format($data->total, 0, ',', '.') : '-' }}
            </th>
        </tr>
    </tbody>

  </table>

  <span class="font-weight-bold" style="font-size: 13px;">*Catatan : </span>
  <p class="d-inline">Jika ditemukan kesalahan dalam perhitungan harap menghubungi ANISSA NUR ROCHIMAH.</p>

  <div class="text-center" style="line-height: 4px; position:absolute; left: 50%; transform:translateX(-50%); bottom:0;">
    <p>Jika ada pertanyaan mengenai sistem, silahkan hubungi</p>
    <p class="text-monospace font-weight-bold"><a href="mailto:rochmanliulin@pusatgrosirsidoarjo.com" style="color: #364968;">rochmanliulin@pusatgrosirsidoarjo.com</a></p>
    <p class="font-weight-bold">TERIMA KASIH!</p>
  </div>
</body>
</html>
