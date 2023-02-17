<div class="page-content page-container" id="page-content">
        <div class="padding">
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
                                
                                left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".date("Y", strtotime($_POST["periode"]))."-01-01' 
                                and '0'=b.is_deleted
                                group by a.kdakun) c on a.kdakun=c.kdakun

                                left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".date("Y", strtotime($_POST["periode"]))."-01-01' and '".$_POST['periode']."' 
                                and '0'=b.is_deleted
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

                                        $qrsaldoutama = $this->db->query("SELECT a.kdakun , a.namaakun , a.is_saldo , sum(c.saldoawal) as saldo_awal, sum(d.debet) as _debet, sum(d.kredit) as _kredit, sum(if(a.normalbalance='D', c.saldoawal+ifnull(d.debet,0)-ifnull(d.kredit,0)     ,    c.saldoawal-ifnull(d.debet,0)+ifnull(d.kredit,0))) as saldoakhir
                                            from m_akun a
                                            -- left join t_keuangan b on a.`kdakun`=b.kdakun
                                            left join (select a.kdakun, if(a.normalbalance='D',a.saldoawal_d+ifnull(sum(b.d),0)-ifnull(sum(b.k),0),a.saldoawal_k+ifnull((sum(b.k)-sum(b.d)),0)) as saldoawal from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun and b.tgltrx<'".date("Y", strtotime($_POST["periode"]))."-01-01' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) c on a.kdakun=c.kdakun
                                            left join (select a.kdakun, ifnull(sum(b.d),0) as debet , ifnull(sum(b.k),0) as kredit from m_akun a left join t_keuangan b on a.`kdakun`=b.kdakun where b.tgltrx between '".date("Y", strtotime($_POST["periode"]))."-01-01' and '".$_POST['periode']."' 
                                            and '0'=b.is_deleted
                                             group by a.kdakun) d on a.kdakun=d.kdakun

                                    where a.kdakun like '".$item->kdakun."%' ")->row(); 
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
                                    <td <?=$style?> ><!-- <?=$pr?> --><?=$item->kdakun?></td>
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


                </div>
            </div>
        </div>
    </div>