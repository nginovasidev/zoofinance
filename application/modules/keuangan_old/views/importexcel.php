<div>
  <!-- <div class="page-hero page-container " id="page-hero">
    <div class="padding d-flex">
      <div class="page-title">
        <h2 class="text-md text-highlight"> <?= $page_title ?> </h2>
      </div>
      <div class="flex"></div>
    </div>
  </div> -->
  <div class="page-content page-container" id="page-content">
    <div class="padding">
      <div class="card">
        <!-- <div class="card-header">
          <ul class="nav nav-pills card-header-pills no-border" id="tab">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#tab-form" role="tab" aria-controls="tab-form" aria-selected="false">Form</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#tab-data" role="tab" aria-controls="tab-data" aria-selected="false">Data</a>
            </li>
          </ul>
        </div> -->
        <div class="card-body">
          <div class="tab-content">
            <div class="tab-pane fade active show" id="tab-form" role="tabpanel" aria-labelledby="tab-form">
              <form id="form" method="POST" autocomplete="off" enctype="multipart/form-data">
                <!-- <form id="form" method="POST" autocomplete="off" action="<?=base_url()?>keuangan/page/importexcel_load" enctype="multipart/form-data"> -->
                <input type="hidden" class="form-control" id="id" name="id" value="" required>
                <input type="hidden" class="form-control" id="<?= $this->security->get_csrf_token_name() ?>" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" required>
                <div class="form-group">
                  <div class="row">
                    <div class="col-6">
                      <label for="nama_barang">Pilih Akun</label>
                      <select class="form-control akun" id="akun" name="akun" required=""></select>
                    </div>
                    <div class="col-6">
                      <label for="kategori">File Excel</label>
                      <input type="file" class="form-control" id="fileExcel" name="fileExcel" placeholder="Upload File">
                    </div>
                  </div>
                </div>
                <div class="text-right">
                  <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </div>
            </form>
            <div class="tab-pane fade" id="tab-data" role="tabpanel" aria-labelledby="tab-data">
              <div class="table-responsive">
                <table id="datatable" class="table table-theme table-row v-middle">
                  <thead>
                    <tr>
                      <th>
                        <span>#</span>
                      </th>
                      <th>
                        <span>Nama Barang</span>
                      </th>
                      <th>
                        <span>Jumlah Barang</span>
                      </th>
                      <th>
                        <span>Tanggal Peroleh</span>
                      </th>
                      <th>
                        <span>Harga Perolehan</span>
                      </th>
                      <th>
                        <span>Kategori</span>
                      </th>
                      <th class="column-2action"></th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="page-content page-container" id="cekdataimportexcel" style="display: none;">
    <div class="padding">
      <div class="card">
        <div class="card-header">
          <div class="text-center"> Cek Data Import Excel </div>
        </div>
        <div class="card-body">
          <div class="result"></div>
          <br>
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


        $("#form").submit(function(evt){  
              evt.preventDefault();
              var formData = new FormData($(this)[0]);
           $.ajax({
               url: url_load,
               type: 'POST',
               data: formData,
               async: false,
               cache: false,
               contentType: false,
               enctype: 'multipart/form-data',
               processData: false,
               success: function (response) {
                
                $('#cekdataimportexcel').show();
                $('.result').html(response);
                 // alert(response);
               }
           });
           // return false;
         });

        $(document).on('submit', '#insertimport', function(e) {
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
                                Swal.fire('Sukses', 'Berhasil menyimpan import excel', 'success');
                                $('#form').trigger("reset");
                                $(".sel2").val(null).trigger('change');

                                $('#cekdataimportexcel').hide();
                                $('.result').html('');
                                
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
        });


        $('.akun').select2({
            placeholder: "Pilih Akun",
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

</script>
