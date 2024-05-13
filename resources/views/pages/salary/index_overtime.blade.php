@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
  @include('layouts.navbars.auth.topnav', ['title' => 'Gaji Lembur'])
  <div class="container-fluid py-4">
    <div class="row mb-5">
      <div class="col-xl-12 col-sm-6 mb-xl-0">
        <div class="card">
          <div class="card-body">
            <h4 class="text-dark">{{ $page_title }}</h4>
            <form action="{{ route('overtime-salary.process') }}" method="POST">
							@csrf
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="form-control-label">Range Tanggal</label>
										<input type="text" class="form-control date-range bg-white" placeholder="Pilih tanggal awal dan akhir" onfocus="focused(this)" onfocusout="defocused(this)" name="range_tgl">
									</div>
								</div>
								<div class="col-md-2">
									<div class="form-group">
										<label class="form-control-label">Tanggal Terbit</label>
										<input type="text" class="form-control date-single bg-white" onfocus="focused(this)" onfocusout="defocused(this)" name="tgl_terbit">
									</div>
								</div>
                <div class="col-md-4" style="margin-top: 2%;">
                  <button type="submit" class="btn bg-dark mb-0 text-white">Proses</button>
                </div>
							</div>
						</form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12 col-sm-6 mb-xl-0">
        <div class="card">
          <div class="d-flex justify-content-end m-3 mb-0">
            <form action="{{ route('overtime-salary.index') }}" method="GET" class="col-lg-3">
              {{-- request() -> Mengambil value request yang dikirim ke server --}}
                <input type="text" id="searchInput" class="form-control" placeholder="Cari..." name="search" value="{{ request()->search }}">
            </form>
          </div>
          <div class="table-responsive">

            @if ($overtimeSalary->isEmpty())
							<p class="d-flex justify-content-center mt-3 mb-1 fw-bold">Tidak ada data yang ditemukan.</p>
						@else
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">No</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">NIK</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Nama</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Lembur</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Do'a</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Premi</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Gaji</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Kopi</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Lembur Minggu</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Uang Makan</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Total</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Keterangan</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Hari</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Jam</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Tanggal Terbit</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Telat</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Jumlah Waktu Telat &#40;Menit&#41;</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Tidak Finger Istirahat</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Tidak Finger Istirahat &#40;Masuk&#41;</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Tidak Finger Istirahat &#40;Kembali&#41;</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Waktu Lebih Istirahat</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($overtimeSalary as $item)
                <tr>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $overtimeSalary->firstItem() + $loop->index }}</p>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0 text-center">{{ $item->nip }}</p>
                  </td>
                  <td>
                    <span class="badge badge-dot me-4 ps-2">
                      <i class="bg-info"></i>
                      <span class="text-dark text-xs">{{ $item->employee->nama }}</span>
                    </span>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0 text-center">{{ $item->total_uang_lembur }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->doa }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->premi }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->gaji }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->total_uang_kopi }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->total_uang_lembur_minggu }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->total_uang_makan }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->total }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->keterangan }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->hari_aktif }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->total_jam_lembur }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->tgl_terbit }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->hari_terlambat }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->total_terlambat }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->tidak_istirahat }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->tidak_istirahat_masuk }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->tidak_istirahat_kembali }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->lebih_istirahat }}</p>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @endif

          </div>
          <div class=" px-5 mt-3">
            {{ $overtimeSalary->onEachSide(1)->appends(['search' => $search])->links() }}
          </div>
        </div>
      </div>
    </div>
    @include('layouts.footers.auth.footer')
  </div>
@endsection

@push('js')
  <script>
    flatpickr('.date-range', {
      altInput: true,
      altFormat: "j F Y",
      dateFormat: "Y-m-d",
      mode: "range"
    });

    flatpickr('.date-single', {
      altInput: true,
      altFormat: "j F Y",
      dateFormat: "Y-m-d",
      minDate: "today"
    });

    // Fungsi untuk menangani tombol Enter pada input pencarian
		document.addEventListener("DOMContentLoaded", function() {
			const searchInput = document.querySelector('#searchInput');

			searchInput.addEventListener('keypress', function(e) {
				if (e.key === 'Enter') {
					// Mencegah event bawaan browser
					e.preventDefault();

          if (this.value.trim() === '') {
            window.location.href = "{{ route('overtime-salary.index') }}";
          } else {
            // Ambil form dari input, kemudian submit
            this.form.submit();
          }
				}
			});
		});
  </script>
@endpush