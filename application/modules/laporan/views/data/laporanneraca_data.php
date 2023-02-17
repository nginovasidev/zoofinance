<div class="page-content page-container" id="page-content">
        <div class="padding">
            <div class="card">
                <div class="card-header">
                    <div class="text-center" style="font-size: 20px;">
                        Ringkasan Neraca
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <?php
                            $satu = $this->db->query("SELECT a.kdakun , a.namaakun , a.is_saldo , sum(c.saldoawal) as saldo_awal, sum(d.debet) as _debet, sum(d.kredit) as _kredit, sum(if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                            from m_akun a
                                            left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".date('Y-m-d', strtotime($_POST['periode']))."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) c on a.kdakun=c.kdakun
                                            left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$_POST['periode']."' and '".$_POST['periode']."' and TIME(b.created_at)<='23:59:58'
                                            and '0'=b.is_deleted
                                             group by a.kdakun) d on a.kdakun=d.kdakun
                                        where a.kdakun like '1%'")->row() ;

                            $dua = $this->db->query("SELECT a.kdakun , a.namaakun , a.is_saldo , sum(c.saldoawal) as saldo_awal, sum(d.debet) as _debet, sum(d.kredit) as _kredit, sum(if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                            from m_akun a
                                            left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".date('Y-m-d', strtotime($_POST['periode']))."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) c on a.kdakun=c.kdakun
                                            left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$_POST['periode']."' and '".$_POST['periode']."' and TIME(b.created_at)<='23:59:58'
                                            and '0'=b.is_deleted
                                             group by a.kdakun) d on a.kdakun=d.kdakun
                                        where a.kdakun like '2%'")->row() ;

                            $tiga = $this->db->query("SELECT a.kdakun , a.namaakun , a.is_saldo , sum(c.saldoawal) as saldo_awal, sum(d.debet) as _debet, sum(d.kredit) as _kredit, sum(if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                            from m_akun a
                                            left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".date('Y-m-d', strtotime($_POST['periode']))."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) c on a.kdakun=c.kdakun
                                            left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$_POST['periode']."' and '".$_POST['periode']."' and TIME(b.created_at)<='23:59:58'
                                            and '0'=b.is_deleted
                                             group by a.kdakun) d on a.kdakun=d.kdakun
                                        where a.kdakun like '3%'")->row() ;

                        ?>

                        <style type="text/css">

                            .resumee th{
                                padding-left: 5px;
                            }

                            .resumee td{
                                padding-left: 5px;
                            }
                        </style>
                        <table width="100%" border="1" class="resumee">
                            <tr>
                                <th>KD AKUN</th>
                                <th>NAMA AKUN</th>
                                <th>SALDO AKHIR</th>
                                <th>TOTAL</th>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td><?=$satu->namaakun?></td>
                                <td><?=number_format($satu->saldoakhir)?></td>
                                <td><?=number_format($satu->saldoakhir)?></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td><?=$dua->namaakun?></td>
                                <td><?=number_format($dua->saldoakhir)?></td>
                                <td rowspan="2"><?=number_format($dua->saldoakhir+$tiga->saldoakhir)?></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td><?=$tiga->namaakun?></td>
                                <td><?=number_format($tiga->saldoakhir)?></td>
                            </tr>
                            <tr>
                                <td colspan="3" style="text-align: center; font-weight: bold;">Selisih</td>
                                <td style="font-weight: bold;"><?=number_format($satu->saldoakhir-($dua->saldoakhir+$tiga->saldoakhir))?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="text-center" style="font-size: 20px;">
                        Laporan Neraca
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <?php

                        $query = $this->db->query("SELECT a.kdakun , a.namaakun , a.is_saldo , c.saldoawal , d.debet , d.kredit ,
                                if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0)) as saldoakhir
                                from m_akun a
                                
                                left join t_keuangan b on a.`kdakun`=b.kdakun
                                
                                left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".date('Y-m-d', strtotime($_POST['periode']))."' 
                                and '0'=b.is_deleted
                                group by a.kdakun) c on a.kdakun=c.kdakun

                                left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$_POST['periode']."' and '".$_POST['periode']."' 
                                and '0'=b.is_deleted and TIME(b.created_at)<='23:59:58'
                                 group by a.kdakun) d on a.kdakun=d.kdakun

                                    where a.kdakun in (1,2,3)
                                group by a.kdakun")->result() ;
                        ?>
                        <table border="1" width="100%" class="table table-bordered">
                            <colgroup>
                                <col width="20%">
                                <col width="">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                                <col width="10%">
                            </colgroup>
                            <tr>
                                <th>Kode Akun</th>
                                <th>Nama Akun</th>
                                <th>Saldo Awal</th>
                                <th>Debet</th>
                                <th>Kredit</th>
                                <th>Saldo Akhir</th>
                            </tr>
                            <?php 
                                foreach ($query as $item) { 

                                    if ($item->is_saldo==0) {
                                            
                                        $qrsaldoutama = $this->db->query(" SELECT a.kdakun , a.namaakun , a.is_saldo , sum(c.saldoawal) as saldo_awal, sum(d.debet) as _debet, sum(d.kredit) as _kredit, sum(if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                            from m_akun a
                                            -- left join t_keuangan b on a.`kdakun`=b.kdakun
                                            left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".date('Y-m-d', strtotime($_POST['periode']))."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) c on a.kdakun=c.kdakun
                                            left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$_POST['periode']."' and '".$_POST['periode']."' and TIME(b.created_at)<='23:59:58'
                                            and '0'=b.is_deleted
                                             group by a.kdakun) d on a.kdakun=d.kdakun

                                        where a.kdakun like '".$item->kdakun."%'  ")->row(); 

                                        $saldoawal = $qrsaldoutama->saldo_awal;
                                        $debet = $qrsaldoutama->_debet;
                                        $kredit = $qrsaldoutama->_kredit;
                                        $saldoakhir = $qrsaldoutama->saldoakhir;
                                    } else {
                                    	
	                                        $saldoawal = $item->saldoawal;
	                                        $debet = $item->debet;
	                                        $kredit = $item->kredit;
	                                        $saldoakhir = $item->saldoakhir;
                                    }
                                    $style = $item->is_saldo==0? 'style="font-weight: bold"': '' ;

                                    $pr = str_repeat("&nbsp;&nbsp;",strlen($item->kdakun));
                                    ?>
                                <tr>
                                    <td <?=$style?> ><?=$item->kdakun?></td>
                                    <td <?=$style?> ><?=$item->namaakun?></td>
                                    <td <?=$style?> align="right"><?=$saldoawal==0? '0' : number_format($saldoawal)?></td>
                                    <td <?=$style?> align="right"><?=$debet==0? '0' : number_format($debet)?></td>
                                    <td <?=$style?> align="right"><?=$kredit==0? '0' : number_format($kredit)?></td>
                                    <td <?=$style?> align="right"><?=$saldoakhir==0? '0' : number_format($saldoakhir)?></td>
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