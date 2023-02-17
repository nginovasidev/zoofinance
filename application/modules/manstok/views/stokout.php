<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<div>
    <div class="page-content page-container" id="page-content">
        <div class="padding">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills card-header-pills no-border" id="tab">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-form" role="tab" aria-controls="tab-form" aria-selected="false">Form</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-data" role="tab" aria-controls="tab-data" aria-selected="false">Data</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="tab-form" role="tabpanel" aria-labelledby="tab-form">
                            <form data-plugin="parsley" data-option="{}" id="form" autocomplete="off">
                                <input type="hidden" class="form-control" id="id" name="id" value="" required>
                                <input type="hidden" class="form-control" id="<?= $this->security->get_csrf_token_name() ?>" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" required>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="no_faktur">No. Dokumen</label>
                                        <select class="form-control sel2" id="no_faktur" name="no_faktur" style="width: 100%" required></select>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <label for="tgl_peroleh">Tanggal Perolehan</label>
                                        <input type="date" class="form-control" id="tgl_peroleh" name="tgl_peroleh" placeholder="Tanggal Perolehan" format="dd-mm-yyyy" required>
                                    </div> -->
                                </div>
                                <table id="tb-perkiraan" class="table table-bordered">
                                    <colgroup>
                                        <col style="width:1%" />
                                        <col style="" />
                                        <col style="width:20%" />
                                        <col style="width:20%" />
                                    </colgroup>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nama Barang</th>
                                            <th class="hrg">Harga Perolehan</th>
                                            <th class="jml">Jumlah Barang Keluar</th>
                                            <!-- <th>Aksi</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="tab-data" role="tabpanel" aria-labelledby="tab-data">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-theme table-row v-middle">
                                    <thead>
                                        <tr>
                                            <th><span>#</span></th>
                                            <th><span>Tanggal</span></th>
                                            <th><span>No. Dokumen</span></th>
                                            <th><span>Nama Barang</span></th>
                                            <th><span>Jumlah Barang</span></th>
                                            <th><span>Harga Barang</span></th>
                                            <!-- <th class="column-2action"></th> -->
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
        $("#tgl_peroleh").val(new Date("<?= date('Y-m-d') ?>").toISOString().substr(0, 10));

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
                                Swal.fire('Sukses', result.message, 'success');
                                $('#form').trigger("reset");
                                // reload window
                                // window.location.reload();
                                // table.ajax.reload();
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
            $('#tgl_peroleh').val('');
            $('.sel2').val(null).trigger('change');
            $('#jml_barang').val('');
            $('#hrg_peroleh').val('');
            $('#ttl_hrg_peroleh').val('');
        });

        $("#no_faktur").select2({
            placeholder: "Pilih Barang",
            minimumInputLength: 0,
            multiple: false,
            allowClear: true,
            ajax: {
                url: url_ajax + 'get_faktur',
                dataType: 'json',
                method: 'POST',
                quietMillis: 100,
                data: function(param) {
                    return {
                        search: param.term, //search term
                        per_page: 5, // page size
                        '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>'
                    };
                },
                processResults: function(data, params) {
                    return {
                        results: data
                    }
                }
            }
        }).on('select2:select', function(e) {
            var data = e.params.data;
            Swal.fire({
                title: "",
                icon: "info",
                text: "Proses mengambil data, mohon ditunggu...",
                didOpen: function() {
                    Swal.showLoading()
                }
            });
            $.ajax({
                url: url_ajax + 'get_faktur_barang',
                type: 'post',
                data: {
                    id: data.id,
                    '<?= $this->security->get_csrf_token_name() ?>': '<?= $this->security->get_csrf_hash() ?>'
                },
                dataType: 'json',
                success: function(result) {
                    if (result.success) {
                        Swal.close();
                        console.log(result.data);
                        for (let i = 0; i < result.data.length; i++) {
                            var $row = $("<tr>");
                            $row.append($("<td>"));
                            $row.append($("<td>").html('<input type="text" class="form-control" id="nama_barang" name="nama_barang[]" value="' + result.data[i].nama_barang + '" data-id="' + result.data[i].id_barang + '" readonly>'));
                            $row.append($("<td>").html('<input type="text" class="form-control" id="hrg_peroleh" name="hrg_peroleh[]" value="' + result.data[i].hrg_peroleh + '" readonly>'));
                            $row.append($("<td>").html('<input type="text" class="form-control" id="jml_barang" name="jml_barang[]" value="' + result.data[i].qty_barang + '" required>'));
                            $row.appendTo("#tb-perkiraan tbody");
                            numberRows($("#tb-perkiraan"));
                        }
                    } else {
                        Swal.close();
                        Swal.fire('Error', result.message, 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                }
            });
        });

        // table = $('#datatable').DataTable({
        //     "serverSide": true,
        //     "processing": true,
        //     "ordering": true,
        //     "paging": true,
        //     "searching": {
        //         "regex": true
        //     },
        //     "lengthMenu": [
        //         [10, 25, 50, 100, -1],
        //         [10, 25, 50, 100, "All"]
        //     ],
        //     "pageLength": 10,
        //     "searchDelay": 500,
        //     "ajax": {
        //         "type": "POST",
        //         "url": url_load,
        //         "dataType": "json",
        //         "data": function(data) {
        //             console.log(data);
        //             // Grab form values containing user options
        //             dataStart = data.start;
        //             let form = {};
        //             Object.keys(data).forEach(function(key) {
        //                 form[key] = data[key] || "";
        //             });

        //             // Add options used by Datatables
        //             let info = {
        //                 "start": data.start || 0,
        //                 "length": data.length,
        //                 "draw": 1,
        //                 "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
        //             };

        //             $.extend(form, info);

        //             return form;
        //         },
        //         "complete": function(response) {
        //             console.log(response);
        //             feather.replace();
        //         }
        //     },
        //     "columns": datatableColumn()
        // }).on('init.dt', function() {
        //     $(this).css('width', '100%');
        // });

        $("#tb-perkiraan").on("click", ".del-perkiraan", function(e) {
            let $this = $(this);
            e.preventDefault();
            if ($this.data('id')) {
                $("#datadelete").append('<input type="hidden" name="is_deleted[]" value="' + $this.data('id') + '" > ');
            }
            var $row = $(this).parent().parent();
            Swal.fire({
                title: "Hapus data ?",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then(function(result) {
                if (result.value) {
                    Swal.fire('Sukses', 'Berhasil menghapus data', 'success', 1000);
                    $row.remove();
                    numberRows($("#tb-perkiraan"));
                } else {
                    Swal.fire('Dibatalkan', 'Batal menghapus data', 'success', 1000);
                }
            });
        });
    });

    // function datatableColumn() {
    //     let columns = [{
    //             data: "id",
    //             orderable: false,
    //             width: 100,
    //             render: function(a, type, data, index) {
    //                 return dataStart + index.row + 1
    //             }
    //         },
    //         {
    //             data: "tgl_peroleh",
    //             orderable: true
    //         },
    //         {
    //             data: "no_faktur",
    //             orderable: false
    //         },
    //         {
    //             data: "nama_barang",
    //             orderable: false
    //         },
    //         {
    //             data: "qty_barang",
    //             orderable: true
    //         },
    //         {
    //             data: "hrg_peroleh",
    //             orderable: true
    //         },
    //         // {
    //         //     data: "id",
    //         //     orderable: false,
    //         //     render: function(a, type, data, index) {
    //         //         let button = ""

    //         //         // if (auth_edit == "1") {
    //         //         //     button += '<button class="btn btn-wave btn-icon-datatable btn-rounded mb-2 light-blue text-white edit" data-id="' + data.id + '">\
    //         //         //                 <i data-feather="edit-3"></i>\
    //         //         //             </button>';
    //         //         // }

    //         //         // if (auth_delete == "1") {
    //         //         //     button += '<button class="btn btn-wave btn-icon-datatable btn-rounded mb-2 red text-white delete" data-id="' + data.id + '">\
    //         //         //                     <i data-feather="trash-2"></i>\
    //         //         //                 </button>';
    //         //         // }

    //         //         button += (button == "") ? "<b>Tidak ada aksi</b>" : ""

    //         //         return button;
    //         //     }
    //         // }
    //     ];

    //     return columns;
    // }

    function numberRows($t) {
        var c = 0;
        $t.find("tbody tr").each(function(ind, el) {
            $(el).find("td:eq(0)").html(++c + ".");
        });
    }

    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        return x1 + x2;
    }
</script>