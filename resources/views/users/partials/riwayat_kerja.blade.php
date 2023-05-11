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
        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target=".riwayat-kerja-modal" onclick="addRiwayatKerja()">Tambah</button>
    </div>
</div>

<div class="table-responsive">
        <table class="table align-items-center" id="tbl-riwayat-kerja">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Tahun</th>
                    <th>Nomor SK</th>
                    <th>Tanggal SK</th>
                    <th>Pejabat Yang Menetapkan</th>
                    <th>TMT Jabatan</th>
                    <th>Jenis Jabatan</th>
                    <th>Dokumen</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody >

            </tbody>
        </table>

</div>

        <div class="modal fade riwayat-kerja-modal"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title"> <span class="formTitle">Tambah</span> Riwayat Kerja</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form id="form-riwayat-kerja" class="needs-validation" id="form" enctype="multipart/form-data" method="POST" autocomplete="off" novalidate>
                    {{ method_field('POST') }}
                    <input type="hidden" id="rkerja_id" name="id" />
                    <input type="hidden" id="user_id" name="personal_information_id" value="{{ $user->personalInformation->id }}" />
                    @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="alertRiwayatKerja"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div   div class="form-group">
                                <label for="jabatan_id">Jenis Jabatan</label>
                                <select class="form-control  select2riwayat-kerja-modal @error('jabatan_id') is-invalid @enderror"  name="jabatan_id" id="jabatan_id"
                                                      >
                                                      <option value="">Pilih</option>
                                                      @foreach ($jenisJabatan as $item)
                                                          <option value="{{ $item->id }}"
                                                              >{{ $item->nama }}
                                                          </option>
                                                      @endforeach
                                                  </select>
                              </div>

                                <div class="form-group">
                                  <label for="nomer_sk">Nomor SK</label>
                                  <input type="text" name="nomer_sk" class="form-control  " id="nomer_sk" placeholder="">
                                </div>

                                <div class="form-group">
                                  <label for="pejabat_sk">Pejabat Penandatanganan</label>
                                  <input type="text" name="pejabat_sk" class="form-control  " id="pejabat_sk" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_sk">Tanggal SK</label>
                                    <input type="text" name="tanggal_sk" class="form-control  datepicker" id="tanggal_sk" placeholder="">
                                  </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tahun">Tahun</label>
                                <input type="text" name="tahun" class="form-control  " id="tahun" placeholder="">
                              </div>
                          <div class="form-group">
                            <label for="tmt_jabatan">TMT Jabatan</label>
                            <input type="text" name="tmt_jabatan" class="form-control datepicker " id="tmt_jabatan" placeholder="">
                          </div>

                          <div class="form-group">
                            <label for="dokumen">Dokumen</label>
                            {{-- <input type="text" name="file" name="dokumen" id="dokumen" class="form-control  "> --}}
                            <div class="custom-file">
                                <input type="file" name="dokumen" class="custom-file-input" id="dokumenRk"  accept=".pdf" lang="en">
                                <label class="custom-file-label labelDokumen" for="customFileLang">Pilih Dokumen</label>

                            </div>
                          </div>
                          @if (auth()->user()->role_id == 1)
                          <div class="form-group statusRk" >
                            <label for="tmt_jabatan">Verifikasi</label>
                            <select name="statusRk" id="statusRk" class="form-control">
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
                  <a class="btn btn-sm btn-secondary reset" onclick="addRiwayatKerja()" >Reset</a>

                </div>
             </form>
              </div>
          </div>
        </div>
@push('scripts')
<script>
    $( document ).ready(function() {
        $('#dokumenRk').change(function(e){
            var fileName = e.target.files[0].name;
            if(fileName){
                $('.labelDokumen').html(fileName);
            }else{
                $('.labelDokumen').html('Pilih Dokumen');

            }
        });
        $('.select2riwayat-kerja-modal').select2({
            theme: "bootstrap",
   dropdownParent: $('.riwayat-kerja-modal')
})
table_riwayat_kerja = $('#tbl-riwayat-kerja').DataTable({
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
                url: "{{ route('profil.riwayat_kerja') }}",
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
                    data: 'tahun',
                    name: 'tahun'
                },
                {
                    data: 'nomer_sk',
                    name: 'nomer_sk'
                },
                {
                    data: 'tanggal_sk',
                    name: 'tanggal_sk'
                },
                {
                    data: 'pejabat_sk',
                    name: 'pejabat_sk'
                },
                {
                    data: 'tmt_jabatan',
                    name: 'tmt_jabatan'
                },
                {
                    data: 'jabatan_id',
                    name: 'jabatan_id'
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

        table_riwayat_kerja.on('draw.dt', function() {
            var PageInfo = $('#tbl-riwayat-kerja').DataTable().page.info();
            table_riwayat_kerja.column(0, {
                page: 'current'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            });
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        if (e.target.hash == '#riwayat-kerja') {
            table_riwayat_kerja.columns.adjust().draw()
        }
        })


});

function addRiwayatKerja() {
            save_method = "add";
            $('#form-riwayat-kerja').trigger('reset');

            $('.formTitle').html('Tambah Data');
            $('#form-riwayat-kerja input[name=_method]').val('POST');
            $('.txtAction').html('Simpan');
            $('.reset').html('Reset');
            $('.reset').show();
            $('#jabatan_id').focus();
            $("#jabatan_id").val('').trigger('change')
            // $('.alertRiwayatKerja').html('');
            $('.labelDokumen').html('Pilih Dokumen');
        }

        function editRiwayatKerja(id) {
            save_method = 'edit';
            var id = id;
            $('.alertRiwayatKerja').html('');
            $('#form-riwayat-kerja').trigger('reset');
            $('.formTitle').html(
                "Edit Data");
            $('.txtAction').html("Simpan");
            $('.reset').html('Batal');
            $('#form-riwayat-kerja input[name=_method]').val('PATCH');
            $.get("{{ route('riwayat_kerja.edit', ':id') }}".replace(':id', id), function(data) {
                $('.riwayat-kerja-modal').modal('show')
                $('#rkerja_id').val(data.id);
                $('#tahun').val(data.tahun);
                $('#nomer_sk').val(data.nomer_sk);
                $('#tanggal_sk').val(data.tanggal_sk);
                $('#pejabat_sk').val(data.pejabat_sk);
                $('#tmt_jabatan').val(data.tmt_jabatan);
                @if (auth()->user()->role_id == 1)
                $('#statusRk').val(data.status);
                @endif
                $("#jabatan_id").val(data.jabatan_id).trigger('change')
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

        addRiwayatKerja();
        $('#form-riwayat-kerja').on('submit', function(e) {
            if ($(this)[0].checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                $('.alertRiwayatKerja').html('');
                $('#action').attr('disabled', true);;
                if (save_method == 'add') {
                    url = "{{ route('riwayat_kerja.store') }}";
                } else {
                    url = "{{ route('riwayat_kerja.update', ':id') }}".replace(':id', $('#rkerja_id').val());
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
                            $('.alertRiwayatKerja').html(
                                "<div role='alert' class='alert alert-success alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><strong>Success!</strong> " +
                                data.message + "</div>");
                                table_riwayat_kerja.columns.adjust().draw()
                            if (save_method == 'add') {
                                addRiwayatKerja();
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

                        $('.alertRiwayatKerja').html(
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
