<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Halaman Login</title>
        <meta name="description" content="Finance v 1.0.0" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
        <!-- style -->
        <!-- build:css ../assets/css/site.min.css -->
        <link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.css" type="text/css" />
        <link rel="stylesheet" href="<?=base_url()?>assets/css/theme.css" type="text/css" />
        <link rel="stylesheet" href="<?=base_url()?>assets/css/style.css" type="text/css" />
        <!-- endbuild -->
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
        <!-- <link rel="shortcut icon" href="<?=base_url()?>assets/img/favicon.ico"/> -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    </head>
    <body class="layout-row">
        <div class="d-flex flex-column flex">
            <div class="row no-gutters h-100">
                <div class="col-md-6 bg-primary" style="">
                    <div class="p-3 p-md-5 d-flex flex-column h-100">
                        <!-- <h4 class="mb-3 text-white" style="font-size: 1.8125rem">Sistem Informasi Akuntansi</h4> -->
                        <div class="text-fade">Sistem Informasi Akuntansi</div>
                        <h4 class="mb-3 text-white" style="font-size: 1.8125rem">SEMARANG ZOO</h4>
                        <div class="d-flex flex align-items-center justify-content-center">
                            <img src="<?=base_url()?>assets/finance.png">
                        </div>
<!--                         <div class="d-flex text-sm text-fade">
                            <a href="index.html" class="text-white">About</a>
                            <span class="flex"></span>
                            <a href="#" class="text-white mx-1">Terms</a>
                            <a href="#" class="text-white mx-1">Policy</a>
                        </div> -->
                    </div>
                </div>
                <div class="col-md-6">
                    <div id="content-body">
                        <div class="p-3 p-md-5">
                            <div class="d-flex flex align-items-center justify-content-center">
                                <img src="https://trans.my.id/ngimobile/assets/img/zoo.png">
                            </div>
                        </div>
                        <div class="p-3 p-md-5">
                            <h5>Selamat Datang</h5>
                            <p>
                                <small class="text-muted">Silahkan masuk dengan menggunakan akun anda</small>
                            </p>
                            <form id="form1" name="form1" class="sign-in-form" method="post" enctype="multipart/form-data" autocomplete="off">
                                <input type="hidden" id="<?=$this->security->get_csrf_token_name()?>" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>" />
                                <div class="form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control" placeholder="Username" id="username" name="username" required="">
                                </div>
                                <div class="form-group">
                                    <label>Password</label>
                                    <input type="password" class="form-control" placeholder="Password" id="password" name="password" required="">
                                </div>
                                <button type="submit" class="btn btn-primary mb-4">Sign in</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- build:js ../assets/js/site.min.js -->
        <!-- jQuery -->
        <script src="<?=base_url()?>assets/libs/jquery/dist/jquery.min.js"></script>
        <!-- Bootstrap -->
        <script src="<?=base_url()?>assets/libs/popper.js/dist/umd/popper.min.js"></script>
        <script src="<?=base_url()?>assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
        <!-- ajax page -->
        <script src="<?=base_url()?>assets/libs/pjax/pjax.min.js"></script>
        <script src="<?=base_url()?>/assets/js/ajax.js"></script>
        <!-- lazyload plugin -->
        <script src="<?=base_url()?>/assets/js/lazyload.config.js"></script>
        <script src="<?=base_url()?>/assets/js/lazyload.js"></script>
        <script src="<?=base_url()?>/assets/js/plugin.js"></script>
        <!-- scrollreveal -->
        <script src="<?=base_url()?>assets/libs/scrollreveal/dist/scrollreveal.min.js"></script>
        <!-- feathericon -->
        <script src="<?=base_url()?>assets/libs/feather-icons/dist/feather.min.js"></script>
        <script src="<?=base_url()?>/assets/js/plugins/feathericon.js"></script>
        <!-- theme -->
        <script src="<?=base_url()?>/assets/js/theme.js"></script>
        <script src="<?=base_url()?>/assets/js/utils.js"></script>
        <!-- endbuild -->
        <script type="text/javascript">
        $(document).ready(function(){
            $("#form1").submit(function(event) {
                event.preventDefault();
                var $form = $( this );

                Swal.fire({
                    title: "",
                    icon: "info",
                    text: "Mohon ditunggu...",
                    onOpen: function() {
                        Swal.showLoading()
                    }
                })

                var url = '<?=base_url()?>auth/page/login';

                $.post(url, $form.serialize(), function(data) {
                    var ret = $.parseJSON(data);
                    swal.close();
                    if(ret.success) { 
                        window.location = "<?=base_url()?>main";
                    } else {  
                        Swal.fire({
                            title: ret.title,
                            text: ret.text,
                            icon: 'error',
                            showConfirmButton: false,
                            timer: 2500
                        })
                    }
                }).fail(function(data) {
                    swal.close();
                    Swal.fire({ 
                        title: 'Error',
                        text: '404 Halaman Tidak Ditemukan',
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 2500
                    })
                });
            });
        });
        </script>
    </body>
</html>