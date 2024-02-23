<?php
if(!isset($_SESSION['idmember'])) echo "<script>location='".URL_PROGRAM.'login'."'</script>";
$modul = isset($_GET['modul']) ? $_GET['modul']:'';
$dtCart 		= new controller_Cart();
$dtProduk 		= new controller_Produk();
$dtReseller 	= new controller_Reseller();

$reseller = array();
if($idmember != '') {
  $reseller = $dtReseller->getResellerByID($idmember);
}
include path_to_includes.'bootcart.php';
switch($modul){
  
	case "konfirmasi":
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			if(isset($_POST['noorder'])) {
				$dtKonfirmasi	= new controller_Konfirmasi();
				echo $dtKonfirmasi->simpandata();
				exit;
			}
		}
	 
		$file = '/konfirmasi.php';
		$script='<script type="text/javascript" src="'.URL_THEMES.'assets/js/konfirmasi.js"></script>';
	break;  
	default:
	case "account":
		if(isset($_GET['keluar'])) {
			session_destroy();
			echo "<script>location='".URL_PROGRAM."'</script>";
			exit;
		}
		if(isset($_REQUEST['load'])) {
			switch($_GET['load']) {
				case "listAlamat":
					$dtAkun = new controller_Account();
					
					$tipe = isset($_POST['tipe']) ? $_POST['tipe'] : null;
					$listalamat = $dtAkun->listAlamat();
					include DIR_THEMES.$folder.'/listalamat.php';
				break;
				case "frmEditAlamat":
				case "frmAddAlamat":
					$id = isset($_POST['id']) ? $_POST['id'] : '';
					$modul = isset($_POST['modul']) ? $_POST['modul'] : 'input';
					$tipe = isset($_POST['tipe']) ? $_POST['tipe'] : null;
					if($idmember != '') {
						if($modul == 'input') {
							$titleform = 'Tambah';
							$nama = '';
							$hp = '';
							$alamat = '';
							$propinsi = '';
							$kabupaten = '';
							$optkabupaten = '';
							$optkecamatan = '';
							$kelurahan = '';
							$kodepos = '';
							$default = '';
							$dataprop = $dtFungsi->cetakcombobox3('- Propinsi -','0',$propinsi,'add_propinsi','_provinsi','provinsi_id','provinsi_nama');
							
						} else {
							$titleform = 'Ubah';
							$dataalamat = $dtReseller->getAlamatCustomerByID($id);
							
							$nama = isset($dataalamat['ca_nama']) ? $dataalamat['ca_nama'] : '';
							$hp = isset($dataalamat['ca_hp']) ? $dataalamat['ca_hp'] : '';
							$alamat = isset($dataalamat['ca_alamat']) ? $dataalamat['ca_alamat'] : '';
							$propinsi = isset($dataalamat['ca_propinsi']) ? $dataalamat['ca_propinsi'] : 0;
							$kabupaten = isset($dataalamat['ca_kabupaten']) ? $dataalamat['ca_kabupaten'] : 0;
							$kecamatan = isset($dataalamat['ca_kecamatan']) ? $dataalamat['ca_kecamatan'] : 0;
							$dataprop = $dtFungsi->cetakcombobox3('- Propinsi -','0',$propinsi,'add_propinsi','_provinsi','provinsi_id','provinsi_nama');
							if($propinsi != '0' || $propinsi != '') {
								$optkabupaten = $dtFungsi->cetakcombobox3('- Kotamadya/Kabupaten -','0',$kabupaten,'add_kabupaten','_kabupaten','kabupaten_id','kabupaten_nama','provinsi_id='.$propinsi);
							}
							
							if($kabupaten != '0' || $kabupaten != '') {
								$optkecamatan = $dtFungsi->cetakcombobox3('- Kecamatan -','0',$kecamatan,'add_kecamatan','_kecamatan','kecamatan_id','kecamatan_nama','kabupaten_id='.$kabupaten);
							}
							$kelurahan = isset($dataalamat['ca_kelurahan']) ? $dataalamat['ca_kelurahan'] : '';
							$kodepos = isset($dataalamat['ca_kodepos']) ? $dataalamat['ca_kodepos'] : '';
							$default = isset($dataalamat['ca_default']) ? $dataalamat['ca_default'] : '';
						}
						
						
						include DIR_THEMES.$folder.'/formalamat.php';
					}
				break;
				case "frmHapusAlamat":
					$id = isset($_POST['id']) ? $_POST['id'] : '';
					$modul = isset($_POST['modul']) ? $_POST['modul'] : 'input';
					if($modul == 'hapus'){
						$dataalamat = $dtReseller->getAlamatCustomerByID($id);
						$titleform = 'Hapus';
						$nama = isset($dataalamat['ca_nama']) ? $dataalamat['ca_nama'] : '';
						$hp = isset($dataalamat['ca_hp']) ? $dataalamat['ca_hp'] : '';
						$alamat = isset($dataalamat['ca_alamat']) ? $dataalamat['ca_alamat'] : '';
						$propinsi = isset($dataalamat['provinsi_nama']) ? $dataalamat['provinsi_nama'] : '';
						$kabupaten = isset($dataalamat['kabupaten_nama']) ? $dataalamat['kabupaten_nama'] : '';
						$kecamatan = isset($dataalamat['kecamatan_nama']) ? $dataalamat['kecamatan_nama'] : '';
						$kelurahan = isset($dataalamat['kelurahan']) ? $dataalamat['kelurahan'] : '';
						$kodepos = isset($dataalamat['kodepos']) ? $dataalamat['kodepos'] : '';
						include DIR_THEMES.$folder.'/formhapusalamat.php';
					}
				break;
				case "frmEditPassword":
					$titleform = 'Ubah Password';
					$modul = 'ubah';
					
					include DIR_THEMES.$folder.'/formeditpassword.php';
				break;
			}
			exit;
		}
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$dtAkun = new controller_Account();
			if(isset($_POST['action'])) {
				
				$dtAkun->simpandata();
				exit;
			}
			
			if(isset($_POST['aksi'])) {
				switch($_POST['aksi']){
					case "inputalamat":
						$dtAkun->simpanalamat('input');
						exit;
					break;
					case "updatealamat":
						$dtAkun->simpanalamat('update');
						exit;
					break;
					case "hapusalamat":
						$dtAkun->hapusalamat();
						exit;
					break;
					case "ubahpassword":
						$dtAkun->ubahpassword();
						exit;
					break;
				}
			}
		}
		$alamats = $dtReseller->getAlamatCustomer($idmember);
		$file = '/akun.php';
		$script = '<script type="text/javascript" src="'.URL_THEMES.'assets/js/akun.js"></script>';
	break;
	case "orderhistory":
	    $link  = array();
	    $orders 		= $dtOrder->tampildata();
		
		$totals	   		= $orders['total'];
		
		$baris 	   		= $orders['baris'];
		$page 	   		= $orders['page'];
		$jmlpage   		= $orders['jmlpage'];
		$ambildata 		= $orders['rows'];
		$cari 			= isset($_GET['datacari']) ? $_GET['datacari']:'';
		$linkpage 		= '/'.$modul.'/';
		$linkcari 		= '';
		$sort			= isset($_GET['sort']) ? $_GET['sort']:'';
		if($sort!='') $link[] = 'sort='.trim(strip_tags($sort));
		if($cari!='') $link[] = 'datacari='.trim(strip_tags($cari));
		$dtPaging 		= new Paging();
		if(!empty($link)){
		   $linkcari = implode("&",$link);
		   $linkcari = '?'.$linkcari;
		}
		
		$file   = '/orderhistory.php';
		$script = '';
	break;
	case "orderedit":
	case "orderdetail":
     
		$mdl = isset($_GET['mdl']) ? $_GET['mdl']:'';
	
		switch($mdl) {
			case "editpenerima":
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					echo $dtCart->editPenerimaOrder();
					exit;
				}
			break;
			case "simpaneditkurir":
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					echo $dtCart->editKurir();
					exit;
				}
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
											<td><img src="'.URL_IMAGE.$ship["logo"].'">';
						
						$html .=     		'</td>
										</tr>
									</table>';
						$html .=  	'<table class="table">';
				
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
								  $valueservis = '<input type="hidden" class="inputbox valueclass" name="valueservis[\'$indexvalue\']" id="valueservis" rel="'.$indexvalue.'">';
								  $labelservis = 'Konfirmasi Admin ';
							}
						
							if($serv["minkilo"] > 0) {
							   $ketservis = '('.$tarif[2].' hari)
											 ('.number_format((int)$totberat/1000,2,",",".").' Kg, dibawah '.number_format($serv["minkilo"],0,",",".") .' - '.number_format($serv["minkilo"] + 0.3,2,",",".") .' Kg akan dibulatkan '. $serv['minkilo'] .' Kg) '. number_format($tarif[3],0,",",".") .' x '.$dtFungsi->fFormatuang($tarif[4]);
							} else {
							   $ketservis = '';
							}
							if($tarif[1] > 0 || $ship['tabeltarif'] == '') {
								$html .= '<tr>
											<td style="width: 80%">
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
			case "addorderproduk":
			   if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					echo $dtCart->addprodukorder();
			   }
				exit;
			break;
			case "cariproduk":
			   
			   print json_encode($dtProduk->produkAutocomplete());
			   exit;
			break;
			case "hapusorderproduk":
				if ($_SERVER['REQUEST_METHOD'] == 'POST') {
					echo $dtCart->hapusprodukorder();
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
		  
				$where = "idwarna in (SELECT warna FROM _produk_option WHERE idproduk='".$pid."' AND ukuran='".$idukuran."' AND stok > 0)";
		 
				echo $dtFungsi->cetakcombobox3('- Pilih Warna -','120',0,$idtxtwarna,'_warna','idwarna','warna',$where);
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
				 echo $stok.' Pcs';
				 exit;
			break;
			case "carieditproduk":
				 $idproduk = isset($_GET['produkid']) ? $_GET['produkid']:'';
				 $idwarna = isset($_GET['warna']) ? $_GET['warna']:'0';
				 $idukuran = isset($_GET['ukuran']) ? $_GET['ukuran']:'0';
				 $nopesan = isset($_GET['nopesan']) ? $_GET['nopesan']:'';
			
				 $ukuran = $dtProduk->getProdukOrder($nopesan,$idproduk,$idukuran,$idwarna,'ukuran');
				 $dt = array();
				 if($idukuran == '0'){
				   $uk = isset($ukuran[0]['id']) ? $ukuran[0]['id']:0;
			  
				 } else {
				   $uk =  $idukuran;
				 }
		   
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
				   echo $dtCart->editprodukorder();
				}
				exit;
			break;
		}
	
	
		$noorder = isset($_GET['order']) ? $_GET['order']:'0';
		
		$dataorder 	= $dtOrder->dataOrderByID($noorder);
		if(!$dataorder) echo "<script>location='".URL_PROGRAM."orderhistory'</script>";
		
		$datadetail 	= $dtOrder->dataOrderDetail($noorder);
		
		$datastatus 	= $dtOrder->dataOrderStatus($noorder);
		//$databayar 	= $dtKonfirmasi->getKonfirm($noorder);
		
		if($modul == 'orderdetail') {
			$file			= '/orderdetail.php';
		} elseif ($modul == 'orderedit') {
			$file			= '/orderedit.php';

		}
		$script = '';
	break;
	case "poin":
		$datapoin  = $dtReseller->getPoin($idmember);
		$totalpoin = $dtReseller->totalPoin($idmember);
		$file		= '/datapoin.php';
		$script    = '';
	break;
	case "deposito":
		$datadeposito  = $dtReseller->getDeposito($idmember);
		$totaldeposito = $dtReseller->totalDeposito($idmember);
		$file		= '/datadeposito.php';
		$script    = '';
	break;
}

include DIR_THEMES."header.php";
?>
<main>
	<?php include DIR_THEMES."/module/bannertop.php";?>
	<?php if($file!='') include DIR_THEMES.$folder.$file; ?>
</main>
<?php include DIR_THEMES."script.php";?>
<?php echo $script ?>
<?php include DIR_THEMES."footer.php";?>