<?php
/**********************************************************************************************
*                            CMS Open Real Estate
*                              -----------------
*	version				:	1.3.3 PRO
*	copyright			:	(c) 2012 Monoray
*	website				:	http://www.monoray.ru/
*	contact us			:	http://www.monoray.ru/contact
*
* This file is part of CMS Open Real Estate
*
* Open Real Estate is free software. This work is licensed under a GNU GPL.
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
* Open Real Estate is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* Without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
***********************************************************************************************/

class viewPdfComponent extends CWidget {
	public $id;
	public $modulePathBase;
	public $libraryPath;
	public $sitePath;
	public $pdfCachePath;
	public $filePrefix = 'bill_';
	public $actPrefix = '_act_';

	public function __construct() {
		$this->preparePaths();
		$this->getViewPath();
	}

	public function run(){
		$this->preparePaths();
		$this->getViewPath();
		$this->generateFile($this->id, $this->fromAdmin);
	}

	public function getViewPath($checkTheme=false){
		return Yii::getPathOfAlias('application.views.account.inc');
	}

	public function preparePaths() {
		$this->modulePathBase = dirname(__FILE__);
		$this->libraryPath = $this->modulePathBase . '/library';
		$this->sitePath = Yii::app()->basePath . '/../';
		$this->pdfCachePath = File::basePath() . '/upload/bill';
	}

	public function getFile($model) {

		$type = get_class($model);



		if ($type == "Payments") {
			$folderPdf = $this->pdfCachePath.'/'.$model->company_id;
			$filePdf = $folderPdf.'/'.$this->filePrefix . $model->bill->id . '.pdf';
		} else {	//если акт
			$folderPdf = $this->pdfCachePath.'/'.$model->bill->payment->company_id;
			$filePdf = $folderPdf.'/'.$this->filePrefix . $model->bill->id . $this->actPrefix . $model->id . '.pdf';
		}

		if (!file_exists($folderPdf)) {
			mkdir($folderPdf);
		}

		//if (!file_exists($filePdf)) {  //TODO: Раскомментить!
			$this->generateFile($model);
		//}

		header('Content-Type: application/pdf');
		header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
		header('Pragma: public');
		header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Content-Disposition: inline; filename='.(($type == "Payments") ? $this->filePrefix.$model->bill->id :
				$this->filePrefix . $model->bill->id . $this->actPrefix . $model->id).'.pdf;');
		header('Content-Length: ' . filesize($filePdf));
		readfile($filePdf);
}

	public function generateFile($model) {

//      Вызывает рекурсию т.к. у Apartment afterSave
//		$dateFree = CDateTimeParser::parse($apartment->is_free_to, 'yyyy-MM-dd');
//		if ($dateFree && $dateFree < (time() - 60 * 60 * 24)) {
//			$apartment->is_special_offer = 0;
//			$apartment->update(array('is_special_offer'));
//		}
		$type = get_class($model);

		require_once $this->libraryPath . '/tcpdf/config/lang/rus.php';
		require_once $this->libraryPath . '/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle('PDF bill: ' . $model->bill->id);
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->AddPage();
		$pdf->SetFont('dejavusans', '', 8);
		$pdf->SetTextColor(90, 90, 90);


		if ($type == "Payments"){

			//$pdf->Image(File::basePath() . '/images/pdf/bill-signature.jpg',170, 170, 30, 30, '', '', '', false, 300);
			//$pdf->Image(File::basePath() . '/images/pdf/bill-seal.png',150, 150, 40, 40, '', '', '', false, 300);

			$content = $this->render('bill_pdf', array('bill' => $model->bill), true);
		} else
			$content = $this->render('act_pdf', array('act' => $model), true);

		$pdf->writeHTML($content, true, 0, true, 0);
		$pdf->lastPage();

		if ($type == "Payments")
			$file_name = $this->pdfCachePath.'/'.$model->company_id.'/'.$this->filePrefix . $model->bill->id . '.pdf';
		else
			$file_name = $this->pdfCachePath.'/'.$model->bill->payment->company_id.'/'.$this->filePrefix . $model->bill->id . $this->actPrefix . $model->id . '.pdf';

		$pdf->Output($file_name, 'F');
	}
}