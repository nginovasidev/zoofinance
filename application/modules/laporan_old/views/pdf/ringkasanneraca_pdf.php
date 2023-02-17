<!DOCTYPE html>
<html>
<head>
    <title>Laporan Ringkasan Neraca</title>
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
                        $query = $this->db->query("SELECT a.kdakun , a.namaakun , a.is_saldo , c.saldoawal , d.debet , d.kredit ,
                                if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0)) as saldoakhir
                                from m_akun a
                                
                                left join t_keuangan b on a.`kdakun`=b.kdakun
                                
                                left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".date("Y", strtotime($_GET["periode"]))."-01-01' 
                                and '0'=b.is_deleted
                                group by a.kdakun) c on a.kdakun=c.kdakun

                                left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".date("Y", strtotime($_GET["periode"]))."-01-01' and '".$_GET['periode']."' 
                                and '0'=b.is_deleted
                                 group by a.kdakun) d on a.kdakun=d.kdakun

                                    where a.kdakun in (1,2,3)
                                group by a.kdakun")->result() ;

                        $temp_query = "SELECT  sum(if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                            from m_akun a
                                            left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".date("Y", strtotime($_GET["periode"]))."-01-01' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) c on a.kdakun=c.kdakun
                                            left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".date("Y", strtotime($_GET["periode"]))."-01-01' and '".$_GET['periode']."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) d on a.kdakun=d.kdakun ";
                        $q_kassetarakas = $this->db->query($temp_query ." where a.kdakun like '1-10%' ")->row();
                        $q_uangmuka = $this->db->query($temp_query ." where a.kdakun like '1-30%' ")->row();
                        $q_piutang = $this->db->query($temp_query ." where a.kdakun like '1-20%' ")->row();

                        $q_hargaperolehan = $this->db->query($temp_query ." where a.kdakun like '1-4001%' ")->row();
                        $q_akumulasipenyusutan = $this->db->query($temp_query ." where a.kdakun like '1-4002%' ")->row();
                        $q_invastasijangkapanjang = $this->db->query($temp_query ." where a.kdakun like '1-50%' ")->row();

                        $q_diterimadimuka = $this->db->query($temp_query ." where a.kdakun like '2-1001%' and a.kdakun not like '2-100103%' ")->row();
                        $q_jangkapendek = $this->db->query($temp_query ." where a.kdakun like '2-20%' ")->row();
                        $q_jangkapendek03 = $this->db->query($temp_query ." where a.kdakun like '2-100103%' ")->row();
                        $q_ekuitas = $this->db->query($temp_query ." where a.kdakun like '3-%' ")->row();


                        ?>

                        <div align="center" style="font-weight: bold;">NERACA</div>
                        <div align="center">Tanggal <?=date("d M Y" , strtotime($_GET['periode']))?></div>
                        <br>

                        <div>
                            <table width="80%" align="center">
                                <tr>
                                    <td width="70%" style="font-weight: bold;">ASET</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 20px; font-weight: bold;">Aset Lancar</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 40px;">Kas Dan Setara Kas</td>
                                    <td align="right"><?=number_format($q_kassetarakas->saldoakhir)?></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 40px;">Uang Muka</td>
                                    <td align="right"><?=number_format($q_uangmuka->saldoakhir)?></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 40px;">Piutang</td>
                                    <td align="right"><?=number_format($q_piutang->saldoakhir)?></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 40px; font-weight: bold;">Jumlah Aset Lancar</td>
                                    <td align="right" style="font-weight: bold;"><?php $asetlancar = $q_kassetarakas->saldoakhir+$q_uangmuka->saldoakhir+$q_piutang->saldoakhir; echo number_format($asetlancar)?></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="height: 25px;"></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 20px; font-weight: bold;">Aset Tetap</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 40px;">Harga Perolehan</td>
                                    <td align="right"><?=number_format($q_hargaperolehan->saldoakhir)?></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 40px;">Akumulasi Penyusutan</td>
                                    <td align="right"><?=number_format($q_akumulasipenyusutan->saldoakhir)?></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 40px;">Investasi Jangka Panjang</td>
                                    <td align="right"><?=number_format($q_invastasijangkapanjang->saldoakhir)?></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 40px; font-weight: bold;">Nilai Buku</td>
                                    <td align="right" style="font-weight: bold;"><?php $asettetap = $q_hargaperolehan->saldoakhir+$q_akumulasipenyusutan->saldoakhir+$q_invastasijangkapanjang->saldoakhir; echo number_format($asettetap)?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;">JUMLAH ASET</td>
                                    <td align="right" style="font-weight: bold;"><?php $jumlahaset = $asetlancar+$asettetap; echo number_format($jumlahaset)?></td>
                                </tr>

                                <tr>
                                    <td colspan="2" style="height: 25px;"></td>
                                </tr>
                                
                                <tr>
                                    <td width="70%" style="font-weight: bold;">KEWAJIBAN DAN EKUITAS</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 40px;">Pendapatan Diterima Dimuka</td>
                                    <td align="right"><?=number_format($q_diterimadimuka->saldoakhir)?></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 40px;">Kewajiban Jangka Pendek</td>
                                    <td align="right"><?=number_format($q_jangkapendek->saldoakhir+$q_jangkapendek03->saldoakhir)?></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 40px;">Kewajiban Dan Ekuitas</td>
                                    <td align="right"><?=number_format($q_ekuitas->saldoakhir)?></td>
                                </tr>
                                <tr>
                                    <td style="padding-left: 40px; font-weight: bold;">JUMLAH KEWAJIBAN DAN EKUITAS</td>
                                    <td align="right" style="font-weight: bold;"><?=number_format($q_kassetarakas->saldoakhir+$q_uangmuka->saldoakhir+$q_piutang->saldoakhir)?></td>
                                </tr>
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
