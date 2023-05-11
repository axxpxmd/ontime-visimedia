@extends('layouts.app')
@section('title')
Tambah User - {{ config('app.name') }}
@endsection
@section('content')

    {{-- <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow h-100">
                <div class="card-header">
                    <h5 class="m-0 pt-1 font-weight-bold float-left">Tambah User</h5>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary float-right ">Kembali</a>
                </div>
                <div class="card-body">
                    <form action=" {{ route('users.store') }} " method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="text-center mb-3">
                            <img id="image" src="{{ asset('argon/default.png') }}" alt="{{ asset('argon/default.png') }}" width="200px" height="200px" class="img-thumbnail mb-1">
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="foto" class="float-right col-form-label">Foto</label></div>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="foto" name="foto" accept="image/*">
                                    <label class="custom-file-label" for="foto">Ubah Foto</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="nama" class="float-right col-form-label">Nama</label></div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}">
                                @error('nama') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="username" class="float-right col-form-label">Username</label></div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}">
                                @error('username') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="uker" class="float-right col-form-label ">Unit Kerja</label></div>
                            <div class="col-sm-9">
                                <select class="form-control @error('uker') is-invalid @enderror" name="uker" id="uker" onchange="getAtasan()">
                                    <option value="">Pilih</option>
                                    @foreach ($ukers as $item)
                                        <option value="{{ $item->id }}" {{ old('uker') == $item->id ? 'selected' : '' }}>{{ $item->unit_kerja }}</option>
                                    @endforeach
                                </select>
                                @error('uker') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="role" class="float-right col-form-label">Role</label></div>
                            <div class="col-sm-9">
                                <select class="form-control @error('role') is-invalid @enderror" name="role" id="role" onchange="select_role()">
                                    <option value="">Pilih</option>
                                    @foreach ($roles as $item)
                                        <option value="{{ $item->id }}" {{ old('role') == $item->id ? 'selected' : '' }}>{{ $item->role }}</option>
                                    @endforeach
                                </select>
                                @error('role') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="atasan" class="float-right col-form-label " id="lAtasan">Nama Atasan</label></div>
                            <div class="col-sm-9">
                                <select class="form-control @error('atasan') is-invalid @enderror" name="atasan" id="atasan">
                                    <option value="">Pilih</option>

                                </select>
                                @error('atasan') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-3"><label for="lokasi" class="float-right col-form-label ">Lokasi Presensi</label></div>
                            <div class="col-sm-9">
                                <select class="form-control select2 @error('lokasi') is-invalid @enderror" name="lokasi[]" id="lokasi" multiple="multiple">
                                    <option value="">Pilih</option>
                                    @foreach ($location as $item)
                                        <option value="{{ $item->id }}" {{ old('lokasi') == $item->id ? 'selected' : '' }}>{{ $item->nama_lokasi }}</option>
                                    @endforeach
                                </select>
                                @error('lokasi') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="sk" class="float-right col-form-label">SK</label></div>
                            <div class="col-sm-9">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input2" id="sk" name="sk" accept=
                                    "application/msword, application/pdf">
                                    <label class="custom-file-label" for="sk">Ubah SK</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="sallary" class="float-right col-form-label">Sallary</label></div>
                            <div class="col-sm-9">
                                <input type="text" class="form-control @error('sallary') is-invalid @enderror" id="sallary" name="sallary" value="{{ old('sallary') }}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" >
                                @error('sallary') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-3"><label for="shift" class="float-right col-form-label">Shift</label>
                            </div>
                            <div class="col-sm-9">
                                <select class="form-control @error('shift_id') is-invalid @enderror" name="shift_id" id="shift_id"
                                    onchange="">
                                    <option value="">Pilih</option>
                                    @foreach ($shifts as $item)
                                        <option value="{{ $item->id }}"
                                           >{{ $item->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('shift_id')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row justify-content-end">
                            <div class="col-sm-10">
                                <button type="submit" class="btn btn-success btn-block">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="row ">

        <div class="col-md-12">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-white border-0">
                  <div class="row align-items-center">
                    <div class="col-8">
                      <h3 class="mb-0">Create</h3>
                    </div>
                    <div class="col-4 text-right">
                      <a href="{{ route('users.index') }}" class="btn btn-sm btn-primary">Kembali</a>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                    <form action=" {{ route('users.store') }} " autocomplete="off" method="post" enctype="multipart/form-data">
                        @csrf
                    <h6 class="heading-small text-muted mb-4">Akun</h6>
                    <div class="pl-lg-4">
                        {{-- <div class="row justify-content-center mb-2">

                            <div class="">
                                <a href="#">
                                  <img src="{{ old('foto') }}" style="max-width: 180px" class="rounded-circle">
                                </a>
                              </div>

                        </div> --}}
                      <div class="row">

                        <div class="col-lg-4 col-md-6">
                          <div class="form-group ">

                            <label class="form-control-label" for="input-foto">Foto</label>

                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="foto" name="foto" accept="image/*">
                                <label class="custom-file-label" for="foto">Foto</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                          <div class="form-group">
                            <label class="form-control-label" for="input-username">Username</label>

                            <input type="text" onkeypress=""
                                    class="form-control @error('username') is-invalid @enderror" id="username"
                                    name="username" value="{{ old('username') }}">
                                @error('username')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                          </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group ">
                                <label class="form-control-label" for="input-last-name">Shift</label>
                                <select class="form-control @error('shift_id') is-invalid @enderror" name="shift_id" id="shift_id"
                                onchange="">
                                <option value="">Pilih</option>
                                @foreach ($shifts as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $item->id == old('shift_id') ? 'selected' : '' }}>{{ $item->name }}
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
                                <label class="form-control-label" for="input-last-name">Sanksi</label>
                                <select class="form-control @error('tmsanksi_id') is-invalid @enderror" name="tmsanksi_id" id="tmsanksi_id"
                                onchange="">

                                @foreach ($tmsanksi as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $item->id == old('tmsanksi_id') ? 'selected' : '' }}>{{ $item->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tmsanksi_id')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                            @enderror
                              </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="form-group ">
                              <label class="form-control-label" for="input-last-name">Role</label>
                              <select class="form-control @error('role') is-invalid @enderror" name="role" id="role"
                              onchange="select_role()" required>
                              <option value="">Pilih</option>
                              @foreach ($roles as $item)
                                  <option value="{{ $item->id }}"
                                      {{ $item->id == old('role_id') ? 'selected' : '' }}>{{ $item->role }}
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
                                <select class="form-control select2 @error('lokasi') is-invalid @enderror" name="lokasi[]"
                                        id="lokasi" multiple="multiple">
                                        <option value="">Pilih</option>
                                        @foreach ($lokasi as $item)
                                            <option value="{{ $item->id }}"
                                               >
                                                {{ $item->nama_lokasi }}</option>
                                        @endforeach
                                    </select>
                                    @error('lokasi')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                              </div>
                            </div>
                      </div>


                    </div>
                    <hr class="my-4">
                    <!-- Address -->
                    <h6 class="heading-small text-muted mb-4">Informasi Personal</h6>
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-nik">NIK</label>

                                    <input type="text" onkeypress="return hanyaAngka(event)"
                                            class="form-control @error('nik') is-invalid @enderror" id="nik"
                                            name="nik" value="{{ old('nik') }}">
                                        @error('nik')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                  </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label" for="input-first-name">Nama</label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                            name="nama" value="{{ old('nama') }}">
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
                                            name="gelar_depan" value="{{ old('gelar_depan') }}">
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
                                            name="gelar_belakang" value="{{ old('gelar_belakang') }}">
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
                                            name="tempat_lahir" value="{{ old('tempat_lahir') }}">
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
                                            name="tanggal_lahir" value="{{ old('tanggal_lahir') }}">
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
                                            name="no_telp" value="{{ old('no_telp') }}">
                                        @error('no_telp')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                  </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label" for="input-email">E-Mail</label>

                                    <input type="text" onkeypress=""
                                            class="form-control @error('email') is-invalid @enderror" id="email"
                                            name="email" value="{{ old('email') }}">
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
                                            name="alamat_ktp" value="{{ old('alamat_ktp') }}">
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
                                            name="alamat_domisili" value="{{ old('alamat_domisili') }}">
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
                                            name="npwp" value="{{ old('npwp') }}">
                                        @error('npwp')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                  </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group ">
                                    <label class="form-control-label" for="input-gaji">Gaji</label>

                                    <input type="text" onkeypress="return hanyaAngka(event)"
                                            class="form-control @error('gaji') is-invalid @enderror" id="gaji"
                                            name="gaji" value="{{ old('gaji') }}">
                                        @error('gaji')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror
                                  </div>
                            </div>


                            <div class="col-lg-4 col-md-6">
                              <div class="form-group ">
                                <label class="form-control-label" for="input-last-name">OPD</label>
                                <select class="form-control select2 @error('opd_id') is-invalid @enderror" name="opd_id" id="opd_id"
                                onchange="getUnitKerja();getAtasan();">
                                <option value="">Pilih</option>
                                @foreach ($opds as $item)
                                    <option value="{{ $item->id }}"
                                        {{ $item->id == old('opd_id') ? 'selected' : '' }}>{{ $item->nama }}
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
                                    <select class="form-control select2 @error('unit_kerja_id') is-invalid @enderror" name="unit_kerja_id" id="unit_kerja_id"
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
                                    <select class="form-control select2 @error('subunit_kerja_id') is-invalid @enderror" name="subunit_kerja_id" id="subunit_kerja_id"
                                    onchange="">
                                    <option value="">Pilih</option>

                                    </select>
                                    @error('subunit_kerja_id')
                                        <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                  </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group ">
                                  <label class="form-control-label" for="input-last-name" id="lAtasan">Nama Atasan</label>
                                  <select class="form-control select2 @error('atasan') is-invalid @enderror" name="atasan_id" id="atasan">
                                    <option value="">Pilih</option>

                                </select>
                                @error('atasan') <span class="invalid-feedback" role="alert">{{ $message }}</span> @enderror
                                </div>
                              </div>


                            <div class="col-lg-4 col-md-6"></div>


                    </div>
                    <div class="row">
                        <div class="offset-lg-8 col-lg-4 offset-md-6 col-md-6">
                            <div class="row justify-content-end">
                                <div class="col-sm-4">
                                    <button type="submit" class="btn btn-success btn-block">
                                        Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                  </form>
                </div>
              </div>
        </div>

    </div>
@endsection

@push('scripts')
<script>

    function select_role() {

        const role = ['1','6','7'];

	}

    $('document').ready(function(){
        $(".custom-file-input").on("change", function () {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            readURL(this);
        });
        $(".custom-file-input2").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);

            });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#image').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $('.select2').select2();
    });

    function getAtasan(){
        val = $('#role').val();
        val2 = $('#opd_id').val();
        let option = "<option value=''>Pilih</option>";
        if(val == "" || val2 == "" ){
            $('#atasan').html(option);
        }else{
            $('#atasan').html("<option value=''>Loading...</option>");
            url = "{{ route('user.getAtasan') }}";
            $.get(url,{ role: val, opd_id: val2 }, function(data){
                $.each(data, function( index, value ) {
                    option += "<option value='" + value.id + "'>" + value.nama +"</li>";
                });
                $('#atasan').html(option);

            }, 'JSON');
        }
    }

    function getUnitKerja(){
        val = $('#opd_id').val();

       let option = "<option value=''>Pilih</option>";
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
                getSubUnitKerja();


            }, 'JSON');
        }
    }
    function getSubUnitKerja(){
        val = $('#unit_kerja_id').val();

        let option = "<option value=''>Pilih</option>";
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



            }, 'JSON');
        }
    }

</script>
@endpush
