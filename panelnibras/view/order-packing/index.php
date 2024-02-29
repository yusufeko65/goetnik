<?php
define("path_toincludes", "../../_includes/");
define("folder", "order-packing");
include "../../../includes/config.php";
include "../../autoloader.php";
include "../../../includes/themes.php";
// if (isset($_SESSION["masukadmin"]) != "xjklmnJk1o~" && isset($_SESSION["userlogin"]) == "") echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
$dtFungsi = new FungsiUmum();
$u_token = isset($_SESSION['u_token']) ? $_SESSION['u_token'] : '';

$cekToken = $dtFungsi->cekTokenValid2();

if (!$cekToken) {
	session_destroy();
	echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
	exit;
}
include path_toincludes . "paging.php";

$dtFungsi->cekHak('order-packing', '', 0);


$dtOrder 		= new controllerOrder();
// $dtProduk 		= new controllerProduk();

$mdlProduk      = new modelProduk();
$mdlKirim       = new modelPengiriman();

$menupage = isset($_GET["modul"]) ? $_GET["modul"] : "view";

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload'] : '';
if ($stsload != "load") {
  include(DIR_INCLUDE . "header.php");
  include(DIR_INCLUDE . "menu.php");
}
$judul = "Order Packing";
$b = 1;
//Ini untuk tampilan
// $datagrup = $dtGrupUser->getGrupUser();
switch ($menupage) {
  case "scanproduk":
    // Check kode scan
    $kodescan = isset($_GET['scan']) ? $_GET['scan'] : '';
    $noorder = isset($_GET['pesanan']) ? $_GET['pesanan'] : '';

    $error = true;
    if(!empty($kodescan)){
        $sts = $mdlProduk->getProdukbyBarcode($kodescan);
        if(isset($sts['idproduk'])){
          $error = false;
          $idproduk = $sts['idproduk'];
          $warna = $sts['warna'];
          $ukuran = $sts['ukuran'];

          $data = $dtOrder->scanProdukPacking($noorder,$kodescan,$idproduk,$warna,$ukuran);
        }
    }
    
    if($error){
        $data = [
          'status'	=> 'error',
          'msg'		  => 'Barcode tidak ditemukan',
          'data'		=> ''
		    ];

		    echo json_encode($data);
    }

    break;
  case "view":
  default:
    $result = null;
    $input = isset($_POST['order_id']) && isset($_POST['submit']) ? $_POST['order_id'] : 0;
    if ($input != '') {
        $result = $mdlKirim->checkSentOrder($input);
        
        if(isset($result["status_id"])){
            if ($result["status_id"] > 0) {
                $status = 'success';
                $pesan  = 'ID Order ditemukan';
            } elseif ($result["status_id"] == 16) {
                $pesan  = "Status order sedang packing";
                $status = 'success';
            }
        }else {
            $pesan  = " ID Order tidak ditemukan";
            $status = 'error';
        }

        $result = array("status" => $status, "message" => $pesan);
    }
    $dataview  = $dtOrder->dataOrderDetail($input);
    $ambildata = $dataview;
    $id_order = empty($input) ? '' : $input;

    $linkpage 	= '&u_token=' . $u_token;

    include "view.php";
    break;
}
if ($stsload != "load") include(DIR_INCLUDE . "footer.php");
