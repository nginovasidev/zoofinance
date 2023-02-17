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
                            <form data-plugin="parsley" data-option="{}" id="form">
                                <input type="hidden" class="form-control" id="id" name="id" value="" required>
                                <input type="hidden" class="form-control" id="<?= $this->security->get_csrf_token_name() ?>" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" required>
                                <div class="form-group">
                                    <label for="id_kota">Kab / Kota</label>
                                    <select class="form-control sel2" id="id_kota" name="id_kota" required>
                                        <option></option>
                                        <?php
                                        $modules = $this->db->query("SELECT * from m_lokasi_indonesia where length(id)='2' " )->result();

                                        foreach ($modules as $module) {
                                            echo '<option value="' . $module->id . '">' . $module->nama . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama_polda">Nama Polda</label>
                                    <input type="text" class="form-control" id="nama_polda" name="nama_polda" required autocomplete="off" placeholder="Tentukan nama polda">
                                </div>
                                <div class="form-group">
                                    <label for="singkatan_polda">Singkatan Polda</label>
                                    <input type="text" class="form-control" id="singkatan_polda" name="singkatan_polda" required autocomplete="off" placeholder="Tentukan singkatan polda">
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
                                            <th><span>Nama Polda</span></th>
                                            <th><span>Singkatan Polda</span></th>
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
                                Swal.fire('Sukses', 'Berhasil menyimpan polda', 'success');
                                $('#form').trigger("reset");
                                table.ajax.reload();
                            } else {
                                Swal.fire('Error', JSON.stringify(result.message), 'error');
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
                        for (key in result.data) {
                            $('#' + key).val(result.data[key]);
                        }

                        setTimeout(function() {
                            $('#id_kota').val(result.data.id_kota).trigger('change');
                        }, 500)

                        $('ul#tab li a').first().trigger('click');
                    } else {
                        Swal.fire('Error', JSON.stringify(result.message), 'error');
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
                                Swal.fire('Sukses', 'Berhasil menghapus polda', 'success');
                                table.ajax.reload();
                            } else {
                                Swal.fire('Error', JSON.stringify(result.message), 'error');
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

        $('#id_kota').select2({
            placeholder: "Pilih kota"
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
                data: "nama_polda",
                orderable: true
            },
            {
                data: "singkatan_polda",
                orderable: true
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