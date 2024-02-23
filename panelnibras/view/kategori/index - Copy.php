<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "kategori");
include "../../../includes/config.php";include "../../autoloader.php";
if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";
$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('kategori','',0);


$dtKategori = new controllerKategori();

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
   case "tambah":
		echo $dtKategori->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtKategori->simpandata('ubah');
		exit;
   break;
   case "hapus":
		echo $dtKategori->hapusdata();
		exit;
   break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
  include(DIR_INCLUDE."header.php");
  include(DIR_INCLUDE."menu.php");
}
$judul = 'Kategori';
$kategori_nama = '';
$kategori_aliasurl = '';
$kategori_induk = 0;
$lock = '';
$iddata = '';
$b = 1;
//Ini untuk tampilan

$results = $dtKategori->getKategori();
$categories = array();
foreach ($results as $result) {
	$categories[] = array(
	   'kategori_id' => $result['kategori_id'],
	   'kategori_nama'  => $result['kategori_nama']
	);
}
switch($menupage){
	case "view": default:
	    $dtPaging = new Paging();
		$dataview = $dtKategori->tampildata();
		$total 	  = $dataview['total'];
		$baris 	  = $dataview['baris'];
		$page 	  = $dataview['page'];
		$jmlpage  = $dataview['jmlpage'];
	    $ambildata= $dataview['rows'];
		$cari = isset($_GET['datacari']) ? $_GET['datacari']:'';
		$linkpage = '';
		if($cari!='') $linkpage = '&datacari='.trim(strip_tags($cari));
		include "view.php"; 
	break;
	case "add":
		$dtFungsi->cekHak("kategori","add",0);
		$modul = "tambah";
		include "form.php"; 
	break;
	case "edit": 
		$dtFungsi->cekHak("kategori","edit",0);
		$modul = "ubah"; $iddata = $_GET["pid"];
		$datakategori = $dtKategori->dataKategoriByID($iddata);
		if(!empty($datakategori)){
			$iddata = $datakategori['category_id'];
			$kategori_nama = $datakategori['name'];
			$kategori_induk = $datakategori['parent_id'];
			$kategori_aliasurl = $datakategori['alias_url'];
			$lock = 'disabled';
			include "form.php";
		}
	break;
}
if($stsload!="load") include(DIR_INCLUDE."footer.php"); 
?>