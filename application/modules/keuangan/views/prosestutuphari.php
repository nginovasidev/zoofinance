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
        <div class="card-body">
          <div class="tab-content">
              <div class="form-group">
                <div class="row">
                  <div class="col-6">
                    <label for="tanggal">Tanggal</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?=date("Y-m-d")?>">
                  </div>
                  <div class="col-12 statusblock" style="display: none;">
                    <label >Status</label>
                    <div id="statusblock"></div>
                  </div>
                </div>
              </div>
              <div class="text-left">
                <button id="lock" class="btn btn-primary">Lock</button>
                <button id="unlock" class="btn btn-primary">Unlock</button>
              </div>

<!--               <div>
                <br>
                <br>
                Data Tanggal Belum Terkunci
                <br>
                <table border="1" width="100%">
                  <tr>
                    <th>Tanggal</th>
                    <th>Status</th>
                  </tr>
                  <?php
                  $qrdataunlock = $this->db->query("SELECT * from m_config_trx where is_block='0' "); 

                  foreach ($qrdataunlock->result() as $item) {
                  ?>
                  <tr>
                    <td><?=$item->tgl?></td>
                    <td>Belum Terkunci</td>
                  </tr>
                  <?php
                  } 
                  ?>
                </table>
              </div> -->
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

        tanggal.max = new Date().toISOString().split("T")[0];
        tanggal.min = "2021-08-29";
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
        }).on('click', '#lock', function() {
            let $this = $(this);

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
                data: {
                    tgl: $("#tanggal").val(),
                    is_block : '1',
                    "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                },
                dataType: 'json',
                success: function(result) {
                    Swal.close();
                    if (result.success) {
                        Swal.fire('Sukses', 'Berhasil lock tanggal', 'success');
                        
                    } else {
                        Swal.fire('Error', result.message, 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                }
            });
        } ).on('click', '#unlock', function() {
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
                url: url_insert,
                type: 'post',
                data: {
                    tgl: $("#tanggal").val(),
                    is_block : '0',
                    "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                },
                dataType: 'json',
                success: function(result) {
                    Swal.close();
                    if (result.success) {
                        Swal.fire('Sukses', 'Berhasil unlock tanggal', 'success');

                    } else {
                        Swal.fire('Error', result.message, 'error');
                    }
                },
                error: function() {
                    Swal.close();
                    Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                }
            });
        } ).on('change', '#tanggal', function() {
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
                    tgl: $("#tanggal").val(),
                    "<?= $this->security->get_csrf_token_name() ?>": "<?= $this->security->get_csrf_hash() ?>"
                },
                dataType: 'json',
                success: function(result) {
                    Swal.close();
                    if (result.success) {
                        for (key in result.data) {
                            $('#' + key).val(result.data[key]);
                        }
                        if (result.data['user_name']==null) {

                          $('.statusblock').show();
                          $('#statusblock').html("Transaksi pada tanggal "+result.data['tgl']+" masih di "+result.data['is_block']);
                        } else {
                          $('.statusblock').show();
                          $('#statusblock').html("Transaksi pada tanggal "+result.data['tgl']+" sudah di "+result.data['is_block']);
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
        } );


    });


</script>
  