<!DOCTYPE html>
<html>
<head>
    <title>Laporan Neraca</title>
</head>
<body>

    <style type="text/css">
/* Pengaturan border-collapse jenis,ukuran serta warna huruf secara keseluruhan tabel */
.tabela table{
    border-collapse:collapse;
    font:normal normal 10px Verdana,Arial,Sans-Serif;
    /*color:#238383;*/
    color:#333333;
}
/* Mengatur warna latar, warna teks, ukruan font dan jenis bold (tebal) pada header tabel */
.tabela table th {
    /*background:#00BFFF;*/
    background:#238383;
    color:#fff;
    font-weight:bold;
    font-size:12px;
}
/* Mengatur border dan jarak/ruang pada kolom */
.tabela table th,
.tabela table td {
    vertical-align:top;
    padding:2px 4px;
    border:1px solid #696969;
}

    </style>

                    <div>
                        <?php
                        $query = $this->db->query("SELECT a.tgltrx, a.nobukti, a.ket, JSON_ARRAYAGG(JSON_OBJECT('idtrx', a.id, 'kd_akun', a.kdakun, 'nama_akun' , b.namaakun, 'debet' , a.d, 'kredit', a.k, 'kdakun' , a.kdakun) ) AS arr_akun , a.created_at FROM t_keuangan a LEFT JOIN m_akun b ON a.kdakun=b.kdakun WHERE a.is_deleted='0' and a.is_transaksi!='2' and date(a.created_at)='".$_GET['periode']."' and a.created_by='".$_GET['iduser']."' GROUP BY a.nobukti order by a.created_at")->result() ;

                        $ceknama = $this->db->query("SELECT * FROM master_user where id='".$_GET['iduser']."' ")->row();
                        ?>

                        <div align="center" style="font-weight: bold;">Laporan Harian User </div>
                        <div align="center">Nama : <?=$ceknama->user_username?></div>
                        <div align="center">Tanggal <?=date("d M Y" , strtotime($_GET['periode']))?></div>
                        <br>

                                <?php 
                                        $no = 0;
                                    foreach ($query as $key => $value) { $no++;
                                            $jmlarray = json_decode($query[$key]->arr_akun, true);
                                            usort($jmlarray, function($a , $b) { return $a['kredit'] - $b['kredit']; });

                                        ?>

                                <?php }
                                ?>
                        <div class="tabela">
                            <table border="1" width="100%" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="10%">Tanggal</th>
                                        <th width="35%">Akun</th>
                                        <th width="">Keterangan</th>
                                        <th width="13%">Debet</th>
                                        <th width="13%">Kredit</th>
                                    </tr>
                                </thead>
                                <?php 
                                        $no = 0;
                                    foreach ($query as $key => $value) { $no++;
                                            $jmlarray = json_decode($query[$key]->arr_akun, true);
                                            usort($jmlarray, function($a , $b) { return $a['kredit'] - $b['kredit']; })
                                        ?>
                                            <tr>
                                                <td colspan="6" height="10px" style="font-size: 6px; text-align: center;"><?=date("H:i:s" , strtotime($query[$key]->created_at))?></td>
                                            </tr>
                                            <tr>
                                                <td rowspan="<?=count($jmlarray)+1?>"><?=$no?>
                                                </td>
                                                <td rowspan="<?=count($jmlarray)+1?>"><?=date( "d-m-Y" , strtotime($query[$key]->tgltrx))?></td>
                                            </tr>
                                            <?php 
                                                for ($i=0; $i < count($jmlarray) ; $i++) { 
                                                    ?>
                                                    <tr>
                                                        <td align="left"><?=$jmlarray[$i]['kdakun']?> - <?=$jmlarray[$i]['nama_akun']?></td>
                                                        <?php 
                                                        	if ($i==0) { ?>
				                                                 <td rowspan="<?=count($jmlarray)?>"><?=$query[$key]->ket?></td>
                                                        <?php }
                                                        ?>
                                                        <td align="right"><?=$jmlarray[$i]['debet']==0? '-' : number_format($jmlarray[$i]['debet'])?></td>
                                                        <td align="right"><?=$jmlarray[$i]['kredit']==0? '-' : number_format($jmlarray[$i]['kredit'])?></td>
                                                    </tr>
                                                    <?php 

                                                }
                                            ?>

                                <?php }
                                ?>
                            </table>
                        </div>
                        <br>
                        <br>
                        <div align="center">SEMARANG , <?=date("d M Y")?></div>
                        <div align="center">PT GRIYAPONDOK BINA SEJATI</div>
                        <div style="height: 90px;"></div>
                        <table width="100%">
                            <tr>
                                <td align="center"><u>ANANG MARDIANTO</u></td>
                                <td align="center"><u>ADHIB NOOR ROSYID</u></td>
                            </tr>
                            <tr>
                                <td align="center">DIREKTUR UTAMA</td>
                                <td align="center">DIREKTUR</td>
                            </tr>
                        </table>

                    </div>

</body>
</html>
