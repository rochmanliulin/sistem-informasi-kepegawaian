@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
  @include('layouts.navbars.auth.topnav', ['title' => 'Fingerprint'])
  <div class="container-fluid py-4">

     {{-- ALERT ERROR UNTUK DATA YANG TIDAK SCAN PULANG --}}
    @if(session('error_data'))
        <div class="alert alert-danger alert-dismissible fade show text-white" role="alert">
            <strong>Import Gagal!</strong> Pegawai berikut tidak melakukan scan pulang:

            {{-- Tombol Close --}}
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 mt-2 me-2" data-bs-dismiss="alert" aria-label="Close"></button>

            {{-- Tabel --}}
            <div class="table-responsive mt-3">
                <table class="table table-bordered table-sm bg-white text-dark">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(session('error_data') as $index => $err)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $err['nip'] }}</td>
                                <td>{{ $err['nama'] }}</td>
                                <td>{{ $err['tanggal'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif



    {{-- ALERT BIASA --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mb-5">
        <div class="col-xl-12 col-sm-6 mb-xl-0">
            <div class="card">
                <div class="card-body">
                    <h4 class="text-dark">{{$page_title}}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="row d-flex justify-content-between pt-5 mb-3">
      <div class="col-md-2 ms-5">
        <button type="button" class="btn btn-block bg-gradient-success mb-3" data-bs-toggle="modal" data-bs-target="#modal-import"><i class="fas fa-upload"></i>&nbsp;&nbsp;Import Excel</button>
        <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="modal-import" aria-hidden="true">
          <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h6 class="modal-title" id="modal-title-notification">IMPORT EXCEL</h6>
              </div>
              <form action="{{ route('fingerprint.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                  <div class="py-3 text-center">
                    <input type="file" name="file" id="importExcel">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn bg-gradient-success">Import</button>
                  <button type="button" class="btn btn-link text-dark ml-auto" data-bs-dismiss="modal">Tutup</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      {{-- Dimatikan dahulu karena fitur belum jelas untuk apa --}}
      {{-- <div class="col-md-6" style="z-index: 1 !important">
        <form action="{{ route('fingerprint.process') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-block bg-gradient-secondary">Proses History</button>
        </form>
      </div> --}}


    </div>
    <div class="row">
      <div class="col-xl-12 col-sm-6 mb-xl-0">
        <div class="card">
          <div class="d-flex justify-content-end m-3 mb-0">
            <form action="{{ route('fingerprint.index') }}" method="GET" class="col-3">
              {{-- request() -> Mengambil value request yang dikirim ke server --}}
              <input type="text" id="searchInput" class="form-control" placeholder="Cari..." name="search" value="{{ request()->search }}">
            </form>
          </div>
          <div class="table-responsive">

            @if ($fingerprint->isEmpty())
              <p class="d-flex justify-content-center mt-3 mb-1 fw-bold">Tidak ada data yang ditemukan.</p>
            @else
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">No</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Tanggal</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Jam Kerja</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Nama</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Scan Masuk</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Terlambat</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Scan Istirahat 1</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Scan Istirahat 2</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Istirahat</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Scan Pulang</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Durasi</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Lembur Akhir</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @foreach ($fingerprint as $item)
                <tr>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $fingerprint->firstItem() + $loop->index }}</p>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0 text-center">{{ $item->tgl }}</p>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0">{{ $item->jam_kerja }}</p>
                  </td>
                  <td>
                    <span class="badge badge-dot me-4 ps-2">
                      <i class="bg-info"></i>
                      <span class="text-dark text-xs">{{ $item->employee->nama }}</span>
                    </span>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->scan_masuk }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->terlambat }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->scan_istirahat_1 }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->scan_istirahat_2 }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->istirahat }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->scan_pulang }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->durasi }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->lembur_akhir }}</p>
                  </td>
                  <td class="align-middle text-center">
                    <a href="{{ route('fingerprint.edit', $item->id) }}" class="text-success font-weight-bold text-xs" data-toggle="tooltip">
                      <i class="fas fa-edit"></i>
                      EDIT
                    </a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @endif
          </div>

          <div class="px-5 mt-3">
            {{ $fingerprint->onEachSide(1)->appends(['search' => $search])->links() }}
          </div>
        </div>
      </div>
    </div>
    @include('layouts.footers.auth.footer')
  </div>
@endsection

@push('js')
  <script>
    // Fungsi untuk menangani tombol Enter pada input pencarian
    document.addEventListener("DOMContentLoaded", function() {
      const searchInput = document.querySelector('#searchInput');

      searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
          // Mencegah event bawaan browser
          e.preventDefault();

          if (this.value.trim() === '') {
            window.location.href = "{{ route('fingerprint.index') }}";
          } else {
            // Ambil form dari input, kemudian submit
            this.form.submit();
          }
        }
      });
    });
  </script>
@endpush
