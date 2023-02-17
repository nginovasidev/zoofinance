<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<div>
    <div class="page-content page-container" id="page-content">
        <div class="padding">
            <div class="card">
                <div class="card-body">
                    <form data-plugin="parsley" data-option="{}" id="form" autocomplete="off">
                        <input type="hidden" class="form-control" id="id" name="id" value="" required>
                        <input type="hidden" class="form-control" id="<?= $this->security->get_csrf_token_name() ?>" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" required>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="periode">Tanggal Awal</label>
                                    <input type="date" class="form-control" id="periode_awal" name="periode_awal" placeholder="Periode" value="<?=date("Y-m-d")?>" required>
                                </div>
                                <div class="col-6">
                                    <label for="periode_akhir">Tanggal Akhir</label>
                                    <input type="date" class="form-control" id="periode" name="periode" placeholder="Periode" value="<?=date("Y-m-d")?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="text-left">
                            <button class="btn btn-primary">Lihat</button>
                            <!-- <a id="pdf-download" class="btn btn-light-primary font-weight-bold">Download PDF</a> -->

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="result"></div>


 

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
        periode.max = new Date().toISOString().split("T")[0];
        periode.min = "2021-08-29";
        $('#pdf-download').on('click', function (e) {
            $(this).attr("href", "<?=base_url()?>laporan/pdf/laporanlabarugi?periode="+ $('#periode').val() );
            $(this).attr("target", "_blank");
        })
        
        $("#forma").submit(function(evt){ 
            if ($('#periode').val()=='') {

            } else {
                
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
                    $('#result').html(response);
                     // alert(response);
                   }
               });
            }
           // return false;
         });

        $(document).on('submit', '#form', function(e) {

            if ($('#periode').val()=='') {

            } else {
                e.preventDefault();
                let $this = $(this);
                $('#result').html(' ');

                Swal.fire({
                    title: "",
                    icon: "info",
                    text: "Proses mencari data, mohon ditunggu...",
                    didOpen: function() {
                        Swal.showLoading()
                    }
                });

                $.ajax({
                    url: url_load,
                    type: 'post',
                    data: $this.serialize(),
                    dataType: false,
                    success: function(result) {
                        Swal.close();
                        $('#result').html(result);
                    },
                    error: function() {
                        Swal.close();
                        Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                    }
                });
            }
        })

    });

</script>