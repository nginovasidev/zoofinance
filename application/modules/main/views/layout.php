<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>E-Finance | E-Finance</title>
        <meta name="description" content="Responsive, Bootstrap, BS4" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <!-- style -->
        <!-- build:css ../assets/css/site.min.css -->
        <link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.css" type="text/css" />
        <link rel="stylesheet" href="<?=base_url()?>assets/css/theme.css" type="text/css" />
        <link rel="stylesheet" href="<?=base_url()?>assets/css/style.css" type="text/css" />
        <link rel="stylesheet" href="<?=base_url()?>assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" type="text/css" />
        <link rel="stylesheet" href="<?=base_url()?>assets/libs/select2/dist/css/select2.min.css" type="text/css" />
        <!-- jQuery -->
        <script src="<?=base_url()?>assets/libs/jquery/dist/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css'>
        <!-- endbuild -->
        <style type="text/css">
            .btn-icon-datatable {
                width: 2.125rem;
                height: 2.125rem;
                padding: 7px;
                margin: 0 3px;
            }

            .column-2action {
                width: 100px !important;
            }


            .select2-container--default .select2-results__option--highlighted[aria-selected]{
                background-color : #a11401; /*#5897fb ;*/

            }

            .select2-container--default .select2-search--dropdown .select2-search__field {
                border-color: #a11401;
            }

            .select2-container--default .select2-selection{
                border-color: #a11401 !important;
            }

            .select2-container--default .select2-dropdown{
                border-color: #a11401 !important;
            }
        </style>
    </head>
    <body class="layout-row">

    	<?php $this->load->view('menu'); ?>

        <div id="main" class="layout-column flex">

            <?php $this->load->view('navbar'); ?>

            <!-- ############ Content START-->
            <div id="content" class="flex ">
                <!-- ############ Main START-->
                <?php if(isset($load_view)){ 
                    $this->load->view($load_view);
                } else { 
                    $this->load->view('dashboard');
                } ?>
                <!-- ############ Main END-->
            </div>
            <!-- ############ Content END-->

            <?php $this->load->view('footer'); ?>
        </div>
        <!-- build:js ../assets/js/site.min.js -->
        <!-- Bootstrap -->
<!--         <?php if(isset($load_view)){
                if(is_file(APPPATH.'modules/'.$this->uri->segment(1).'/views/js/'.$load_view.'_js' . EXT)) {
                    $this->load->view('js/'.$load_view.'_js');
                }else{
                    // echo APPPATH.'modules/'.$this->uri->segment(1).'/views/js/'.$load_view.'_js' . EXT;
                }
            }
        ?> -->
        <script src="<?=base_url()?>assets/libs/popper.js/dist/umd/popper.min.js"></script>
        <script src="<?=base_url()?>assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- ajax page -->
        <!-- <script src="<?=base_url()?>assets/libs/pjax/pjax.min.js"></script> -->
        <!-- <script src="<?=base_url()?>assets/js/ajax.js"></script> -->
        <!-- lazyload plugin -->
        <script src="<?=base_url()?>assets/js/lazyload.config.js"></script>
        <script src="<?=base_url()?>assets/js/lazyload.js"></script>
        <script src="<?=base_url()?>assets/js/plugin.js"></script>
        <!-- scrollreveal -->
        <script src="<?=base_url()?>assets/libs/scrollreveal/dist/scrollreveal.min.js"></script>
        <!-- feathericon -->
        <script src="<?=base_url()?>assets/libs/feather-icons/dist/feather.min.js"></script>
        <script src="<?=base_url()?>assets/js/plugins/feathericon.js"></script>
        <!-- theme -->
        <script src="<?=base_url()?>assets/js/theme.js"></script>
        <script src="<?=base_url()?>assets/js/utils.js"></script>
        <!-- endbuild -->
        <script src="<?=base_url()?>assets/libs/datatables/media/js/jquery.dataTables.min.js"></script>
        <script src="<?=base_url()?>assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="<?=base_url()?>assets/libs/select2/dist/js/select2.full.min.js"></script>
        <script src="<?=base_url()?>assets/js/plugins/sweetalert.js"></script>
    </body>
</html>