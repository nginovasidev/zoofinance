<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
<div>
    <!-- <div class="page-hero page-container " id="page-hero">
        <div class="padding d-flex">
            <div class="page-title">
                <h2 class="text-md text-highlight"><?= $page_title ?></h2>
            </div>
            <div class="flex"></div>
        </div>
    </div> -->
    <div class="page-content page-container" id="page-content">
        <div class="padding">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills card-header-pills no-border" id="tab">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-form" role="tab" aria-controls="tab-form" aria-selected="false">Periode</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <form data-plugin="parsley" data-option="{}" id="form" autocomplete="off">
                        <input type="hidden" class="form-control" id="id" name="id" value="" required>
                        <input type="hidden" class="form-control" id="<?= $this->security->get_csrf_token_name() ?>" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>" required>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-6">
                                    <label for="periode">Periode</label>
                                    <input type="date" class="form-control" id="periode" name="periode" placeholder="Periode" required>
                                </div>
                            </div>
                        </div>
                        <div class="text-left">
                            <button class="btn btn-primary">Lihat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="page-content page-container" id="page-content">
        <div class="padding">
            <div class="card">
                <div class="card-header">
                    <div class="text-center">
                        Laporan Inventaris
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <table class="table table-bordered" id="tb-perkiraan">
                            <colgroup>
                                <col style="width:1%" />
                                <col style="" />
                                <col style="width:15%" />
                                <col style="width:15%" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah Barang</th>
                                    <th>Tgl Peroleh</th>
                                    <th>Harga Peroleh</th>
                                    <th>Kategori</th>
                                    <th>Akumilasi</th>
                                    <th>Total Akumulasi</th>
                                    <th>Nilai Buku</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Meja</td>
                                    <td>100</td>
                                    <td>02-02-2021</td>
                                    <td>15.000</td>
                                    <td>Kelompok I</td>
                                    <td>10</td>
                                    <td>1000</td>
                                    <td>1.500.000</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Kursi</td>
                                    <td>10</td>
                                    <td>02-02-2021</td>
                                    <td>10.000</td>
                                    <td>Kelompok I</td>
                                    <td>10</td>
                                    <td>100</td>
                                    <td>100.000</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">Total</td>
                                    <td>110</td>
                                    <td></td>
                                    <td>25.000</td>
                                    <td></td>
                                    <td>20</td>
                                    <td>1.100</td>
                                    <td>1.600.000</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="text-left">
                        <button class="btn btn-primary">Download</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>