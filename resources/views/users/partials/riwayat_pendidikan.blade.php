@push('styles')
    <style>
        .select2-container--bootstrap .select2-selection--single{
            height: calc(2.75rem + 2px) !important;
    padding: 0.625rem 0.75rem !important;
        }
        .select2-container--bootstrap .select2-selection--single .select2-selection__rendered {

            color: #8898aa !important;
        }
    </style>
@endpush

<div class="row mb-1">
    <div class="col-md-12 text-right">
        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target=".riwayat-pendidikan-modal" onclick="addRiwayatPendidikan()">Tambah</button>
    </div>
</div>
<div class="table-responsive">

        <table class="table align-items-center" id="tbl-riwayat-pendidikan">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Tingkat</th>
                    <th>Jurusan</th>
                    <th>Lembaga</th>
                    <th>Nomor Ijazah</th>
                    <th>Tanggal Ijazah</th>
                    <th>Tahun Lulus</th>
                    <th>Nilai</th>
                    <th>Dokumen</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody >

            </tbody>
        </table>
</div>


        <div class="modal fade riwayat-pendidikan-modal"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title"> <span class="formTitle">Tambah</span> Riwayat Pendidikan</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form id="form-riwayat-pendidikan" class="needs-validation" id="form" enctype="multipart/form-data" method="POST" autocomplete="off" novalidate>
                    {{ method_field('POST') }}
                    <input type="hidden" id="rpendidikan_id" name="id" />
                    <input type="hidden" id="user_id" name="personal_information_id" value="{{ $user->personalInformation->id }}" />
                    @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="alertRiwayatPendidikan"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div   div class="form-group">
                                <label for="tingkat">Tingkat Pendidikan</label>
                                <select class="form-control  select2riwayat-pendidikan-modal @error('tingkat') is-invalid @enderror"  name="tingkat" id="tingkat"
                                                      >
                                                      <option value="">Pilih</option>
                                                      @foreach ($tingkat_pendidikan as $item)
                                                      <option value="{{ $item }}"
                                                          >{{ $item }}
                                                      </option>
                                                  @endforeach
                                                  </select>
                              </div>

                                <div class="form-group">
                                  <label for="jurusan">Jurusan</label>
                                  <input type="text" name="jurusan" class="form-control  " id="jurusan" placeholder="">
                                </div>

                                <div class="form-group">
                                  <label for="lembaga">Lembaga</label>
                                  <input type="text" name="lembaga" class="form-control  " id="lembaga" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="nomor_ijazah">Nomor Ijazah</label>
                                    <input type="text" name="nomor_ijazah" class="form-control  " id="nomor_ijazah" placeholder="">
                                  </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tgl_ijazah">Tanggal Ijazah</label>
                                <input type="text" name="tgl_ijazah" class="form-control  datepicker" id="tgl_ijazah" placeholder="">
                              </div>
                          <div class="form-group">
                            <label for="tahun_lulus">Tahun Lulus</label>
                            <input type="text" name="tahun_lulus" class="form-control  " id="tahun_lulus" placeholder="">
                          </div>
                          <div class="form-group">
                            <label for="nilai">Nilai</label>
                            <input type="text" name="nilai" class="form-control  " id="nilai" placeholder="">
                          </div>

                          <div class="form-group">
                            <label for="dokumen">Dokumen</label>
                            {{-- <input type="text" name="file" name="dokumen" id="dokumen" class="form-control  "> --}}
                            <div class="custom-file">
                                <input type="file" name="dokumen" class="custom-file-input" id="dokumenRPend"  accept=".pdf" lang="en">
                                <label class="custom-file-label labelDokumen" for="customFileLang">Pilih Dokumen</label>

                            </div>
                          </div>
                          @if (auth()->user()->role_id == 1)
                          <div class="form-group statusRpen" >
                            <label for="tmt_jabatan">Verifikasi</label>
                            <select name="statusRpen" id="statusRpen" class="form-control">
                                <option value="0">Belum Diverifikasi</option>
                                <option value="1">Verifikasi</option>
                                <option value="2">Ditolak</option>
                            </select>
                          </div>
                          @endif
                        </div>
                    </div>




                </div>
                <div class="modal-footer">
                  <button  type="submit" class="btn btn-primary btn-sm txtAction">Simpan</button>
                  <a class="btn btn-sm btn-secondary reset" onclick="addRiwayatPendidikan()" >Reset</a>

                </div>
             </form>
              </div>
          </div>
        </div>
@push('scripts')
<script>
    $( document ).ready(function() {
        $('#dokumenRPend').change(function(e){
            var fileName = e.target.files[0].name;
            if(fileName){
                $('.labelDokumen').html(fileName);
            }else{
                $('.labelDokumen').html('Pilih Dokumen');

            }
        });
        $('.select2riwayat-pendidikan-modal').select2({
            theme: "bootstrap",
   dropdownParent: $('.riwayat-pendidikan-modal')
})
table_riwayat_pendidikan = $('#tbl-riwayat-pendidikan').DataTable({
    responsive: true,
    autoWidth: false,
    searching: false,
            language: {
                paginate: {
                    next: '›', // or '→'
                    previous: '‹' // or '←'
                }
            },
            pageLength: 10,
            processing: true,
            serverSide: true,

            ajax: {
                url: "{{ route('profil.riwayat_pendidikan') }}",
                method: 'GET',
                data:{personal_information_id:{{ $user->personalInformation->id ?? null }}}
            },
            columns: [{
                    data: 'id',
                    name: 'id',
                    orderable: false,
                    searchable: false,
                    align: 'center',
                    className: 'text-center'
                },
                {
                    data: 'tingkat',
                    name: 'tingkat'
                },
                {
                    data: 'jurusan',
                    name: 'jurusan'
                },
                {
                    data: 'lembaga',
                    name: 'lembaga'
                },
                {
                    data: 'nomor_ijazah',
                    name: 'nomor_ijazah'
                },
                {
                    data: 'tgl_ijazah',
                    name: 'tgl_ijazah'
                },



                {
                    data: 'tahun_lulus',
                    name: 'tahun_lulus'
                },
                {
                    data: 'nilai',
                    name: 'nilai'
                },

                {
                    data: 'dokumen',
                    name: 'dokumen'
                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ]
        });

        table_riwayat_pendidikan.on('draw.dt', function() {
            var PageInfo = $('#tbl-riwayat-pendidikan').DataTable().page.info();
            table_riwayat_pendidikan.column(0, {
                page: 'current'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            });
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        if (e.target.hash == '#riwayat-pendidikan') {
            table_riwayat_pendidikan.columns.adjust().draw()
        }
        })


});

function addRiwayatPendidikan() {
            save_method = "add";
            $('#form-riwayat-pendidikan').trigger('reset');
            $('.formTitle').html('Tambah Data');
            $('#form-riwayat-pendidikan input[name=_method]').val('POST');
            $('.txtAction').html('Simpan');
            $('.reset').html('Reset');
            $('.reset').show();
            $('#tingkat').focus();
            $("#tingkat").val('').trigger('change')
            // $('.alertRiwayatPendidikan').html('');
            $('.labelDokumen').html('Pilih Dokumen');
        }

        function editRiwayatPendidikan(id) {
            save_method = 'edit';
            var id = id;
            $('.alertRiwayatPendidikan').html('');
            $('#form-riwayat-pendidikan').trigger('reset');
            $('.formTitle').html(
                "Edit Data");
            $('.txtAction').html("Simpan");
            $('.reset').html('Batal');
            $('#form-riwayat-pendidikan input[name=_method]').val('PATCH');
            $.get("{{ route('riwayat_pendidikan.edit', ':id') }}".replace(':id', id), function(data) {
                $('.riwayat-pendidikan-modal').modal('show')
                $('#rpendidikan_id').val(data.id);
                $('#tgl_ijazah').val(data.tgl_ijazah);
                $('#jurusan').val(data.jurusan);
                $('#nomor_ijazah').val(data.nomor_ijazah);
                $('#lembaga').val(data.lembaga);
                $('#tahun_lulus').val(data.tahun_lulus);
                $('#nilai').val(data.nilai);
                @if (auth()->user()->role_id == 1)
                $('#statusRpen').val(data.status);
                @endif
                $("#tingkat").val(data.tingkat).trigger('change')
                if(data.dokumen){
                    $('.labelDokumen').html('Ganti Dokumen');
                }else{
                    $('.labelDokumen').html('Pilih Dokumen');
                }

            }, "JSON").fail(function() {
                console.log("Nothing Data");
                // reload();
            });
        }

        addRiwayatPendidikan();
        $('#form-riwayat-pendidikan').on('submit', function(e) {
            if ($(this)[0].checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                $('.alertRiwayatPendidikan').html('');
                $('#action').attr('disabled', true);;
                if (save_method == 'add') {
                    url = "{{ route('riwayat_pendidikan.store') }}";
                } else {
                    url = "{{ route('riwayat_pendidikan.update', ':id') }}".replace(':id', $('#rpendidikan_id').val());
                }
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: new FormData($(this)[0]),
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        $('#action').removeAttr('disabled');
                        if (data.success == 1) {
                            $('.alertRiwayatPendidikan').html(
                                "<div role='alert' class='alert alert-success alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><strong>Success!</strong> " +
                                data.message + "</div>");
                                table_riwayat_pendidikan.columns.adjust().draw()
                            if (save_method == 'add') {
                                addRiwayatPendidikan();
                            }
                        }
                    },
                    error: function(data) {
                        $('#action').removeAttr('disabled');
                        err = '';
                        respon = data.responseJSON;
                        $.each(respon.errors, function(index, value) {
                            err = err + "<li>" + value + "</li>";
                        });

                        $('.alertRiwayatPendidikan').html(
                            "<div role='alert' class='alert alert-danger alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><strong>Error!</strong> " +
                            respon.message + "<ol class='pl-3 m-0'>" + err + "</ol></div>");
                    }
                });
                return false;
            }
            $(this).addClass('was-validated');
        });


</script>
@endpush
