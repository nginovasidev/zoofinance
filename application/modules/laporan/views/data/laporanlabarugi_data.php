<div class="page-content page-container" id="page-content">
        <div class="padding">
            <div class="card">
                <div class="card-header">
                    <div class="text-center" style="font-size: 20px;">
                        Laporan Laba Rugi
                    </div>
                </div>
                <div class="card-body">
                    <div>
                        <?php

// $tgla = date('Y-m', strtotime($_POST['periode'])).'-01';
$tgla = date('Y-m-d', strtotime($_POST['periode_awal']));
$tglawal = date('Y-m-d', strtotime($tgla.' - 1 days'));

                        $query = $this->db->query("SELECT a.kdakun , a.namaakun , a.is_saldo , a.is_bold, c.saldoawal , d.debet , d.kredit ,
                                if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0)) as saldoakhir
                                from m_akun a
                                
                                left join t_keuangan b on a.`kdakun`=b.kdakun
                                
                                left join (select a.kdakun, if(a.normalbalance='D',if('2021'='".date("Y", strtotime($_POST["periode"]))."', a.saldoawal_d , 0)+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),if('2021'='".date("Y", strtotime($_POST["periode"]))."', a.saldoawal_k , 0) +ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal 
                                from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun
                                 and b.tgltrx between '".date("Y", strtotime($_POST["periode"]))."-01-01' and '".$tglawal."' 
                                and '0'=b.is_deleted
                                group by a.kdakun) c on a.kdakun=c.kdakun

                                left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$tgla."' and '".$_POST['periode']."' 
                                and '0'=b.is_deleted
                                 group by a.kdakun) d on a.kdakun=d.kdakun

                                    where a.kdakun in (4,5)
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

                                        // old
                                    //     $qrsaldoutama = $this->db->query("
                                    // SELECT kdakun, namaakun , is_saldo , sum(saldo_awal) as saldo_awal , sum(_debet) as _debet , sum(_kredit) as _kredit , IF(normalbalance='D', IFNULL(SUM(saldo_awal),0)+IFNULL(SUM(_debet),0)-IFNULL(SUM(_kredit),0)     ,    IFNULL(SUM(saldo_awal),0)-IFNULL(SUM(_debet),0)+IFNULL(SUM(_kredit),0)) AS saldoakhir from (
                                    // SELECT a.kdakun , a.namaakun, a.normalbalance , a.is_saldo , (c.saldoawal) as saldo_awal, (d.debet) as _debet, (d.kredit) as _kredit, (if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                    //         from m_akun a
                                    //         left join t_keuangan b on a.`kdakun`=b.kdakun
                                    //         left join (select a.kdakun, if(a.normalbalance='D',if('2021'='".date("Y", strtotime($_POST["periode"]))."', a.saldoawal_d , 0)+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),if('2021'='".date("Y", strtotime($_POST["periode"]))."', a.saldoawal_k , 0) +ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun 
                                    //         and b.tgltrx between '".date("Y", strtotime($_POST["periode"]))."-01-01' and '".$tglawal."' 
                                    //         and '0'=b.is_deleted
                                    //          group by a.kdakun) c on a.kdakun=c.kdakun
                                    //         left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$tgla."' and '".$_POST['periode']."' 
                                    //         and '0'=b.is_deleted
                                    //          group by a.kdakun) d on a.kdakun=d.kdakun

                                    // where a.kdakun like '".$item->kdakun."%' group by kdakun ) a")->row(); 

                                        // new 
                                        $qrsaldoutama = $this->db->query("
                                    SELECT *, IF(normalbalance='D', sum(saldo_awal_k)+sum(saldo_awal_d) ,    sum(saldo_awal_k)-sum(saldo_awal_d)) AS saldo_awal, IF(normalbalance='D', sum(saldoakhir_k)+sum(saldoakhir_d) ,    sum(saldoakhir_k)-sum(saldoakhir_d)) AS saldoakhir FROM (SELECT kdakun, namaakun ,normalbalance, is_saldo , if(normalbalance='K', sum(saldo_awal), 0 ) as saldo_awal_k,if(normalbalance='D', sum(saldo_awal), 0 ) as saldo_awal_d, sum(_debet) as _debet , sum(_kredit) as _kredit , if(normalbalance='K', IFNULL(SUM(saldo_awal),0)-IFNULL(SUM(_debet),0)+IFNULL(SUM(_kredit),0), 0 ) as saldoakhir_k,if(normalbalance='D', IFNULL(SUM(saldo_awal),0)+IFNULL(SUM(_debet),0)-IFNULL(SUM(_kredit),0), 0 ) as saldoakhir_d from (
                                    SELECT a.kdakun , a.namaakun, a.normalbalance , a.is_saldo , (c.saldoawal) as saldo_awal, (d.debet) as _debet, (d.kredit) as _kredit, (if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                            from m_akun a
                                            left join t_keuangan b on a.`kdakun`=b.kdakun
                                            left join (select a.kdakun, if(a.normalbalance='D',if('2021'='".date("Y", strtotime($_POST["periode"]))."', a.saldoawal_d , 0)+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),if('2021'='".date("Y", strtotime($_POST["periode"]))."', a.saldoawal_k , 0) +ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun 
                                            and b.tgltrx between '".date("Y", strtotime($_POST["periode"]))."-01-01' and '".$tglawal."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) c on a.kdakun=c.kdakun
                                            left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$tgla."' and '".$_POST['periode']."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) d on a.kdakun=d.kdakun

                                    where a.kdakun like '".$item->kdakun."%' group by kdakun ) a group by normalbalance order by kdakun) a")->row(); 

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
                                    $is_bold = $item->is_bold==1? 'style="font-weight: bold"': '' ;

                                    $pr = str_repeat("&nbsp;&nbsp;",strlen($item->kdakun));
                                    ?>
                                <tr>
                                    <td <?=$style?> <?=$is_bold?> ><!-- <?=$pr?> --><?=$item->kdakun?></td>
                                    <td <?=$style?> <?=$is_bold?>><!-- <?=$pr?> --><?=$item->namaakun?></td>
                                    <td <?=$style?> <?=$is_bold?> align="right"><?=$saldoawal==0? '0' : number_format($saldoawal)?></td>
                                    <td <?=$style?> <?=$is_bold?> align="right"><?=$debet==0? '0' : number_format($debet)?></td>
                                    <td <?=$style?> <?=$is_bold?> align="right"><?=$kredit==0? '0' : number_format($kredit)?></td>
                                    <td <?=$style?> <?=$is_bold?> align="right"><?=$saldoakhir==0? '0' : number_format($saldoakhir)?></td>
                                </tr>

                            <?php }
                            $querytotalpendapatan = $this->db->query("
                                SELECT *, IF(normalbalance='D', sum(saldo_awal_k)+sum(saldo_awal_d) ,    sum(saldo_awal_k)-sum(saldo_awal_d)) AS saldo_awal, IF(normalbalance='D', sum(saldoakhir_k)+sum(saldoakhir_d) ,    sum(saldoakhir_k)-sum(saldoakhir_d)) AS saldoakhir FROM (SELECT kdakun, namaakun ,normalbalance, is_saldo , if(normalbalance='K', sum(saldo_awal), 0 ) as saldo_awal_k,if(normalbalance='D', sum(saldo_awal), 0 ) as saldo_awal_d, sum(_debet) as _debet , sum(_kredit) as _kredit , if(normalbalance='K', IFNULL(SUM(saldo_awal),0)-IFNULL(SUM(_debet),0)+IFNULL(SUM(_kredit),0), 0 ) as saldoakhir_k,if(normalbalance='D', IFNULL(SUM(saldo_awal),0)+IFNULL(SUM(_debet),0)-IFNULL(SUM(_kredit),0), 0 ) as saldoakhir_d from (
                                    SELECT a.kdakun , a.namaakun, a.normalbalance , a.is_saldo , (c.saldoawal) as saldo_awal, (d.debet) as _debet, (d.kredit) as _kredit, (if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                            from m_akun a
                                            left join t_keuangan b on a.`kdakun`=b.kdakun
                                            left join (select a.kdakun, if(a.normalbalance='D',if('2021'='".date("Y", strtotime($_POST["periode"]))."', a.saldoawal_d , 0)+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),if('2021'='".date("Y", strtotime($_POST["periode"]))."', a.saldoawal_k , 0) +ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun 
                                            and b.tgltrx between '".date("Y", strtotime($_POST["periode"]))."-01-01' and '".$tglawal."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) c on a.kdakun=c.kdakun
                                            left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$tgla."' and '".$_POST['periode']."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) d on a.kdakun=d.kdakun

                                    where a.kdakun like '4-%' group by kdakun ) a group by normalbalance order by kdakun) a ")->row();

                            $querytotalbiaya = $this->db->query("
                                SELECT *, IF(normalbalance='D', sum(saldo_awal_k)+sum(saldo_awal_d) ,    sum(saldo_awal_k)-sum(saldo_awal_d)) AS saldo_awal, IF(normalbalance='D', sum(saldoakhir_k)+sum(saldoakhir_d) ,    sum(saldoakhir_k)-sum(saldoakhir_d)) AS saldoakhir FROM (SELECT kdakun, namaakun ,normalbalance, is_saldo , if(normalbalance='K', sum(saldo_awal), 0 ) as saldo_awal_k,if(normalbalance='D', sum(saldo_awal), 0 ) as saldo_awal_d, sum(_debet) as _debet , sum(_kredit) as _kredit , if(normalbalance='K', IFNULL(SUM(saldo_awal),0)-IFNULL(SUM(_debet),0)+IFNULL(SUM(_kredit),0), 0 ) as saldoakhir_k,if(normalbalance='D', IFNULL(SUM(saldo_awal),0)+IFNULL(SUM(_debet),0)-IFNULL(SUM(_kredit),0), 0 ) as saldoakhir_d from (
                                    SELECT a.kdakun , a.namaakun, a.normalbalance , a.is_saldo , (c.saldoawal) as saldo_awal, (d.debet) as _debet, (d.kredit) as _kredit, (if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                            from m_akun a
                                            left join t_keuangan b on a.`kdakun`=b.kdakun
                                            left join (select a.kdakun, if(a.normalbalance='D',if('2021'='".date("Y", strtotime($_POST["periode"]))."', a.saldoawal_d , 0)+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),if('2021'='".date("Y", strtotime($_POST["periode"]))."', a.saldoawal_k , 0) +ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun 
                                            and b.tgltrx between '".date("Y", strtotime($_POST["periode"]))."-01-01' and '".$tglawal."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) c on a.kdakun=c.kdakun
                                            left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$tgla."' and '".$_POST['periode']."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) d on a.kdakun=d.kdakun

                                    where a.kdakun like '5-%' group by kdakun ) a group by normalbalance order by kdakun) a ")->row();

                            $querytotaltaksiran = $this->db->query("
                                SELECT *, IF(normalbalance='D', sum(saldo_awal_k)+sum(saldo_awal_d) ,    sum(saldo_awal_k)-sum(saldo_awal_d)) AS saldo_awal, IF(normalbalance='D', sum(saldoakhir_k)+sum(saldoakhir_d) ,    sum(saldoakhir_k)-sum(saldoakhir_d)) AS saldoakhir FROM (SELECT kdakun, namaakun ,normalbalance, is_saldo , if(normalbalance='K', sum(saldo_awal), 0 ) as saldo_awal_k,if(normalbalance='D', sum(saldo_awal), 0 ) as saldo_awal_d, sum(_debet) as _debet , sum(_kredit) as _kredit , if(normalbalance='K', IFNULL(SUM(saldo_awal),0)-IFNULL(SUM(_debet),0)+IFNULL(SUM(_kredit),0), 0 ) as saldoakhir_k,if(normalbalance='D', IFNULL(SUM(saldo_awal),0)+IFNULL(SUM(_debet),0)-IFNULL(SUM(_kredit),0), 0 ) as saldoakhir_d from (
                                    SELECT a.kdakun , a.namaakun, a.normalbalance , a.is_saldo , (c.saldoawal) as saldo_awal, (d.debet) as _debet, (d.kredit) as _kredit, (if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                            from m_akun a
                                            left join t_keuangan b on a.`kdakun`=b.kdakun
                                            left join (select a.kdakun, if(a.normalbalance='D',if('2021'='".date("Y", strtotime($_POST["periode"]))."', a.saldoawal_d , 0)+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),if('2021'='".date("Y", strtotime($_POST["periode"]))."', a.saldoawal_k , 0) +ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun 
                                            and b.tgltrx between '".date("Y", strtotime($_POST["periode"]))."-01-01' and '".$tglawal."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) c on a.kdakun=c.kdakun
                                            left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$tgla."' and '".$_POST['periode']."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) d on a.kdakun=d.kdakun

                                    where a.kdakun like '6%' group by kdakun ) a group by normalbalance order by kdakun) a ")->row();

                            $labakotor = $querytotalpendapatan->saldoakhir-$querytotalbiaya->saldoakhir;
                            ?>

                            <tr>
                                <td colspan="5" align="right" style="font-weight:700">Laba Kotor</td>
                                <td align="right" style="font-weight: bold;"><?=$labakotor==0? '0' : number_format($labakotor)?></td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right" style="font-weight:700">Taksiran Pajak Penghasilan</td>
                                <td align="right" style="font-weight: bold;"><?=$querytotaltaksiran->saldo_awal==0? '0' : number_format($querytotaltaksiran->saldo_awal)?></td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right" style="font-weight:700">Laba Bersih</td>
                                <td align="right" style="font-weight: bold;"><?=$labakotor-$querytotaltaksiran->saldo_awal==0? '0' : number_format($labakotor-$querytotaltaksiran->saldo_awal)?></td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    <!-- <br> -->
                    <!-- <br> -->
                    <!-- <div class="text-left">
                        <button class="btn btn-primary">Download</button>
                    </div> -->
                </div>
            </div>
        </div>
    </div>