@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
  @include('layouts.navbars.auth.topnav', ['title' => 'Payroll'])
  <div class="container-fluid py-4">
    <div class="row mb-5">
      <div class="col-xl-12 col-sm-6 mb-xl-0">
        <div class="card">
          <div class="card-body">
            <h4 class="text-dark">{{ $page_title }}</h4>
            <form action="{{ route('payroll.process') }}" method="POST">
							@csrf
							<div class="row">
                <div class="col-md-3">
									<div class="form-group">
										<label class="form-control-label">Remark</label>
										<input type="text" class="form-control " placeholder="ex: Gaji Januari 2024" name="remark" required>
									</div>
								</div>
                <div class="col-md-2">
									<div class="form-group">
										<label for="salaryType" class="form-control-label">Jenis Gaji</label>
										<select class="form-select" id="salaryType" aria-label="Default select example" name="salary_type">
                      {{-- <option disabled selected></option> --}}
                      <option value="1" selected>Gaji Lembur</option>
                      {{-- <option value="2">Gaji Bulanan</option>
                      <option value="3">Bonus</option> --}}
                    </select>
                    @error('salary_type') <p class='text-danger text-xs pt-1'> {{ $message }} </p> @enderror
									</div>
								</div>
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="dataOvertimeSalary" class="form-control-label">Tanggal</label>
										<select class="form-select" id="dataOvertimeSalary" aria-label="Default select example" name="keterangan" required>
                      <option disabled selected></option>
                      @foreach ($data as $item)
                      <option value="{{ $item }}">{{ $item }}</option>
                      @endforeach
                    </select>
									</div>
								</div>
                <div class="col-md-1" style="margin-top: 2%;">
                  <button type="submit" class="btn bg-dark mb-0 text-white">Generate</button>
                </div>
							</div>
						</form>
          </div>
          <hr>
          <div class="card-body">
            <form action="{{ route('payroll.export') }}" method="GET">
              @csrf
              <div class="row">
                <div class="col-md-3">
									<div class="form-group">
                    <label for="dataRemark" class="form-control-label">Remark</label>
										<select class="form-select" id="dataRemark" aria-label="Default select example" name="remark" required>
                      <option disabled selected></option>
                      @foreach ($remark as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                      @endforeach
                    </select>
									</div>
								</div>
                <div class="col-md-2" style="margin-top: 2%;">
                  <button type="submit" class="btn btn-block bg-gradient-secondary"><i class="fas fa-download"></i>&nbsp;&nbsp;Export</button>
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
          <form action="{{ route('payroll.index') }}" method="GET" class="d-flex justify-content-end m-3 mb-0" style="z-index: 1 !important">
            {{-- request() -> Mengambil value request yang dikirim ke server --}}
            <div class="col-md-3">
              <input type="text" id="searchInput" class="form-control" placeholder="Cari..." name="search" value="{{ request()->search }}">
            </div>
          </form>
          
          <div class="table-responsive">

            @if ($payroll->isEmpty())
							<p class="d-flex justify-content-center mt-3 mb-1 fw-bold">Tidak ada data yang ditemukan.</p>
						@else
            <table class="table align-items-center mb-0">
              <thead>
                <tr>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Trx ID</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Transfer Type</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Beneficiary ID</th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Credited Account
                  </th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 ps-3">Receiver Name
                  </th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Amount
                  </th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">NIP
                  </th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Remark
                  </th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Beneficiary email
                  </th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Swift Code
                  </th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Cust Type
                  </th>
                  <th class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 text-center">Cust Residence
                  </th>
                </tr>
              </thead>
              <tbody>
                @foreach ($payroll as $item)
                <tr>
                  <td>
                    <p class="text-xs font-weight-bold mb-0 text-center">{{ $item->trx_id }}</p>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0 text-center">{{ $item->transfer_type }}</p>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0 text-center"></p>
                  </td>
                  <td>
                    <p class="text-xs font-weight-bold mb-0 text-center">{{ $item->employee->credited_account ?? '-' }}</p>
                  </td>
                  <td>
                    <span class="badge badge-dot me-4 ps-2">
                      <i class="bg-info"></i>
                      <span class="text-dark text-xs">{{ $item->employee->nama ?? '-' }}</span>
                    </span>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->amount }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->nip }}</p>
                  </td>
                  <td>
                    <p class="text-xs my-auto text-center">{{ $item->remark }}</p>
                  </td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                @endforeach
              </tbody>
            </table>
            @endif

          </div>
          <div class=" px-5 mt-3">
            {{ $payroll->onEachSide(1)->appends(['search' => $search])->links() }}
          </div>
        </div>
      </div>
    </div>
    @include('layouts.footers.auth.footer')
  </div>
@endsection