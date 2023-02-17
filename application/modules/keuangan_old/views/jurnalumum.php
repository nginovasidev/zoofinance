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
                    <form data-plugin="parsley" data-option="{}" id="form" autocomplete="off">
                                <input type="hidden" class="form-control" id="id" name="id" value="" required>
                                <input type="hidden" class="form-control" id="<?= $this->security->get_csrf_token_name() ?>" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" required>
                                <div id="datadelete"></div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-3">
                                            <label for="tgltrx">Tanggal</label>
                                            <input type="date" class="form-control" id="tgltrx" name="tgltrx" placeholder="Tanggal Barang" value="<?=date("Y-m-d")?>" required>
                                        </div>
                                        <div class="col-3">
                                            <label for="nobukti">No Bukti</label>
                                            <input type="text" class="form-control" id="nobukti" name="nobukti" placeholder="No Bukti" required>
                                        </div>
                                        <div class="col-6">
                                            <label for="ket">Keterangan</label>
                                            <textarea class="form-control" id="ket" name="ket" placeholder="Ketarangan" required=""></textarea>
                                            <!-- <input type="text" class="form-control" id="ket" name="ket" placeholder="Keterangan" required> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <table id="tb-perkiraan"  class="table table-bordered">
                                        <colgroup>
                                            <col style="width:1%" />
                                            <col style="" />
                                            <col style="width:15%" />
                                            <col style="width:15%" />
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

    <div id="modal-lihat-data" class="modal fade" data-backdrop="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header ">
                    <div class="modal-title text-md">Preview Data</div>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <input type="text" class="form-control" id="v_tgltrx" disabled="">
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control" id="v_nobukti" disabled="">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-6">
                                <textarea class="form-control" id="v_ket" disabled=""></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="v_perkiraan">
                    </div>

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
                                Swal.fire('Sukses', 'Berhasil menyimpan jurnal umum', 'success');
                                $('#form').trigger("reset");
                                table.ajax.reload();
                                $(".sel2").val(null).trigger('change');
                                $("#tb-perkiraan tbody").html('');
                                $("#tot_d").html('0');
                                $("#tot_k").html('0');
                                $('#modal-input-data').modal("hide");

                                updatelababerjalan(result.message['0'].tgltrx);
                                
                            } else {
                                Swal.fire('Error', result.message, 'error');
                            }
                            console.log(result);
                            // location.reload();

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
                        $('ul#tab li a').first().trigger('click');

                        for (key in result.data) {
                            $('#' + key).val(result.data[key]);
                        }
                        var obj = JSON.parse(result.data.arr_akun);

                        $('#idparent').select2("trigger", "select", {
                            data: {
                                id: result.data.idparent,
                                text: result.data.idparent
                            }
                        });
                        var urut = 0;
                        obj.forEach(function(x) {
                            console.log(x);

                            var $row = $("<tr>");
                            $row.append($("<td>"));
                            $row.append($("<td>").html('<input type="hidden" name="id['+urut+']" value="'+x.idtrx+'"><select class="form-control sel2 akun" style="width: 100%" name="kdakun['+urut+']"></select>'));
                            $row.append($("<td>").html('<input type="text" class="form-control num kredit-d" name="d['+urut+']" value="'+x.debet+'" onkeyup="hitungtotal()" placeholder="0">'));
                            $row.append($("<td>").html('<input type="text" class="form-control num debet-d" name="k['+urut+']" value="'+x.kredit+'" onkeyup="hitungtotal()" placeholder="0">'));
                            $row.append($("<td>").html("<a class='del-perkiraan'  data-id='"+x.idtrx+"' href='#' title='Click to remove this entry'><i class='fa fa-trash-o'></i></a>"));
                            $row.appendTo($("#tb-perkiraan tbody"));
                            $(".num").append($('<input>'));

                            $(".akun").append($("<select><select/>"));

                            var c = 0;
                            $("#tb-perkiraan").find("tbody tr").each(function(ind, el) {
                                  $(el).find("td:eq(0)").html(++c + ".");
                                  // console.log(el);
                                  $('select[name="kdakun['+urut+']"]').select2("trigger", "select", {
                                        data: {
                                            id: x.kd_akun,
                                            text: x.kd_akun + ' | ' +x.nama_akun
                                        }
                                    });
                            });

                            ++urut
                        });

                        hitungtotal();

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
        }).on('click', '.lihat', function() {
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
                    inputdelete = '';
                    $('#idakunsecond').html('');
                    $("#alert_nobukti").html('');
                    var tabel;

                    if (result.success) {
                        // $('ul#tab li a').first().trigger('click');

                        for (key in result.data) {
                            $('#v_' + key).val(result.data[key]);
                        }
                        var obj = JSON.parse(result.data.arr_akun);
                        var urut = 0;
                        var kredit = [];
                        var debet = [];

                        tabel = '<table  class="table table-bordered">\
                            <colgroup>\
                                <col style="width:1%" />\
                                <col style="" />\
                                <col style="width:20%" />\
                                <col style="width:20%" />\
                                <col style="width:1%" />\
                            </colgroup>\
                            <thead>\
                                <tr>\
                                    <th>#</th>\
                                    <th style="position:relative;">Perkiraan</th>\
                                    <th class="kredit">D</th>\
                                    <th class="debet">K</th>\
                                </tr>\
                            </thead>\
                            <tbody>\
                            ';
                        obj.forEach(function(x) {
                            kredit[urut] = x.kredit;
                            debet[urut] = x.debet;
                            urut++;
                            tabel += '<tr>\
                                    <td>'+urut+'</td>\
                                    <td>'+x.nama_akun+'</td>\
                                    <td>'+x.kredit.toLocaleString('en-US')+'</td>\
                                    <td>'+x.debet.toLocaleString('en-US')+'</td>\
                                </tr>';
                        });

                        tabel += '</tbody>\
                            <tfoot>\
                                <tr>\
                                    <th colspan="2"><div class="text-right">Jumlah</div></th>\
                                    <th><div class="text-left">'+debet.reduce(add, 0).toLocaleString('en-US')+'</div></th>\
                                    <th><div class="text-left">'+kredit.reduce(add, 0).toLocaleString('en-US')+'</div></th>\
                                </tr>\
                            </tfoot>\
                        </table>';

                        $('#v_perkiraan').html(tabel);

                        $('#modal-lihat-data').modal("show");
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
                                Swal.fire('Sukses', 'Berhasil menghapus jurnal umum', 'success');
                                table.ajax.reload();
                                // console.log(result.tgl);
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
            addrows();
        }).on('click', '#tambah-data' , function(){
            $('#form').trigger("reset");
            $("#tb-perkiraan tbody").html('');
            $("#tot_d").html('0');
            $("#tot_k").html('0');
            $('#modal-input-data').modal("show"); 
        } );


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
                hitungtotal();
            }
        });


        $('.akun').select2({
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
                    let button = "";

                    button += '<button class="btn btn-sm btn-outline-primary lihat" data-id="'+data.id+'" title="lihat">\
                                    <i class="fa fa-eye"></i>\
                                </button>\
                                ';

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
        $('body').on('DOMNodeInserted', '.akun', function () {
            $(this).select2();
            $(".akun").select2({
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
        });
        
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
        $row.append($("<td>").html('<input type="hidden" name="id[]"><select class="form-control sel2 akun" style="width:100%" name="kdakun[]"></select>'));
        $row.append($("<td>").html('<input type="text" class="form-control num kredit-d" name="d[]" onkeyup="hitungtotal()" placeholder="0">'));
        $row.append($("<td>").html('<input type="text" class="form-control num debet-d" name="k[]" onkeyup="hitungtotal()" placeholder="0">'));
        $row.append($("<td>").html("<a class='del-perkiraan' href='#' title='Click to remove this entry'><i class='fa fa-trash-o'></i></a>"));
        $row.appendTo($("#tb-perkiraan tbody"));
        numberRows($("#tb-perkiraan"));

          $(".akun").append($("<select ><select/>"));
          $(".num").append($('<input>'));
    }

    function hitungtotal(){
        var sumd = 0;
        var sumk = 0;
        var inpsd = document.getElementsByClassName('kredit-d');
            for (var i = 0; i <inpsd.length; i++) {
            var inp=inpsd[i];
                if (inp.value!='') {
                    sumd+=parseInt(inp.value.replaceAll(',',''));
                }
            }
         $("#tot_d").html(rubah(sumd));

         var inpsk = document.getElementsByClassName('debet-d');
            for (var i = 0; i <inpsk.length; i++) {
            var inp=inpsk[i];
                if (inp.value!='') {
                    sumk+=parseInt(inp.value.replaceAll(',',''));
                }
            }

         $("#tot_k").html(rubah(sumk));
         console.log(rubah(sumk));

             if (sumd==sumk) {
                 $("#buttonsimpan").attr('disabled', false);
             } else {
                $("#buttonsimpan").attr('disabled', true);
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
                // location.reload();
            }

        });

    }

    function add(accumulator, a) {
      return accumulator + a;
    }
</script>