<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "lap-order");
include "../../../includes/config.php";
include "../../autoloader.php";
include path_toincludes . "PHPExcel.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('lap-order', '', 0);


$dtLapOrder = new controllerLapOrder();

$dataview 	= $dtLapOrder->tampilOrderDaily();

$tanggal1 	= isset($_GET['tanggal']) ? $_GET['tanggal1'] : date('Y-m-d');
$tanggal2 	= isset($_GET['tanggal']) ? $_GET['tanggal2'] : date('Y-m-d');
$status		= isset($_GET['status']) ? $_GET['status'] : '';
$customer_id 	= isset($_GET['customer_id']) ? $_GET['customer_id'] : '';
$tanggal = date("Ymd");

// Create new PHPExcel object
$object = new PHPExcel();

// Add some data
$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$object->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$object->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('D')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('F')->setWidth(20);
$object->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$object->getActiveSheet()->mergeCells('A1:G1');
$object->getActiveSheet()->mergeCells('A2:G2');
$object->getActiveSheet()->mergeCells('A3:G3');

$object->setActiveSheetIndex(0)
	->setCellValue('A1', 'Laporan Order Nibras.com')
	->setCellValue('A2', 'Periode ' . $tanggal)
	->setCellValue('A4', 'No')
	->setCellValue('B4', 'Tgl')
	->setCellValue('C4', 'Order ID')
	->setCellValue('D4', 'Pelanggan')
	->setCellValue('E4', 'Status')
	->setCellValue('F4', 'Jumlah')
	->setCellValue('G4', 'Total')
	->setCellValue('H4', 'Total+Ongkir');

$sharedStyle1 = new PHPExcel_Style();
$sharedStyle2 = new PHPExcel_Style();
$sharedStyle1->applyFromArray(
	array(
		'fill' 	=> array(
			'type'    => PHPExcel_Style_Fill::FILL_SOLID,
			'color'   => array('argb' => 'FFCCFFCC')
		),
		'borders' => array(
			'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
			'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
			'left'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			'top'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		)
	)
);
$sharedStyle2->applyFromArray(
	array(
		'fill' 	=> array(
			'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
			'color'	=> array('argb' => 'FFFFFFFF')
		),
		'borders' => array(
			'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
			'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
			'left'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
			'top'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		)
	)
);

$object->getActiveSheet()->setSharedStyle($sharedStyle1, "A4:H4");
$object->getActiveSheet()->getStyle('A1:H4')->getFont()->setBold(true);
$object->getActiveSheet()
	->getStyle('A1:H4')
	->getAlignment()
	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$counter = 5;
$ex = $object->setActiveSheetIndex(0);

$no = 1;
$jmlOrder = 0;
$total = 0;
$grandtot = 0;
$grandtotplusongkir = 0;
foreach ($dataview as $datanya) {
	$total = ($datanya["pesanan_kurir"] + $datanya["subtotal"]) - $datanya['dari_poin'];
	$grandtotplusongkir = $grandtotplusongkir + $total;
	$grandtot = $grandtot + $datanya['subtotal'];
	$jmlOrder = $jmlOrder + $datanya['jml'];
	$ex->setCellValue("A" . $counter, $no++);
	$ex->setCellValue("B" . $counter, $datanya["tgl"]);
	$ex->setCellValueExplicit("C" . $counter, sprintf('%08s', (int) $datanya["pesanan_no"]), PHPExcel_Cell_DataType::TYPE_STRING);
	$ex->setCellValue("D" . $counter, $datanya["cust_nama"]);
	$ex->setCellValue("E" . $counter, $datanya['status']);
	$ex->setCellValue("F" . $counter, $datanya["jml"]);
	$ex->setCellValue("G" . $counter, $datanya['subtotal']);
	$ex->setCellValue("H" . $counter, $total);

	$counter = $counter + 1;
}
$object->getActiveSheet()->setSharedStyle($sharedStyle2, "A5:H$counter");
$z = $counter + 1;
$y = $z + 1;
$x = $y + 1;
$a = $x + 1;
$object->getActiveSheet()->mergeCells("A$z:H$z");
$object->getActiveSheet()->mergeCells("A$y:H$y");
$object->getActiveSheet()->mergeCells("A$x:C$x");

$ex->setCellValue("F" . $x, 'Total QTY');
$ex->setCellValue("G" . $x, 'Grand Total');
$ex->setCellValue("H" . $x, 'Grand Total + Total Ongkir');

$ex->setCellValue("F" . $a, "$jmlOrder");
$ex->setCellValue("G" . $a, "$grandtot");
$ex->setCellValue("H" . $a, "$grandtotplusongkir");

$object->getActiveSheet()->setSharedStyle($sharedStyle1, "F$x:H$x");
$object->getActiveSheet()->getStyle("F$x:H$x")->getFont()->setBold(true);
$object->getActiveSheet()->getStyle('F5:H' . $counter)->getNumberFormat()->setFormatCode("#,##0");

$object->getActiveSheet()->setSharedStyle($sharedStyle2, "F$a:H$a");
$object->getActiveSheet()->getStyle("F$a:H$a")->getNumberFormat()->setFormatCode("#,##0");
$object->getActiveSheet()
	->getStyle("F$x:H$x")
	->getAlignment()
	->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Rename sheet
$object->getActiveSheet()->setTitle('Lap_Order_' . $tanggal);


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$object->setActiveSheetIndex(0);


// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=LaporanOrder_' . $tanggal . '.xlsx');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
$objWriter->save('php://output');
exit;
