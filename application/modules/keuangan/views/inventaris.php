<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<div>
    <div class="page-hero page-container " id="page-hero">
        <div class="padding d-flex">
            <div class="page-title">
                <h2 class="text-md text-highlight"><?= $page_title ?></h2>
            </div>
            <div class="flex"></div>
        </div>
    </div>
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
                                        <div class="col-6">
                                            <label for="nama_barang">Nama Barang</label>
                                            <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Nama Barang" required>
                                        </div>
                                        <div class="col-6">
                                            <label for="kategori">Kategori</label>
                                            <select class="form-control sel2" id="kategori" name="kategori" required>
                                                <option value="">Pilih Kategori</option>
                                                <option value="1">Kelompok I (25%)</option>
                                                <option value="2">Kelompok II (12,5%)</option>
                                            </select>
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
                                            <label for="hrg_peroleh">Total Harga Perolehan</label>
                                            <input type="text" class="form-control" id="hrg_peroleh" name="hrg_peroleh" placeholder="Harga Perolehan" required>
                                        </div>
                                        <div class="col-4">
                                            <label for="tgl_peroleh">Tanggal Perolehan</label>
                                            <input type="date" class="form-control" id="tgl_peroleh" name="tgl_peroleh" placeholder="Tanggal Perolehan" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                        </div>
                        </form>
                        <div class="tab-pane fade" id="tab-data" role="tabpanel" aria-labelledby="tab-data">
                            <div class="table-responsive">
                                <table id="datatable" class="table table-theme table-row v-middle">
                                    <thead>
                                        <tr>
                                            <th><span>#</span></th>
                                            <th><span>Nama Barang</span></th>
                                            <th><span>Jumlah Barang</span></th>
                                            <th><span>Tanggal Peroleh</span></th>
                                            <th><span>Harga Perolehan</span></th>
                                            <th><span>Kategori</span></th>
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
                                Swal.fire('Sukses', 'Berhasil menyimpan inventaris', 'success');
                                $('#form').trigger("reset");
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
                success: function(result) {
                    Swal.close();
                    if (result.success) {
                        $('ul#tab li a').first().trigger('click');

                        for (key in result.data) {
                            $('#' + key).val(result.data[key]);
                        }

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
                                Swal.fire('Sukses', 'Berhasil menghapus inventaris', 'success');
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
                data: "nama_barang",
                orderable: true
            },
            {
                data: "jmlperoleh",
                orderable: true
            },
            {
                data: "tgl_peroleh",
                orderable: true
            },
            {
                data: "hrgperoleh",
                orderable: true
            },
            {
                data: "kategori",
                orderable: true,
                render: function(a, type, data, index) {
                    let kat = ""

                    if (data.kategori == "1") {
                        kat += 'Kelompok I';
                    }

                    if (data.kategori == "2") {
                        kat += 'Kelompok II';
                    }

                    kat += (kat == "") ? "<b>Tidak Ada Kelompok</b>" : ""
                    return kat;
                }
            },
            {
                data: "id",
                orderable: false,
                render: function(a, type, data, index) {
                    let button = ""

                    if (auth_edit == "1") {
                        button += '<button class="btn btn-wave btn-icon-datatable btn-rounded mb-2 light-blue text-white edit" data-id="' + data.id + '">\
                                    <i data-feather="edit-3"></i>\
                                </button>';
                    }

                    if (auth_delete == "1") {
                        button += '<button class="btn btn-wave btn-icon-datatable btn-rounded mb-2 red text-white delete" data-id="' + data.id + '">\
                                        <i data-feather="trash-2"></i>\
                                    </button>';
                    }

                    button += (button == "") ? "<b>Tidak ada aksi</b>" : ""

                    return button;
                }
            }
        ];

        return columns;
    }
</script>