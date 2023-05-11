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
        <button class="btn btn-sm btn-primary" data-toggle="modal" data-target=".riwayat-pelatihan-modal" onclick="addRiwayatPelatihan()">Tambah</button>
    </div>
</div>

<div class="table-responsive">
        <table class="table align-items-center" id="tbl-riwayat-pelatihan">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Nama Kegiatan</th>
                    <th>Penyelenggara</th>
                    <th>Nomor Sertifikat</th>
                    <th>Tanggal Sertifikat</th>

                    <th>Dokumen</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody >

            </tbody>
        </table>

</div>

        <div class="modal fade riwayat-pelatihan-modal"  role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title"> <span class="formTitle">Tambah</span> Riwayat Pelatihan</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <form id="form-riwayat-pelatihan" class="needs-validation" id="form" enctype="multipart/form-data" method="POST" autocomplete="off" novalidate>
                    {{ method_field('POST') }}
                    <input type="hidden" id="rpelatihan_id" name="id" />
                    <input type="hidden" id="user_id" name="personal_information_id" value="{{ $user->personalInformation->id }}" />
                    @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="alertRiwayatPelatihan"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">


                                <div class="form-group">
                                  <label for="nama_kegiatan">Nama Kegiatan</label>
                                  <input type="text" name="nama_kegiatan" class="form-control  " id="nama_kegiatan" placeholder="">
                                </div>

                                <div class="form-group">
                                  <label for="penyelenggara">Pejabat Penandatanganan</label>
                                  <input type="text" name="penyelenggara" class="form-control  " id="penyelenggara" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="nomor_sertifikat">Nomor Sertifikat</label>
                                    <input type="text" name="nomor_sertifikat" class="form-control  " id="nomor_sertifikat" placeholder="">
                                  </div>
                        </div>
                        <div class="col-md-6">

                          <div class="form-group">
                            <label for="tanggal_sertifikat">Tanggal Sertifikat</label>
                            <input type="text" name="tanggal_sertifikat" class="form-control datepicker " id="tanggal_sertifikat" placeholder="">
                          </div>

                          <div class="form-group">
                            <label for="dokumen">Dokumen</label>
                            {{-- <input type="text" name="file" name="dokumen" id="dokumen" class="form-control  "> --}}
                            <div class="custom-file">
                                <input type="file" name="dokumen" class="custom-file-input" id="dokumenRPel"  accept=".pdf" lang="en">
                                <label class="custom-file-label labelDokumen" for="customFileLang">Pilih Dokumen</label>

                            </div>
                          </div>
                          @if (auth()->user()->role_id == 1)
                          <div class="form-group statusRp" >
                            <label for="tmt_jabatan">Verifikasi</label>
                            <select name="statusRp" id="statusRp" class="form-control">
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
                  <a class="btn btn-sm btn-secondary reset" onclick="addRiwayatPelatihan()" >Reset</a>

                </div>
             </form>
              </div>
          </div>
        </div>
@push('scripts')
<script>
    $( document ).ready(function() {
        $('#dokumenRPel').change(function(e){
            var fileName = e.target.files[0].name;
            if(fileName){
                $('.labelDokumen').html(fileName);
            }else{
                $('.labelDokumen').html('Pilih Dokumen');

            }
        });

table_riwayat_pelatihan = $('#tbl-riwayat-pelatihan').DataTable({
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
                url: "{{ route('profil.riwayat_pelatihan') }}",
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
                    data: 'nama_kegiatan',
                    name: 'nama_kegiatan'
                },
                {
                    data: 'penyelenggara',
                    name: 'penyelenggara'
                },
                {
                    data: 'nomor_sertifikat',
                    name: 'nomor_sertifikat'
                },
                {
                    data: 'tanggal_sertifikat',
                    name: 'tanggal_sertifikat'
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

        table_riwayat_pelatihan.on('draw.dt', function() {
            var PageInfo = $('#tbl-riwayat-pelatihan').DataTable().page.info();
            table_riwayat_pelatihan.column(0, {
                page: 'current'
            }).nodes().each(function(cell, i) {
                cell.innerHTML = i + 1 + PageInfo.start;
            });
        });

        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        if (e.target.hash == '#riwayat-pelatihan') {
            table_riwayat_pelatihan.columns.adjust().draw()
        }
        })


});

function addRiwayatPelatihan() {
            save_method = "add";
            $('#form-riwayat-pelatihan').trigger('reset');
            $('.formTitle').html('Tambah Data');
            $('#form-riwayat-pelatihan input[name=_method]').val('POST');
            $('.txtAction').html('Simpan');
            $('.reset').html('Reset');
            $('.reset').show();

            // $('.alertRiwayatPelatihan').html('');
            $('.labelDokumen').html('Pilih Dokumen');
        }

        function editRiwayatPelatihan(id) {
            save_method = 'edit';
            var id = id;
            $('.alertRiwayatPelatihan').html('');
            $('#form-riwayat-pelatihan').trigger('reset');
            $('.formTitle').html(
                "Edit Data");
            $('.txtAction').html("Simpan");
            $('.reset').html('Batal');
            $('#form-riwayat-pelatihan input[name=_method]').val('PATCH');
            $.get("{{ route('riwayat_pelatihan.edit', ':id') }}".replace(':id', id), function(data) {
                $('.riwayat-pelatihan-modal').modal('show')
                $('#rpelatihan_id').val(data.id);
                $('#tahun').val(data.tahun);
                $('#nama_kegiatan').val(data.nama_kegiatan);
                $('#nomor_sertifikat').val(data.nomor_sertifikat);
                $('#penyelenggara').val(data.penyelenggara);
                $('#tanggal_sertifikat').val(data.tanggal_sertifikat);
                @if (auth()->user()->role_id == 1)
                $('#statusRp').val(data.status);
                @endif

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

        addRiwayatPelatihan();
        $('#form-riwayat-pelatihan').on('submit', function(e) {
            if ($(this)[0].checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                $('.alertRiwayatPelatihan').html('');
                $('#action').attr('disabled', true);;
                if (save_method == 'add') {
                    url = "{{ route('riwayat_pelatihan.store') }}";
                } else {
                    url = "{{ route('riwayat_pelatihan.update', ':id') }}".replace(':id', $('#rpelatihan_id').val());
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
                            $('.alertRiwayatPelatihan').html(
                                "<div role='alert' class='alert alert-success alert-dismissible'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><strong>Success!</strong> " +
                                data.message + "</div>");
                                table_riwayat_pelatihan.columns.adjust().draw()
                            if (save_method == 'add') {
                                addRiwayatPelatihan();
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

                        $('.alertRiwayatPelatihan').html(
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
