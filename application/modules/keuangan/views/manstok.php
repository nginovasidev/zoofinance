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
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-4">
                                            <label for="tgl_peroleh">Tanggal</label>
                                            <input type="date" class="form-control" id="tgl_peroleh" name="tgl_peroleh" placeholder="Tanggal Perolehan" required>
                                        </div>
                                        <div class="col-4">
                                            <label for="nama_barang">Nama Barang</label>
                                            <select class="form-control sel2" id="nama_barang" name="nama_barang" required></select>
                                        </div>
                                        <div class="col-4">
                                            <label for="kategori">Keterangan</label>
                                            <div class="col-12 row">
                                                <div class="mb-3 pr-5">
                                                    <label class="md-check">
                                                        <input type="radio" name="kategori" value="0" required><i class="blue"></i>Pembelian
                                                    </label>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="md-check">
                                                        <input type="radio" name="kategori" value="1" required><i class="blue"></i>Pemakaian
                                                        <input type="text" name="before_qty" id="before_qty" hidden>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-4">
                                            <label for="jml_barang">Jumlah Barang</label>
                                            <input type="text" class="form-control" id="jml_barang" name="jml_barang" placeholder="Jumlah Barang" required>
                                        </div>
                                        <div class="col-4">
                                            <label for="hrg_peroleh">Harga Barang</label>
                                            <input type="text" class="form-control" id="hrg_peroleh" name="hrg_peroleh" placeholder="Harga Perolehan" required>
                                        </div>
                                        <div class="col-4">
                                            <label for="hrg_peroleh">Total Harga</label>
                                            <input type="text" class="form-control" id="ttl_hrg_peroleh" name="hrg_peroleh" placeholder="Harga Perolehan" disabled required>
                                        </div>
                                    </div>
                                </div>
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
                                            <th><span>Nama Barang</span></th>
                                            <th><span>Jumlah Barang</span></th>
                                            <th><span>Harga Perolehan</span></th>
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
        $('#hrg_peroleh').keyup(function() {
            var hrg_peroleh = $('#hrg_peroleh').val();
            var jml_barang = $('#jml_barang').val();
            var ttl_hrg_peroleh = hrg_peroleh * jml_barang;
            $('#ttl_hrg_peroleh').val(ttl_hrg_peroleh);
        }); 

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
                    console.log($this.serialize());
                    $.ajax({
                        url: url_insert,
                        type: 'post',
                        data: $this.serialize(),
                        dataType: 'json',
                        success: function(result) {
                            Swal.close();
                            if (result.success) {
                                Swal.fire('Sukses', 'Berhasil menyimpan data stok', 'success');
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

        $('#nama_barang').select2({
            placeholder: "Pilih Barang",
            minimumInputLength: 0,
            multiple: false,
            allowClear: true,
            ajax: {
                url: url_ajax+'/caribarang',
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
            var data = e.params.data;
            if (data.ttl_qty_barang == null) {
                $("input[value='0']").prop('checked', true);
                $("input[value='1']").prop('disabled', true);
            } else {
                $("input[value='1']").prop('disabled', false);
                $("input[id='before_qty']").val(data.ttl_qty_barang);
            }
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
                    // console.log(response);
                    feather.replace();
                }
            },
            "columns": datatableColumn()
        }).on('init.dt', function() {
            $(this).css('width', '100%');
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
                data: "nama_barang",
                orderable: false,
                // render: function(a, type, data, index) {

                //     var string = data.kdakun;
                //     var sp = "&nbsp;&nbsp;";
                //     sp.repeat(string.length);

                //     return sp.repeat(string.length)+' '+data.kdakun;
                // }
            },
            {
                data: "ttl_qty_barang",
                orderable: true
            },
            {
                data: "ttl_hrg_barang",
                orderable: true
            },
            {
                data: "id",
                orderable: false,
                render: function(a, type, data, index) {
                    let button = ""

                    // if (auth_edit == "1") {
                    //     button += '<button class="btn btn-sm btn-outline-primary edit" data-id="'+data.id+'" title="Edit">\
                    //                 <i class="fa fa-edit"></i>\
                    //             </button>\
                    //             ';
                    // }

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
    
    });
</script>