<div>
    <!-- <div class="page-hero page-container " id="page-hero">
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
                        <div class="form-group">
                            <div class="row">
                                
                                <div class="col-6">
                                    <label for="nobukti">Tanggal</label>
                                    <input type="date" class="form-control" id="tgl_trx" name="tgl_trx" value="<?=date("Y-m-d")?>">
                                </div>
                            </div>
                        </div>
                            <div class="table-responsive">
                                <table id="datatable" class="table table-theme table-row v-middle">
                                    <colgroup>
                                        <col style="width:1%" />
                                        <col style="width:15%" />
                                        <col style="width:20%" />
                                        <col style="" />
                                        <col style="width:15%" />
                                        <col style="width:10%" />
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th><span>#</span></th>
                                            <th><span>TANGGAL</span></th>
                                            <th><span>REFF</span></th>
                                            <th><span>KET</span></th>
                                            <th><span>NOMINAL</span></th>
                                            <th class="column-2action"></th>
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
                    <form data-option="{}" id="form" autocomplete="off">
                        <input type="hidden" class="form-control" id="idakunutama" name="idakunutama" value="" required>
                        <input type="hidden" class="form-control" id="<?= $this->security->get_csrf_token_name() ?>" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" required>
                        <div id="datadelete"></div>

                        <div class="form-group">

                            <div class="row">
                                <div class="col-6">
                                    <label for="kdjentrx">Jenis</label>
                                    <select class="form-control sel2" id="kdjentrx" name="kdjentrx" style="width: 100%" required>
                                        <option value="">Pilih Jenis</option>
                                        <option value="1">Pembayaran</option>
                                        <option value="2">Penerimaan</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label for="tgltrx">Tanggal</label>
                                    <input type="date" class="form-control" id="tgltrx" name="tgltrx" value="<?=date("Y-m-d")?>" >
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                
                                <div class="col-6">
                                    <label for="nobukti">No Bukti</label>
                                    <input type="text" class="form-control" id="nobukti" name="nobukti" required  placeholder="No Bukti">
                                    <small id="alert_nobukti" class="form-text text-muted"></small>
                                </div>
                                <div class="col-6">
                                    <label for="ket">Keterangan</label>
                                    <input type="text" class="form-control" id="ket" name="ket" required autocomplete="off" placeholder="Keterangan">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="kdakun" id="labelakunutama"> Kemana </label>
                                    <select class="form-control akunutama sel2" id="kdakunutama" name="kdakunutama" style="width: 100%" required>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label id="labelnominal">Nominal</label>
                                    <input type="text" class="form-control num debet" id="dutama" name="dutama" placeholder="Disini nominal debet">
                                    <input type="hidden" class="form-control num kredit" id="kutama" name="kutama" placeholder="Disini nominal kredit">
                                </div>
                            </div>
                            
                        </div>
                        

                        <div class="form-group" id="">
                            <table id="tb-perkiraan"  class="table table-bordered">
                                <colgroup>
                                    <col style="width:1%" />
                                    <col style="" />
                                    <col style="width:20%" />
                                    <col style="width:20%" />
                                    <col style="width:1%" />
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="position:relative;">Perkiraan
                                        <div style="position:absolute;right:5px;top:5px;">
                                            <a id="add-service-button" data-original-title="Tambah Item Jurnal"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus mx-2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></a>
                                        </div>
                                        </th>
                                        <th class="kredit">D</th>
                                        <th class="debet">K</th>
                                        <th>Act</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2"><div class="text-right">Jumlah</div></th>
                                        <th><div class="text-right" id="tot_d"></div></th>
                                        <th><div class="text-right" id="tot_k"></div></th>
                                        <th><div class="text-right" id="notes"></div></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="text-right">
                            <!-- <button type="submit" class="btn btn-primary" >Simpan</button> -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" form="form" id="buttonsimpan" disabled="">Simpan</button>
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

        tgltrx.max = new Date().toISOString().split("T")[0];
        tgltrx.min = "2021-08-29";

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
                                Swal.fire('Sukses', 'Berhasil menyimpan transaksi', 'success');
                                table.ajax.reload();
                                $('#form').trigger("reset");
                                $(".sel2").val(null).trigger('change');
                                $("#tb-perkiraan tbody").html('');

                                $("#tot_d").html('0');
                                $("#tot_k").html('0');

                                $('#modal-input-data').modal("hide");
                                // console.log(result.message['0'].tgltrx);
                                updatelababerjalan(result.message['0'].tgltrx);

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
                    console.log(data);
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
                        "tgl_trx" : $('#tgl_trx').val(),
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
                beforeSend: function() {
                    $("#tb-perkiraan tbody").html('');
                },
                success: function(result) {
                    Swal.close();
                    if (result.success) {
                        // $('ul#tab li a').first().trigger('click');

                        for (key in result.data) {
                            $('#' + key).val(result.data[key]);
                        }
                        $('#kdjentrx').select2('trigger', 'select', {
                            data : {
                                id: result.data.kdjentrx
                            }
                        });
                        var obj = JSON.parse(result.data.arr_akun);
                        var urut = 0;
                        obj.forEach(function(x) {

                            if (x.is_parent==0) {

                                $('#idakunutama').val(x.idtrx);
                                $('#dutama').val(rubah(x.debet));
                                $('#kutama').val(rubah(x.kredit));

                                var _debet = rubah(x.debet);
                                var _kredit = rubah(x.kredit);
                                $('#kdakunutama').select2('trigger', 'select', {
                                    data : {
                                        id: x.kd_akun,
                                        text: x.kd_akun + ' | ' +x.nama_akun
                                    }
                                });
                            } else {

                                var $row = $("<tr>");
                                $row.append($("<td>"));
                                $row.append($("<td>").html('<input type="hidden" name="id['+urut+']" value="'+x.idtrx+'"><select class="form-control sel2 akun" style="width: 100%" name="kdakun['+urut+']"></select>'));
                                // $row.append($("<td>").html('<input type="text" class="form-control num kredit-d" name="d['+urut+']" value="'+x.debet+'"  onkeyup="hitungtotal()" placeholder="0">'));
                                // $row.append($("<td>").html('<input type="text" class="form-control num debet-d" name="k['+urut+']" value="'+x.kredit+'"  onkeyup="hitungtotal()" placeholder="0">'));

                                if ($('#kdjentrx').val()==1) {

                                    $row.append($("<td>").html('<input type="text" class="form-control num kredit-d" name="d['+urut+']" value="'+x.debet+'" onkeyup="hitungtotal()" placeholder="0" >'));
                                    $row.append($("<td>").html('<input type="text" class="form-control num debet-d" name="k['+urut+']" value="'+x.kredit+'" onkeyup="hitungtotal()" placeholder="0" disabled>'));

                                } else if ($('#kdjentrx').val()==2) {

                                    $row.append($("<td>").html('<input type="text" class="form-control num kredit-d" name="d['+urut+']" value="'+x.debet+'" onkeyup="hitungtotal()" placeholder="0" disabled>'));
                                    $row.append($("<td>").html('<input type="text" class="form-control num debet-d" name="k['+urut+']" value="'+x.kredit+'" onkeyup="hitungtotal()" placeholder="0" >'));

                                } else {
                                    $row.append($("<td>").html('<input type="text" class="form-control num kredit-d" name="d[]" onkeyup="hitungtotal()" placeholder="0" disabled>'));
                                    $row.append($("<td>").html('<input type="text" class="form-control num debet-d" name="k[]" onkeyup="hitungtotal()" placeholder="0" disabled>'));
                                }

                                $row.append($("<td>").html("<a class='del-perkiraan'  data-id='"+x.idtrx+"' href='#' title='Click to remove this entry'><i class='fa fa-trash-o'></i></a>"));
                                $row.appendTo($("#tb-perkiraan tbody"));
                                $(".num").append($('<input>'));

                                $(".akun").append($("<select ><select/>"));

                                var c = 0;
                                $("#tb-perkiraan").find("tbody tr").each(function(ind, el) {
                                      $(el).find("td:eq(0)").html(++c + ".");
                                      $('select[name="kdakun['+urut+']"]').select2("trigger", "select", {
                                            data: {
                                                id: x.kd_akun,
                                                text: x.kd_akun + ' | ' +x.nama_akun
                                            }
                                        });
                                });

                                ++urut
                            }

                            hitungtotal();

                            if ($('#kdjentrx').val()==1) {
                                $("#tot_k").html(_kredit);

                            } else if ($('#kdjentrx').val()==2) {
                                $("#tot_d").html(_debet);
                            }

                        });

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
                                Swal.fire('Sukses', 'Berhasil menghapus transaksi', 'success');
                                table.ajax.reload();
                                updatelababerjalan(result.tgl);
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
        }).on('click', '#add-service-button', function() {
            console.log('add service button');
            addrows();
        }).on('click', '#tambah-data' , function(){
            $('#form').trigger("reset");
            $('#idakunutama').val('');
            $(".sel2").val(null).trigger('change');
            $('#modal-input-data').modal("show"); 
            $("#tb-perkiraan tbody").html('');
            $("#tot_d").html('0');
            $("#tot_k").html('0');
        } ).on('change', '#tgl_trx' , function(){
            updatedatatabel();
        } );

        $('#kdjentrx').select2({
            placeholder: "Pilih jenis transaksi",
            multiple: false,
            allowClear: true,
        }).on('select2:select', function(e) {

            if ($('#kdjentrx').val()==1) {
                $('#labelakunutama').html("Pembayaran");
                $('#labelnominal').html("Nominal Kredit");
                $('.debet').attr('type', 'hidden');
                $('.kredit').attr('type', 'text');

                $('#dutama').val('');
                $('#kutama').val('');

                $("#tot_d").html('0');
                $("#tot_k").html('0');

                $("#tb-perkiraan tbody").html('');

                $('.debet-d').attr('disabled', true);
                $('.kredit-d').attr('disabled', false);

            } else if ($('#kdjentrx').val()==2) {
                $('#labelakunutama').html("Penerimaan");
                $('#labelnominal').html("Nominal Debet");

                $('.debet').attr('type', 'text');
                $('.kredit').attr('type', 'hidden');

                $("#tot_d").html('0');
                $("#tot_k").html('0');
                $('#dutama').val('');
                $('#kutama').val('');

                $("#tb-perkiraan tbody").html('');

                $('.debet-d').attr('disabled', false);
                $('.kredit-d').attr('disabled', true);

            }
        });

        $('.akunutama').select2({
            placeholder: "Pilih akun",
            minimumInputLength: 0,
            multiple: false,
            allowClear: true,
            ajax: {
                url: url_ajax+'/cariakuntransaksi',
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
                        console.log(data);
                }
            }
        }).on('select2:select', function(e) {
        });

        // add row 

        $("#tb-perkiraan").on("click", ".del-perkiraan", function(e) {
            let $this = $(this);
            e.preventDefault();
            if ($this.data('id')) {
                $("#datadelete").append('<input type="hidden" name="is_deleted[]" value="'+$this.data('id')+'" > ');
            }
            var $row = $(this).parent().parent();
            var retResult = confirm("Are you sure you wish to remove this entry?");
            if (retResult) {
                $row.remove();
                numberRows($("#tb-perkiraan"));
            }
        });

        $("#nobukti").keyup(function(){

            $.ajax({

                type:'post',

                url: url_ajax+'ceknomorbukti',

                data:{ nobukti: $("#nobukti").val(), '<?=$this->security->get_csrf_token_name()?>':'<?=$this->security->get_csrf_hash()?>' },

                beforeSend:function(){
                    $("#alert_nobukti").html('loading');
                },

                success:function(response){

                    $("#alert_nobukti").html(response);

                }

            });
        });

        $("#dutama").keyup(function(){
            $("#tot_d").html($("#dutama").val());
            hitungtotal();
        });

        $("#kutama").keyup(function(){
            $("#tot_k").html($("#kutama").val());
            hitungtotal();
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
                data: "tgltrx",
                orderable: true
            },
            {
                data: "nobukti",
                orderable: true
            },
            {
                data: "ket",
                orderable: true
            },
            {
                data: "saldo_k",
                orderable: true
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

    $(function () {
        $('body').on('DOMNodeInserted', '.akun:last', function () {
            
            
            $(this).select2();
            $(".akun:last").select2({
                    placeholder: "Pilih akun",
                    minimumInputLength: 0,
                    multiple: false,
                    allowClear: true,
                    ajax: {
                        url: url_ajax+'/cariakunall',
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
                                console.log(data);
                        }
                    }
                }).on('select2:select', function(e) {
                });
              
              console.log('---');  
        }).on('DOMNodeInserted', '.num:last', function(){
	        $('.num:last').mask('000,000,000,000,000', {reverse: true});
        } );

        
    });

    function numberRows($t) {
        var c = 0;
        $t.find("tbody tr").each(function(ind, el) {
          $(el).find("td:eq(0)").html(++c + ".");
        });
    }

    function addrows(){
        var $row = $("<tr>");
        $row.append($("<td>"));
        $row.append($("<td>").html('<input type="hidden" name="id[]"><select class="form-control sel2 akun" style="width: 100%" name="kdakun[]"></select>'));
          if ($('#kdjentrx').val()==1) {

                $row.append($("<td>").html('<input type="text" class="form-control num kredit-d" name="d[]" onkeyup="hitungtotal()" placeholder="0" >'));
                $row.append($("<td>").html('<input type="text" class="form-control num debet-d" name="k[]" onkeyup="hitungtotal()" placeholder="0" disabled>'));

            } else if ($('#kdjentrx').val()==2) {

                $row.append($("<td>").html('<input type="text" class="form-control num kredit-d" name="d[]" onkeyup="hitungtotal()" placeholder="0" disabled>'));
                $row.append($("<td>").html('<input type="text" class="form-control num debet-d" name="k[]" onkeyup="hitungtotal()" placeholder="0" >'));

            } else {
                $row.append($("<td>").html('<input type="text" class="form-control num kredit-d" name="d[]" onkeyup="hitungtotal()" placeholder="0" disabled>'));
                $row.append($("<td>").html('<input type="text" class="form-control num debet-d" name="k[]" onkeyup="hitungtotal()" placeholder="0" disabled>'));
            }
        $row.append($("<td>").html("<a class='del-perkiraan' href='#' title='Click to remove this entry'><i class='fa fa-trash-o'></i></a>"));
        $row.appendTo($("#tb-perkiraan tbody"));
        numberRows($("#tb-perkiraan"));

          $(".akun:last").append($("<select class='asd'><select/>"));
          $(".num:last").append($('<input class="num">'));
          console.log('add rows');

    }

    function hitungtotal(){
        console.log('hitung total');
        var sum = 0;
        if ($('#kdjentrx').val()==1) {
            // var inps = document.getElementsByName('d[]');
            var inps = document.getElementsByClassName('kredit-d');


                for (var i = 0; i <inps.length; i++) {
                var inp=inps[i];
                    if (inp.value!='') {
                        sum+=parseInt(inp.value.replaceAll(',',''));
                    }
                }
             $("#tot_d").html(rubah(sum));

             if (rubah(sum)==$("#kutama").val()) {
                 $("#buttonsimpan").attr('disabled', false);
             } else {
                $("#buttonsimpan").attr('disabled', true);
             }

        } else if ($('#kdjentrx').val()==2) {
            // var inps = document.getElementsByName('k[]');
             var inps = document.getElementsByClassName('debet-d');

                for (var i = 0; i <inps.length; i++) {
                var inp=inps[i];
                    if (inp.value!='') {
                        sum+=parseInt(inp.value.replaceAll(',',''));
                    }
                }
            $("#tot_k").html(rubah(sum));
            if (rubah(sum)==$("#dutama").val()) {
                $("#buttonsimpan").attr('disabled', false);
             } else {
                $("#buttonsimpan").attr('disabled', true);
             }

        } else {
            
        }

    }

    function rubah(angka){
       var reverse = angka.toString().split('').reverse().join(''),
       ribuan = reverse.match(/\d{1,3}/g);
       ribuan = ribuan.join(',').split('').reverse().join('');
       return ribuan;
    }

    function updatelababerjalan(tgl){

        $.ajax({

            type:'post',

            url: url_ajax+'/updatelababerjalan',

            data:{ 'tanggal':tgl, '<?=$this->security->get_csrf_token_name()?>':'<?=$this->security->get_csrf_hash()?>' },

            beforeSend:function(){

            },

            success:function(response){
                // console.log('done');
                // location.reload();
            }

        });

    }


    function updatedatatabel(){
        // table = '';
        $('#datatable').DataTable().destroy()
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
                    console.log(data);
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
                        "tgl_trx" : $('#tgl_trx').val(),
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
    }
</script>