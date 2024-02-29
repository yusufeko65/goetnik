<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "produk");
include "../../../includes/config.php";include "../../autoloader.php";
include path_toincludes."PHPExcel.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('produk','',0);


$dtProduk = new controllerProduk();

$dataview 	= $dtProduk->getProdukExport();

// Create new PHPExcel object
$object = new PHPExcel();
// Set properties
/*
$object->getProperties()->setCreator("Goetnikcom")
               ->setLastModifiedBy("Goetnikcom")
               ->setCategory("Laporan Order ");
*/
// Add some data
$object->getActiveSheet()->getColumnDimension('A')->setWidth(8); // ID Produk
$object->getActiveSheet()->getColumnDimension('B')->setWidth(20); // Nama Produk
$object->getActiveSheet()->getColumnDimension('C')->setWidth(15); // Kode
$object->getActiveSheet()->getColumnDimension('D')->setWidth(15); // Harga
$object->getActiveSheet()->getColumnDimension('E')->setWidth(5); // Status
$object->getActiveSheet()->getColumnDimension('F')->setWidth(8); // ID Option
$object->getActiveSheet()->getColumnDimension('G')->setWidth(12); // Warna
$object->getActiveSheet()->getColumnDimension('H')->setWidth(8); // Ukuran
$object->getActiveSheet()->getColumnDimension('I')->setWidth(15); // Barcode
$object->getActiveSheet()->getColumnDimension('J')->setWidth(5); // Stok
$object->getActiveSheet()->getColumnDimension('K')->setWidth(15); // Tambahan Harga
$object->getActiveSheet()->getColumnDimension('L')->setWidth(15); // Total Harga
$object->getActiveSheet()->mergeCells('A1:L1');

$object->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Data Produk')
            ->setCellValue('A3', 'ID Produk')
            ->setCellValue('B3', 'Nama Produk')
            ->setCellValue('C3', 'Kode')
            ->setCellValue('D3', 'Harga')
            ->setCellValue('E3', 'Status')
            ->setCellValue('F3', 'ID Option')
            ->setCellValue('G3', 'Warna')
			->setCellValue('H3', 'Ukuran')
            ->setCellValue('I3', 'Barcode')
            ->setCellValue('J3', 'Stok')
            ->setCellValue('K3', 'Tambahan Harga')
            ->setCellValue('L3', 'Total Harga')
            ;
			
			


$sharedStyle1 = new PHPExcel_Style();
$sharedStyle2 = new PHPExcel_Style();
$sharedStyle1->applyFromArray(
	array('fill' 	=> array(
		  'type'    => PHPExcel_Style_Fill::FILL_SOLID,
		  'color'   => array('argb' => 'FFCCFFCC')),
		  'borders' => array(
						'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
						'left'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'top'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		  )
	));
$sharedStyle2->applyFromArray(
	array('fill' 	=> array(
		  'type'	=> PHPExcel_Style_Fill::FILL_SOLID,
		  'color'	=> array('argb' => 'FFFFFFFF')),
		  'borders' => array(
						'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM),
						'left'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'top'	    => array('style' => PHPExcel_Style_Border::BORDER_THIN)
		  )
    ));
    
	$object->getActiveSheet()->setSharedStyle($sharedStyle1, "A3:L3");
	$object->getActiveSheet()->getStyle('A1:L3')->getFont()->setBold(true);	
	$object->getActiveSheet()
	       ->getStyle('A1:L3')
		   ->getAlignment()
		   ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $counter=5;
    $ex = $object->setActiveSheetIndex(0);
    
    $counter = 4;
	foreach($dataview as $datanya) {
		$harga = $datanya['hrg_jual'];

		$ex->setCellValue("A".$counter,$datanya['idproduk']);
		$ex->setCellValue("B".$counter,$datanya["nama_produk"]);
		$ex->setCellValue("C".$counter,$datanya["kode_produk"]);
		$ex->setCellValue("D".$counter,$datanya["hrg_jual"]);
		$ex->setCellValue("E".$counter,$datanya['status_produk']);

        $options = $dtProduk->getAllStokOptionByProduk($datanya['idproduk']);
        foreach($options as $option) {
            $total = $harga + $option['tambahan_harga'];

            $ex->setCellValue("F".$counter,$option['idopt']);
            $ex->setCellValue("G".$counter,$option['warna']);
            $ex->setCellValue("H".$counter,$option['ukuran']);
            $ex->setCellValue("I".$counter,$option['barcode']);
            $ex->setCellValue("J".$counter,$option['stok']);
            $ex->setCellValue("K".$counter,$option['tambahan_harga']);
            $ex->setCellValue("L".$counter,$total);

            $counter=$counter+1;
        }
    }
	$object->getActiveSheet()->setSharedStyle($sharedStyle2, "A4:L$counter");
	
// Rename sheet
$object->getActiveSheet()->setTitle('Data_Produk');
 
 
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$object->setActiveSheetIndex(0);
 
 
// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=Data Produk.xlsx');
header('Cache-Control: max-age=0');
 
$objWriter = PHPExcel_IOFactory::createWriter($object, 'Excel2007');
$objWriter->save('php://output');
exit;
?>