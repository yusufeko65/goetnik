<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("path_to_language", "../../language/");
define("folder", "order");
include "../../../includes/config.php";
include "../../autoloader.php";


if(isset($_SESSION["masukadmin"])!="xjklmnJk1o~" && isset($_SESSION["userlogin"])=="") echo "<script>window.location='".URL_PROGRAM_ADMIN."'</script>";
include path_toincludes."paging.php";

$dtFungsi = new FungsiUmum();
$dtFungsi->cekHak('order','',0);

$dtOrder 		= new controllerOrder();
$dtSettingToko 	= new controllerSetting();
$dtReseller 	= new controllerCustomer();
$dtProduk 		= new controllerProduk();
$dtShipping		= new controllerShipping();

//$reseller_bayar = $dtFungsi->fcaridata('_setting_toko','reseller_bayar','1','1');

$menupage = isset($_GET["op"])? $_GET["op"]:"view";

$module = isset($_GET['modul']) ? $_GET['modul']:'';

switch($module){
	case "tarifkurir":
		$dtShipping->tarifkurir();
		exit;
	break;
	case "cetaklabel":
	case "cetak":
		require_once '../../fpdf/fpdf.php';
		if($module == 'cetak') $dtOrder->cetakInvoice();
		if($module == 'cetaklabel') $dtOrder->cetakLabel();
	   
		exit;
	break;
	case "frmEditStatus":
		$data = isset($_POST['data']) ? $_POST['data'] : '';
		if($data != '') {
			
			$zdata = explode("::",$data);
			
			$nopesan = $zdata[0];
			$stsnow = $zdata[1];
			$stsshipping = $zdata[2];
			$stskonfirm = $zdata[3];
			$stssudahbayar = $zdata[10];
			$datashipping = explode('::',$zdata[4]);
			$servis		 = $datashipping[0];
			$shipping	 = $datashipping[1];
			$tglshipping = $zdata[5];
			$awbshipping = $zdata[6];
			$stsgetpoin  = $zdata[7];
			$pelangganid  = $zdata[8];
			$totpoin  = $zdata[9];
			$grandtotal = $zdata[11];
			$datastatus 	= $dtOrder->dataOrderStatus($nopesan);
			$datakonfirmasi = $dtOrder->dataOrderKonfirmasi($nopesan);
		
			if($datakonfirmasi) {
			   $modekonfirm    = "updatekonfirm";
			   $jmlbayar       = $datakonfirmasi['jml_bayar'];
			   $bankto         = $datakonfirmasi['bank_rek_tujuan'];
			   $bankfrom       = $datakonfirmasi['bank_dari'];
			   $norekfrom      = $datakonfirmasi['bank_rek_dari'];
			   $atasnamafrom   = $datakonfirmasi['bank_atasnama_dari'];
			   $tglbayar       = $datakonfirmasi['tgl_transfer'];
			   $buktitransfer  = $datakonfirmasi['buktitransfer'];
				   //$status         = $datakonfirmasi['status_bayar'];
			} else {
			   $modekonfirm = "inputkonfirm";
			   $jmlbayar       = $grandtotal;
			   $bankto         = '';
			   $bankfrom       = '';
			   $norekfrom      = '';
			   $atasnamafrom   = '';
			   $tglbayar       = '';
			   $buktitransfer  = '';
			}
			include DIR_INCLUDE."view/order/formeditstatus.php"; 
			exit;
		}
	break;
	case "frmEditProduk":
		$data = isset($_POST['data']) ? $_POST['data']:'';
		if($data != ''){
	    
			$zdata    = explode("::",$data);
			$nopesan  = $zdata[0];
			$iddetail = $zdata[1];
			$produkid = $zdata[2];
			$produknm = $zdata[3];
			$idwarna  = $zdata[4];
			$idukuran = $zdata[5];
			$qty      = $zdata[6];
			$idgrup   = $zdata[7];
			$idmember = $zdata[8];
		//$poin     = $zdata[9];
		
			$ukuran = $dtProduk->getProdukOrder($nopesan,$produkid,$idukuran,$idwarna,'ukuran');
			$dt = array();
			if($idukuran == '0' && $idukuran != ''){
				$uk = isset($ukuran[0]['id']) ? $ukuran[0]['id']:0;
			} else {
				$uk =  $idukuran;
			}
	   
	  
			$jmlstok = $dtFungsi->fcaridata('_produk','jml_stok','idproduk',$produkid);
			if(($uk != '' && $uk != '0') || ($idwarna != '' && $idwarna !='0')) {
				$stokdata = $dtProduk->getProdukStokOption($produkid,$idwarna,$idukuran);
				$warna = $dtProduk->getProdukWarnaByUkuran($produkid,$uk);
			}
			$stokdata = isset($stokdata) ? $stokdata : array();
			$stokdata['stok'] = isset($stokdata['stok']) ? $stokdata['stok']:0;
			$warna = isset($warna) ? $warna: array();
			if($stokdata['stok'] > 0){
				$stok = $stokdata['stok'];
			} else {
				$stok = $jmlstok;
			}
			$stok = $stok == '' ? 0 : $stok;
	   
			include DIR_INCLUDE."view/order/formeditprod.php"; 
			exit;
		}
	break;
   case "frmEditKurir":
		$data = isset($_POST['data']) ? $_POST['data']:'';
		if($data != ''){
	    
			$zdata    = explode("::",$data);
			$negara   = $zdata[0];
			$prop     = $zdata[1];
			$kota     = $zdata[2];
			$kec      = $zdata[3];
			$kdpos    = $zdata[4];
			$totberat = $zdata[5];
			$nopesan  = $zdata[6];
			$idkurir  = $zdata[7];
			$idservis = $zdata[8];
			$konfirmadmin = $zdata[9];
			$subtotal = $zdata[10];
			$servis = $dtShipping->getAllServicesAndTarifByWilayah($prop,$kota,$kec);
			
			include DIR_INCLUDE."view/order/formeditkurir.php"; 
			exit;
		}
	break;
	case "frmDelProduk":
		$data = isset($_POST['data']) ? $_POST['data']:'';
		if($data != ''){
	    
			$zdata    = explode("::",$data);
			$nopesan  = $zdata[0];
			$iddetail = $zdata[1];
			$produkid = $zdata[2];
			$produknm = $zdata[3];
			$warna    = $zdata[4];
			$ukuran   = $zdata[5];
			$qty      = $zdata[6];
			$idgrup   = $zdata[7];
			$idmember = $zdata[8];
			$nmwarna  = $zdata[9];
			$nmukuran = $zdata[10];
			$subtotal = $zdata[11];
			//$poin     = $zdata[12];
			include DIR_INCLUDE."view/order/formdelprod.php"; 
			exit;
		}
	break;
	case "reseller": 
		$data = array();
		$id = isset($_GET['idreseller']) ? $_GET['idreseller']:'';
		$data = $dtReseller->dataResellerByKode($id);
		if(count($data)==0) $data='';
		print json_encode($data);
		exit;
	break;
	case "simpanpelanggan":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			echo $dtReseller->simpandata('simpan');
			exit;
		}
	break;
	case "carinpkk":
		$id 	 = isset($_GET['id']) ? $_GET['id']:'';
		$jenis = isset($_GET['jenis']) ? $_GET['jenis']:'';
		if($jenis == 'negara'){
			$tabel = '_negara';
			$fieldambil = 'negara_nama';
			$fieldkondisi = 'negara_id';
		} else if($jenis == 'propinsi'){
			$tabel = '_provinsi';
			$fieldambil = 'provinsi_nama';
			$fieldkondisi = 'provinsi_id';
		} else if($jenis == 'kabupaten'){
			$tabel = '_kabupaten';
			$fieldambil = 'kabupaten_nama';
			$fieldkondisi = 'kabupaten_id';
		} else if($jenis == 'kecamatan'){
			$tabel = '_kecamatan';
			$fieldambil = 'kecamatan_nama';
			$fieldkondisi = 'kecamatan_id';
		}
	  
		if($jenis != '')  $data = $dtFungsi->fcaridata($tabel,$fieldambil,$fieldkondisi,$id);
		print json_encode($data);
		exit;
	break;
	case "createcombonpkk":
		$id 	 = isset($_GET['id']) ? $_GET['id']:'';
		$id2 	 = isset($_GET['id2']) ? $_GET['id2']:'';
		$jenis = isset($_GET['jenis']) ? $_GET['jenis']:'';
		$ukuran 		= '200';
		if($jenis == 'negara'){
			$juduloption 	= '- Negara -';
			$idobject 	= 'pnegara';
			$tabel 		= '_negara';
			$fieldoption 	= 'negara_id';
			$fieldisi 	= 'negara_nama';
			$where		= Null;
		} else if($jenis == 'propinsi'){
			$juduloption 	= '- Propinsi -';
			$idobject 	= 'ppropinsi';
			$tabel 		= '_provinsi';
			$fieldoption 	= 'provinsi_id';
			$fieldisi 	= 'provinsi_nama';
			$where		= 'negara_id='.$id2.' ORDER by provinsi_nama';
		} else if($jenis == 'kabupaten') {
			$juduloption 	= '- Kabupaten -';
			$idobject 	= 'pkabupaten';
			$tabel 		= '_kabupaten';
			$fieldoption 	= 'kabupaten_id';
			$fieldisi 	= 'kabupaten_nama';
			$where		= 'provinsi_id='.$id2. ' ORDER by kabupaten_nama';
		} else if($jenis == 'kecamatan') {
			$juduloption 	= '- Kecamatan -';
			$idobject 	= 'pkecamatan';
			$tabel 		= '_kecamatan';
			$fieldoption 	= 'kecamatan_id';
			$fieldisi 	= 'kecamatan_nama';
			$where		= 'kabupaten_id='.$id2.' ORDER by kecamatan_nama';
		  
		}
		echo $dtFungsi->cetakcombobox($juduloption,$ukuran,$id,$idobject,$tabel,$fieldoption,$fieldisi,$where);
		exit;
	break;
	case "grupreseller": 
		$id 	 = isset($_GET['id']) ? $_GET['id']:'';
		print json_encode($dtFungsi->fcaridata('_reseller_grup','rs_grupnama','rs_grupid',$id));
		exit;
	break;
	case "cariproduk":
		//print json_encode($dtProduk->produkAutocomplete());
		echo json_encode("test");
        exit;
	break;
	case "hapusorderproduk":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			echo $dtOrder->hapusprodukorder();
		}
		exit;
	break;
	case "carieditproduk":
		$idproduk = isset($_GET['produkid']) ? $_GET['produkid']:'';
		$idwarna = isset($_GET['warna']) ? $_GET['warna']:'0';
		$idukuran = isset($_GET['ukuran']) ? $_GET['ukuran']:'0';
		$nopesan = isset($_GET['nopesan']) ? $_GET['nopesan']:'';
		//$ukuran = $dtProduk->getProdukOption($idproduk,'ukuran');
		$ukuran = $dtProduk->getProdukOrder($nopesan,$idproduk,$idukuran,$idwarna,'ukuran');
		$dt = array();
		if($idukuran == '0'){
			$uk = isset($ukuran[0]['id']) ? $ukuran[0]['id']:0;
		  
		} else {
			$uk =  $idukuran;
		}
		//$warna = $dtProduk->getProdukWarnaByUkuran($idproduk,$uk);
		$warna = $dtProduk->getProdukOrderWarnaByUkuran($idproduk,$idukuran,$idwarna,$nopesan);
		$jmlstok = $dtFungsi->fcaridata('_produk','jml_stok','idproduk',$idproduk);
		$stokdata = $dtProduk->getProdukStokOption($idproduk,$idwarna,$idukuran);
		if($stokdata['stok'] > 0){
			$stok = $stokdata['stok'];
		} else {
			$stok = $jmlstok;
		}
		$stok = $stok == '' ? 0 : $stok;

		$data['ukuran'] = $ukuran;
		$data['warna'] = $warna;
		$data['jmlstok'] = $stok;
		echo json_encode($data);
		exit;
	break;
	case "editorderproduk":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			echo $dtOrder->editprodukorder();
		}
		exit;
	break;
	case "addorderproduk":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			echo $dtOrder->addprodukorder();
		}
		exit;
	break;
	case "addcart":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(isset($_POST['qyt']) && isset($_POST['idmember'])) {
				$pesan	= array();
				$jml		= isset($_POST['qyt']) ? $_POST['qyt']:0;
				$pid		= isset($_POST['idproduk']) ? $_POST['idproduk']:0;
				$ukuran	= isset($_POST['ukuran']) ? $_POST['ukuran']:'';
				$warna 	= isset($_POST['warna']) ? $_POST['warna']:'';
				$idmember = isset($_POST['idmember']) ? $_POST['idmember']:'';
				$jenis	= isset($_POST['jenis']) ? $_POST['jenis']:'';
				$where	= "WHERE ukuran > 0 AND warna='".$warna."'";
				$jmlukuran  = $dtFungsi->fjumlahdata('_produk_option',$where);
				if($jml < 0) $pesan[] = 'Masukkan Jumlah Pesanan Anda';
		
				if($warna == '0') {
					$pesan[] = 'Pilih Warna';
				}
		
				if($jmlukuran > 0) {
					if($ukuran == '0') 
						$pesan[] = 'Pilih Jenis Ukuran';
				}
				if(count($pesan) > 0) {
					$hasil = implode("<br>",$pesan);
					echo "gagal|$hasil";
				} else {
				   $data 		 	= array();
				   $produk       	= $dtProduk->dataProdukByID($pid);
				   $data['tipe'] 	= $jenis;
				   $data['stok']	= $produk['jml_stok'];
				   $data['produk']	= $produk['nama_produk'];
				   $data['pid']		= $pid;
				   $data['jml']		= $jml;
				   $data['option']  = array($ukuran,$warna);
				   $data['idmember'] = $idmember;

				   echo $dtOrder->addCart($data);
				}

			}
		}
		exit;
	break;
	case "updatecart":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(isset($_POST['jumlah']) && isset($_POST['idreseller'])) {
				echo $dtOrder->updateCart();
			}
		}
		exit;
	break;
   
	case "delcart":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		  
			if(isset($_POST['idmember'])) {
			   $data = $_POST['data'];
			   echo $dtOrder->delCart($data);
			} 
		} 
		exit;
	break;
   
	case "caristok":
		$idukuran	= isset($_GET['ukuran']) ? $_GET['ukuran']:'';
		$idwarna	= isset($_GET['warna']) ? $_GET['warna']:'';
		$pid		= isset($_GET['idproduk']) ? $_GET['idproduk']:'';		
		$stokdata = $dtProduk->getProdukStokOption($pid,$idwarna,$idukuran);
		$jmlstok = $dtFungsi->fcaridata('_produk','jml_stok','idproduk',$pid);
		if($stokdata['stok'] > 0){
			$stok = $stokdata['stok'];
		} else {
			$stok = $jmlstok;
		}
		echo $stok;
		exit;
	break;
	case "cariwarna":
		$idukuran	= isset($_GET['ukuran']) ? $_GET['ukuran']:'';
		$pid		= isset($_GET['idproduk']) ? $_GET['idproduk']:'';
		$idtxtwarna = isset($_GET['idtxtwarna']) ? $_GET['idtxtwarna']:'';
	  
		$tb = "_produk_options LEFT JOIN _warna ON _produk_options.warna = _warna.idwarna";
		$where = "idproduk='".$pid."' AND ukuran = '".$idukuran."' AND stok > 0";
		$rw1 = "DISTINCT(_produk_options.warna) as idwarna";
		$rw2 = "_warna.warna";
		// echo $dtFungsi->cetakcombobox('- Pilih Warna -','120',0,'warna',$tb,'idwarna','warna',$where);
	 
		echo $dtFungsi->cetakcombobox2('- Pilih Warna -','',0,$idtxtwarna,$tb,$rw1,$rw2,'form-control',$where);
		// echo json_encode($dtProduk->getProdukWarnaByUkuran($pid,$idukuran));
		exit;
	 
	break;
	case "listcart":
		$html = '';
		$tipemember = isset($_GET['jenis']) ? $_GET['jenis']:'';
		$idmember = isset($_GET['idmember']) ? $_GET['idmember']:'';
		$h = isset($_GET['h']) ? $_GET['h']:'';
		if(isset($_SESSION['hsadmincart'][$idmember])) {
			$keranjang = $dtOrder->showminiCart($_SESSION['hsadmincart'][$idmember],$tipemember,$idmember);
			$totalitem   = $keranjang['items'];
			$cart 		= $keranjang['carts'];
			$subtotal 	= 0;
			$i = 0;
			$totberat = 0;
			$zbiayaregis = $dtFungsi->fcaridata2('_reseller_invoice','biaya',"stsbayar='0' AND idmember='".$idmember."' AND member_grup='".$tipemember."'");
			$biayaregis = (int)$zbiayaregis[0];
			$jml = 0;
			if($totalitem > 0){
				foreach($cart as $c){
					$pid 	= $c['product_id'];
					$nama_produk = $c['product'];
					//$gbr 		 = $c['gbr'];
					$jml 		 	 = $c['qty'];
					$satuanberat 	 = $c['satuanberat'];
					$berat 		 = $c['berat'];
					$harga 		 = $c['harga'];
					$total 		 = $c['total'];
					$subtotal	+= $total;
					$totberat   += $berat;
					$idwarna	 = $_SESSION['wrnadmincart'][$idmember][$pid][$i];
					$idukuran 	 = $_SESSION['ukradmincart'][$idmember][$pid][$i];
					if($idwarna !='') $warna = $dtFungsi->fcaridata('_warna','warna','idwarna',$idwarna);
					else $warna = '';
						
					if($idukuran != '')	$ukuran	= $dtFungsi->fcaridata('_ukuran','ukuran','idukuran',$idukuran);
					else $ukuran = '';
				  
					$option  	 = $idukuran.','.$idwarna;
					$options     = array($idukuran,$idwarna);
					$alias_url	 = $c['aliasurl']; 
					$idhapus = $pid.'::'.base64_encode(serialize($options)).'::'.$idmember.'::'.$jml;
					$img = "<img src='".URL_PROGRAM_ADMIN."/images/minus.png'>";
					if($h == 'hitung'){
						$l = '<a id="hapus" onclick="hapusCart(\''.$idhapus.'\')">'.$img.'</a>';
						$j = '<input type="text" size="1" value="'.$jml.'" name="jumlah['.$pid.'::'.base64_encode(serialize($options)).'::'.$idmember.']" id="jumlah" class="jumlahqyt">';
						$updt = ' <tr><td colspan="2"></td><td class="ltengah"><a class="tombols" id="tblupdate" onclick="updatecart()">Update</a></td><td colspan="3"></td></tr>';
					} else {
						$l = '';
						$j = $jml;
						$updt = '';
					}
				   
					$img = "<img src='".URL_PROGRAM_ADMIN."/images/minus.png'>";
					$html .= "<tr>";
					$html .= '<td class="ltengah">'. $l .'</td>';
					$html .= '<td class="lkiri">'.$nama_produk.'<br>'.$warna.'<br>'.$ukuran.'</td>';
					$html .= '<td class="ltengah">';
					$html .= $j;
					$html .= '</td>';
					$html .= '<td class="lkanan">'.$berat.' Gram</td>';
					$html .= '<td class="lkanan">'.$dtFungsi->fFormatuang($harga).'</td>';
					$html .= '<td class="lkanan">'.$dtFungsi->fFormatuang($total).'</td>';
					$html .= '</tr>';
											
					$i++;
				}
				$subtotal += $biayaregis;
				$html .= $updt;
				$html .= '<tr><td colspan="4" align="right"><td>';
				$html .= '<b>Sub Total : </b></td><td class="lkanan">' . $dtFungsi->fFormatuang($subtotal) .'<input type="hidden" value="'.$totberat.'" name="totberat" id="totberat"><input type="hidden" value="'.$subtotal.'" name="subtotal" id="subtotal"></td></tr>';

				if((int)$biayaregis > 0) {
					$html .= '<tr><td colspan="4"><td class="lkanan"><b>Biaya Registrasi : </b> </td><td>'.$dtFungsi->fFormatuang($biayaregis). '</td></tr>';
				}
		    
			
			} else {
				$html = '<tr><td colspan="6" class="ltengah">Tidak ada data</td></tr>';
			}
		} else {
		   $html = '<tr><td colspan="6" class="ltengah">Tidak ada data</td></tr>';
		}
		echo $html;
		exit;
	break;
	case "cekcart":
     // if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	        $jmldata = 0; 
			 $tipemember = isset($_GET['jenis']) ? $_GET['jenis']:'';
			 $idmember = isset($_GET['idmember']) ? $_GET['idmember']:'';
	         if(isset($_SESSION['hsadmincart'][$idmember])) {
			      $keranjang = $dtOrder->showminiCart($_SESSION['hsadmincart'][$idmember],$tipemember,$idmember);
			      $jmldata   = $keranjang['items'];
			 } 
			
			echo $jmldata;
			exit;
	  //} 
	  
	break;
	case "editshipping":
		$html			= '';	
	  
		/*penerima */
		$namapenerima = isset($_GET['namapenerima']) ? $_GET['namapenerima']:'';
		$alamatpenerima = isset($_GET['alamatpenerima']) ? $_GET['alamatpenerima']:'';
		$negara 		= isset($_GET['negara']) ? $_GET['negara']:'';
		$propinsi 	= isset($_GET['propinsi']) ? $_GET['propinsi']:'';
		$kabupaten 	= isset($_GET['kabupaten']) ? $_GET['kabupaten']:'';
		$kecamatan 	= isset($_GET['kecamatan']) ? $_GET['kecamatan']:'';
		/* /penerima */
	  
		$totberat 	= isset($_GET['totberat']) ? $_GET['totberat']:'';
	  
		$shipping 	= $dtShipping->getShipping();
	  
		if($shipping) {
			$zloop = 0;
			foreach($shipping as $ship) {
				$servis = $dtShipping->getServis($ship['tabel']);
				$html .=  	'<table>
								<tr>
									<td>
									<img src="'.URL_IMAGE.$ship["logo"].'">';
									if($ship['ket_shipping'] != Null or $ship['ket_shipping'] != '') {
									   $html .= '<br><br><p><b>'.$ship['ket_shipping'].'</b></p>';
									}
				 $html .=           '</td>
								</tr>
							 </table>';
				$html .=  	'<table class="form">';
				
				foreach($servis as $serv) {
				
				    if($ship['tabeldiskon'] != Null || $ship['tabeldiskon'] != '') {
					    $zdizkon = explode("::",$ship['tabeldiskon']);
						$tabel = $zdizkon[0];
						$fieldambil = $zdizkon[1];
						$where = " $zdizkon[2]='".$serv['id']."' AND $zdizkon[3]=1";
					
						$dtdiskon = $dtFungsi->fcaridata2($tabel,$fieldambil,$where);
						$diskon = $dtdiskon[0] / 100;
					} else {
					    $diskon = 0;
					}
				 
					$tarif = $dtShipping->getTarif($serv['id'],$negara,$propinsi,$kabupaten,$kecamatan,$totberat,$serv['minkilo'],$ship['tabeltarif'],$ship['detek_kdpos'],$ship['nama']);
					$zsrv = str_replace(' ','',$serv["nama"]);
		
					    $tarif[4] = $tarif[4] - ($tarif[4]*$diskon);
			            $tarif[1] = $tarif[1] - ($tarif[1]*$diskon);
						
					  if( $ship['tabeltarif'] != '' || $ship['tabeltarif'] != Null ) {
					      $indexvalue = $serv["id"].$ship["nama"].$tarif[4].$zsrv;
					      $valueservis = '<input type="hidden" class="inputbox valueclass" name="valueservis[\'$indexvalue\']" id="valueservis" rel="'.$indexvalue.'" value="'.$tarif[1].'">';
					      $labelservis = '<b><span>'.$dtFungsi->fFormatuang($tarif[1]).'</span></b>';
					   } else {
					      $indexvalue = $serv["id"].$ship["nama"].$tarif[4].$zsrv;
					      $valueservis = '<input type="text" class="inputbox valueclass" name="valueservis[\'$indexvalue\']" id="valueservis" rel="'.$indexvalue.'">';
					      $labelservis = '';
					   }
						
						if($serv["minkilo"] > 0) {
						  $ketservis = '('.$tarif[2].' hari)
								     ('.number_format((int)$totberat/1000,2,",",".").' Kg, dibawah '.number_format($serv["minkilo"],0,",",".") .' - '.number_format($serv["minkilo"] + 0.3,2,",",".") .' Kg akan dibulatkan '. $serv['minkilo'] .' Kg) '. number_format($tarif[3],0,",",".") .' x '.$dtFungsi->fFormatuang($tarif[4]);
                        } else {
						   $ketservis = '';
						}
						if($tarif[1] > 0 || $ship['tabeltarif'] == '') {
						    $html .= '<tr>
										<td style="width: 84%">
											<input type="radio" name="serviskurir" class="servisclass" rel="'.$indexvalue.'" id="serviskurir" value="'.$serv["id"].':'.$ship["nama"].':'.$tarif[4].':'.$serv["nama"].'"><b>'.$serv["nama"].'</b>
											   '. $ketservis.' '.$valueservis.'
										</td>
										<td align="right">'.$labelservis.'</td>
								  </tr>';
						}
				}
			
				$html .=    '</table><br>';
				$zloop++;
			}
		}
		echo $html;
		exit;
	  
	break;
	case "shipping":
		$idmember 	= isset($_GET['idmember']) ? $_GET['idmember']:'';
   
		/*penerima */
		$namapenerima = isset($_GET['namapenerima']) ? $_GET['namapenerima']:'';
		$alamatpenerima = isset($_GET['alamatpenerima']) ? $_GET['alamatpenerima']:'';
		$negara 		= isset($_GET['negara']) ? $_GET['negara']:'';
		$propinsi 	= isset($_GET['propinsi']) ? $_GET['propinsi']:'';
		$kabupaten 	= isset($_GET['kabupaten']) ? $_GET['kabupaten']:'';
		$kecamatan 	= isset($_GET['kecamatan']) ? $_GET['kecamatan']:'';
		/* /penerima */
	  
		$totberat 	= isset($_GET['totberat']) ? $_GET['totberat']:'';
	  
	  
		/* member */


		$field 			= 'reseller_nama,reseller_toko,rs_dropship,reseller_grup';
		$tabel 			= '_reseller INNER JOIN _reseller_grup ON _reseller.reseller_grup = _reseller_grup.rs_grupid';
		$where 			= "reseller_id = '".$idmember."'";
		$reseller 		= $dtFungsi->fcaridata2($tabel,$field,$where);
		$nmreseller 	    = $reseller[0];
		$tokoreseller	    = $reseller[1];
		$dropship		    = $reseller[2];
		$alamatmember     = isset($_GET['alamatmember']) ? $_GET['alamatmember']:'';
		$negaramember     = isset($_GET['negaramember']) ? $_GET['negaramember']:'';
		$propmember       = isset($_GET['propmember']) ? $_GET['propmember']:'';
		$kabmember        = isset($_GET['kabmember']) ? $_GET['kabmember']:'';
		$kecmember        = isset($_GET['kecmember']) ? $_GET['kecmember']:'';

		/* /member */
	  
		$shipping 	= $dtShipping->getShipping();
		$deposit		= $dtFungsi->fcaridata('_reseller_deposit','jumlah','reseller_id',"$idmember");
		$html			= '';	

	 
	  
		if($shipping) {
			$zloop = 0;
			foreach($shipping as $ship) {
				$servis = $dtShipping->getServis($ship['tabel']);
			
				$html .=  	'<table>
								<tr>
									<td>
									<img src="'.URL_IMAGE.$ship["logo"].'">';
									if($ship['ket_shipping'] != Null or $ship['ket_shipping'] != '') {
									   $html .= '<br><br><p><b>'.$ship['ket_shipping'].'</b></p>';
									}
				 $html .=           '</td>
								</tr>
							 </table>';
				$html .=  	'<table class="form">';
				
				foreach($servis as $serv) {
				
				    if($ship['tabeldiskon'] != Null || $ship['tabeldiskon'] != '') {
					    $zdizkon = explode("::",$ship['tabeldiskon']);
						$tabel = $zdizkon[0];
						$fieldambil = $zdizkon[1];
						$where = " $zdizkon[2]='".$serv['id']."' AND $zdizkon[3]=1";
					
						$dtdiskon = $dtFungsi->fcaridata2($tabel,$fieldambil,$where);
						$diskon = $dtdiskon[0] / 100;
					} else {
					    $diskon = 0;
					}
				   /*	$tabel = "_servis_jne_diskon";
					$fieldambil = 'jml_disk';
					$where = " servis_id='".$serv['id']."' AND stsdisk=1";
					
					$dtdiskon = $dtFungsi->fcaridata2($tabel,$fieldambil,$where);
					*/
					//if($namapenerima != $nmreseller && $alamatpenerima != $alamatmember && $propinsi != $propmember && $kabupaten != $kabmember && $kecamatan != $kecmember && $dropship=='1') {
					//   $diskon = 0;
					//} else {
					   //$diskon = $dtdiskon[0] / 100;
					//}
					
					$tarif = $dtShipping->getTarif($serv['id'],$negara,$propinsi,$kabupaten,$kecamatan,$totberat,$serv['minkilo'],$ship['tabeltarif'],$ship['detek_kdpos'],$ship['nama']);
					$zsrv = str_replace(' ','',$serv["nama"]);
					
					
					//if($tarif[1] > 0) {
					    $tarif[4] = $tarif[4] - ($tarif[4]*$diskon);
			            $tarif[1] = $tarif[1] - ($tarif[1]*$diskon);
					/*	
					  if($tarif[1] > 0) {
					      $indexvalue = $serv["id"].$ship["nama"].$tarif[4].$zsrv;
					      $valueservis = '<input type="hidden" class="inputbox valueclass" name="valueservis[\'$indexvalue\']" id="valueservis" rel="'.$indexvalue.'" value="'.$tarif[1].'">';
					      $labelservis = '<b><span>'.$dtFungsi->fFormatuang($tarif[1]).'</span></b>';
					   } else {
					      $indexvalue = $serv["id"].$ship["nama"].$tarif[4].$zsrv;
					      $valueservis = '<input type="text" class="inputbox valueclass" name="valueservis[\'$indexvalue\']" id="valueservis" rel="'.$indexvalue.'">';
					      $labelservis = '';
					   }
						*/
						
						if( $ship['tabeltarif'] != '' || $ship['tabeltarif'] != Null ) {
					      $indexvalue = $serv["id"].$ship["nama"].$tarif[4].$zsrv;
					      $valueservis = '<input type="hidden" class="inputbox valueclass" name="valueservis[\'$indexvalue\']" id="valueservis" rel="'.$indexvalue.'" value="'.$tarif[1].'">';
					      $labelservis = '<b><span>'.$dtFungsi->fFormatuang($tarif[1]).'</span></b>';
					   } else {
					      $indexvalue = $serv["id"].$ship["nama"].$tarif[4].$zsrv;
					      $valueservis = '<input type="text" class="inputbox valueclass" name="valueservis[\'$indexvalue\']" id="valueservis" rel="'.$indexvalue.'">';
					      $labelservis = '';
					   }
						
						if($serv["minkilo"] > 0) {
						  $ketservis = '('.$tarif[2].' hari)
								     ('.number_format((int)$totberat/1000,2,",",".").' Kg, dibawah '.number_format($serv["minkilo"],0,",",".") .' - '.number_format($serv["minkilo"] + 0.3,2,",",".") .' Kg akan dibulatkan '. $serv['minkilo'] .' Kg) '. number_format($tarif[3],0,",",".") .' x '.$dtFungsi->fFormatuang($tarif[4]);
                        } else {
						   $ketservis = '';
						}
						if($tarif[1] > 0 || $ship['tabeltarif'] == '') {
						    $html .= '<tr>
										<td style="width: 87%">
											<input type="radio" name="serviskurir" class="servisclass" rel="'.$indexvalue.'" id="serviskurir" value="'.$serv["id"].':'.$ship["nama"].':'.$tarif[4].':'.$serv["nama"].'"><b>'.$serv["nama"].'</b>
											   '. $ketservis.' '.$valueservis.'
										</td>
										<td align="right">'.$labelservis.'</td>
								  </tr>';
						}
								 /* if($serv['keterangan'] != '') {
						$html .= '<tr>
										<td><br>'.trim($serv["keterangan"]).'</td>
										<td></td>
								  </tr>';
								  }
								  */
					//}
				}
			
				$html .=    '</table><br>';
				$zloop++;
			}
			$html .= '<fieldset>
							<legend>BELANJA SAMBIL BERAMAL</legend><p>
							<table class="form">
								<tr>
									<td align="right" style="width: 87%"><b>Infaq</b></td>
									<td align="right"><input type="text" class="inputbox" name="infaq" id="infaq"></td>
								</tr>
							</table>
						</fieldset>';
			if($deposit > 0) {
				$html .='<table class="form">
							<tr>
								<td align="right" colspan="2"><b>Gunakan Deposit ? <input type="checkbox" name="deposit" id="deposit" value="'.$deposit.'" /> </b> </span>'.$dtFungsi->fFormatuang((int)$deposit) .'</div>
							</tr>
						  </table>';
			} 
	
		}
		echo $html;
		exit;
	break;
	case "simpanorder":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$s			= $dtFungsi->fcaridata2("_setting_toko","order_status","setid<>''");
			$order_status = $s[0];
			$order = $dtOrder->buyCart($order_status);
			if($order['sts'] == 'sukses'){
				$hasil = $order['data'];
				unset($_SESSION['hsadmincart']);
				unset($_SESSION['wrnadmincart']);
				unset($_SESSION['ukradmincart']);
				unset($_SESSION['qtyadmincart']);
			}
			$hasil = implode("|",$hasil);
			echo $hasil;
			exit;
		}
	break;
	case "simpaneditkurir":
		$dtOrder->editKurir();
		exit;
		/*
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			exit;
		}
		*/
	break;
}
// ini untuk aksi
$aksipage = isset($_POST["aksi"])? $_POST["aksi"]:"";
switch($aksipage){
	case "tambah":
		echo $dtOrder->simpandata('simpan');
		exit;
   break;
   case "ubah":
		echo $dtOrder->simpandata('ubah');
		exit;
	break;
	case "ubahorder":
		echo $dtOrder->simpandata('ubahorder');
		exit;
	break;
	case "hapus":
		echo $dtOrder->hapusdata($_POST);
		exit;
	break;
	case "generate":
     
		$nopesan = isset($_POST['id']) ? $_POST['id']:'';
		if($nopesan != '' && $nopesan != '-'){
			echo $dtOrder->generateinvoice($nopesan);
		}
		exit;
	break;
	case "simpanstatusorder":
		$dtOrder->simpanstatusorder();
		exit;
	break;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload']:'';
if($stsload!="load") {
 include(DIR_INCLUDE.'header.php');
  include(DIR_INCLUDE.'menu.php');
}

$judul = 'Order';

$iddata = '';
$b = 1;
$lock = '';
//Ini untuk tampilan
 
switch($menupage){
	case "view": default:
	    $dtPaging 	= new Paging();
		$dataview 	= $dtOrder->tampildata();
		$total 	  	= $dataview['total'];
		$baris 	  	= $dataview['baris'];
		$page 	  	= $dataview['page'];
		$jmlpage  	= $dataview['jmlpage'];
	    $ambildata	= $dataview['rows'];
		$cari 		= isset($_GET['datacari']) ? $_GET['datacari']:'';
		$status		= isset($_GET['status']) ? $_GET['status']:'';
		$linkpage 	= '';
		if($cari != '') $linkpage .= '&datacari='.trim(strip_tags(urlencode($cari)));
		if($status != '' && $status != '0') $linkpage .= '&status='.trim(strip_tags(urlencode($status)));
		
		include "view.php"; 
	break;
	case "add":
		$dtFungsi->cekHak("order","add",0);
		$tabel      = "_reseller_grup";
		$fieldambil = "rs_grupid,rs_grupnama";
		$where      = "rs_grupid NOT IN (SELECT reseller_bayar FROM _setting_toko)";
		$dtreseller = $dtFungsi->fcaridata2($tabel,$fieldambil,$where);
		$idgrupresellers = $dtreseller[0];
		$nmgrupresellers = $dtreseller[1];
		$modul      = "tambah"; 
		unset($_SESSION['hsadmincart']);
        unset($_SESSION['wrnadmincart']);
        unset($_SESSION['ukradmincart']);
	    unset($_SESSION['qtyadmincart']);
		include "formorder.php";
	break;
	case "invoice":
		
	case "info": 
		$dtFungsi->cekHak("order","edit",0);
		$modul = "ubahorder"; $iddata = urlencode($_GET["pid"]);
		$order = $dtOrder->dataOrderByID($iddata);
		if(!empty($order)){
		    $datadetail 	= $dtOrder->dataOrderDetail($iddata);
			
			
			$whereprint		= "nopesanan='".$order['pesanan_no']."' AND status_id='".$order['status_id']."'";
			$tglprint		= $dtFungsi->fcaridata2('_order_status','tanggal',$whereprint);
			
		
			$setting    = $dtSettingToko->getSetting();
			$dataset = array();
		    foreach($setting as $st){
	           $key = $st['setting_key'];
	           $value = $st['setting_value'];
	           if ($key == 'config_grupcust' || $key == 'config_editorder') {
	              $dataset["$key"]	= explode("::",$value);
               } else {
	              $dataset["$key"] = $value;
	           }
            }
			
			
			unset($_SESSION['hsadmincart']);
			unset($_SESSION['wrnadmincart']);
			unset($_SESSION['ukradmincart']);
			unset($_SESSION['qtyadmincart']);
		
			
			$regis 			= 0;
			if($menupage == "info")	{
			   include "form.php";
			} else {
			   include "invoice.php";
			}
		}
	break;
	

}
if($stsload!="load") include(DIR_INCLUDE.'footer.php');
 
?>