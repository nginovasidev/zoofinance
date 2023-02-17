<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/handsontable/dist/handsontable.full.min.js"></script>
<div>
  <div class="page-content page-container" id="page-content">
    <div class="padding">
      <div class="card">
        <div class="card-body">
          <div class="tab-content">
              <div class="form-group">
                <div class="row">
                  <div class="col-6">
                    <label for="tanggal">Tanggal</label>
                    <input type="text" id="tanggal" name="tanggal" class="form-control" value="2022">
                    <!-- <input type="text" id="datepicker1" class="form-control" /> -->
                  </div>
                </div>
              </div>
              <div class="text-left">
                <button id="cektgl" class="btn btn-primary">Cek RKAP</button>
                <!-- <a id="pdf-download" class="btn btn-light-primary font-weight-bold">Download PDF</a> -->
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="modal-backdrop-dark-opname" class="modal bg-dark fade" data-backdrop="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content ">
            <div class="modal-header ">
                <div class="modal-title text-md">RKAP</div>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
              <div class="alert alert-info" role="alert">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info">
                      <circle cx="12" cy="12" r="10"></circle>
                      <line x1="12" y1="16" x2="12" y2="12"></line>
                      <line x1="12" y1="8" x2="12" y2="8"></line>
                  </svg>
                  <span class="mx-2" id="example1console">Kolom nilai dapat diubah dan disimpans secara otomatis</span>
              </div>
              <div class="filterHeader"><input id="kd-akun-search"></div>
              <div class="filterHeader"><input id="nama-akun-search"></div>
              <div id="list-container">
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
                  <!-- <button type="submit" form="forminput" id="buttonsimpanopname" class="btn btn-primary" disabled>Simpan</button> -->
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


    $(document).ready(function() {

      $("#tanggal").datepicker({
          format: "yyyy",
          viewMode: "years", 
          minViewMode: "years",
          autoclose:true //to close picker once year is selected
      });


      $('#pdf-download').on('click', function (e) {
            $(this).attr("href", "<?=base_url()?>keuangan/pdf/cashopname?tanggal="+ $('#tanggal').val() );
            $(this).attr("target", "_blank");
      });

      $(document).on('submit', '#forminput', function(e) {
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
                                Swal.fire('Sukses', 'Berhasil menyimpan Opname', 'success');
                                $('#forminput').trigger("reset");
                                $('.jadienol').html("0");
                                $('#buttonsimpanopname').attr('disabled' , true);
                                $('#modal-backdrop-dark-opname').modal("hide");
                                
                            } else {
                                Swal.fire('Error', result.message, 'error');
                            }
                            console.log(result);
                        },
                        error: function() {
                            Swal.close();
                            Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                        }
                    });
                }
            });
        }).on('click', '#cektgl', function() {
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
                url: url_load,
                type: 'post',
                data: {
                    "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>",
                    tahun : function () {
                      return $('#tanggal').val();
                    }
                },
                dataType: 'json',
                success: function(result) {
                    Swal.close();
                    if (result.success) {
                      loadTable(result);
                        $('#modal-backdrop-dark-opname').modal("show");
                    } else {
                        Swal.fire('Error', result.message, 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                }
            });
        } );


    });



    function loadTable(result) {

        // if (hot !== undefined && !hot.isDestroyed) {
        //     hot.destroy();
        // }
        $('#table-card').show();
        const container = document.querySelector('#list-container');
        const exampleConsole = document.querySelector('#example1console');
        let autosaveNotification;

        // Event for `keydown` event. Add condition after delay of 200 ms which is counted from the time of last pressed key.
        var debounceFn = Handsontable.helper.debounce(function(colIndex, event, input) {
            var filtersPlugin = hot.getPlugin('filters');
            var search = input.value;
            filtersPlugin.removeConditions(colIndex);
            filtersPlugin.addCondition(colIndex, 'contains', [event.target.value]);
            filtersPlugin.filter();
            input.value = search;
            input.focus();

        }, 200);

        const addEventListeners = (input, colIndex) => {
            input.addEventListener('keydown', event => {
                debounceFn(colIndex, event, input);
            });
        };

        // Build elements which will be displayed in header.
        const getInitializedElements = (colIndex, id) => {
            var input = document.getElementById(id);
            if (!input) {
                input = document.createElement('input');
                input.setAttribute("id", id);
            }
            addEventListeners(input, colIndex);
            return input;
        };

        // Add elements to header on `afterGetColHeader` hook.
        const addInput = (col, TH) => {
            if (typeof col !== 'number') {
                return col;
            }

            let label = $(TH).find('.colHeader').html();
            if (col >= 0 && TH.childElementCount < 2) {
                if (label === 'Kode Akun') {
                    TH.appendChild(getInitializedElements(col, 'kd-akun-search'));
                } else if (label === 'Nama Akun') {
                    TH.appendChild(getInitializedElements(col, 'nama-akun-search'));
                }
            }
        };

        // Deselect column after click on input.
        var doNotSelectColumn = function(event, coords) {
            if (coords.row === -1 && event.target.nodeName === 'INPUT') {
                event.stopImmediatePropagation();
                this.deselectCell();
            }
        };

        setTimeout(function() {
          console.log(result.data);
            hot = new Handsontable(container, {
                data: result.data,
                width: '100%',
                height: 600,
                colHeaders: true,
                rowHeaders: true,
                manualColumnResize: true,
                fixedColumnsLeft: 7,
                fixedRowsTop: 0,
                contextMenu: false,
                manualColumnFreeze: true,
                stretchH: 'last',
                filters: true,
                afterGetColHeader: addInput,
                beforeOnCellMouseDown: doNotSelectColumn,
                renderAllRows: true,
                hiddenColumns: {
                    columns: [0]
                },
                licenseKey: 'non-commercial-and-evaluation',
                nestedHeaders: [
                    ['#id', 'Kode Akun', 'Nama Akun', 'Nominal']
                ],
                columns: [{
                        data: 'id',
                        readOnly: true
                    },
                    {
                        data: 'kdakun',
                        readOnly: true
                    },
                    {
                        data: 'namaakun',
                        readOnly: true
                    },
                    {
                        data: 'nominal',
                        type: 'numeric',
                        numericFormat: {
                            pattern: '0,0',
                            culture: 'en-US'
                        },
                        readOnly: false,
                    },

                ],
                afterChange: function(change, source) {
                    if (source === 'loadData') {
                        return;
                    }
                    clearTimeout(autosaveNotification);
                    const rowData = hot.getData()[change[0][0]];
                    autosaveNotification = setTimeout(() => {
                        $(".alert").prop('class', 'alert alert-info');
                        exampleConsole.innerText = 'Ubahan pada tabel akan otomatis disimpan';
                    }, 5000);

                    // if (rowData[8] <= 100 && rowData[8] >= 0) {
                        const formData = {
                            '<?= $this->security->get_csrf_token_name() ?>' : '<?= $this->security->get_csrf_hash() ?>',
                            'id': rowData[0],
                            'kdakun': rowData[1],
                            'nominal': rowData[3],
                            'tahun' : $('#tanggal').val()
                        };
                        $.ajax({
                            url: "<?= base_url() . $this->uri->segment(1) . "/page/" . $this->uri->segment(2) . "hot_save" ?>",
                            method: "POST",
                            data: formData,
                            dataType: 'json',
                            success: function(result) {
                                if (result.success) {

                                  
                                    let res = result.message;
                                    let row = hot.toPhysicalRow(change[0][0]);

                                    if (rowData[0]=="" || rowData[0]== null) {
                                      hot.setSourceDataAtCell(row, 'id', res.idnya);
                                    }
                                    $(".alert").prop('class', 'alert alert-success');
                                    exampleConsole.innerText = 'Ubahan berhasil disimpan : (' + rowData[4] + ' ' + change[0][1] + ': ' + change[0][3] + ')';
                                } else {
                                    $(".alert").prop('class', 'alert alert-danger');
                                    exampleConsole.innerText = 'Gagal Menyimpan data ! : (' + rowData[4] + ' ' + change[0][1] + ': ' + change[0][3] + ')';
                                }
                            },
                            error: function() {
                                $(".alert").prop('class', 'alert alert-danger');
                                exampleConsole.innerText = 'Gagal Menyimpan data ! : (' + rowData[4] + ' ' + change[0][1] + ': ' + change[0][3] + ')';
                            }
                        });
                }
            });
        }, 100);

    }


</script>
