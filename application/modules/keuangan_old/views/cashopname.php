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
                </div>
              </div>
              <div class="text-left">
                <button id="cektgl" class="btn btn-primary">Cek Opname</button>
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
                <div class="modal-title text-md">Cash Opname</div>
                <button class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <form id="forminput" autocomplete="off">
                  <input type="hidden" id="id" name="id">
                  <input type="hidden" id="kd_akun" name="kdakun">
                  <input type="hidden" class="form-control" id="<?= $this->security->get_csrf_token_name() ?>" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" required>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-9">
                            <input type="date" class="form-control" id="tgltrx" name="tgl" placeholder="Date" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Saldo Kas</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="saldo" name="saldo_kas" placeholder="Saldo Kas" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-12 col-form-label">Rincian Mata Uang</label>
                    </div>
                    <div class="form-group row row-sm">
                        <label class="col-sm-3 col-form-label">Nominal</label>
                        <label class="col-sm-5 col-form-label">Jumlah</label>
                        <label class="col-sm-4 col-form-label">Total</label>
                    </div>
                    <div class="form-group row row-sm">
                        <label class="col-sm-3 col-form-label">100.000  <input type="hidden" class="form-control" id="nominal100k" value="100000">x</label>
                        <div class="col-sm-3">
                            <input type="tel" class="form-control" id="jml100k" name="jml100k" placeholder="Jumlah">
                        </div>
                        <label class="col-sm-2 col-form-label">
                          Lembar
                        </label>
                        <label class="col-sm-4 col-form-label row">Rp&nbsp;
                          <div id="total100k" class="jadienol">0</div>
                        </label>
                    </div>

                    <div class="form-group row row-sm">
                        <label class="col-sm-3 col-form-label">75.000  <input type="hidden" class="form-control" id="nominal75k" value="75000">x</label>
                        <div class="col-sm-3">
                            <input type="tel" class="form-control" id="jml75k" name="jml75k" placeholder="Jumlah">
                        </div>
                        <label class="col-sm-2 col-form-label">
                          Lembar
                        </label>
                        <label class="col-sm-4 col-form-label row">Rp&nbsp;
                          <div id="total75k" class="jadienol">0</div>
                        </label>
                    </div>

                    <div class="form-group row row-sm">
                        <label class="col-sm-3 col-form-label">50.000  <input type="hidden" class="form-control" id="nominal50k" value="50000">x</label>
                        <div class="col-sm-3">
                            <input type="tel" class="form-control" id="jml50k" name="jml50k" placeholder="Jumlah">
                        </div>
                        <label class="col-sm-2 col-form-label">
                          Lembar
                        </label>
                        <label class="col-sm-4 col-form-label row">Rp&nbsp;
                          <div id="total50k" class="jadienol">0</div>
                        </label>
                    </div>

                    <div class="form-group row row-sm">
                        <label class="col-sm-3 col-form-label">20.000  <input type="hidden" class="form-control" id="nominal20k" value="20000">x</label>
                        <div class="col-sm-3">
                            <input type="tel" class="form-control" id="jml20k" name="jml20k" placeholder="Jumlah">
                        </div>
                        <label class="col-sm-2 col-form-label">
                          Lembar
                        </label>
                        <label class="col-sm-4 col-form-label row">Rp&nbsp;
                          <div id="total20k" class="jadienol">0</div>
                        </label>
                    </div>


                    <div class="form-group row row-sm">
                        <label class="col-sm-3 col-form-label">10.000  <input type="hidden" class="form-control" id="nominal10k" value="10000">x</label>
                        <div class="col-sm-3">
                            <input type="tel" class="form-control" id="jml10k" name="jml10k" placeholder="Jumlah">
                        </div>
                        <label class="col-sm-2 col-form-label">
                          Lembar
                        </label>
                        <label class="col-sm-4 col-form-label row">Rp&nbsp;
                          <div id="total10k" class="jadienol">0</div>
                        </label>
                    </div>

                    <div class="form-group row row-sm">
                        <label class="col-sm-3 col-form-label">5.000  <input type="hidden" class="form-control" id="nominal5k" value="5000">x</label>
                        <div class="col-sm-3">
                            <input type="tel" class="form-control" id="jml5k" name="jml5k" placeholder="Jumlah">
                        </div>
                        <label class="col-sm-2 col-form-label">
                          Lembar
                        </label>
                        <label class="col-sm-4 col-form-label row">Rp&nbsp;
                          <div id="total5k" class="jadienol">0</div>
                        </label>
                    </div>

                    <div class="form-group row row-sm">
                        <label class="col-sm-3 col-form-label">2.000  <input type="hidden" class="form-control" id="nominal2k" value="2000">x</label>
                        <div class="col-sm-3">
                            <input type="tel" class="form-control" id="jml2k" name="jml2k" placeholder="Jumlah">
                        </div>
                        <label class="col-sm-2 col-form-label">
                          Lembar
                        </label>
                        <label class="col-sm-4 col-form-label row">Rp&nbsp;
                          <div id="total2k" class="jadienol">0</div>
                        </label>
                    </div>

                    <div class="form-group row row-sm">
                        <label class="col-sm-3 col-form-label">1.000  <input type="hidden" class="form-control" id="nominal1k" value="1000">x</label>
                        <div class="col-sm-3">
                            <input type="tel" class="form-control" id="jml1k" name="jml1k" placeholder="Jumlah">
                        </div>
                        <label class="col-sm-2 col-form-label">
                          Lembar
                        </label>
                        <label class="col-sm-4 col-form-label row">Rp&nbsp;
                          <div id="total1k" class="jadienol">0</div>
                        </label>
                    </div>

                    <div class="form-group row row-sm">
                        <label class="col-sm-3 col-form-label">1.000  <input type="hidden" class="form-control" id="nominal1kc" value="1000">x</label>
                        <div class="col-sm-3">
                            <input type="tel" class="form-control" id="jml1kc" name="jml1kc" placeholder="Jumlah">
                        </div>
                        <label class="col-sm-2 col-form-label">
                          Coin
                        </label>
                        <label class="col-sm-4 col-form-label row">Rp&nbsp;
                          <div id="total1kc" class="jadienol">0</div>
                        </label>
                    </div>

                    <div class="form-group row row-sm">
                        <label class="col-sm-3 col-form-label">500  <input type="hidden" class="form-control" id="nominal500c" value="500">x</label>
                        <div class="col-sm-3">
                            <input type="tel" class="form-control" id="jml500c" name="jml500c" placeholder="Jumlah">
                        </div>
                        <label class="col-sm-2 col-form-label">
                          Coin
                        </label>
                        <label class="col-sm-4 col-form-label row">Rp&nbsp;
                          <div id="total500c" class="jadienol">0</div>
                        </label>
                    </div>

                    <div class="form-group row row-sm">
                        <label class="col-sm-3 col-form-label">200  <input type="hidden" class="form-control" id="nominal200c" value="200">x</label>
                        <div class="col-sm-3">
                            <input type="tel" class="form-control" id="jml200c" name="jml200c" placeholder="Jumlah">
                        </div>
                        <label class="col-sm-2 col-form-label">
                          Coin
                        </label>
                        <label class="col-sm-4 col-form-label row">Rp&nbsp;
                          <div id="total200c" class="jadienol">0</div>
                        </label>
                    </div>

                    <div class="form-group row row-sm">
                        <label class="col-sm-3 col-form-label">100  <input type="hidden" class="form-control" id="nominal100c" value="100">x</label>
                        <div class="col-sm-3">
                            <input type="tel" class="form-control" id="jml100c" name="jml100c" placeholder="Jumlah">
                        </div>
                        <label class="col-sm-2 col-form-label">
                          Coin
                        </label>
                        <label class="col-sm-4 col-form-label row">Rp&nbsp; 
                          <div id="total100c" class="jadienol">0</div>
                        </label>
                    </div>

                    <div class="form-group row row-sm">
                        <label class="col-sm-8 col-form-label">Total</label>
                        <label class="col-sm-4 col-form-label row">Rp&nbsp; 
                          <div id="grandtotal" class="jadienol">0</div>
                        </label>
                    </div>
                    <div class="form-group row row-sm selisih" >
                        <label class="col-sm-8 col-form-label" style="color: red !important;" >Selisih</label>
                        <label class="col-sm-4 col-form-label row" style="color: red !important;" >Rp&nbsp; 
                          <div id="selisihgrandtotal" class="jadienol" style="color: red;">0</div>
                        </label>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
                <button type="submit" form="forminput" id="buttonsimpanopname" class="btn btn-primary" disabled>Simpan</button>
            </div>
        </div>
        <!-- /.modal-content -->
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

      $('#pdf-download').on('click', function (e) {
            $(this).attr("href", "<?=base_url()?>keuangan/pdf/cashopname?tanggal="+ $('#tanggal').val() );
            $(this).attr("target", "_blank");
        })

      $("#jml100k").keyup(function(event) {
        $("#total100k").html(rubah($("#jml100k").val()*$("#nominal100k").val()));
        hitunggrandtotal();
      });

      $("#jml75k").keyup(function(event) {
        $("#total75k").html(rubah($("#jml75k").val()*$("#nominal75k").val()));
        hitunggrandtotal();
      });

      $("#jml50k").keyup(function(event) {
        $("#total50k").html(rubah($("#jml50k").val()*$("#nominal50k").val()));
        hitunggrandtotal();
      });

      $("#jml20k").keyup(function(event) {
        $("#total20k").html(rubah($("#jml20k").val()*$("#nominal20k").val()));
        hitunggrandtotal();
      });

      $("#jml10k").keyup(function(event) {
        $("#total10k").html(rubah($("#jml10k").val()*$("#nominal10k").val()));
        hitunggrandtotal();
      });

      $("#jml5k").keyup(function(event) {
        $("#total5k").html(rubah($("#jml5k").val()*$("#nominal5k").val()));
        hitunggrandtotal();
      });

      $("#jml2k").keyup(function(event) {
        $("#total2k").html(rubah($("#jml2k").val()*$("#nominal2k").val()));
        hitunggrandtotal();
      });

      $("#jml1k").keyup(function(event) {
        $("#total1k").html(rubah($("#jml1k").val()*$("#nominal1k").val()));
        hitunggrandtotal();
      });

      $("#jml1kc").keyup(function(event) {
        $("#total1kc").html(rubah($("#jml1kc").val()*$("#nominal1kc").val()));
        hitunggrandtotal();
      });

      $("#jml500c").keyup(function(event) {
        $("#total500c").html(rubah($("#jml500c").val()*$("#nominal500c").val()));
        hitunggrandtotal();
      });

      $("#jml200c").keyup(function(event) {
        $("#total200c").html(rubah($("#jml200c").val()*$("#nominal200c").val()));
        hitunggrandtotal();
      });

      $("#jml100c").keyup(function(event) {
        $("#total100c").html(rubah($("#jml100c").val()*$("#nominal100c").val()));
        hitunggrandtotal();
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

                        $("#total100k").html(rubah($("#jml100k").val()*$("#nominal100k").val()));
                        $("#total75k").html(rubah($("#jml75k").val()*$("#nominal75k").val()));
                        $("#total50k").html(rubah($("#jml50k").val()*$("#nominal50k").val()));
                        $("#total20k").html(rubah($("#jml20k").val()*$("#nominal20k").val()));
                        $("#total10k").html(rubah($("#jml10k").val()*$("#nominal10k").val()));
                        $("#total5k").html(rubah($("#jml5k").val()*$("#nominal5k").val()));
                        $("#total2k").html(rubah($("#jml2k").val()*$("#nominal2k").val()));
                        $("#total1k").html(rubah($("#jml1k").val()*$("#nominal1k").val()));
                        $("#total1kc").html(rubah($("#jml1kc").val()*$("#nominal1kc").val()));
                        $("#total500c").html(rubah($("#jml500c").val()*$("#nominal500c").val()));
                        $("#total200c").html(rubah($("#jml200c").val()*$("#nominal200c").val()));
                        $("#total100c").html(rubah($("#jml100c").val()*$("#nominal100c").val()));
                        hitunggrandtotal();

                        if (result.data['tgltrx']==='<?=date("Y-m-d")?>') {
                          $('#buttonsimpanopname').show();
                        } else {
                          $('#buttonsimpanopname').hide();
                        }

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


    function rubah(angka){
       var reverse = angka.toString().split('').reverse().join(''),
       ribuan = reverse.match(/\d{1,3}/g);
       ribuan = ribuan.join(',').split('').reverse().join('');
       return ribuan;
    }

    function hitunggrandtotal(){
      var grandtotal = parseInt($("#total100k").html().replaceAll(",", ""))+parseInt($("#total75k").html().replaceAll(",", ""))+parseInt($("#total50k").html().replaceAll(",", ""))+parseInt($("#total20k").html().replaceAll(",", ""))+parseInt($("#total10k").html().replaceAll(",", ""))+parseInt($("#total5k").html().replaceAll(",", ""))+parseInt($("#total2k").html().replaceAll(",", ""))+parseInt($("#total1k").html().replaceAll(",", ""))+parseInt($("#total1kc").html().replaceAll(",", ""))+parseInt($("#total500c").html().replaceAll(",", ""))+parseInt($("#total200c").html().replaceAll(",", ""))+parseInt($("#total100c").html().replaceAll(",", ""));

      if (grandtotal==$("#saldo").val().replaceAll(",", "")) {
        $('#buttonsimpanopname').attr('disabled' , false);
        $('#selisihgrandtotal').html("0");
        $('.selisih').hide();
      } else {
        $('#buttonsimpanopname').attr('disabled' , true);
        $('#selisihgrandtotal').html(rubah(grandtotal-$("#saldo").val().replaceAll(",", "")));
        $('.selisih').show();
      }

      $('#grandtotal').html(rubah(grandtotal));

      
    }

</script>
