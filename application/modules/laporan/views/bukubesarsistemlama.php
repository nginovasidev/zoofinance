	<style type="text/css">
    table{
        border-collapse:collapse;
    }
    
    table td{
        border:1px solid #898989;
        padding:3px;
        vertical-align:top;
    }
    
    table th{
        border:1px solid #2e74b5;
        padding:3px;
    }
    
    table thead tr{
        background-color:#d0e3ff;
    }
    
    table.noborder th{

        border:none;

        padding:3px;

    }

    

    table.noborder td{

        border:none;

        padding:3px;

        vertical-align:top;

    }

    

    table.noborder th{

        border:none;

        padding:3px;

    }
</style>
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
                                <div class="col-3">
                                    <label for="periode_awal">Periode</label>
                                    <input type="date" class="form-control" id="periode_awal" name="periode_awal" placeholder="Tanggal Barang" required>
                                </div>
                                <div class="col-3">
                                    <label for="periode_akhir">&nbsp;</label>
                                    <input type="date" class="form-control" id="periode_akhir" name="periode_akhir" placeholder="No Bukti" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="Keterangan">Akun</label>
                                    <select id="idparent" name="kdakun" class="form-control"></select>
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

        periode_awal.max = new Date().toISOString().split("T")[0];
        periode_awal.min = "2020-01-01";

        periode_akhir.max = new Date().toISOString().split("T")[0];
        periode_akhir.min = "2020-01-01";

        $('#pdf-download').on('click', function (e) {
            $(this).attr("href", "<?=base_url()?>laporan/pdf/laporanledgerdetail?periode_awal="+ $('#periode_awal').val()+"&periode_akhir="+ $('#periode_akhir').val()+"&kdakun="+ $('#idparent').val()+"" );
            $(this).attr("target", "_blank");
        })

        $("#form").submit(function(evt){ 
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

        $('#idparent').select2({
            placeholder: " - ",
            minimumInputLength: 0,
            multiple: false,
            allowClear: true,
            ajax: {
                url: '<?= base_url() ?>keuangan/ajaxfile/cariparent_datalama',
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

    })
    
</script>