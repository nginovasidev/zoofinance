<!DOCTYPE html>
<html>
<head>
    <title>Laporan Laba Rugi</title>
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
    color:#FFF;
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
                        <?php
                        // $query = $this->db->query("SELECT a.kdakun , a.namaakun , a.is_saldo , c.saldoawal , d.debet , d.kredit ,
                        //         if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0)) as saldoakhir
                        //         from m_akun a
                                
                        //         left join t_keuangan b on a.`kdakun`=b.kdakun
                                
                        //         left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".date("Y", strtotime($_GET["periode"]))."-01-01'
                        //         and '0'=b.is_deleted
                        //          group by a.kdakun) c on a.kdakun=c.kdakun

                        //         left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.is_deleted='0' and b.tgltrx between '".date("Y", strtotime($_GET["periode"]))."-01-01' and '".$_GET['periode']."'
                        //          group by a.kdakun) d on a.kdakun=d.kdakun

                        //             where a.kdakun in (4,5)
                        //         group by a.kdakun")->result() ;

                        // $tgla = date('Y-m', strtotime($_GET['periode'])).'-01';
                        $tgla = date('Y-m-d', strtotime($_GET['periode']));
                        $tglawal = date('Y-m-d', strtotime($tgla.' - 1 days'));

                        $query = $this->db->query("SELECT a.kdakun , a.namaakun , a.is_saldo , a.is_bold, c.saldoawal , d.debet , d.kredit ,
                                if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0)) as saldoakhir
                                from m_akun a
                                
                                left join t_keuangan b on a.`kdakun`=b.kdakun
                                
                                left join (select a.kdakun, if(a.normalbalance='D',if('2021'='".date("Y", strtotime($_GET["periode"]))."', a.saldoawal_d , 0)+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),if('2021'='".date("Y", strtotime($_GET["periode"]))."', a.saldoawal_k , 0) +ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal 
                                from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun
                                 and b.tgltrx between '".date("Y", strtotime($_GET["periode"]))."-01-01' and '".$tglawal."' 
                                and '0'=b.is_deleted
                                group by a.kdakun) c on a.kdakun=c.kdakun

                                left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$tgla."' and '".$_GET['periode']."' 
                                and '0'=b.is_deleted
                                 group by a.kdakun) d on a.kdakun=d.kdakun

                                    where a.kdakun in (4,5)
                                group by a.kdakun")->result() ;
                        ?>

                        <div align="center">LAPORAN LABA RUGI</div>
                        <div align="center">TANGGAL <?=date("d M Y" , strtotime($_GET['periode']))?></div>
                        <br>
                        <div class="tabela">
                        <table border="1" width="100%" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="13%">Kode Akun</th>
                                    <th >Nama Akun</th>
                                    <th width="13%">Saldo Awal</th>
                                    <th width="13%">Debet</th>
                                    <th width="13%">Kredit</th>
                                    <th width="13%">Saldo Akhir</th>
                                </tr>
                            </thead>
                            <?php 
                                foreach ($query as $item) { 

                                    if ($item->is_saldo==0) {

                                    //     $qrsaldoutama = $this->db->query("
                                    // SELECT kdakun, namaakun , is_saldo , sum(saldo_awal) as saldo_awal , sum(_debet) as _debet , sum(_kredit) as _kredit , sum(saldoakhir) as saldoakhir from (
                                    // SELECT a.kdakun , a.namaakun , a.is_saldo , (c.saldoawal) as saldo_awal, (d.debet) as _debet, (d.kredit) as _kredit, (if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                    //         from m_akun a
                                    //         left join t_keuangan b on a.`kdakun`=b.kdakun
                                    //         left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".date("Y", strtotime($_GET["periode"]))."-01-01'
                                    //         and '0'=b.is_deleted
                                    //          group by a.kdakun) c on a.kdakun=c.kdakun
                                    //         left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".date("Y", strtotime($_GET["periode"]))."-01-01' and '".$_GET['periode']."'
                                    //         and '0'=b.is_deleted
                                    //          group by a.kdakun) d on a.kdakun=d.kdakun

                                    // where a.kdakun like '".$item->kdakun."%' group by kdakun ) a ")->row(); 

                                        $qrsaldoutama = $this->db->query("
                                    SELECT kdakun, namaakun , is_saldo , sum(saldo_awal) as saldo_awal , sum(_debet) as _debet , sum(_kredit) as _kredit , sum(saldoakhir) as saldoakhir from (
                                    SELECT a.kdakun , a.namaakun , a.is_saldo , (c.saldoawal) as saldo_awal, (d.debet) as _debet, (d.kredit) as _kredit, (if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                            from m_akun a
                                            left join t_keuangan b on a.`kdakun`=b.kdakun
                                            left join (select a.kdakun, if(a.normalbalance='D',if('2021'='".date("Y", strtotime($_GET["periode"]))."', a.saldoawal_d , 0)+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),if('2021'='".date("Y", strtotime($_GET["periode"]))."', a.saldoawal_k , 0) +ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun 
                                            and b.tgltrx between '".date("Y", strtotime($_GET["periode"]))."-01-01' and '".$tglawal."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) c on a.kdakun=c.kdakun
                                            left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$tgla."' and '".$_GET['periode']."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) d on a.kdakun=d.kdakun

                                    where a.kdakun like '".$item->kdakun."%' group by kdakun ) a")->row(); 
                                    // where a.kdakun in (".$item->kdakun.") ")->row(); like '1-100%'
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
                                    <td <?=$style?> <?=$is_bold?> ><!-- <?=$pr?> --><?=$item->namaakun?></td>
                                    <td <?=$style?> <?=$is_bold?> align="right"><?=$saldoawal==0? '0' : number_format($saldoawal)?></td>
                                    <td <?=$style?> <?=$is_bold?> align="right"><?=$debet==0? '0' : number_format($debet)?></td>
                                    <td <?=$style?> <?=$is_bold?> align="right"><?=$kredit==0? '0' : number_format($kredit)?></td>
                                    <td <?=$style?> <?=$is_bold?> align="right"><?=$saldoakhir==0? '0' : number_format($saldoakhir)?></td>
                                </tr>

                            <?php }
                            $querytotalpendapatan = $this->db->query("
                                 SELECT kdakun, namaakun , is_saldo , sum(saldo_awal) as saldo_awal , sum(_debet) as _debet , sum(_kredit) as _kredit , sum(saldoakhir) as saldoakhir from (
                                    SELECT a.kdakun , a.namaakun , a.is_saldo , (c.saldoawal) as saldo_awal, (d.debet) as _debet, (d.kredit) as _kredit, (if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                            from m_akun a
                                            left join t_keuangan b on a.`kdakun`=b.kdakun
                                            left join (select a.kdakun, if(a.normalbalance='D',if('2021'='".date("Y", strtotime($_GET["periode"]))."', a.saldoawal_d , 0)+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),if('2021'='".date("Y", strtotime($_GET["periode"]))."', a.saldoawal_k , 0) +ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx between '".date("Y", strtotime($_GET["periode"]))."-01-01' and '".$tglawal."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) c on a.kdakun=c.kdakun
                                            left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$tgla."' and '".$_GET['periode']."'
                                            and '0'=b.is_deleted
                                             group by a.kdakun) d on a.kdakun=d.kdakun

                                    where a.kdakun like '4-%' group by kdakun ) a")->row();

                            $querytotalbiaya = $this->db->query("
                                SELECT kdakun, namaakun , is_saldo , sum(saldo_awal) as saldo_awal , sum(_debet) as _debet , sum(_kredit) as _kredit , sum(saldoakhir) as saldoakhir from (
                                    SELECT a.kdakun , a.namaakun , a.is_saldo , (c.saldoawal) as saldo_awal, (d.debet) as _debet, (d.kredit) as _kredit, (if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                            from m_akun a
                                            left join t_keuangan b on a.`kdakun`=b.kdakun
                                            left join (select a.kdakun, if(a.normalbalance='D',if('2021'='".date("Y", strtotime($_GET["periode"]))."', a.saldoawal_d , 0)+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),if('2021'='".date("Y", strtotime($_GET["periode"]))."', a.saldoawal_k , 0) +ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx between '".date("Y", strtotime($_GET["periode"]))."-01-01' and '".$tglawal."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) c on a.kdakun=c.kdakun
                                            left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$tgla."' and '".$_GET['periode']."'
                                            and '0'=b.is_deleted
                                             group by a.kdakun) d on a.kdakun=d.kdakun

                                    where a.kdakun like '5-%' group by kdakun ) a")->row();

                            $querytotaltaksiran = $this->db->query("
                                SELECT kdakun, namaakun , is_saldo , sum(saldo_awal) as saldo_awal , sum(_debet) as _debet , sum(_kredit) as _kredit , sum(saldoakhir) as saldoakhir from (
                                    SELECT a.kdakun , a.namaakun , a.is_saldo , (c.saldoawal) as saldo_awal, (d.debet) as _debet, (d.kredit) as _kredit, (if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                            from m_akun a
                                            left join t_keuangan b on a.`kdakun`=b.kdakun
                                            left join (select a.kdakun, if(a.normalbalance='D',if('2021'='".date("Y", strtotime($_GET["periode"]))."', a.saldoawal_d , 0)+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),if('2021'='".date("Y", strtotime($_GET["periode"]))."', a.saldoawal_k , 0) +ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx between '".date("Y", strtotime($_GET["periode"]))."-01-01' and '".$tglawal."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) c on a.kdakun=c.kdakun
                                            left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$tgla."' and '".$_GET['periode']."'
                                            and '0'=b.is_deleted
                                             group by a.kdakun) d on a.kdakun=d.kdakun

                                    where a.kdakun like '6%' group by kdakun ) a")->row();

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
