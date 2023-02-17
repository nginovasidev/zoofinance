<div>
<!--     <div class="page-hero page-container " id="page-hero">
        <div class="padding d-flex">
            <div class="page-title">
                <h2 class="text-md text-highlight"><?= $page_title ?></h2>
            </div>
            <div class="flex"></div>
        </div>
    </div> -->


    <div class="page-content page-container" id="page-content">
        <div class="padding">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center px-4 py-3" data-toggle="collapse" data-parent="#accordion" data-target="#c_1">
                        <div class="mx-3 d-none d-md-block">
                            <strong>Semua Data</strong>
                        </div>
                        <div class="flex"></div>
                        <div>
                            <button class="btn w-sm mb-1 btn-primary" id="tambah-data" >Tambah Data</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-theme table-row v-middle">
                                    <colgroup>
                                        <col style="width:1%" />
                                        <col style="" />
                                        <col style="" />
                                        <col style="width:5%" />
                                        <col style="width:8%" />
                                        <col style="width:10%" />
                                        <col style="width:10%" />
                                        <col style="width:15%" />
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th><span>#</span></th>
                                            <th><span>KODE AKUN</span></th>
                                            <th><span>NAMA AKUN</span></th>
                                            <th style="width: 10%"><span>NB</span></th>
                                            <th style="width: 10%"><span>POSISI</span></th>
                                            <th style="width: 10%"><span>SALDO DEBET</span></th>
                                            <th style="width: 10%"><span>SALDO KREDIT</span></th>
                                            <th style="width: 10%" class="column-2action"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-input-data" class="modal fade" data-backdrop="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header ">
                    <div class="modal-title text-md">Form Data</div>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form data-plugin="parsley" data-option="{}" id="form">
                        <input type="hidden" class="form-control" id="id" name="id" value="" required>
                        <input type="hidden" class="form-control" id="<?= $this->security->get_csrf_token_name() ?>" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" required>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="idparent">Parent Akun</label>
                                    <select class="form-control sel2" id="idparent" name="idparent" style="width: 100%" required>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="kdakun">Kode Akun</label>
                                    <input type="text" class="form-control" id="kdakun" name="kdakun" required  placeholder="Kode Akun">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="namaakun">Nama Akun</label>
                            <input type="text" class="form-control" id="namaakun" name="namaakun" required autocomplete="off" placeholder="Tentukan nama akun">
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="normalbalance">Normal Balance</label>
                                    <select class="form-control sel2" id="normalbalance" name="normalbalance" style="width: 100%" required>
                                        <option value="">Pilih Balance</option>
                                        <option value="D">Debit</option>
                                        <option value="K">Kredit</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="posisi">Posisi</label>
                                    <select class="form-control sel2" id="posisi" name="posisi" style="width: 100%" required>
                                        <option value="">Pilih Balance</option>
                                        <option value="Neraca">Neraca</option>
                                        <option value="Laba Rugi">Laba Rugi</option>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group" id="idsaldodebet" style="display: none;">
                            <label for="saldoawal_d">Saldo Awal Debet</label>
                            <input type="text" class="form-control num" id="ssaldoawal_d" name="saldoawal_d" autocomplete="off" placeholder="Tentukan saldo debet anda">
                        </div>
                        <div class="form-group" id="idsaldokredit" style="display: none;">
                            <label for="saldoawal_k">Saldo Awal Kredit</label>
                            <input type="text" class="form-control num" id="ssaldoawal_k" name="saldoawal_k" autocomplete="off" placeholder="Tentukan saldo kredit anda">
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="is_cash">Akun Kas</label>
                                    <select class="form-control" id="is_cash" name="is_cash" required>
                                        <option value="0">TIDAK</option>
                                        <option value="1">YA</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="is_tampungan">Akun Tampungan</label>
                                    <select class="form-control" id="is_tampungan" name="is_tampungan" required>
                                        <option value="0">TIDAK</option>
                                        <option value="1">YA</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="divopname" style="display: none;">
                            <div class="row">
                                <div class="col-6">
                                    <label for="is_opname">Akun Opname</label>
                                    <select class="form-control" id="is_opname" name="is_opname" required>
                                        <option value="0">TIDAK</option>
                                        <option value="1">YA</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <!-- <button type="submit" class="btn btn-primary">Simpan</button> -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" form="form">Simpan</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
    </div>
</div>
<script type="text/javascript">
    const auth_insert = '<?= $rules->i ?>';
    const auth_edit = '<?= $rules->e ?>';
    const auth_delete = '<?= $rules->d ?>';
    const auth_otorisasi = '<?= $rules->o ?>';

    const url_insert = '<?= base_url() . $this->uri->segment(1) . "/page/" . $this->uri->segment(2) . "_save" ?>';
    const url_edit = '<?= base_url() . $this->uri->segment(1) . "/page/" . $this->uri->segment(2) . "_edit" ?>';
    const url_delete = '<?= base_url() . $this->uri->segment(1) . "/page/" . $this->uri->segment(2) . "_delete" ?>';
    const url_load = '<?= base_url() . $this->uri->segment(1) . "/page/" . $this->uri->segment(2) . "_load" ?>';
    const url_ajax = '<?= base_url() . $this->uri->segment(1) . "/ajaxfile/" ?>';

    var dataStart = 0;
    var table;

    $(document).ready(function() {

        $('.num').mask('000,000,000,000,000', {reverse: true});

        // $('#datatable')

        $(document).on('submit', '#form', function(e) {
            e.preventDefault();
            let $this = $(this);

            Swal.fire({
                title: "Simpan data ?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Simpan",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    Swal.fire({
                        title: "",
                        icon: "info",
                        text: "Proses menyimpan data, mohon ditunggu...",
                        didOpen: function() {
                            Swal.showLoading()
                        }
                    });

                    $.ajax({
                        url: url_insert,
                        type: 'post',
                        data: $this.serialize(),
                        dataType: 'json',
                        success: function(result) {
                            Swal.close();
                            if (result.success) {
                                Swal.fire('Sukses', 'Berhasil menyimpan akun', 'success');
                                table.ajax.reload();

                                $('#modal-input-data').modal("hide");
                                $('#form').trigger("reset");
                                $('.sel2').val(null).trigger('change');
                                // location.reload();
                            } else {
                                Swal.fire('Error', result.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.close();
                            Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                        }
                    });
                }
            });
        }).on('reset', '#form', function() {
            $('#id').val('');
            $('.sel2').val(null).trigger('change');
        });

        table = $('#datatable').DataTable({
            "serverSide": true,
            "processing": true,
            "ordering": true,
            "paging": true,
            "searching": {
                "regex": true
            },
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            "pageLength": 10,
            "searchDelay": 500,
            "ajax": {
                "type": "POST",
                "url": url_load,
                "dataType": "json",
                "data": function(data) {
                    // console.log(data);
                    // Grab form values containing user options
                    dataStart = data.start;
                    let form = {};
                    Object.keys(data).forEach(function(key) {
                        form[key] = data[key] || "";
                    });

                    // Add options used by Datatables
                    let info = {
                        "start": data.start || 0,
                        "length": data.length,
                        "draw": 1,
                        "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                    };

                    $.extend(form, info);

                    return form;
                },
                "complete": function(response) {
                    console.log(response);
                    feather.replace();
                }
            },
            "columns": datatableColumn()
        }).on('init.dt', function() {
            $(this).css('width', '100%');
        });

        $(document).on('click', '.edit', function() {
            let $this = $(this);

            Swal.fire({
                title: "",
                icon: "info",
                text: "Proses mengambil data, mohon ditunggu...",
                didOpen: function() {
                    Swal.showLoading()
                }
            });

            $.ajax({
                url: url_edit,
                type: 'post',
                data: {
                    id: $this.data('id'),
                    "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                },
                dataType: 'json',
                success: function(result) {
                    Swal.close();
                    if (result.success) {
                        // $('ul#tab li a').first().trigger('click');

                        for (key in result.data) {
                            $('#' + key).val(result.data[key]);
                        }

                        $('#idparent').select2("trigger", "select", {
                            data: {
                                id: result.data.idparent,
                                text: result.data.namaparent
                            }
                        });

                        $('#normalbalance').select2("trigger", "select", {
                            data: {
                                id: result.data.normalbalance
                            }
                        });

                        $('#posisi').select2("trigger", "select", {
                            data: {
                                id: result.data.posisi
                            }
                        });
                        if (result.data.is_saldo==1) {
                            
                            if (result.data.normalbalance=='D') {

                                $('#idsaldodebet').show();
                                $('#idsaldokredit').hide();
                            
                            } else if (result.data.normalbalance=='K') {

                                $('#idsaldodebet').hide();
                                $('#idsaldokredit').show();
                            } else {

                                $('#idsaldodebet').hide();
                                $('#idsaldokredit').hide();

                            }
                        }

                        if (result.data.is_cash=='1') {
                        	$('#divopname').show();
			        	} else {
							$('#divopname').hide();
			        	}

                        $('#modal-input-data').modal("show");

                    } else {
                        Swal.fire('Error', result.message, 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                }
            });
        }).on('click', '.delete', function() {
            let $this = $(this);

            Swal.fire({
                title: "Hapus data ?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Hapus",
                confirmButtonColor: '#d33',
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    $.ajax({
                        url: url_delete,
                        type: 'post',
                        data: {
                            id: $this.data('id'),
                            "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                        },
                        dataType: 'json',
                        success: function(result) {
                            Swal.close();
                            if (result.success) {
                                Swal.fire('Sukses', 'Berhasil menghapus akun', 'success');
                                table.ajax.reload();
                            } else {
                                Swal.fire('Error', result.message, 'error');
                            }
                        },
                        error: function() {
                            Swal.close();
                            Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                        }
                    });
                }
            })
        }).on('change', '#is_cash', function(){
        	if ($('#is_cash').val()==1) {
				$('#divopname').show();
        	} else {
				$('#divopname').hide();
        	}
        } ).on('click', '#tambah-data' , function(){
            $('#form').trigger("reset");
            $('#idparent').val('');
            $(".sel2").val(null).trigger('change')
            $('#idsaldodebet').hide();
            $('#idsaldokredit').hide();;
            $('#modal-input-data').modal("show"); 
            $('#divopname').hide();
        } );


        $('#idparent').select2({
            placeholder: "Pilih Parent",
            minimumInputLength: 0,
            multiple: false,
            allowClear: true,
            ajax: {
                url: url_ajax+'/cariparent',
                dataType: 'json',
                method: 'POST',
                quietMillis: 100,
                data: function(param) {
                    return {
                        search: param.term, //search term
                        per_page: 5, // page size
                        '<?=$this->security->get_csrf_token_name()?>':'<?=$this->security->get_csrf_hash()?>'
                    };
                },
                processResults: function(data, params) {
                    return {
                        results: data
                    }
                }
            }
        }).on('select2:select', function(e) {

            if ($('#id').val()==null || $('#id').val()=='' ) {
                getSuggestCode($('#idparent').val());
            }

            $('#posisi').select2("trigger", "select", {
                data: {
                    id: $('#idparent').select2('data')[0]['posisi']
                }
            });

            $('#normalbalance').select2("trigger", "select", {
                data: {
                    id: $('#idparent').select2('data')[0]['normalbalance']
                }
            });
        });


        $('#normalbalance').select2({
            placeholder: "Pilih normal balance",
            allowClear: true,
        }).on('select2:select', function(e) {

            // if ($('#normalbalance').val()=='D') {

            //     $('#idsaldodebet').show();
            //     $('#idsaldokredit').hide();
            
            // } else if ($('#normalbalance').val()=='K') {

            //     $('#idsaldodebet').hide();
            //     $('#idsaldokredit').show();
            // } else {

            //     $('#idsaldodebet').hide();
            //     $('#idsaldokredit').hide();

            // }
        });

        $('#posisi').select2({
            placeholder: "Pilih posisi",
        });
    });

    function datatableColumn() {
        let columns = [{
                data: "id",
                orderable: false,
                width: 100,
                render: function(a, type, data, index) {
                    return dataStart + index.row + 1
                }
            },
            {
                data: "kdakun",
                orderable: false,
                render: function(a, type, data, index) {

                    var string = data.kdakun;
                    var sp = "&nbsp;&nbsp;";
                    sp.repeat(string.length);

                    return sp.repeat(string.length)+' '+data.kdakun;
                }
            },
            {
                data: "namaakun",
                orderable: true
            },
            {
                data: "normalbalance",
                orderable: true
            },
            {
                data: "posisi",
                orderable: true
            },
            {
                data: "ssaldoawal_d",
                orderable: true,
                className: "text-right"

            },
            {
                data: "ssaldoawal_k",
                orderable: true,
                className: "text-right"
            },
            {
                data: "id",
                orderable: false,
                render: function(a, type, data, index) {
                    let button = ""

                    if (auth_edit == "1") {
                        button += '<button class="btn btn-sm btn-outline-primary edit" data-id="'+data.id+'" title="Edit">\
                                    <i class="fa fa-edit"></i>\
                                </button>\
                                ';
                    }

                    if (auth_delete == "1") {
                        button += '<button class="btn btn-sm btn-outline-danger delete" data-id="'+data.id+'" title="Delete">\
                                        <i class="fa fa-trash-o"></i>\
                                    </button>';
                    }

                    button += (button == "") ? "<b>Tidak ada aksi</b>" : ""

                    return button;
                }
            }
        ];

        return columns;
    }


    function getSuggestCode(vid){

        $.ajax({

            type:'post',

            url: url_ajax+'/getSuggestAkun',

            data:{ 'idparent':vid, '<?=$this->security->get_csrf_token_name()?>':'<?=$this->security->get_csrf_hash()?>' },

            beforeSend:function(){

            },

            success:function(response){

                $('#kdakun').val(response);

                $('#namaakun').focus();

            }

        });

    }
</script>