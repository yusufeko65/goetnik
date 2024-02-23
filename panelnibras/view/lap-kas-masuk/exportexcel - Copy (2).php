<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("path_to_language", "../../language/");
define("folder", "lap-kas-masuk");
include "../../../includes/config.php";include "../../autoloader.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('lap-kas-masuk','',0);

include DIR_INCLUDE."controller/controlLapKasMasuk.php";
$dtLapKasMasuk = new controllerLapKasMasuk();

$dataview 	= $dtLapKasMasuk->tampilData();
$bulan 	= isset($_GET['bulan']) ? $_GET['bulan']:date('m');
$tahun 	= isset($_GET['tahun']) ? $_GET['tahun']:date('Y');
$tanggal=date("Ymd");
//header("Content-Type: application/xls");;
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=LaporanKasMasuk_".$dtFungsi->cariBulan($bulan-1).$tahun.".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<?php echo '<?xml version="1.0"?>' ?>
<?php echo '<?mso-application progid="Excel.Sheet"?>'?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <Styles>
  <Style ss:ID="judul">
	 <Alignment ss:Vertical="Center" ss:Horizontal="Center"/>
     <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="14" ss:Bold="1"/>
  </Style>
  
  <Style ss:ID="subjudul">
	 <Alignment ss:Vertical="Center" ss:Horizontal="Center"/>
     <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="12" ss:Bold="1"/>
  </Style>
  <Style ss:ID="supersubjudul">
	 <Alignment ss:Horizontal="Right"/>
     <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11"/>
  </Style>
  <Style ss:ID="header">
   
   <Alignment ss:Vertical="Center" ss:Horizontal="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
   <Font ss:FontName="Calibri" x:Family="Swiss" ss:Size="11" ss:Color="#FFFFFF"
    ss:Bold="1"/>
   <Interior ss:Color="#5f891e" ss:Pattern="Solid"/>
  </Style>
  <Style ss:ID="body">
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
  <Style ss:ID="kanan">
	<Alignment ss:Vertical="Center" ss:Horizontal="Right"/>
	<Borders>
		<Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
		<Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
		<Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
		<Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
  <Style ss:ID="kiri">
	<Alignment ss:Vertical="Center" ss:Horizontal="Left"/>
	<Borders>
		<Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
		<Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
		<Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>	
		<Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
  <Style ss:ID="tengah">
	<Alignment ss:Vertical="Center" ss:Horizontal="Center"/>
	<Borders>
		<Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
		<Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
		<Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
		<Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
 </Styles>
 <Worksheet ss:Name="LaporanKasMasuk<?php echo $dtFungsi->cariBulan($bulan-1).$tahun ?>">
  <Table>
   <Column ss:AutoFitWidth="0" ss:Width="30.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="60.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="70.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Column ss:AutoFitWidth="0" ss:Width="90.00"/>
   <Row>
    <Cell ss:MergeAcross="11" ss:StyleID="judul"><Data ss:Type="String">Laporan Kas Masuk HijabSupplier.com</Data></Cell>
   </Row>
   <Row>
    <Cell ss:MergeAcross="11" ss:StyleID="subjudul"><Data ss:Type="String">Periode <?php echo $dtFungsi->cariBulan($bulan-1). ' '.$tahun ?></Data></Cell>
   </Row>
   <Row>
     <Cell ss:MergeAcross="11"></Cell>
   </Row>
   <Row>
	<Cell ss:MergeAcross="11" ss:StyleID="supersubjudul"><Data ss:Type="String">Print at <?php echo date('d M Y') ?></Data></Cell>
   </Row>
   <Row ss:Height="25">
    <Cell ss:StyleID="header"><Data ss:Type="String">No</Data></Cell>
    <Cell ss:StyleID="header"><Data ss:Type="String">Tgl</Data></Cell>
    <Cell ss:StyleID="header"><Data ss:Type="String">Order ID</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">Total Belanja</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">Potongan</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">Total Barang</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">Ongkos Kirim</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">Infaq</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">Keuntungan</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">Deposit</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">Kekurangan</Data></Cell>
	<Cell ss:StyleID="header"><Data ss:Type="String">Kas Masuk</Data></Cell>
   </Row>
 <?php 
    $no = 1;
	$jmlOrder = 0;
	$totBelanja = 0;
	$totBarang = 0;
	$totInfaq = 0;
	$totLaba = 0;
	$totDeposit = 0;
	$totKekurangan = 0;
	$totKM = 0;
	$hrgpotongan = 0;
	$totPotongan = 0;
	foreach($dataview as $datanya) {
	  $laba = ($datanya["subtotal"] - $datanya["hrgbeli"]) - $datanya["kekurangan"];
	  $total= ($datanya["subtotal"] + $datanya["ongkir"] + $datanya["infaq"]);
	  $hrgpotongan = $datanya["hrgsatuan"] - $datanya["subtotal"];
	  
	  $totBelanja = $totBelanja + $datanya["hrgsatuan"];
	  $totBarang = $totBarang + (int)$datanya["hrgbeli"];
	  $totInfaq = $totInfaq + $datanya["infaq"];
	  $totLaba = $totLaba + $laba;
	  $totDeposit = $totDeposit + $datanya["penambahan"];
	  $totPotongan = $totPotongan + $hrgpotongan;
	  $KM	= ((int)$datanya["hrgbeli"] + (($datanya["subtotal"] - $datanya["hrgbeli"])-$datanya["kekurangan"]) + $datanya["infaq"] + $datanya["ongkir"]) - $datanya["penambahan"];
	  $totKekurangan = $totKekurangan + $datanya["kekurangan"];
	
	  $totKM = $totKM + $KM;
	  $jmlOrder = $jmlOrder+1;
  ?>
  <Row>
    <Cell ss:StyleID="tengah"><Data ss:Type="String"><?php echo $no++?></Data></Cell>
    <Cell ss:StyleID="tengah"><Data ss:Type="String"><?php echo $dtFungsi->ftanggalFull2($datanya['tglkomplet'])?></Data></Cell>
    <Cell ss:StyleID="tengah"><Data ss:Type="String"><?php echo $datanya["noorder"]?></Data></Cell>
	<Cell ss:StyleID="kanan"><Data ss:Type="String"><?php echo $dtFungsi->fuang($datanya["hrgsatuan"])?></Data></Cell>
	<Cell ss:StyleID="kanan"><Data ss:Type="String"><?php echo $dtFungsi->fuang($hrgpotongan)?></Data></Cell>
	<Cell ss:StyleID="kanan"><Data ss:Type="String"><?php echo $dtFungsi->fuang($datanya["hrgbeli"])?></Data></Cell>
	<Cell ss:StyleID="kanan"><Data ss:Type="String"><?php echo $dtFungsi->fuang($datanya["ongkir"])?></Data></Cell>
	<Cell ss:StyleID="kanan"><Data ss:Type="String"><?php echo $dtFungsi->fuang($datanya["infaq"])?></Data></Cell>
	<Cell ss:StyleID="kanan"><Data ss:Type="String"><?php echo $dtFungsi->fuang($laba)?></Data></Cell>
	<Cell ss:StyleID="kanan"><Data ss:Type="String"><?php echo $dtFungsi->fuang($datanya["penambahan"])?></Data></Cell>
	<Cell ss:StyleID="kanan"><Data ss:Type="String"><?php echo $dtFungsi->fuang($datanya["kekurangan"])?></Data></Cell>
	<Cell ss:StyleID="kanan"><Data ss:Type="String"><?php echo $dtFungsi->fuang($KM) ?></Data></Cell>
   </Row>
<?php } ?>
	<Row>
     <Cell></Cell>
   </Row>
   <Row>
     <Cell></Cell>
   </Row>
   <Row ss:Height="25">
	<Cell ss:StyleID="header" ss:Index="3"><Data ss:Type="String">Total Order</Data></Cell>
	<Cell ss:StyleID="header" ss:Index="4"><Data ss:Type="String">Total Belanja</Data></Cell>
	<Cell ss:StyleID="header" ss:Index="5"><Data ss:Type="String">Total Potongan</Data></Cell>
	<Cell ss:StyleID="header" ss:Index="6"><Data ss:Type="String">Total Barang</Data></Cell>
	<Cell ss:StyleID="header" ss:Index="7"><Data ss:Type="String">Infaq</Data></Cell>
	<Cell ss:StyleID="header" ss:Index="8"><Data ss:Type="String">Keuntungan</Data></Cell>
	<Cell ss:StyleID="header" ss:Index="9"><Data ss:Type="String">Deposit</Data></Cell>
	<Cell ss:StyleID="header" ss:Index="10"><Data ss:Type="String">Kekurangan</Data></Cell>
	<Cell ss:StyleID="header" ss:Index="11"><Data ss:Type="String">Kas Masuk</Data></Cell>
   </Row>
   
   <Row>
    <Cell ss:StyleID="tengah" ss:Index="3"><Data ss:Type="String"><?php echo $jmlOrder ?></Data></Cell>
	<Cell ss:StyleID="kanan" ss:Index="4"><Data ss:Type="String"><?php echo $dtFungsi->fuang($totBelanja)?></Data></Cell>
	<Cell ss:StyleID="kanan" ss:Index="5"><Data ss:Type="String"><?php echo $dtFungsi->fuang($totPotongan)?></Data></Cell>
	<Cell ss:StyleID="kanan" ss:Index="6"><Data ss:Type="String"><?php echo $dtFungsi->fuang($totBarang)?></Data></Cell>
	<Cell ss:StyleID="kanan" ss:Index="7"><Data ss:Type="String"><?php echo $dtFungsi->fuang($totInfaq)?></Data></Cell>
	<Cell ss:StyleID="kanan" ss:Index="8"><Data ss:Type="String"><?php echo $dtFungsi->fuang($totLaba)?></Data></Cell>
	<Cell ss:StyleID="kanan" ss:Index="9"><Data ss:Type="String"><?php echo $dtFungsi->fuang($totDeposit) ?></Data></Cell>
	<Cell ss:StyleID="kanan" ss:Index="10"><Data ss:Type="String"><?php echo $dtFungsi->fuang($totKekurangan) ?></Data></Cell>
	<Cell ss:StyleID="kanan" ss:Index="11"><Data ss:Type="String"><?php echo $dtFungsi->fuang($totKM)?></Data></Cell>
   </Row>
  </Table>
  
 </Worksheet>
 
</Workbook>