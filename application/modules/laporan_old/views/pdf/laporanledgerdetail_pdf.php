<!DOCTYPE html>
<html>
<head>
    <title>Laporan Ledger Detail</title>
</head>
<body>

    <style type="text/css">
/* Pengaturan border-collapse jenis,ukuran serta warna huruf secara keseluruhan tabel */
.tabela table{
    border-collapse:collapse;
    font:normal normal 10px Verdana,Arial,Sans-Serif;
    color:#333333;

}
/* Mengatur warna latar, warna teks, ukruan font dan jenis bold (tebal) pada header tabel */
.tabela table th {
    /*background:#00BFFF;*/
    background:#238383;
    color:#000;
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
/* Mengatur warna baris */
.tabela table tr {
    background:#F5FFFA;
}
/* Mengatur warna baris genap (akan menghasilkan warna selang seling pada baris tabel) */
.tabela table tr:nth-child(even) {
    background:#F0F8FF;
}

    </style>


                    <div>

                        <div align="center">LAPORAN LEDGER DETAIL</div>
                        <div align="center">TANGGAL <?=date("d M Y" , strtotime($_GET['periode_awal'])).' SD '.date("d M Y" , strtotime($_GET['periode_akhir']))?></div>
                        <br>
                        <?php 
                        if ($_GET['kdakun']=='null') {
                            $wherebyakun = " ";
                        } else {
                            $wherebyakun = " and a.kdakun='".$_GET['kdakun']."' ";
                        }
                        $querylegerdetail = $this->db->query("SELECT a.namaakun  , a.normalbalance
                                , JSON_ARRAYAGG(JSON_OBJECT('tgl_trx', b.tgltrx , 'ket' , b.ket , 'd' , b.d , 'k' , b.k , 'saldo' , NULL) ) AS arr_detailakun,
                                c.saldo
                                from m_akun a
                                join t_keuangan b on a.`kdakun`=b.kdakun
                                left join (select
                                    a.kdakun,
                                    if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldo
                                    from m_akun a
                                    left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".$_GET['periode_awal']."'
                                    group by a.kdakun) c on a.kdakun=c.kdakun

                                where b.is_deleted='0' and b.tgltrx between '".$_GET['periode_awal']."' and '".$_GET['periode_akhir']."' $wherebyakun
                                group by a.kdakun");

                        if ($querylegerdetail->num_rows()!=0) {

                         } else {
                            echo "Maaf Tidak Ada Transaksi";
                         }
                        foreach ($querylegerdetail->result() as $item) {
                            echo "<br><strong>".$item->namaakun."</strong><br>";
                            ?>
                            <?php

                            
                            $decode_detailakun = json_decode($item->arr_detailakun, TRUE);

                            // echo count($decode_detailakun)."<br>";
                              
                             ?>
                            <div class="tabela">
                             <table width="100%" border="1">
                                 <thead>
                                    <tr>
                                        <th width="4%" >#</th>
                                        <th width="10%" align="center">Tanggal</th>
                                        <th>Keterangan</th>
                                        <th width="13%" align="right">D</th>
                                        <th width="13%" align="right">K</th>
                                        <th width="13%" align="right">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#</td>
                                        <td align="center"><?=date('Y-m-d', strtotime($_GET['periode_awal']. "-1 days") )?></td>
                                        <td>Saldo Awal</td>
                                        <td></td>
                                        <td></td>
                                        <td align="right"><?=number_format($item->saldo)?></td>
                                    </tr>
                                    <?php
                                    $no = 0;
                                    $saldo = $item->saldo;
                                    foreach ($decode_detailakun as $detailtrx) {
                                        $no++;
                                        if($item->normalbalance=='D'){
                                            $saldo = $saldo+$detailtrx['d']-$detailtrx['k'];
                                        }else{
                                            $saldo = $saldo-$detailtrx['d']+$detailtrx['k'];
                                        }
                                     ?>
                                    <tr>
                                        <td ><?=$no?></td>
                                        <td  align="center"><?=$detailtrx['tgl_trx']?></td>
                                        <td><?=$detailtrx['ket']?></td>
                                        <td  align="right"><?=number_format($detailtrx['d'])?></td>
                                        <td  align="right"><?=number_format($detailtrx['k'])?> </td>
                                        <td  align="right"><?=number_format($saldo)?></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                             </table>
                             </div>

                             <?php  
                        }
                        ?>
<br>
                        <br>
                        <div align="center">SEMARANG , <?=date("d M Y")?></div>
                        <div align="center">PT GRIYAPONDOK BINA SEJATI</div>
                        <div style="height: 70px;"></div>
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
