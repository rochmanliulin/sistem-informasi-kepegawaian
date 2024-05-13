@extends('layouts.app')

@section('content')
	@include('layouts.navbars.auth.topnav', ['title' => 'Manajemen User'])
	<div class="container-fluid py-4">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header d-flex justify-content-between">
						<h5>Users</h5>
						<a href="{{ route('users-management.create') }}" class="btn bg-gradient-dark btn-sm float-end mb-0">Tambah User</a>
					</div>
					<div class="card-body px-0 pt-0 pb-2">
						<div class="table-responsive p-0">
							@if ($users->isEmpty())
								<p class="d-flex justify-content-center mt-3 mb-1 fw-bold">Tidak ada data yang ditemukan.</p>
							@else
							<table class="table align-items-center mb-0">
								<thead>
									<tr>
										<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Avatar</th>
										<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Nama</th>
										<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email
										</th>
										<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Terakhir Dilihat
										</th>
										<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Status
										</th>
										<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Role
										</th>
										<th
											class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
											Tanggal dibuat</th>
										<th
											class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
											Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($users as $item)
										<tr>
											{{-- PR untuk membuat fitur show --}}
											<td class="w-5">
												<div class="px-3 py-1">
													<div>
														<img src="{{ asset('storage/user_profile/' . $item->profile_image) }}" class="avatar" style="width: 100px; height: auto;" alt="User Profile">
													</div>
												</div>
											</td>
											<td>
												<div class="d-flex flex-column justify-content-center">
													<h6 class="mb-0 text-sm">{{ $item->fullname }}</h6>
												</div>
											</td>
											<td>
												<p class="text-sm font-weight-bold mb-0">{{ $item->email }}</p>
											</td>
											<td>
												<p class="text-sm font-weight-bold mb-0">{{ Carbon\Carbon::parse($item->last_seen)->diffForHumans() }}</p>
											</td>
											<td class="text-center">
												<span class="text-sm font-weight-bold mb-0 badge bg-{{ $item->last_seen >= now()->subMinutes(5) ? 'success' : 'danger' }}">{{ $item->last_seen >= now()->subMinutes(5) ? 'Online' : 'Offline' }}</span>
											</td>
											<td>
												<p class="text-sm font-weight-bold mb-0 text-center">{{ $item->role }}</p>
											</td>
											<td class="align-middle text-center text-sm">
												<p class="text-sm font-weight-bold mb-0">{{ $item->created_at }}</p>
											</td>
											<td class="align-middle text-end">
												<div class="d-flex px-3 py-1 justify-content-center align-items-center">
													<a href="{{ route('users-management.edit', $item->id) }}" class="text-sm font-weight-bold mb-0 text-success">Edit</a>
													<form action="{{ route('users-management.destroy', $item->id) }}" onsubmit="showDeleteConfirm(this)" method="POST">
														@csrf
														@method('DELETE')
														<input type="submit" class="text-sm font-weight-bold mb-0 ps-2 text-danger" style="background:none; border:none;" value="Delete">
													</form>
												</div>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
							@endif
						</div>

						@if ($users->total() > $users->perPage())
							<div class="px-5 mt-3">
								{{ $users->onEachSide(1)->links() }}
							</div>
						@else
							<div class="pt-0 px-4 pb-4">
								<p class="my-1" style="font-size: .875rem; color: #8392ab;">Showing 1 to {{ $users->total() }} of {{ $users->total() }} entries</p>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
		@include('layouts.footers.auth.footer')
	</div>
@endsection

@push('js')
	<script>
		let success = "{{ session('success') }}";
		let error = "{{ session('error') }}";

		// ALert success
		if (success !== '') {
			Swal.fire({
					icon: 'success',
					title: 'Yeayy...',
					text: success,
					showConfirmButton: false,
					timer: 2500,
					timerProgressBar: true,
			});
		}

		// Alert error
		if (error !== '') {
			Swal.fire({
					icon: 'error',
					title: 'Waduh :(',
					text: error,
					showConfirmButton: false,
					timer: 3000,
					timerProgressBar: true,
			});
		}

		// Alert delete
		function showDeleteConfirm(element) {
			event.preventDefault();

			Swal.fire({
				title: "Yakin?",
				text: "Apakah Anda ingin menghapus data?",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Iya, Hapus",
				cancelButtonText: "Batal"
			}).then((result) => {
				if (result.isConfirmed) {
					element.submit();
				}
			});
		}
	</script>
@endpush