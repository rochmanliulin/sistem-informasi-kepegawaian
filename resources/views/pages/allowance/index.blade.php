@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav', ['title' => 'Tunjangan'])
    <div class="container-fluid py-4">
        <div class="row mb-5">
            <div class="col-xl-12 col-sm-6 mb-xl-0">
                <div class="card">
                    <div class="card-body">
                        <h4 class="text-dark">{{ $page_title }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-5 mb-3">
          <div class="col-md-2 ms-5">
            <a href="{{ route('allowance.create') }}"><button type="button" class="btn btn-block bg-gradient-info"><i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah Data</button></a>
          </div>
          <div class="col-md-6 btn-crud">
            <button type="button" class="btn btn-block bg-gradient-success mb-3" data-bs-toggle="modal" data-bs-target="#modal-import"><i class="fas fa-upload"></i>&nbsp;&nbsp;Import Excel</button>
            <div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="modal-import" aria-hidden="true">
              <div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h6 class="modal-title" id="modal-title-notification">IMPORT EXCEL</h6>
                  </div>
                  <form action="{{ route('allowance.import') }}" method="post" enctype="multipart/form-data">
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
        </div>
        <div class="row">
          <div class="col-xl-12 col-sm-6 mb-xl-0">
            <div class="card">
              <div class="d-flex justify-content-end m-3 mb-0">
                <form action="{{ route('allowance.index') }}" method="GET" class="col-3">
                  {{-- request() -> Mengambil value request yang dikirim ke server --}}
                  <input type="text" id="searchInput" class="form-control" placeholder="Cari..." name="search" value="{{ request()->search }}">
                </form>
              </div>
              <div class="table-responsive">

                @if ($allowance->isEmpty())
                  <p class="d-flex justify-content-center mt-3 mb-1 fw-bold">Tidak ada data yang ditemukan.</p>
                @else                  
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">No</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Nama</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Gaji</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Kos</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Masuk Pagi</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Prestasi</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Komunikasi</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Jabatan</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Lain - Lain</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Uang Makan</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Kasbon</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Premi Hadir</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Premi Lembur</th>
                        <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Do'a</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($allowance as $item)
                      <tr>
                        <td>
                            {{-- 
                              firstItem() -> Mendapatkan nomor urut pertama dari data
                              $loop()->index -> Nomor urut dari iterasi
														--}}
                            <p class="text-xs my-auto text-center">{{ $allowance->firstItem() + $loop->index }}</p>
                        </td>
                        <td>
                          <span class="badge badge-dot me-4 ps-2">
                            <i class="bg-info"></i>
                            <span class="text-dark text-xs">{{ $item->employee->nama }}</span>
                          </span>
                        </td>
                        <td>
                          <p class="text-xs mb-0 text-center">{{ $item->gaji }}</p>
                        </td>
                        <td>
                          <p class="text-xs mb-0 text-center">{{ $item->kos }}</p>
                        </td>
                        <td>
                          <p class="text-xs mb-0 text-center">{{ $item->masuk_pagi }}</p>
                        </td>
                        <td>
                          <p class="text-xs mb-0 text-center">{{ $item->prestasi }}</p>
                        </td>
                        <td>
                          <p class="text-xs mb-0 text-center">{{ $item->komunikasi }}</p>
                        </td>
                        <td>
                          <p class="text-xs mb-0 text-center">{{ $item->jabatan }}</p>
                        </td>
                        <td>
                          <p class="text-xs mb-0 text-center">{{ $item->lain_lain }}</p>
                        </td>
                        <td>
                          <p class="text-xs mb-0 text-center">{{ $item->uang_makan }}</p>
                        </td>
                        <td>
                          <p class="text-xs mb-0 text-center">{{ $item->kasbon }}</p>
                        </td>
                        <td class="align-middle">
                          <p class="text-xs my-auto text-center">{{ $item->premi_hadir }}</p>
                        </td>
                        <td>
                          <p class="text-xs my-auto text-center">{{ $item->premi_lembur }}</p>
                        </td>
                        <td>
                          <p class="text-xs my-auto text-center">{{ $item->doa }}</p>
                        </td>
                        <td class="align-middle text-center">
                          {{-- route('nama route', id) --}}
                          <a href="{{ route('allowance.edit', $item->nip) }}" class="text-success font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
                            <i class="fas fa-edit"></i>
                            EDIT
                          </a>
                          {{-- route('nama route', id) --}}
                          <form action="{{ route('allowance.destroy', $item->nip) }}" onsubmit="showDeleteConfirm(this);" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            {{-- Method delete -> method bawaan jika menggunakan route resource --}}
                            <button type="submit" class="text-danger font-weight-bold text-xs ms-4" style="background:none; border:none;">
                              <i class="fas fa-trash"></i>
                              DELETE
                            </button>
                          </form>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                @endif

              </div>
              <div class=" px-5 mt-3">
                {{-- 
                  onEachSide() -> Menampilkan 1 angka disisi kanan dan kiri button page saat ini
                  links() -> Navigasi pagination
                --}}
                {{ $allowance->onEachSide(1)->appends(['search' => $search])->links() }}
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
            window.location.href = "{{ route('allowance.index') }}";
          } else {
            // Ambil form dari input, kemudian submit
            this.form.submit();
          }
				}
			});
		});
	</script>
@endpush