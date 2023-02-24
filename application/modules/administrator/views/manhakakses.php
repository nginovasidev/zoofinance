<div class="flex">
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
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="tab-form" role="tabpanel" aria-labelledby="tab-form">
                            <form data-plugin="parsley" data-option="{}" id="form" name="form">
                                <input type="hidden" id="delete" name="delete">
                                <input type="hidden" class="form-control" id="<?= $this->security->get_csrf_token_name() ?>" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" required>
                                <div class="form-group row">
                                    <label class="col-2 col-form-label">Jenis User</label>
                                    <div class="col-10">
                                        <select type="text" class="form-control sel2" id="iduser" name="iduser">
                                            <option value="">Pilih Jenis User</option>
                                            <option></option>
                                        </select>
                                    </div>
                                </div>
                                <div id="module_table" style="display: none; margin-top: 50px;">
                                    <div class="form-group row">
                                        <table class="table table-bordered table-md">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="align-center">#</th>
                                                    <th class="align-left">Modul</th>
                                                    <th class="align-center">Semua Akses</th>
                                                    <th class="align-center">Akses View</th>
                                                    <th class="align-center">Akses Insert</th>
                                                    <th class="align-center">Akses Edit</th>
                                                    <th class="align-center">Akses Delete</th>
                                                    <th class="align-center">Akses Otorisasi</th>
                                                    <th class="align-center">Hapus</th>
                                                </tr>
                                            </thead>
                                            <tbody id="module">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="text-right">
                                        <button type="button" class="btn btn-success" id="tambahmodul">Tambah Modul</button>
                                        <button type="submit" class="btn btn-primary" id="save">Simpan</button>
                                    </div>
                                </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        const url_insert = '<?= base_url() . $this->uri->segment(1) . "/page/" . $this->uri->segment(2) . "_save" ?>';
        const url_edit = '<?= base_url() . $this->uri->segment(1) . "/page/" . $this->uri->segment(2) . "_edit" ?>';
        const url_delete = '<?= base_url() . $this->uri->segment(1) . "/page/" . $this->uri->segment(2) . "_delete" ?>';
        const url_load = '<?= base_url() . $this->uri->segment(1) . "/page/" . $this->uri->segment(2) . "_load" ?>';
        const url_ajax = '<?= base_url() . $this->uri->segment(1) . "/ajaxfile/" ?>';

        var dataStart = 0;
        var table;
        let datatable;
        let modulOption;
        let modules = [];
        let deleted = [];

        // begin select 2 jenis user
        $('#iduser').select2({
            placeholder: "Pilih Jenis User",
            minimumInputLength: 0,
            multiple: false,
            allowClear: true,
            ajax: {
                url: '<?= base_url() . "administrator/ajaxfile/cariJenisUser/" ?>',
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
        });

        $('#iduser').change(function() {
            let id = $('#iduser').val();
            $('#module_table').show();
            $('#module').html('');
            $('#delete').val('');
            $.ajax({
                url: url_ajax + '/getModuleUser',
                method: 'post',
                dataType: 'json',
                data: {
                    id: id,
                    "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                },
                success: function(rs) {
                    modules = [];
                    Object.keys(rs).forEach(function(key) {
                        if (rs.hasOwnProperty(key)) {
                            $.when($('#tambahmodul').click()).done(function() {
                                let element = $('.idmodule').last();
                                element.val(key);
                                getListMenu(key, element, rs[key]);
                            });
                        }
                    })
                },
                error: function(err) {
                    Swal.fire('Error', 'Terjadi kesalahan saat mengambil data', 'error');
                }
            })
        });
        // end select 2 jenis user

        $(document).on('submit', 'form[name="form"]', function(e) {
            e.preventDefault();

            let form = $(this);
            $('#save').html("<i class=\"fa fa-circle-o-notch fa-spin\" aria-hidden=\"true\"></i> Menyimpan...");
            $('#save').attr("disabled", true);

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
                method: 'post',
                dataType: 'json',
                data: form.serialize(),
                success: function(rs) {
                    swal.close();
                    $('#save').html("Simpan");
                    $('#save').attr("disabled", false);

                    if (rs.success) {
                        $('#iduser').val($('#iduser').val()).trigger('change');
                        Swal.fire('Sukses', 'Berhasil mengatur hak akses', 'success');
                    } else {
                        Swal.fire('Error', result.message, 'error');
                    }
                },
                error: function(xhr, error, code) {
                    $('#save').html("Simpan");
                    $('#save').attr("disabled", false);
                    Swal.close();
                    Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                }
            })
        }).on("click", "a[title='Delete']", function() {
            let value = $(this).closest("tr").find('select.idmodule').val();

            deleted.push($(this).closest("tr").find('select').val())
            $('#delete').val(deleted.join(','));

            $(this).closest("tr").nextUntil(".module-row").remove();
            $(this).closest("tr").remove();

            $('#module tr.module-row').each(function() {
                let index = $(this).index('tr.module-row');
                $(this).find('.number').html(index + 1);
            });

            $(".idmenu").each(function() {
                let index = $(this).index('.idmenu');

                $(this).attr("name", "idmenu[" + index + "]");
                $(this).closest('tr').find('.id').attr("name", "id[" + index + "]");
                $(this).closest('tr').find('.d').attr("name", "d[" + index + "]");
                $(this).closest('tr').find('.e').attr("name", "e[" + index + "]");
                $(this).closest('tr').find('.v').attr("name", "v[" + index + "]");
                $(this).closest('tr').find('.i').attr("name", "i[" + index + "]");
                $(this).closest('tr').find('.o').attr("name", "o[" + index + "]");
            });

            modules = modules.filter(x => x !== value);

        }).on('click', '#tambahmodul', function() {
            let index = $('#module tr.module-row').length;

            $('#module').append(formModule(index + 1));
            $("tr").last().find(".idmodule").attr("name", "idmodule[" + (index + 1) + "]")

        }).on('click', '.check_all', function() {
            let val = $(this).prop("checked")

            $(this).parent().siblings().each(function() {
                $(this).find('input').prop("checked", val)
            })
        }).on('click', '.checkbox', function() {
            let isAll = true;

            $(this).closest('tr').find('td').each(function() {
                if ($(this).find('input.checkbox').prop("checked") === false) {
                    isAll = false
                }
            });

            $(this).closest('tr').find("td input.check_all").prop("checked", isAll)

            if ($(this).hasClass("d") || $(this).hasClass("e") || $(this).hasClass("i") || $(this).hasClass("o")) {
                $(this).closest('tr').find('td input.v').prop('checked', true);
            }

            if ($(this).hasClass("v")) {
                if ($(this).prop('checked') === false) {
                    $(this).closest('tr').find('td input.check_all').prop('checked', false);
                    $(this).closest('tr').find('td input.checkbox').prop('checked', false);
                }
            }

        }).on('change', '.idmodule', function() {
            let id = $(this).val();
            let $this = $(this);

            if (modules.includes(id)) {
                $this.closest("tr").nextUntil('.module-row').remove();
                modules = $('.idmodule').map(function() {
                    return this.value;
                }).get();
                $this.closest("tr").remove();
            } else {
                getListMenu(id, $this);
            }
        });

        $.ajax({
            url: url_ajax + '/getModuleSelect',
            success: function(rs) {
                modulOption = rs
            }
        });

        function formModule(id) {
            return "<tr class='module-row'>" +
                "<th scope=\"row\" class=\"number align-center\">" + id + "</th>" +
                "<td class=\"align-left\">" + modulOption + "</td>" +
                "<td class=\"align-center\"></td>" +
                "<td class=\"align-center\"></td>" +
                "<td class=\"align-center\"></td>" +
                "<td class=\"align-center\"></td>" +
                "<td class=\"align-center\"></td>" +
                "<td class=\"align-center\"></td>" +
                "<td class=\"align-center\"><a href=\"#\"  title=\"Delete\">" +
                "<i class=\"material-icons\">delete</i>\n" +
                "</a></td>\n" +
                "</tr>"
        }

        function getListMenu(id, element, data = null) {
            $.get({
                url: url_ajax + '/getMenu/' + id,
                dataType: 'json',
                success: function(rs) {
                    let menu = rs.map(x => createForm(x, data));

                    element.closest('tr').nextUntil('.module-row').remove();
                    $(menu.join()).insertAfter(element.closest('tr'));

                    $(".idmenu").each(function() {
                        let index = $(this).index('.idmenu');

                        $(this).attr("name", "idmenu[" + index + "]");
                        $(this).closest('tr').find('.id').attr("name", "id[" + index + "]");
                        $(this).closest('tr').find('.d').attr("name", "d[" + index + "]");
                        $(this).closest('tr').find('.e').attr("name", "e[" + index + "]");
                        $(this).closest('tr').find('.v').attr("name", "v[" + index + "]");
                        $(this).closest('tr').find('.i').attr("name", "i[" + index + "]");
                        $(this).closest('tr').find('.o').attr("name", "o[" + index + "]");
                    });

                    modules = $('.idmodule').map(function() {
                        return this.value;
                    }).get();

                    if (data != null) {
                        $('.idmodule').each(function() {
                            $(this).attr("disabled", true);
                        });
                    }
                }
            });
        }

        function createForm(menu, data) {
            if (menu.submenu.length > 0) {
                let subMenu = menu.submenu.map(x => formSubMenu(x, data));

                return "<tr>" +
                    "<th scope=\"row\" class=\"number align-center\"></th>" +
                    "<td class=\"align-left\"><ul style='padding-inline-start:20px;'><li>" + menu.menu + "</li></ul></td>" +
                    "<td class=\"align-center\"></td>" +
                    "<td class=\"align-center\"></td>" +
                    "<td class=\"align-center\"></td>" +
                    "<td class=\"align-center\"></td>" +
                    "<td class=\"align-center\"></td>" +
                    "<td class=\"align-center\"></td>" +
                    "</tr>" + subMenu.join()
            } else {
                return formMenu(menu, data);
            }
        }

        function formMenu(menu, data) {
            let checkAll = "";
            let checkD = "";
            let checkE = "";
            let checkV = "";
            let checkI = "";
            let checkO = "";
            let id = null;

            if (data != null) {
                let menuData = data.filter(function(x) {
                    return x.menu_id == menu.id
                });

                if (menuData.length > 0) {
                    checkAll = (menuData[0].d === "1" && menuData[0].e === "1" && menuData[0].v === "1" && menuData[0].i === "1" && menuData[0].o === "1") ? "checked" : "";
                    checkD = (menuData[0].d === "1") ? "checked" : "";
                    checkE = (menuData[0].e === "1") ? "checked" : "";
                    checkV = (menuData[0].v === "1") ? "checked" : "";
                    checkI = (menuData[0].i === "1") ? "checked" : "";
                    checkO = (menuData[0].o === "1") ? "checked" : "";
                    id = menuData[0].id
                }
            }

            return "<tr><input type='hidden' name='id[]' class='id' value='" + id + "'>\" " +
                "<th scope=\"row\" class=\"number align-center\"><input type=\"hidden\" class='idmenu' name=\"idmenu[]\" value=\"" + menu.id + "\"></th>" +
                "<td class=\"align-left\"><ul style='padding-inline-start:20px;'><li>" + menu.menu_name + "</li></ul></td>" +
                "<td class=\"align-center\"><input type=\"checkbox\" class='check_all' name=\"check_all[]\" value=\"1\" " + checkAll + "></td>" +
                "<td class=\"align-center\"><input type=\"checkbox\" class='v checkbox' name=\"v[]\" value=\"1\" " + checkV + "></td>" +
                "<td class=\"align-center\"><input type=\"checkbox\" class='i checkbox' name=\"i[]\" value=\"1\" " + checkI + "></td>" +
                "<td class=\"align-center\"><input type=\"checkbox\" class='e checkbox' name=\"e[]\" value=\"1\" " + checkE + "></td>" +
                "<td class=\"align-center\"><input type=\"checkbox\" class='d checkbox' name=\"d[]\" value=\"1\" " + checkD + "></td>" +
                "<td class=\"align-center\"><input type=\"checkbox\" class='o checkbox' name=\"o[]\" value=\"1\" " + checkO + "></td>" +
                "<td class=\"align-center\"></td>" +
                "</tr>"
        }

        function formSubMenu(menu, data) {
            let checkAll = "";
            let checkD = "";
            let checkE = "";
            let checkV = "";
            let checkI = "";
            let checkO = "";
            let id = 0;

            if (data != null) {
                let menuData = data.filter(function(x) {
                    return x.idmenu === menu.id
                });

                if (menuData.length > 0) {
                    checkAll = (menuData[0].d === "1" && menuData[0].e === "1" && menuData[0].v === "1" && menuData[0].i === "1" && menuData[0].o === "1") ? "checked" : "";
                    checkD = (menuData[0].d === "1") ? "checked" : "";
                    checkE = (menuData[0].e === "1") ? "checked" : "";
                    checkV = (menuData[0].v === "1") ? "checked" : "";
                    checkI = (menuData[0].i === "1") ? "checked" : "";
                    checkO = (menuData[0].o === "1") ? "checked" : "";
                    id = menuData[0].id
                }
            }

            return "<tr><input type='hidden' name='id[]' class='id' value='" + id + "'>" +
                "<th scope=\"row\" class=\"number align-center\"><input type=\"hidden\" class='idmenu' name=\"idmenu[]\" value=\"" + menu.id + "\"></th>" +
                "<td class=\"align-left\" style='padding-inline-start:20px;'><ul><li style='list-style-type: circle;'>" + menu.menu + "</li></ul></td>" +
                "<td class=\"align-center\"><input type=\"checkbox\" class='check_all' name=\"check_all[]\" value=\"1\" " + checkAll + "></td>" +
                "<td class=\"align-center\"><input type=\"checkbox\" class='v checkbox' name=\"v[]\" value=\"1\" " + checkV + "></td>" +
                "<td class=\"align-center\"><input type=\"checkbox\" class='i checkbox' name=\"i[]\" value=\"1\" " + checkI + "></td>" +
                "<td class=\"align-center\"><input type=\"checkbox\" class='e checkbox' name=\"e[]\" value=\"1\" " + checkE + "></td>" +
                "<td class=\"align-center\"><input type=\"checkbox\" class='d checkbox' name=\"d[]\" value=\"1\" " + checkD + "></td>" +
                "<td class=\"align-center\"><input type=\"checkbox\" class='o checkbox' name=\"o[]\" value=\"1\" " + checkO + "></td>" +
                "<td class=\"align-center\"></td>" +
                "</tr>"
        }
    });
</script>