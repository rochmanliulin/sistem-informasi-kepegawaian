@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
	@include('layouts.navbars.auth.topnav', ['title' => 'Pegawai'])

	<div class="container-fluid py-4">
		<div class="row mb-5">
			<div class="col-xl-12 col-sm-6 mb-xl-0">
				<div class="card">
					<div class="card-body">
						<h4 class="text-dark">Data Pegawai</h4>
					</div>
				</div>
			</div>
		</div>
		<div class="row pt-5 mb-3">
			<div class="col-md-2 ms-lg-5 btn-crud">
				<a href="{{ route('employee.create') }}"><button type="button" class="btn btn-block bg-gradient-info"><i class="fas fa-plus"></i>&nbsp;&nbsp;Tambah Data</button></a>
			</div>
			<div class="col-md-2">
				<button type="button" class="btn btn-block bg-gradient-success mb-3" data-bs-toggle="modal" data-bs-target="#modal-import"><i class="fas fa-upload"></i>&nbsp;&nbsp;Import Excel</button>
				<div class="modal fade" id="modal-import" tabindex="-1" role="dialog" aria-labelledby="modal-import" aria-hidden="true">
					<div class="modal-dialog modal-danger modal-dialog-centered modal-" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<h6 class="modal-title" id="modal-title-notification">IMPORT EXCEL</h6>
							</div>
							<form action="{{ route('employee.import') }}" method="post" enctype="multipart/form-data">
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
			<div class="col-md-4 btn-crud" style="z-index: 1 !important">
				<a href="{{ route('employee.export') }}"><button type="button" class="btn btn-block bg-gradient-secondary"><i class="fas fa-download"></i>&nbsp;&nbsp;Export Data</button></a>
			</div>
		</div>
		<div class="row">
			<div class="col-xl-12 col-sm-6 mb-xl-0">
				<div class="card">
					<div class="d-flex justify-content-end m-3 mb-0">
            <form action="{{ route('employee.index') }}" method="GET" class="col-3">
              {{-- request() -> Mengambil value request yang dikirim ke server --}}
              <input type="text" id="searchInput" class="form-control" placeholder="Cari..." name="search" value="{{ request()->search }}">
            </form>
          </div>
					<div class="table-responsive">

						@if ($employee->isEmpty())
							<p class="d-flex justify-content-center mt-3 mb-1 fw-bold">Tidak ada data yang ditemukan.</p>
						@else
							<table class="table align-items-center mb-0">
								<thead>
									<tr>
										<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">No</th>
										<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Nama</th>
										<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">NIP</th>
										<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Credited Account</th>
										<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Jabatan</th>
										<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Departemen</th>
										<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-2">Status</th>
										<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Tanggal Masuk Kerja</th>
										<th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Email</th>
										<th></th>
									</tr>
								</thead>
								<tbody>
									@foreach ($employee as $item)
										<tr>
											<td>
													{{--
														firstItem() -> Mendapatkan nomor urut pertama dari data
														$loop()->index -> Nomor urut dari iterasi
													--}}
													<p class="text-xs my-auto text-center">{{ $employee->firstItem() + $loop->index }}</p>
											</td>
											<td>
												<span class="badge badge-dot ps-2">
													<i class="bg-info"></i>
													<span class="text-dark text-xs">{{ $item->nama }}</span>
												</span>
											</td>
											<td>
												<p class="text-xs font-weight-bold mb-0 fw-bold text-center">{{ $item->nip }}</p>
											</td>
											<td>
												<p class="text-xs my-auto text-center">{{ $item->credited_account }}</p>
											</td>
											<td>
												<p class="text-xs my-auto">{{ $item->jabatan }}</p>
											</td>
											<td>
												<p class="text-xs my-auto">{{ $item->departemen }}</p>
											</td>
											<td>
												<p class="text-xs my-auto">{{ $item->status }}</p>
											</td>
											<td>
												<p class="text-xs my-auto text-center">{{ $item->tgl_masuk_kerja }}</p>
											</td>
                                            <td>
                                                <p class="text-xs my-auto text-center">{{ $item->email }}</p>
											<td class="align-middle text-center">
												{{-- route('nama route', id) --}}
												<a href="{{ route('employee.edit', $item->nip) }}" class="text-success font-weight-bold text-xs" data-toggle="tooltip" data-original-title="Edit user">
													<i class="fas fa-edit"></i>
													EDIT
												</a>
												{{-- route('nama route', id) --}}
												<form action="{{ route('employee.destroy', $item->nip) }}" onsubmit="showDeleteConfirm(this)" method="POST" class="d-inline">
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
						{{ $employee->onEachSide(1)->appends(['search' => $search])->links() }}
					</div>
				</div>
			</div>
		</div>
		@include('layouts.footers.auth.footer')
	</div>
@endsection
