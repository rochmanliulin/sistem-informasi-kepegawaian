@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
  @include('layouts.navbars.auth.topnav', ['title' => 'Video Tutorial'])
  <div class="container-fluid py-4">
    <div class="row mb-5">
      <div class="col-xl-12 col-sm-6 mb-xl-0">
        <div class="card">
          <div class="accordion" id="accordionExample">
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                  <strong>Daftar Video</strong>
                </button>
              </h2>
              <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                  <table class="table table-hover">
                    <tbody >
                      <tr class="text-muted">
                        <td class="col-1"><i class="fas fa-play-circle"></i></td>
                        <td class="col-auto"><a class="link" href="https://youtu.be/n3r5AxM0Zi4"><span class="text-muted">Cara Login</span></a></td>
                        <td class="col-1 text-muted small">00:32</td>
                      </tr>
                      <tr class="text-muted">
                        <td class="col-1"><i class="fas fa-play-circle"></i></td>
                        <td class="col-auto"><a class="link" href="https://youtu.be/lAiwdRcphiU"><span class="text-muted">Cara Reset Password</span></a></td>
                        <td class="col-1 text-muted small">01:40</td>
                      </tr>
                      <tr class="text-muted">
                        <td class="col-1"><i class="fas fa-play-circle"></i></td>
                        <td class="col-auto"><a class="link" href="https://youtu.be/ySBFrlx3uMU"><span class="text-muted">Cara Import Excel Pegawai</span></a></td>
                        <td class="col-1 text-muted small">00:48</td>
                      </tr>
                      <tr class="text-muted">
                        <td class="col-1"><i class="fas fa-play-circle"></i></td>
                        <td class="col-auto"><a class="link" href="https://youtu.be/Lpm9ZIc1zL8"><span class="text-muted">Cara Export Pegawai</span></a></td>
                        <td class="col-1 text-muted small">00:52</td>
                      </tr>
                      <tr class="text-muted">
                        <td class="col-1"><i class="fas fa-play-circle"></i></td>
                        <td class="col-auto"><a class="link" href="https://youtu.be/8faDHATFJ6E"><span class="text-muted">Cara Buat Pegawai</span></a></td>
                        <td class="col-1 text-muted small">00:48</td>
                      </tr>
                      <tr class="text-muted">
                        <td class="col-1"><i class="fas fa-play-circle"></i></td>
                        <td class="col-auto"><a class="link" href="https://youtu.be/C9y-0xJiEuQ"><span class="text-muted">Cara Edit Pegawai</span></a></td>
                        <td class="col-1 text-muted small">00:41</td>
                      </tr>
                      <tr class="text-muted">
                        <td class="col-1"><i class="fas fa-play-circle"></i></td>
                        <td class="col-auto"><a class="link" href="https://youtu.be/NQOLxMb6otQ"><span class="text-muted">Cara Import Excel Tunjangan</span></a></td>
                        <td class="col-1 text-muted small">01:02</td>
                      </tr>
                      <tr class="text-muted">
                        <td class="col-1"><i class="fas fa-play-circle"></i></td>
                        <td class="col-auto"><a class="link" href="https://youtu.be/TgkhT6clm1g"><span class="text-muted">Cara Buat Tunjangan</span></a></td>
                        <td class="col-1 text-muted small">01:06</td>
                      </tr>
                      <tr class="text-muted">
                        <td class="col-1"><i class="fas fa-play-circle"></i></td>
                        <td class="col-auto"><a class="link" href="https://youtu.be/-bE1npP0Jr4"><span class="text-muted">Cara Edit Tunjangan</span></a></td>
                        <td class="col-1 text-muted small">00:39</td>
                      </tr>
                      <tr class="text-muted">
                        <td class="col-1"><i class="fas fa-play-circle"></i></td>
                        <td class="col-auto"><a class="link" href="https://youtu.be/7Y_b78-jB9I"><span class="text-muted">Cara Import Excel Fingerprint</span></a></td>
                        <td class="col-1 text-muted small">01:08</td>
                      </tr>
                      <tr class="text-muted">
                        <td class="col-1"><i class="fas fa-play-circle"></i></td>
                        <td class="col-auto"><a class="link" href="https://youtu.be/GRcr6MFwrYA"><span class="text-muted">Cara Edit Fingerprint</span></a></td>
                        <td class="col-1 text-muted small">01:10</td>
                      </tr>
                      <tr class="text-muted">
                        <td class="col-1"><i class="fas fa-play-circle"></i></td>
                        <td class="col-auto"><a class="link" href="https://youtu.be/mBlEuwDtzzk"><span class="text-muted">Cara Proses Gaji Lembur</span></a></td>
                        <td class="col-1 text-muted small">00:48</td>
                      </tr>
                      <tr class="text-muted">
                        <td class="col-1"><i class="fas fa-play-circle"></i></td>
                        <td class="col-auto"><a class="link" href="https://youtu.be/NNxADaBq7fw"><span class="text-muted">Cara Export Gaji Lembur</span></a></td>
                        <td class="col-1 text-muted small">01:19</td>
                      </tr>
                      <tr class="text-muted">
                        <td class="col-1"><i class="fas fa-play-circle"></i></td>
                        <td class="col-auto"><a class="link" href="https://youtu.be/OlErHLepTGo"><span class="text-muted">Cara Proses Payroll</span></a></td>
                        <td class="col-1 text-muted small">00:36</td>
                      </tr>
                      <tr class="text-muted">
                        <td class="col-1"><i class="fas fa-play-circle"></i></td>
                        <td class="col-auto"><a class="link" href="https://youtu.be/w6oCJp1v_zE"><span class="text-muted">Cara Export Payroll</span></a></td>
                        <td class="col-1 text-muted small">00:39</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('layouts.footers.auth.footer')
  </div>
@endsection