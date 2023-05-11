@extends('layouts.app')
@section('title')
    Profil - {{ config('app.name') }}
@endsection
@push('styles')
<link href="{{ url('argon') }}/assets/js/plugins/datatable/dataTables.bootstrap4.min.css" rel="stylesheet" />
<style>
.form-control:disabled{
    background-color: #ffffff !important;
}
.select2-container--bootstrap.select2-container--disabled .select2-selection{
        background-color: #ffffff !important;
}
</style>


@endpush
@section('content')


    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow h-100">
                <div class="card-header">
                    <h5 class="m-0 pt-1 font-weight-bold">Profil</h5>
                </div>
                <div class="card-body">
                    <div class="nav-wrapper">
                        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-sm-2 mb-md-0 active" id="akun-tab" data-toggle="tab" href="#akun" role="tab" aria-controls="akun" aria-selected="true"></i>Akun</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-2 mb-md-0 " id="password-tab" data-toggle="tab" href="#password-tab-panel" role="tab" aria-controls="password-tab-panel" aria-selected="true"></i>Password</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-2 mb-md-0" id="informasi-personal-tab" data-toggle="tab" href="#informasi-personal" role="tab" aria-controls="informasi-personal" aria-selected="false"></i>Informasi Personal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-2 mb-md-0" id="riwayat-kerja-tab" data-toggle="tab" href="#riwayat-kerja" role="tab" aria-controls="riwayat-kerja" aria-selected="false">Riwayat Kerja</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-2 mb-md-0" id="riwayat-pendidikan-tab" data-toggle="tab" href="#riwayat-pendidikan" role="tab" aria-controls="riwayat-pendidikan" aria-selected="false">Riwayat Pendidikan</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-sm-2 mb-md-0" id="riwayat-pelatihan-tab" data-toggle="tab" href="#riwayat-pelatihan" role="tab" aria-controls="riwayat-pelatihan" aria-selected="false">Seminar / Workshop / Diklat /Pelatihan</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card shadow">
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="akun" role="tabpanel" aria-labelledby="akun-tab">
                                    <form action=" {{ route('update-akun') }} " method="post"  enctype="multipart/form-data">
                                        @method('patch')
                                        @csrf
                                    <div class="pl-lg-4">
                                        <div class="row justify-content-center mb-2">

                                            <div class="">
                                                <a href="#">
                                                  <img src="{{ $user->foto }}" style="max-width: 180px;max-height: 200px" class="rounded-circle">
                                                </a>
                                              </div>

                                        </div>
                                      <div class="row">

                                        <div class="col-lg-4 col-md-6">
                                          <div class="form-group ">

                                            <label class="form-control-label" for="input-foto">Foto</label>

                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="foto" name="foto" accept="image/*">
                                                <label class="custom-file-label" for="foto">Ubah Foto</label>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                          <div class="form-group">
                                            <label class="form-control-label" for="input-username">Username</label>

                                            <input type="text" onkeypress=""
                                                    class="form-control @error('username') is-invalid @enderror" id="username"
                                                    name="username" value=" {{ old('username') ?? $user->username }}">
                                                @error('username')
                                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                @enderror
                                          </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6">
                                            <div class="form-group ">
                                                <label class="form-control-label" for="input-last-name">Shift</label>
                                                <select class="form-control @error('shift_id') is-invalid @enderror" name="shift_id" id="shift_id" disabled
                                                onchange="">
                                                <option value="">Pilih</option>
                                                @foreach ($shifts as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $item->id == $user->shift_id ? 'selected' : '' }}>{{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('shift_id')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                            @enderror
                                              </div>
                                        </div>

                                        <div class="col-lg-4 col-md-6">
                                            <div class="form-group ">
                                              <label class="form-control-label" for="input-last-name">Role</label>
                                              <select class="form-control @error('role') is-invalid @enderror" name="role" id="role" disabled
                                              onchange="select_role()">
                                              <option value="">Pilih</option>
                                              @foreach ($roles as $item)
                                                  <option value="{{ $item->id }}"
                                                      {{ $item->id == $user->role_id ? 'selected' : '' }}>{{ $item->role }}
                                                  </option>
                                              @endforeach
                                          </select>
                                          @error('role')
                                              <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                          @enderror
                                            </div>
                                          </div>
                                            <div class="col-lg-4 col-md-6">
                                              <div class="form-group ">
                                                <label class="form-control-label" for="input-first-name">Lokasi Presensi</label>
                                                <select class="form-control select2 @error('lokasi') is-invalid @enderror" name="lokasi[]" disabled
                                                        id="lokasi" multiple="multiple">
                                                        <option value="">Pilih</option>
                                                        @foreach ($lokasi as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ is_array($user->lokasi_id) ? (in_array($item->id, $user->lokasi_id) ? 'selected' : '') : '' }}>
                                                                {{ $item->nama_lokasi }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('lokasi')
                                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                    @enderror
                                              </div>
                                            </div>
                                      </div>
                                      <div class="row">
                                        <div class="col-lg-2 col-md-2">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                                        </div>
                                        </div>
                                      </div>


                                    </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="password-tab-panel" role="tabpanel" aria-labelledby="password-tab">
                                    <div class="row justify-content-center">
                                        <div class="col-md-6">
                                            <form action=" {{ route('update-password', Auth::user()->id) }} " method="post">
                                                @method('patch')
                                                @csrf
                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input type="password" class="form-control  @error('password') is-invalid @enderror" id="password" name="password">
                                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="password_baru">Password Baru</label>
                                                    <input type="password" class="form-control  @error('password_baru') is-invalid @enderror" id="password_baru" name="password_baru">
                                                    @error('password_baru')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="konfirmasi_password">Konfirmasi Password</label>
                                                    <input type="password" class="form-control  @error('konfirmasi_password') is-invalid @enderror" id="konfirmasi_password" name="konfirmasi_password">
                                                    @error('konfirmasi_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-2">
                                                        <div class="form-group">
                                                            <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="informasi-personal" role="tabpanel" aria-labelledby="informasi-personal-tab">
                                    <form action=" {{ route('update-personal') }} " method="post">
                                        @method('patch')
                                        @csrf
                                    <div class="pl-lg-4">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="input-nik">NIK</label>

                                                    <input type="text" onkeypress="return hanyaAngka(event)"
                                                            class="form-control @error('nik') is-invalid @enderror" id="nik"
                                                            name="nik" value="{{ $user->personalInformation->nik }}">
                                                        @error('nik')
                                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                        @enderror
                                                  </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-first-name">Nama</label>
                                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                                            name="nama" value="{{ $user->nama }}">
                                                        @error('nama')
                                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                        @enderror
                                                  </div>
                                            </div>


                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-gelar_depan">Gelar Depan</label>

                                                    <input type="text" onkeypress=""
                                                            class="form-control @error('gelar_depan') is-invalid @enderror" id="gelar_depan"
                                                            name="gelar_depan" value="{{ $user->personalInformation->gelar_depan }}">
                                                        @error('gelar_depan')
                                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                        @enderror
                                                  </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-gelar_belakang">Gelar Belakang</label>

                                                    <input type="text" onkeypress=""
                                                            class="form-control @error('gelar_belakang') is-invalid @enderror" id="gelar_belakang"
                                                            name="gelar_belakang" value="{{ $user->personalInformation->gelar_belakang }}">
                                                        @error('gelar_belakang')
                                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                        @enderror
                                                  </div>
                                            </div>


                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-tempat_lahir">Tempat Lahir</label>

                                                    <input type="text" onkeypress=""
                                                            class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir"
                                                            name="tempat_lahir" value="{{ $user->personalInformation->tempat_lahir }}">
                                                        @error('tempat_lahir')
                                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                        @enderror
                                                  </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-tanggal_lahir">Tanggal Lahir</label>

                                                    <input type="text" onkeypress=""
                                                            class="form-control datepicker @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir"
                                                            name="tanggal_lahir" value="{{ $user->personalInformation->tanggal_lahir }}">
                                                        @error('tanggal_lahir')
                                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                        @enderror
                                                  </div>
                                            </div>


                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-jenis_kelamin">Jenis Kelamin</label>

                                                    <select class="form-control @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" id="jenis_kelamin"
                                                    onchange="">
                                                    <option value="">Pilih</option>
                                                    <option value="P">Perempuan </option>
                                                    <option value="L">Laki - Laki </option>
                                                    </select>
                                                    @error('jenis_kelamin')
                                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                    @enderror
                                                  </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-agama">Agama</label>

                                                    <select class="form-control @error('agama') is-invalid @enderror" name="agama" id="agama"
                                                    onchange="">
                                                    <option value="">Pilih</option>
                                                    <option value="Islam">Islam </option>
                                                    <option value="Protestan">Protestan </option>
                                                    <option value="Katolik">Katolik </option>
                                                    <option value="Hindu">Hindu </option>
                                                    <option value="Buddha">Buddha </option>
                                                    <option value="Khonghucu">Khonghucu </option>
                                                    </select>
                                                    @error('agama')
                                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                    @enderror
                                                  </div>
                                            </div>


                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-golongan_darah">Golongan Darah</label>

                                                    <select class="form-control @error('golongan_darah') is-invalid @enderror" name="golongan_darah" id="golongan_darah"
                                                    onchange="">
                                                    <option value="">Pilih</option>
                                                    <option value="A">A </option>
                                                    <option value="B">B </option>
                                                    <option value="AB">AB </option>
                                                    <option value="O">O </option>

                                                    </select>
                                                    @error('golongan_darah')
                                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                    @enderror
                                                  </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-agama">Status Kawin</label>

                                                    <select class="form-control @error('status_kawin') is-invalid @enderror" name="status_kawin" id="status_kawin"
                                                    onchange="">
                                                    <option value="">Pilih</option>
                                                    <option value="Kawin">Kawin </option>
                                                    <option value="Belum Kawin">Belum Kawin </option>
                                                    <option value="Cerai Hidup">Cerai Hidup </option>
                                                    <option value="Cerai Mati">Cerai Mati </option>

                                                    </select>
                                                    @error('status_kawin')
                                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                    @enderror
                                                  </div>
                                            </div>


                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-no_telp">Nomor Telepon</label>

                                                    <input type="text" onkeypress=""
                                                            class="form-control @error('no_telp') is-invalid @enderror" id="no_telp"
                                                            name="no_telp" value="{{ $user->personalInformation->no_telp }}">
                                                        @error('no_telp')
                                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                        @enderror
                                                  </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-email">E-Mail</label>

                                                    <input type="email" onkeypress=""
                                                            class="form-control @error('email') is-invalid @enderror" id="email"
                                                            name="email" value="{{ $user->personalInformation->email }}">
                                                        @error('email')
                                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                        @enderror
                                                  </div>
                                            </div>


                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-alamat_ktp">Alamat KTP</label>

                                                    <input type="text" onkeypress=""
                                                            class="form-control @error('alamat_ktp') is-invalid @enderror" id="alamat_ktp"
                                                            name="alamat_ktp" value="{{ $user->personalInformation->alamat_ktp }}">
                                                        @error('alamat_ktp')
                                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                        @enderror
                                                  </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-alamat_domisili">Alamat Domisili</label>

                                                    <input type="text" onkeypress=""
                                                            class="form-control @error('alamat_domisili') is-invalid @enderror" id="alamat_domisili"
                                                            name="alamat_domisili" value="{{ $user->personalInformation->alamat_domisili }}">
                                                        @error('alamat_domisili')
                                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                        @enderror
                                                  </div>
                                            </div>


                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-npwp">NPWP</label>

                                                    <input type="text" onkeypress=""
                                                            class="form-control @error('npwp') is-invalid @enderror" id="npwp"
                                                            name="npwp" value="{{ $user->personalInformation->npwp }}">
                                                        @error('npwp')
                                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                        @enderror
                                                  </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-gaji">Gaji</label>

                                                    <input type="text" onkeypress=""
                                                            class="form-control @error('gaji') is-invalid @enderror" disabled id="gaji"
                                                            name="gaji" value="{{ $user->personalInformation->gaji }}">
                                                        @error('gaji')
                                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                        @enderror
                                                  </div>
                                            </div>


                                            <div class="col-lg-4 col-md-6">
                                              <div class="form-group ">
                                                <label class="form-control-label" for="input-last-name">OPD</label>
                                                <select class="form-control select2 @error('opd_id') is-invalid @enderror"  name="opd_id" id="opd_id"
                                                onchange="getUnitKerja()">
                                                <option value="">Pilih</option>
                                                @foreach ($opds as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ $item->id == $user->personalInformation->opd_id ? 'selected' : '' }}>{{ $item->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('opd_id')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                            @enderror
                                              </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-last-name">Unit Kerja</label>
                                                    <select class="form-control select2 @error('unit_kerja_id') is-invalid @enderror"  name="unit_kerja_id" id="unit_kerja_id"
                                                    onchange="getSubUnitKerja()">
                                                    <option value="">Pilih</option>

                                                    </select>
                                                    @error('unit_kerja_id')
                                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                    @enderror
                                                  </div>
                                            </div>


                                            <div class="col-lg-4 col-md-6">
                                                <div class="form-group ">
                                                    <label class="form-control-label" for="input-last-name">Sub Unit Kerja</label>
                                                    <select class="form-control select2 @error('subunit_kerja_id') is-invalid @enderror"   name="subunit_kerja_id" id="subunit_kerja_id"
                                                    onchange="">
                                                    <option value="">Pilih</option>

                                                    </select>
                                                    @error('subunit_kerja_id')
                                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                                    @enderror
                                                  </div>
                                            </div>
                                            <div class="col-lg-4 col-md-6">

                                            </div>


                                            <div class="col-lg-4 col-md-6"></div>



                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary btn-block">Simpan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
                                </div>

                                {{-- Riwayat Kerja --}}
                                <div class="tab-pane fade" id="riwayat-kerja" role="tabpanel" aria-labelledby="riwayat-kerja-tab">
                                    @include('users.partials.riwayat_kerja')


                                </div>

                              {{-- Riwayat Pendidikan --}}
                                <div class="tab-pane fade" id="riwayat-pendidikan" role="tabpanel" aria-labelledby="riwayat-pendidikan-tab">
                                    @include('users.partials.riwayat_pendidikan')
                                </div>

                              {{-- Riwayat Seminar --}}
                                <div class="tab-pane fade" id="riwayat-pelatihan" role="tabpanel" aria-labelledby="riwayat-pelatihan-tab">
                                    @include('users.partials.riwayat_pelatihan')
                                </div>



                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('scripts')
<script src="{{ url('argon') }}/assets/js/plugins/datatable/jquery.dataTables.min.js"></script>
<script src="{{ url('argon') }}/assets/js/plugins/datatable/dataTables.bootstrap4.min.js"></script>
    <script>
           var table_riwayat_kerja,table_riwayat_pelatihan,table_riwayat_pendidikan;
        $('.datepicker').datetimepicker({
            timepicker: false,
            format: 'Y-m-d',

            scrollMonth: false,
            scrollInput: false
        });
 $('.select2').select2({
                    theme: "bootstrap"
                });

                getUnitKerja({{ $user->personalInformation->unit_kerja_id }});
        $('#jenis_kelamin').val('{{ $user->personalInformation->jenis_kelamin }}')
        $('#agama').val('{{ $user->personalInformation->agama }}')
        $('#golongan_darah').val('{{ $user->personalInformation->golongan_darah }}')
        $('#status_kawin').val('{{ $user->personalInformation->status_kawin }}')
        function getUnitKerja(unit_kerja = null){
        val = $('#opd_id').val();

        option = "<option value=''>Pilih</option>";
        if(val == ""){
            $('#unit_kerja_id').html(option);
        }else{
            $('#unit_kerja_id').html("<option value=''>Loading...</option>");
            url = "{{ route('config.getUnit') }}";
            $.post(url,{ opd_id: val }, function(data){
                $.each(data, function( index, value ) {
                    option += "<option value='" + value.id + "'>" + value.nama +"</li>";
                });
                $('#unit_kerja_id').html(option);
                if(unit_kerja != null){
                    $('#unit_kerja_id').val(unit_kerja);
                    getSubUnitKerja({{ $user->personalInformation->subunit_kerja_id }});
                }else{
                    getSubUnitKerja();
                }


            }, 'JSON');
        }
    }
    function getSubUnitKerja(subunit_kerja = null){
        val = $('#unit_kerja_id').val();

        option = "<option value=''>Pilih</option>";
        if(val == ""){
            $('#subunit_kerja_id').html(option);
        }else{
            $('#subunit_kerja_id').html("<option value=''>Loading...</option>");
            url = "{{ route('config.getSubUnit') }}";
            $.post(url,{ unit_kerja_id: val }, function(data){
                $.each(data, function( index, value ) {
                    option += "<option value='" + value.id + "'>" + value.nama +"</li>";
                });
                $('#subunit_kerja_id').html(option);
                if(subunit_kerja != null){
                    $('#subunit_kerja_id').val(subunit_kerja);
                }


            }, 'JSON');
        }
    }
    function hanyaAngka(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode
    if (charCode > 31 && (charCode < 48 || charCode > 57))
        return false;
    return true;
}

function removeRiwayat(id,type) {
            $.confirm({
                title: '',
                content: 'Apakah Anda yakin akan menghapus data ini?',
                icon: 'icon icon-question amber-text',
                theme: 'modern',
                closeIcon: true,
                animation: 'scale',
                type: 'red',
                buttons: {
                    ok: {
                        text: "ok!",
                        btnClass: 'btn-primary',
                        keys: ['enter'],
                        action: function() {
                            $.ajax({
                                url: "{{ route('profil.remove_riwayat', ':id') }}".replace(':id', id),
                                type: "POST",
                                data: {
                                    '_method': 'DELETE',
                                    '_token': '{{ csrf_token() }}',
                                    'type': type,
                                },
                                success: function(data) {
                                   if(type == 1){
                                    table_riwayat_kerja.columns.adjust().draw();
                                   }
                                   if(type == 3){
                                    table_riwayat_pelatihan.columns.adjust().draw();
                                   }
                                   if(type == 2){
                                    table_riwayat_pendidikan.columns.adjust().draw();
                                   }
                                    // if (id == $('#id').val()) {
                                    //     add();
                                    // }
                                    $.alert({type:'green',title:'',content:data.message});
                                },
                                error: function() {
                                    console.log('Opssss...');
                                    reload();
                                }
                            });
                        }
                    },
                    cancel: function() {
                        console.log('the user clicked cancel');
                    }
                }
            });
        }
    </script>

@endpush
