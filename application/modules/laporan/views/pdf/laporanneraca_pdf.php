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
                         $tgla = date('Y-m-d', strtotime($_GET['periode']));
                            $tglawal = date('Y-m-d', strtotime($tgla.' - 1 days'));
                        $query = $this->db->query("SELECT a.kdakun , a.namaakun , a.is_saldo , c.saldoawal , d.debet , d.kredit ,
                                if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0)) as saldoakhir
                                from m_akun a
                                
                                left join t_keuangan b on a.`kdakun`=b.kdakun
                                
                                left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".date('Y-m-d', strtotime($_GET['periode']))."' 
                                and '0'=b.is_deleted
                                group by a.kdakun) c on a.kdakun=c.kdakun

                                left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$_GET['periode']."' and '".$_GET['periode']."' 
                                and '0'=b.is_deleted
                                 group by a.kdakun) d on a.kdakun=d.kdakun

                                    where a.kdakun in (1,2,3)
                                group by a.kdakun")->result() ;

                        // $tgla = date('Y-m', strtotime($_GET['periode'])).'-01';
                        // $tgla = date('Y-m-d', strtotime($_GET['periode']));
                        // $tglawal = date('Y-m-d', strtotime($tgla.' - 1 days'));

                        // $query = $this->db->query("SELECT a.kdakun , a.namaakun , a.is_saldo , c.saldoawal , d.debet , d.kredit ,
                        //         if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0)) as saldoakhir
                        //         from m_akun a
                                
                        //         left join t_keuangan b on a.`kdakun`=b.kdakun
                                
                        //         left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal 
                        //         from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun 
                        //         and b.tgltrx between '2021-01-01' and '".$tglawal."' 
                        //         and '0'=b.is_deleted
                        //         group by a.kdakun) c on a.kdakun=c.kdakun

                        //         left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$tgla."' and '".$_GET['periode']."' 
                        //         and '0'=b.is_deleted
                        //          group by a.kdakun) d on a.kdakun=d.kdakun

                        //             where a.kdakun in (1,2,3)
                        //         group by a.kdakun")->result();
                        ?>

                        <div align="center" style="font-weight: bold;">LAPORAN NERACA</div>
                        <div align="center">Tanggal <?=date("d M Y" , strtotime($_GET['periode']))?></div>
                        <br>

                        <div class="tabela">
                            <table border="1" width="100%" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="13%">Kode Akun</th>
                                        <th width="">Nama Akun</th>
                                        <th width="13%">Saldo Awal</th>
                                        <th width="13%">Debet</th>
                                        <th width="13%">Kredit</th>
                                        <th width="13%">Saldo Akhir</th>
                                    </tr>
                                </thead>
                                <?php 
                                    foreach ($query as $item) { 

                                        if ($item->is_saldo==0) {

                                            $qrsaldoutama = $this->db->query(" SELECT a.kdakun , a.namaakun , a.is_saldo , sum(c.saldoawal) as saldo_awal, sum(d.debet) as _debet, sum(d.kredit) as _kredit, sum(if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                            from m_akun a
                                            -- left join t_keuangan b on a.`kdakun`=b.kdakun
                                            left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".date('Y-m-d', strtotime($_GET['periode']))."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) c on a.kdakun=c.kdakun
                                            left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$_GET['periode']."' and '".$_GET['periode']."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) d on a.kdakun=d.kdakun

                                        where a.kdakun like '".$item->kdakun."%' ")->row(); 

//                                         $qrsaldoutama = $this->db->query("SELECT a.kdakun , a.namaakun , a.is_saldo , sum(c.saldoawal) as saldo_awal, sum(d.debet) as _debet, sum(d.kredit) as _kredit, sum(if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
//                                             from m_akun a
//                                             -- left join t_keuangan b on a.`kdakun`=b.kdakun
//                                             left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun
                                             
// and b.tgltrx between '2021-01-01' and '".$tglawal."' 
//                                             and '0'=b.is_deleted
//                                              group by a.kdakun) c on a.kdakun=c.kdakun
//                                             left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".$tgla."' and '".$_GET['periode']."' 
//                                             and '0'=b.is_deleted
//                                              group by a.kdakun) d on a.kdakun=d.kdakun

//                                     where a.kdakun like '".$item->kdakun."%' ")->row(); 
                                        
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

                                        $pr = str_repeat("&nbsp;&nbsp;",strlen($item->kdakun));
                                        ?>
                                    <tr>
                                        <td <?=$style?> ><!-- <?=$pr?> --><?=$this->uri->segment('2')=='excel'? "'": ""?><?=$item->kdakun?></td>
                                        <td <?=$style?> ><!-- <?=$pr?> --><?=$item->namaakun?></td>
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
                        <br>
                        <?php 
                            $profilpt = $this->db->query("SELECT * FROM m_config_profile")->row();
                            $cekttd = $this->db->query("SELECT * FROM master_ttd")->result();
                        ?>
                        <div align="center"><?=$profilpt->tempat?> , <?=date("d M Y")?></div>
                        <div align="center"><?=$profilpt->nama_panjang?></div>
                        <div style="height: 90px;"></div>
                        <table width="100%">
                            <tr>
                                <td align="center"><u><?=$cekttd[0]->nama_ttd?></u></td>
                                <td align="center"><u><?=$cekttd[1]->nama_ttd?></u></td>
                            </tr>
                            <tr>
                                <td align="center"><?=$cekttd[0]->jataban_ttd?></td>
                                <td align="center"><?=$cekttd[1]->jataban_ttd?></td>
                            </tr>
                        </table>

                    </div>

</body>
</html>
