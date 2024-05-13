@extends('layouts.app')

@section('content')
	@include('layouts.navbars.auth.topnav', ['title' => 'Manajemen User'])
	<div class="container-fluid py-4">
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-header d-flex justify-content-between">
						<h5>Aktifitas User</h5>
					</div>
					<div class="card-body px-0 pt-0 pb-2">
						<div class="table-responsive p-0">
							@if ($logs->isEmpty())
								<p class="d-flex justify-content-center mt-3 mb-1 fw-bold">Tidak ada data yang ditemukan.</p>
							@else
							<table class="table align-items-center mb-0">
								<thead>
									<tr>
										<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 w-5">LOG ID</th>
										<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 w-10 text-center">Waktu</th>
										<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Deskripsi</th>
										<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Tipe Subjek</th>
										<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nama</th>
										<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">IP
										</th>
										<th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 w-10"></th>
									</tr>
								</thead>
								<tbody>
									@foreach ($logs as $key => $log)
										<tr>
											<td>
												<p class="text-sm font-weight-bold mb-0 ps-3">{{ $log->id }}</p>
											</td>
											<td class="text-center">
												<small class="text-muted pb-1 btn btn-light btn-sm my-2">
													@if ($log->created_at->diffInDays(now()) < 1)
														{{ $log->created_at->diffForHumans() }}
													@else
														{{ $log->created_at->format('d-M-Y') }}
													@endif
												</small>
											</td>
											<td>
												<p class="text-sm font-weight-bold mb-0 d-inline">{{ $log->description }}</p>
											</td>
											<td>
												<p class="text-sm font-weight-bold mb-0 d-inline">{{ $log->subject_type }}</p>
											</td>
											<td>
												<p class="text-sm font-weight-bold mb-0 ps-3">
													<i class="fas fa-user-shield"></i>&nbsp;
													{{ $log->causer->fullname }}
												</p>
											</td>
											<td class="align-middle text-sm text-center">
												<p class="text-sm font-weight-bold mb-0">
													<i class="fas fa-hashtag"></i>
													{{ $ip }}
												</p>
											</td>
											<td class="align-middle text-sm text-end pe-4">
												<a href="" class="text-secondary text-xs" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $log->id }}">
													<i class="fas fa-eye"></i>&nbsp;
													PREVIEW
												</a>
												<!-- Modal -->
												<div class="modal fade" id="exampleModal{{ $log->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
													<div class="modal-dialog modal-dialog-centered">
														<div class="modal-content">
															<div class="modal-header">
																<h1 class="modal-title fs-5" id="exampleModalLabel">Preview</h1>
																<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
															</div>
															<div class="modal-body text-start">
																<ul class="list-group">
																	@if ($log->event === 'updated')
																		@foreach (['old' => 'Old Data', 'attributes' => 'New Data'] as $dataKey => $dataTitle)
																				<li class="list-group-item bg-dark text-white">{{ $dataTitle }}</li>
																				@foreach ($log->properties[$dataKey] as $key => $value)
																						<li class="list-group-item d-flex flex-wrap"><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}: </strong> {{ $value }}</li>
																				@endforeach
																		@endforeach
																@elseif ($log->event === 'deleted')
																		<li class="list-group-item bg-dark text-white">Deleted Data</li>
																		@foreach ($log->properties['old'] as $key => $value)
																				<li class="list-group-item d-flex flex-wrap"><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}: </strong> {{ $value }}</li>
																		@endforeach
																@elseif (in_array($log->event, ['created', 'exported', 'imported', 'processed']))
																		<li class="list-group-item bg-dark text-white">{{ ucfirst($log->event) }} Data</li>
																		<li class="list-group-item d-flex flex-wrap"><strong>Keterangan: </strong> {{ $log->description }}</li>
																@endif
																</ul>
															</div>
															<div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Okay</button>
															</div>
														</div>
													</div>
												</div>
												<!--Modal-->
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
							@endif
						</div>

						@if ($logs->total() > $logs->perPage())
							<div class="px-5 mt-3">
								{{ $logs->onEachSide(1)->links() }}
							</div>
						@else
							<div class="pt-0 px-4 pb-4">
								<p class="my-1" style="font-size: .875rem; color: #8392ab;">Showing 1 to {{ $logs->total() }} of {{ $logs->total() }} entries</p>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
		@include('layouts.footers.auth.footer')
	</div>
@endsection