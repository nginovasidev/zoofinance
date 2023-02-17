<div class="page-content page-container" id="page-content">
        <div class="padding">
            <div class="card">
                <div class="card-header">
                    <div class="text-center" style="font-size: 20px;">
                        Cek Laba Rugi
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <?php

                        $awal = $_POST['periode_awal'];
                        $akhir = $_POST['periode_akhir'];
                        $query = $this->db->query("SELECT a1.tgltrx , a1.debet_n , a1.kredit_n , IFNULL(b1.kredit-b1.debet,0) AS kredit_l , IFNULL(c1.debet-c1.kredit, 0) AS debet_l  FROM 
                                                    (SELECT *, SUM(a.d) AS debet_n , SUM(a.k) AS kredit_n FROM `t_keuangan` a 
                                                        WHERE a.`is_transaksi`='2' AND a.`tgltrx` BETWEEN  '".$awal."' AND '".$akhir."'
                                                        GROUP BY a.tgltrx 
                                                        ORDER BY a.tgltrx ) a1
                                                    LEFT JOIN 
                                                    (SELECT b.tgltrx , IFNULL(SUM(b.d),0) AS debet, IFNULL(SUM(b.k),0) AS kredit FROM m_akun a LEFT JOIN t_keuangan b ON a.`kdakun`=b.kdakun WHERE b.tgltrx BETWEEN '".$awal."' AND '".$akhir."'
                                                                                            AND '0'=b.is_deleted AND a.kdakun LIKE '4-%'
                                                                                             GROUP BY b.tgltrx) b1 ON a1.tgltrx=b1.tgltrx
                                                        LEFT JOIN 
                                                    (SELECT b.tgltrx , IFNULL(SUM(b.d),0) AS debet, IFNULL(SUM(b.k),0) AS kredit FROM m_akun a LEFT JOIN t_keuangan b ON a.`kdakun`=b.kdakun WHERE b.tgltrx BETWEEN '".$awal."' AND '".$akhir."'
                                                                                            AND '0'=b.is_deleted AND a.kdakun LIKE '5-%'
                                                                                             GROUP BY b.tgltrx) c1 ON a1.tgltrx=c1.tgltrx")->result() ;
                        ?>

                        <style type="text/css">
                            .th-center th {
                                text-align: center;
                            }
                        </style>
                        <table border="1" width="100%" class="table table-bordered th-center">
                            <colgroup>
                                <col width="10%">
                                <col width="13%">
                                <col width="13%">
                                <col width="13%">
                                <col width="13%">
                                <col width="13%">
                                <col width="13%">
                                <col width="12%">
                            </colgroup>
                            <tr>
                                <th rowspan="2">Tanggal</th>
                                <th colspan="3">Neraca</th>
                                <th colspan="3">Laba Rugi</th>
                                <th rowspan="2">Status</th>
                            </tr>
                            <tr>
                                <th>Debet</th>
                                <th>Kredit</th>
                                <th>Total</th>
                                <th>Debet</th>
                                <th>Kredit</th>
                                <th>Total</th>
                            </tr>
                            <?php 
                                foreach ($query as $item) { 
                                    $selisih = ($item->debet_l+$item->kredit_l)-($item->debet_n+$item->kredit_n);
                                    ?>
                                <tr>
                                    <td ><?=$item->tgltrx?></td>
                                    <td ><?=$item->debet_n==0? '0' : number_format($item->debet_n)?></td>
                                    <td ><?=$item->kredit_n==0? '0' : number_format($item->kredit_n)?></td>
                                    <td ><?=$item->debet_n+$item->kredit_n==0? '0' : number_format($item->debet_n+$item->kredit_n)?></td>

                                    <td ><?=$item->debet_l==0? '0' : number_format($item->debet_l)?></td>
                                    <td ><?=$item->kredit_l==0? '0' : number_format($item->kredit_l)?></td>
                                    <td ><?=$item->debet_l+$item->kredit_l==0? '0' : number_format($item->debet_l+$item->kredit_l)?></td>
                                    <td ><?=$item->debet_l+$item->kredit_l==number_format($item->debet_n+$item->kredit_n)? 'BL' : '(' .$selisih.')'?></td>
                                </tr>

                            <?php }
                            ?>
                        </table>
                    </div>
                    <br>


                </div>
            </div>
        </div>
    </div>