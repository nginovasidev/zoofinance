<div class="page-content page-container" id="page-content">
        <div class="padding">
            <div class="card">
                <div class="card-header">
                    <div class="text-center" style="font-size: 20px;">
                        Laporan Ledger Detail
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <?php 

                        function date_compare($element1, $element2) {
                                $datetime1 = strtotime($element1['tgl_trx']);
                                $datetime2 = strtotime($element2['tgl_trx']);
                                return $datetime1 - $datetime2;
                            }


                        if (isset($_POST['kdakun'])=='') {
                            $wherebyakun = " ";
                        } else {
                            $wherebyakun = " and a.kdakun='".$_POST['kdakun']."' ";
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
                                    left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".$_POST['periode_awal']."'
                                    group by a.kdakun) c on a.kdakun=c.kdakun

                                where b.is_deleted='0' and b.tgltrx between '".$_POST['periode_awal']."' and '".$_POST['periode_akhir']."' $wherebyakun
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


                            

                            usort($decode_detailakun, 'date_compare');
                            // echo json_encode($decode_detailakun)."<br>";
                              
                             ?>

                             <table width="100%" border="1">
                                <colgroup>
                                    <col width="5%">
                                    <col width="13%">
                                    <col width="">
                                    <col width="13%">
                                    <col width="13%">
                                    <col width="13%">
                                </colgroup>
                                 <thead>
                                    <tr>
                                        <th width="5%" style="text-align: center;">#</th>
                                        <th width="" style="text-align: center;">Tanggal</th>
                                        <th style="text-align: center;">Keterangan</th>
                                        <th width="13%" style="text-align: center;">Debet</th>
                                        <th width="13%" style="text-align: center;">Kredit</th>
                                        <th width="13%" style="text-align: center;">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#</td>
                                        <td align="center"><?=date('d M Y', strtotime($_POST['periode_awal']. "-1 days") )?></td>
                                        <td>Saldo Awal</td>
                                        <td></td>
                                        <td></td>
                                        <td align="right"><?=number_format($item->saldo)?></td>
                                    </tr>
                                    <?php
                                    $no = 0;
                                    $saldo = $item->saldo;

                                    $debet_awal = 0;
                                    $kredit_awal = 0;
                                    foreach ($decode_detailakun as $detailtrx) {
                                        $no++;
                                        if($item->normalbalance=='D'){
                                            $saldo = $saldo+$detailtrx['d']-$detailtrx['k'];
                                        }else{
                                            $saldo = $saldo-$detailtrx['d']+$detailtrx['k'];
                                        }

                                        $debet_awal = $debet_awal+$detailtrx['d'];
										$kredit_awal = $kredit_awal+$detailtrx['k'];

                                     ?>
                                    <tr>
                                        <td ><?=$no?></td>
                                        <td  align="center"><?=date("d M Y" , strtotime($detailtrx['tgl_trx']))?></td>
                                        <td><?=$detailtrx['ket']?></td>
                                        <td  align="right"><?=number_format($detailtrx['d'])?></td>
                                        <td  align="right"><?=number_format($detailtrx['k'])?> </td>
                                        <td  align="right"><?=number_format($saldo)?></td>
                                    </tr>
                                    <?php
                                        }
                                    ?>
                                </tbody>
                                <tfoot>
                                	<tr>
                                        <th colspan="3" style="text-align: center; font-weight: bold;">Total</th>
                                        <th  style="text-align: right;"><?=number_format($debet_awal)?></th>
                                        <th  style="text-align: right;"><?=number_format($kredit_awal)?> </th>
                                        <th  style="text-align: right;"><?=number_format($saldo)?></th>
                                    </tr>
                                </tfoot>
                             </table>

                             <?php  
                        }
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </div>