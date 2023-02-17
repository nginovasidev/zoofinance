<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pdf extends MY_Controller {
	var $modul = "pdf";

	function __construct(){

		parent::__construct();
	}

	public function index(){
		parent::_view();
	}

	function laporanneraca(){

                $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);

                $mpdf->curlAllowUnsafeSslRequests = true;

                $html = $this->load->view('pdf/laporanneraca_pdf',[],true);

                $mpdf->AddPage('P','', '', '', '',
                14, // margin_left
                14, // margin right
                40, // margin top
                10, // margin bottom
                90, // margin header
                10); // margin footer

                $pagecount = $mpdf->SetSourceFile('assets/grinatha.pdf');
                $tplId = $mpdf->importPage($pagecount);
                $mpdf->useTemplate($tplId);

                $mpdf->WriteHTML($html);

                $mpdf->Output('laporanneraca .pdf','I');

        }

        function laporanringkasanneraca(){

                $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);

                $mpdf->curlAllowUnsafeSslRequests = true;

                $html = $this->load->view('pdf/ringkasanneraca_pdf',[],true);

                $mpdf->AddPage('P','', '', '', '',
                14, // margin_left
                14, // margin right
                40, // margin top
                10, // margin bottom
                90, // margin header
                10); // margin footer

                $pagecount = $mpdf->SetSourceFile('assets/grinatha.pdf');
                $tplId = $mpdf->importPage($pagecount);
                $mpdf->useTemplate($tplId);

                $mpdf->WriteHTML($html);

                $mpdf->Output('Ringkasan Neraca .pdf','I');

        }


	function laporanlabarugi(){
	$mpdf = new \Mpdf\Mpdf(['format' => 'A4']);

        $mpdf->curlAllowUnsafeSslRequests = true;
        
        $html = $this->load->view('pdf/laporanlabarugi_pdf',[],true);


        $mpdf->AddPage('P','', '', '', '',
        14, // margin_left
        14, // margin right
        40, // margin top
        10, // margin bottom
        90, // margin header
        10); // margin footer

        $pagecount = $mpdf->SetSourceFile('assets/grinatha.pdf');
        $tplId = $mpdf->importPage($pagecount);
        $mpdf->useTemplate($tplId);

        $mpdf->WriteHTML($html);
        // $mpdf->Output();
        $mpdf->Output('laporanneraca .pdf','I');
	}

	function laporanledgerdetail(){
		$mpdf = new \Mpdf\Mpdf(['format' => 'A4']);

        $mpdf->curlAllowUnsafeSslRequests = true;
        
		// $this->load->view('pdf/laporanneraca_pdf');
        $html = $this->load->view('pdf/laporanledgerdetail_pdf',[],true);


        $mpdf->AddPage('P','', '', '', '',
        14, // margin_left
        14, // margin right
        40, // margin top
        10, // margin bottom
        90, // margin header
        10); // margin footer

        $pagecount = $mpdf->SetSourceFile('assets/grinatha.pdf');
        $tplId = $mpdf->importPage($pagecount);
        $mpdf->useTemplate($tplId);

        $mpdf->WriteHTML($html);
        // $mpdf->Output();
        $mpdf->Output('laporanneraca .pdf','I');
	}

        function laporandaily(){
                $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);

                $mpdf->curlAllowUnsafeSslRequests = true;
                
                        // $this->load->view('pdf/laporanneraca_pdf');
                $html = $this->load->view('pdf/laporandaily_pdf',[],true);


                $mpdf->AddPage('P','', '', '', '',
                14, // margin_left
                14, // margin right
                40, // margin top
                10, // margin bottom
                90, // margin header
                10); // margin footer

                $pagecount = $mpdf->SetSourceFile('assets/grinatha.pdf');
                $tplId = $mpdf->importPage($pagecount);
                $mpdf->useTemplate($tplId);

                $mpdf->WriteHTML($html);
                // $mpdf->Output();
                $mpdf->Output('laporanneraca .pdf','I');

                $this->load->view('pdf/laporandaily_pdf');
        }
	

}
