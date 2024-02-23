<?php
require_once('../../../includes/phpmailer/class.phpmailer.php');

class controllerOrder {
   private $page;
   private $rows;
   private $offset;
   private $model;
   private $Fungsi;
   private $data=array();
   private $idlogin;
   private $setting;
   private $jmlhrg;
   private $jmlhrgbeli;
   private $jmluklama;
   private $jmlwnlama;
   private $jmlukuran;
   private $jmlwarna;
   private $jmlqytlama;
   private $jmliddetail;
   private $dataProduk;
   private $bank;
   private $dataShipping;
   private $kirimemail;
   private $error;
   //private $tipemember;
   
   public function __construct(){
		$this->model= new modelOrder();
		$this->Fungsi= new FungsiUmum();
		$this->idlogin = isset($_SESSION['idmember']) ? $_SESSION['idmember']:'';
		$this->dataProduk = new controllerProduk();
		$this->dataShipping = new modelShipping();
		$this->dataCustomer = new modelCustomer();
		$this->bank = new controllerBank();
		
		//$this->tipemember = isset($_POST['jenis']) ? $_POST['jenis']:'';
		if(!isset($_SESSION['hsadmincart']))
           $_SESSION['hsadmincart'] = array();
		
		if(!isset($_SESSION['qtyadmincart']))
           $_SESSION['qtyadmincart'] = array();
		
		if(!isset($_SESSION['wrnadmincart']))
           $_SESSION['wrnadmincart'] = array();
		
		if(!isset($_SESSION['ukradmincart']))
           $_SESSION['ukradmincart'] = array();
		
		if(!isset($_SESSION['katadmincart']))
           $_SESSION['katadmincart'] = array();
   }
   
   public function cekOrder($noorder) {
      return $this->model->cekOrder($noorder,$this->idlogin);
   }
   
   public function simpandata($aksi){
	  $hasil = '';
	  $this->data['iddata']	  		= isset($_POST['iddata']) ? trim($_POST['iddata']):'';
	  $this->data['reseller']  		= isset($_POST['reseller']) ? trim($_POST['reseller']):'';
	  $this->data['tipereseller']   = $this->Fungsi->fcaridata('_reseller','cust_grup','cust_id',$this->data['reseller']);
	  $this->data['orderstatus']	= isset($_POST['orderstatus']) ? trim($_POST['orderstatus']):'';
	  $this->data['stsnow']			= isset($_POST['stsnow']) ? trim($_POST['stsnow']):'';
	  $this->data['stsprint']		= isset($_POST['stsprint']) ? trim($_POST['stsprint']):'';
	  $this->data['keterangan']		= isset($_POST['keterangan']) ? trim($_POST['keterangan']):'-';
	  $this->data['hrg']			= isset($_POST['hrg']) ? $_POST['hrg']:'';
	  $this->data['hrglama']		= isset($_POST['hrglama']) ? $_POST['hrglama']:'';
	  $this->data['iddetail']		= isset($_POST['iddetail']) ? $_POST['iddetail']:'';
	  $this->data['idproduk']		= isset($_POST['idproduk']) ? $_POST['idproduk']:'';
	  $this->data['qyt']			= isset($_POST['qyt']) ? $_POST['qyt']:'';
	  $this->data['qytlama']		= isset($_POST['qytlama']) ? $_POST['qytlama']:'';
	  $this->data['ukuran']		    = isset($_POST['ukuran']) ? $_POST['ukuran']:'';
	  $this->data['warna']		    = isset($_POST['warna']) ? $_POST['warna']:'';
	  $this->data['ukuranlama']		= isset($_POST['ukuranlama']) ? $_POST['ukuranlama']:'';
	  $this->data['warnalama']		= isset($_POST['warnalama']) ? $_POST['warnalama']:'';
	  $this->data['hrgbeli']		= isset($_POST['hrgbeli']) ? $_POST['hrgbeli']:'';
	  $this->data['tambah']			= isset($_POST['tambah']) ? $_POST['tambah']:0;
	  $this->data['kurang']			= isset($_POST['kurang']) ? $_POST['kurang']:0;
	  $this->data['tambahlama']		= isset($_POST['tambahlama']) ? $_POST['tambahlama']:0;
	  $this->data['kuranglama']		= isset($_POST['kuranglama']) ? $_POST['kuranglama']:0;
	  $this->data['transaksiclose']	= isset($_POST['transaksiclose']) ? $_POST['transaksiclose']:0;
	  $this->data['aksi']			= $aksi;
	  
	  /* konfirmasi order */
	  $this->data['bankfrom']	    = isset($_POST['bankfrom']) ? $_POST['bankfrom']:'';
	  $this->data['norekfrom']	    = isset($_POST['norekfrom']) ? $_POST['norekfrom']:'';
	  $this->data['atasnamafrom']	= isset($_POST['atasnamafrom']) ? $_POST['atasnamafrom']:'';
	  $this->data['bankto']	        = isset($_POST['bankto']) ? $_POST['bankto']:'';
	  $this->data['tglbayar']	    = isset($_POST['tglbayar']) ? $_POST['tglbayar']:'';
	  $this->data['jmlbayar']	    = isset($_POST['jmlbayar']) ? $_POST['jmlbayar']:'';
	  $this->data['modekonfirm']	= isset($_POST['modekonfirm']) ? $_POST['modekonfirm']:'';
	  
	  $this->data['tglinput']	= date('Y-m-d H:i:s');
	  $this->data['ipdata']	= $_SERVER['REMOTE_ADDR'];
	  
	  
	  if($this->data['hrg']) $this->jmlhrg = count($this->data['hrg']);
	  else $this->jmlhrg = 0;
	  
	  if($this->data['hrgbeli']) $this->jmlhrgbeli = count($this->data['hrgbeli']);
	  else $this->jmlhrgbeli = 0;
	  
	  if($this->data['qytlama']) $this->jmlqytlama = count($this->data['qytlama']);
	  else $this->jmlqytlama = 0;
	  
	  if($this->data['warnalama']) $this->jmlwnlama = count($this->data['warnalama']);
	  else $this->jmlwnlama = 0;
	  
	  if($this->data['ukuranlama']) $this->jmluklama = count($this->data['ukuranlama']);
	  else $this->jmluklama = 0;
	  
	  if($this->data['warna']) $this->jmlwarna = count($this->data['warna']);
	  else $this->jmlwnlama = 0;
	  
	  if($this->data['ukuran']) $this->jmlukuran = count($this->data['ukuran']);
	  else $this->jmluklama = 0;
	  
	  if($this->data['iddetail']) $this->jmliddetail = count($this->data['iddetail']);
	  else $this->jmliddetail = 0;
	  
	  
	  if ($aksi=='simpan') {
		  $hasil = $this->adddata();
	  } elseif ($aksi == 'ubahorder') {
	      $hasil = $this->editorder();
	  }
	  return $hasil;
   } 
   
   function editorder(){
		$modulnya = "updateorder";
		$pesan = "";
		$cek = $this->Fungsi->cekHak(folder,"edit",1);
		$proses = array();
		if($cek) {
			$pesan =" Anda tidak mempunyai Akses untuk mengubah data ";
		} else {
			if(!$this->model->cekOrder($this->data['iddata'],$this->data['reseller'])){
				$pesan = " Ada kesalahan data ";
			} else {
				$this->data['tgl'] = date('Y-m-d H:i:s');
				
				if((int)$this->data['tambah'] < 1 and (int)$this->data['tambahlama'] > 0){
				       $this->data['tambah'] = $this->data['tambahlama'];
				} 
				if((int)$this->data['kurang'] < 1 and (int)$this->data['kuranglama'] > 0){
				       $this->data['kurang'] = $this->data['kuranglama'];
				}
				
				if((int)$this->data['tambah'] < 1) $this->data['tambah'] = 0;
				if((int)$this->data['kurang'] < 1) $this->data['kurang'] = 0;
				
				
				
				$tabungan = $this->model->checkTabunganReseller($this->data['reseller']);
				$jml = 0;
				if((int)$this->data['tambah'] > 0){
				   
					//if((int)$this->data['tambah'] > (int)$this->data['tambahlama']){
						if($tabungan) { 
							if(!$this->model->updateTabunganReseller($this->data['reseller'],$this->data['tambah'],$this->data['tambahlama'],'tambah',$this->data['tgl'])) {
							   $proses[] = $this->model->updateTabunganReseller($this->data['reseller'],$this->data['tambah'],$this->data['tambahlama'],'tambah',$this->data['tgl']);
							   $pesan = 'salah di update tabungan tambah';
							}
					    } else {
							if(!$this->model->insertTabunganReseller($this->data['reseller'],$this->data['tambah'],$this->data['tgl'])) {
							  $proses[] = $this->model->insertTabunganReseller($this->data['reseller'],$this->data['tambah'],$this->data['tgl']);
							  $pesan = 'salah di insert tabungan';
							} 
						}
					//} else {
					//   if($tabungan) {
					//	   if($this->model->updateTabunganReseller($this->data['reseller'],$this->data['tambah'],$this->data['tambahlama'],'kurang',$this->data['tgl'])) $pesan = 'saah di updatetabungan kurang';
					 //  } else {
					 //       if(!$this->model->insertTabunganReseller($this->data['reseller'],$this->data['tambah'],$this->data['tgl'])) $pesan = 'salah di insert tabungan';
					 //  }
					//}
					$jml = $this->data['tambah'];
				} 
				if((int)$this->data['kurang'] > 0 && (int)$this->data['tambahlama'] > 0) {
				    $selisih = (int)$this->data['tambahlama'] - (int)$this->data['kurang'];
					if($selisih > 0){
						$jml = $selisih;	
					//} else {
					//	$jml = 0;
					}
					if(!$this->model->updateTabunganReseller($this->data['reseller'],$jml,$this->data['tambahlama'],'tambah',$this->data['tgl'])) { 
					   $proses[] = $this->model->updateTabunganReseller($this->data['reseller'],$jml,$this->data['tambahlama'],'tambah',$this->data['tgl']);
					   $pesan = 'salah di update tabungan tambah';
					}
					$this->data['tambah'] = 0;
				//} else {
				//	$jml = 0;
				   //echo "jml  ".$jml;
				}
				
				if($jml > 0){
					if(!$this->model->insertTabunganDetail($this->data['reseller'],$jml,$this->data['tgl'],$this->data['iddata'],'GET')) {
   					   $proses[] = $this->model->insertTabunganDetail($this->data['reseller'],$jml,$this->data['tgl'],$this->data['iddata'],'GET');
					   $pesan = 'salah di insert tabungan detail';
					}
				}
				if(!$this->model->editPenambahanKekuranganOrder($this->data['tambah'],$this->data['kurang'],$this->data['iddata'],$this->data['transaksiclose'])) {
				   $proses[] = $this->model->editPenambahanKekuranganOrder($this->data['tambah'],$this->data['kurang'],$this->data['iddata'],$this->data['transaksiclose']);
				   $pesan = 'salah di editpenambahankekuranganorder';
				}
			    
				/* konfirmasi order */
				$wsts		= "1=1";
			    $stsset	= $this->Fungsi->fcaridata2('_setting_toko','order_status,status_print,konfirm_status',$wsts);
				$stspends   = $stsset[0];
				$stsprint   = $stsset[1];
				$stskonfirm = $stsset[2];
					
				if($this->data['orderstatus'] != $stspends ) {
				   
				   if($this->data['modekonfirm'] == 'updatekonfirm') {
	  
	                   if( $this->data['bankfrom'] == '0' || $this->data['norekfrom'] == '' || $this->data['atasnamafrom'] == '' || $this->data['bankto'] == '0' || $this->data['tglbayar'] == '' || !is_int((int)$this->data['jmlbayar']) || $this->data['jmlbayar'] == '' || $this->data['jmlbayar'] < 1 ) {
				          $pesan = ' Lengkapi form konfirmasi pembayaran ';
				       } else {
				          $proses[] = $this->model->editKonfirmasiOrder($this->data);
				       } 
				 
				   } else {
				       if( $this->data['bankfrom'] != '0' && $this->data['norekfrom'] != '' && $this->data['atasnamafrom'] != '' && $this->data['bankto'] != '0' || $this->data['tglbayar'] != '' || is_int((int)$this->data['jmlbayar']) && $this->data['jmlbayar'] != '' && $this->data['jmlbayar'] > 0 ) {
					   //if( $this->data['bankfrom'] == '0' || $this->data['norekfrom'] == '' || $this->data['atasnamafrom'] == '' || $this->data['bankto'] == '0' || $this->data['tglbayar'] == '' || !is_int((int)$this->data['jmlbayar']) || $this->data['jmlbayar'] == '' || $this->data['jmlbayar'] < 1 ) {
				           $proses[] = $this->model->addKonfirmasiOrder($this->data);
						   //$pesan = ' Lengkapi form konfirmasi pembayaran ';
				       } 
				   }
				   
				   /* update intensif order pas konfirmasi */
				   if($this->model->cekIntensif($this->data['iddata'])){
				        if($this->data['orderstatus'] == $stsprint && $this->data['stsnow'] != $this->data['orderstatus']  ){
						    $this->data['tglkomplet'] = $this->data['tgl'];
						} else {
						    $this->data['tglkomplet'] = '0000-00-00 00:00:00';
						}
						if($this->data['orderstatus'] == $stskonfirm && $this->data['stsnow'] != $this->data['orderstatus'] ) {
						   $this->data['tglkonfirm'] = date('Y-m-d');
						} else {
						   $this->data['tglkonfirm'] = '0000-00-00';
						}
						if(!$this->model->UpdateIntensif($this->data)) {
				           $proses[] = $this->model->UpdateIntensif($this->data);
						   $pesan = 'error intensif';
						}
						//print_r($this->model->UpdateIntensif($this->data));
				   } 
				   //print_r($proses);
					   
				}
				
			   if($this->data['stsnow'] != $this->data['orderstatus'])  {
					//proses 
					
					if($pesan == ''){
					   
					   $proses[] = $this->model->editOrderStatus($this->data);
					   $proses[] = $this->model->insertOrderStatus($this->data);
					   
					
				    }
				}
				/* @end konfirmasi order */
				/*
				$jmlqytnya = array();
				$jmlcartnya = array();
				$prod = '';
				$optprod = '';
				$optprodlama = '';
				$dataprod = array();
				$stsedit = true;
				$detailid = array();
				*/
				if($this->data['stsnow'] != $this->data['stsprint'])  {
				  
				   if($this->jmlhrg > 0){
					 $subtot = 0;
					 $qyt    = 0;
				      for($i=0;$i<$this->jmlhrg;$i++){
					     if(!is_int((int)$this->data['hrg'][$i]) || $this->data['hrg'][$i] == ''){
							$harga = $this->data['hrglama'][$i];
						 } else {
							$harga = $this->data['hrg'][$i];
						 }
						 //$subtot += (int)$this->data['hrg'][$i];
						// if(!is_int($this->data['qyt'][$i]) || $this->data['qyt'][$i] == '') {
						//    $qyt = (int)$this->data['qytlama'][$i];
						// } else {
						 //   $qyt = (int)$this->data['qyt'][$i];
						 //}
						 //$subtot += ((int)$harga*(int)$this->data['qyt'][$i]);
						 //$this->model->editHrgOrderDetail((int)$this->data['hrg'][$i],$this->data['iddetail'][$i]);
						 //$subtot += ((int)$harga*$qyt);
						  $subtot += ((int)$harga*(int)$this->data['qyt'][$i]);
						 $proses[] = $this->model->editHrgOrderDetail((int)$harga,$this->data['iddetail'][$i],'jual');
						 
					  }
					  $proses[] = $this->model->editSubtotalOrder($subtot,$this->data['iddata']);
					 
					  
				   }
				   
				   /*
				   if($this->jmliddetail > 0){
				      $jmledit = 0;
					  $nopesanan = $this->data['iddata'];
					  $wdtorder  = "pesanan_no = '".$nopesanan."'"; 
					  $dataord   = $this->Fungsi->fcaridata2('_order','kurir,servis_kurir',$wdtorder);
					  
					  $dataordpenerima  = $this->Fungsi->fcaridata2('_order_penerima','negara_penerima,propinsi_penerima,kota_penerima,kecamatan_penerima',$wdtorder);
					  $negara    = $dataordpenerima[0];
					  $propinsi  = $dataordpenerima[1];
					  $kota      = $dataordpenerima[2];
					  $kecamatan = $dataordpenerima[3];
					  $idkurir	 = $dataord[0];
					  $idservis  = $dataord[1];
					  
					  $kurir		= $this->dataShipping->getShippingbyName($idkurir);
					  $namashipping = $kurir['nama_shipping'];
					  $tabelservis	= $kurir['tabel_servis'];
					  $tabeltarif	= $kurir['tabel_tarif'];
	  
					  $servisdata   = $this->dataShipping->getServisbyId($tabelservis,$idservis);
					  $namaservis	= $servisdata[1];
					  $servisid		= $servisdata[0];
					  $minkilo		= $servisdata[4];
					  
					  
				      for($i=0;$i<$this->jmliddetail;$i++){
						 if(!is_int($this->data['qyt'][$i]) || $this->data['qyt'][$i] == '') {
						    $qyt = (int)$this->data['qytlama'][$i];
							$stsedit = false;
						 } else {
						    $qyt = (int)$this->data['qyt'][$i];
							$stsedit = true;
						 }
						 if($this->data['warna'][$i]=='0' || $this->data['warna'][$i] == '') {
						    $warna = $this->data['warnalama'][$i];
						 } else {
						    $warna = $this->data['warna'][$i];
						 }
						 if($this->data['ukuran'][$i]=='0' || $this->data['ukuran'][$i] == '') {
						    $ukuran = $this->data['ukuranlama'][$i];
						 } else {
						    $ukuran = $this->data['ukuran'][$i];
						 }
						 $optprod = $warna.','.$ukuran;
						 $optprodlama = $this->data['warnalama'][$i].','.$this->data['ukuranlama'][$i];
						 $prod    = $this->data['idproduk'][$i].':'.$optprod.':'.$this->data['iddetail'][$i].':'.$optprodlama.':'.$this->data['qytlama'][$i];
						 $idprod = $this->data['idproduk'][$i];
						 if (in_array($prod, $dataprod)) {
						    $jmlcartnya[$prod] = $qyt;
							$jmlqytnya[$idprod] = $qyt;
						 } else {
						    $jmlcartnya[$prod] += $qyt;
							$jmlqytnya[$idprod] += $qyt;
						    $dataprod[] = $prod;
						 }
						 $cekstok = $this->dataProduk->getOption($idprod,$warna,$ukuran);
						 $allstok = $this->dataProduk->getProdukStok($idprod);
						 if($cekstok['stok'] > 0) {
							$stok = $cekstok['stok'];
						 } else {
							$stok = $allstok['jml_stok'];
						 }
					
						 $stok = $stok + (int)$this->data['qytlama'][$i];
						 if($stok < (int)$jmlcartnya[$prod]) {
						    $pesan = 'gagal|Maaf, stok tinggal tersedia '.$stok.' item';
							break;
					     }
						 if(($this->data['qyt'][$i] != $this->data['qytlama'][$i] && $this->data['qyt'][$i] != '') || ($this->data['warna'][$i] != $this->data['warnalama'][$i] && $this->data['warna'][$i] != '0') || ($this->data['ukuran'][$i] != $this->data['ukuranlama'][$i] && $this->data['ukuran'][$i] != '0')) {
						    $jmledit = $jmledit + 1; 
						 }
					  }
					  
					  if($jmledit == 0){
					     if($this->jmlhrg > 0){
							$subtot = 0;
							for($i=0;$i<$this->jmlhrg;$i++){
					          if(!is_int((int)$this->data['hrg'][$i]) || $this->data['hrg'][$i] == ''){
								$harga = $this->data['hrglama'][$i];
							  } else {
								$harga = $this->data['hrg'][$i];
							  }
						 
							  $subtot += ((int)$harga*(int)$this->data['qyt'][$i]);
						      $this->model->editHrgOrderDetail((int)$harga,$this->data['iddetail'][$i],'jual');
					        }
						    $this->model->editSubtotalOrder($subtot,$this->data['iddata']);
				         }
					  }
					  
					  if($pesan == '' && $jmledit > 0){
					     $options = array();
						 $optionslama = array();
						 $carts = array();
						 $i = 0;
						 $stoks = 0;
						 $ziddetail = array();
						 $totberat = 0;
						 $total = 0;
						 $data = array();
						 $balikin = false;
					     foreach ($jmlcartnya as $key => $quantity) {
						    $product = explode(':', $key);
						    $id = $product[0];
							$option = $product[1];
							$iddet = $product[2];
							$optionlama = $product[3];
							$qtylama = $product[4];
						    $ukuran = '';
							$warna = '';
							$options = explode(",",$option);
							$optionslama = explode(",",$optionlama);
							if(!isset($ukradmincart[$id]))
								$ukradmincart[$id] = array();
		
							if(!isset($wrnadmincart[$id])) 
								$wrnadmincart[$id] = array();
		
							$ukradmincart[$id][$i] = $options[1];
							$wrnadmincart[$id][$i] = $options[0];
							
							$uklama[$id][$i] = $optionslama[1];
							$wnlama[$id][$i] = $optionslama[0];
			
							$jmlitem = (int)$jmlqytnya[$id];
							$jmlcart = $quantity;
							
							$prod = $this->dataProduk->dataProdukByID($id);
							$prodDiskon = $this->dataProduk->getProdukDiskons($id, $this->data['tipereseller']);
	
							$hrgsatuan = $prod['hrg_jual'];
							$hrgbeli   = $prod['hrg_beli'];
							
							$cekstok = $this->dataProduk->getOption($id,$options[1],$options[0]);
							$allstok = $this->dataProduk->getProdukStok($id);
							if($cekstok['stok'] > 0) {
								$stok = $cekstok['stok'];
							} else {
								$stok = $allstok['jml_stok'];
							}
							
							if($qtylama > $jmlcart) {
							    $qtyreal = $qtylama - $jmlcart;
								$balikin = true;
							} elseif ($qtylama < $jmlitem) {
							    $qtyreal = $jmlcart - $qtylama;
								$balikin = false;
							} else {
							    $qtyreal = $jmlitem;
								$balikin = false;
							}
							
							if($balikin == true){
							  $stoks = $allstok['jml_stok'] + $qtyreal;
							  $stok = $stok + $qtyreal;
							} else {
							  $stoks = $allstok['jml_stok'];
							}
							
							
						    //$stok = $stok + $jmlitem;
							$x = 0;
							$jmldtdisk = count($prodDiskon)-1;
							$harga = 0;
							
							foreach($prodDiskon as $pdisk){
							   $hrgdiskon = $pdisk['harga'];
							   $minbeli   = $pdisk['minimal']; 
            
							   if($x < $jmldtdisk ) {
								  $maxbeli = ($prodDiskon[$x+1]['minimal']) - 1;
							   } elseif ($x == $jmldtdisk) {
								  $maxbeli = $stoks;
							   }
			
							   if(((int)$minbeli <= $jmlitem) && ((int)$maxbeli >= $jmlitem)) {
								  $harga = (int)$hrgdiskon;
							   } 
							   $x++;
						    } // end foreach prodDiskon
							
							 if($harga == 0){
								$harga = (int)$hrgsatuan;
							 }
							 $item_total = ((int)$harga) * (int)$jmlcart;
							 $berat = ((int)$prod['berat_produk']) * (int)$jmlcart;
							 
							 $totberat = $totberat + $berat;
							 $total = $total + $item_total;
							 
							 $data['iddetail'] = $iddet;
							 $data['jml'] = $jmlcart;
							 $data['harga'] = $harga;
							 $data['satuan'] = $hrgsatuan;
							 $data['berat'] = $berat;
							 $data['hrgbeli'] = $hrgbeli;
							 $data['warna'] = $wrnadmincart[$id][$i];
							 $data['ukuran'] = $ukradmincart[$id][$i];
							 
							 if(!$this->model->EditOrderDetail($data)) $pesan[] = 'error edit detail';
						     if($wrnadmincart[$id][$i] != '' || $wrnadmincart[$id][$i] != '0' || $ukradmincart[$id][$i] != '' || $ukradmincart[$id][$i] != '0') {
							    $wodetail = "iddetail = '".$iddet."' AND warnaid='".$wnlama[$id][$i]."' AND ukuranid='".$uklama[$id][$i]."'";
							    $dataodetail = $this->Fungsi->fcaridata2('_order_detail_option','idodetail',$wodetail);
								$data['idodetail'] = $dataodetail[0];
								if(!$this->model->EditOrderDetailOption($data)) $pesan[] = 'error simpan detail option' ;
								if($balikin==true){
								  if(!$this->model->UpdateStokOptionberTambah($data)) $pesan[] = 'error update stok option tambah' ;
								} else {
								  $data['jml'] = $qtyreal; 
								  if(!$this->model->UpdateStokOptionberKurang($data)) $pesan[] = 'error update stok option kurang' ;
								}
								
							 }
							 /*
							 $carts[] = array(
								"product_id"	=> $id, 
								"product"		=> $prod['nama_produk'],
								"qty"			=> $jmlcart,
								"harga"			=> $harga,
								"total"			=> $item_total,
								"satuanberat"	=> (int)$prod['berat_produk'],
								"berat"			=> $berat,
								"iddetail"	    => $iddet
							 );
							 */							 
							 $i++;
						// } // foreach jmlcartnya
						 /*
						 $carts = array(
						   'carts'	=> $carts,
						   'items'	=> count($jmlcartnya),
						   'idmember' => $this->data['reseller']
						 );
						 */
					 // } // enf if $jmledit > 0
					  
					 
				   //} // end if $jmldetail
				}
				
				//echo "jml hrg beli ".$this->jmlhrgbeli."<br>";
			     if($this->jmlhrgbeli > 0){
				    for($i=0;$i<$this->jmlhrgbeli;$i++){
				       if(!is_int((int)$this->data['hrgbeli'][$i]) || $this->data['hrgbeli'][$i] == '' || $this->data['hrgbeli'][$i] < 1){
					      $pesan = 'Masukkan Harga beli';
					  	  break;
					   } else {
						  if(!$this->model->editHrgOrderDetail((int)$this->data['hrgbeli'][$i],$this->data['iddetail'][$i],'beli')) {
							 $proses[] = $this->model->editHrgOrderDetail((int)$this->data['hrgbeli'][$i],$this->data['iddetail'][$i],'beli');
						     $pesan = ' error di edit harga beli detail ';
						  }
					   }
					}
				 }
				 
				
			}
			$this->model->prosesTransaksi($proses);
		}
		return $this->Fungsi->pesandata($pesan,$this->data['iddata']);
   }
   
   public function hapusprodukorder() {
     $data     = array();
	 $error    = false;
	 $pesan = '';
	 $pesans = array();
	 $proses = array();
	 $data['idproduk'] = isset($_POST['didproduk']) ? $_POST['didproduk']:'';
	 $data['idmember'] = isset($_POST['didmember']) ? $_POST['didmember']:'';
	 $data['nopesanan']  = isset($_POST['dnopesan'])  ? $_POST['dnopesan']:'';
	 $data['qtylama']  = isset($_POST['dqtylama'])  ? $_POST['dqtylama']:0; 
	 $data['uklama']   = isset($_POST['duklama'])   ? $_POST['duklama']:0;
	 $data['wnlama']   = isset($_POST['dwnlama'])   ? $_POST['dwnlama']:0;
	 $data['iddetail'] = isset($_POST['diddetail']) ? $_POST['diddetail']:0;
	 $data['idgrup']   = isset($_POST['didgrup']) ? $_POST['didgrup']:'';
	 $data['totlama']   = isset($_POST['dtotlama']) ? $_POST['dtotlama']:'';
	 $data['potpoinlama']   = isset($_POST['potpoinlama']) ? $_POST['potpoinlama']:0;
	 $data['potdepositolama']   = isset($_POST['potdepositolama']) ? $_POST['potdepositolama']:0;
	 $zhrgkurir = isset($_POST['zhrgkurir']) ? $_POST['zhrgkurir']:0;
	 if($data['iddetail'] == '' && $data['iddetail'] == '0'){
	    $pesan = 'Data tidak ada';
		$error = true;
	 } else {
	    //Kurir
		$wdtorder  = "pesanan_no = '".$data['nopesanan']."'"; 
		$dataord   = $this->Fungsi->fcaridata2('_order','kurir,servis_kurir',$wdtorder);
					  
        $dataordpenerima  = $this->Fungsi->fcaridata2('_order_penerima','negara_penerima,propinsi_penerima,kota_penerima,kecamatan_penerima',$wdtorder);
		$negara    = $dataordpenerima[0];
		$propinsi  = $dataordpenerima[1];
		$kota      = $dataordpenerima[2];
		$kecamatan = $dataordpenerima[3];
		$idkurir   = $dataord[0];
		$idservis  = $dataord[1];
					  
		$kurir		= $this->dataShipping->getShippingbyName($idkurir);
		$namashipping = $kurir['nama_shipping'];
		$tabelservis	= $kurir['tabel_servis'];
		$tabeltarif	= $kurir['tabel_tarif'];
		$tabeldiskon	= $kurir['tabel_diskon'];
	    $logoshipping	= $kurir['logo'];
	    $detekkdpos   = $kurir['detek_kdpos'];
	  
		$servisdata     = $this->dataShipping->getServisbyId($tabelservis,$idservis);
		$namaservis  	= $servisdata[1];
		$servisid		= $servisdata[0];
		$minkilo		= $servisdata[3];
		
		if((int)$data['wnlama'] > 0 || (int)$data['uklama'] > 0){
		   // stok produk optionnya nya dibalikin dulu
		   $proses[] = $this->model->updateStokOption($data['nopesanan'],$data['qtylama'],$data['wnlama'],$data['uklama'],$data['idproduk']);
		}
		// stok nya dibalikin dulu
		$proses[] = $this->model->updateStok($data['qtylama'],$data['idproduk']);
		
		// Hapus order_detail, order_Detail_option
		$this->model->hapusProdukOrderOption($data);
		
		$bongkar = $this->model->getProdukOrderOption('','','',$data['nopesanan']);
	    $data['tipe'] 	  = $data['idgrup'];
		
		foreach($bongkar as $order) {
		    
		     $produk         = $this->dataProduk->dataProdukByID($order['idproduk']);
			 //print_r($produk);
			 $data['stok']   = $produk['jml_stok'];
			 $data['produk'] = $produk['nama_produk'];
			 $data['pid']    = $order['idproduk'];
			 $data['jml']	 = $order['jml'];
			 $data['kategori'] = $produk['idkategori'];
			 $data['option'] = array($order['ukuran'],$order['warna']);
			 
			// if((int)$data['wnlama'] > 0 || (int)$data['uklama'] > 0){
			 
		       // stok produk optionnya nya dibalikin dulu
		     //  $proses[] = $this->model->updateStokOption($data['nopesanan'],$order['jml'],$order['warna'],$order['ukuran'],$order['idproduk']);
			   
		    // }
		     
			 // stok nya dibalikin dulu
		     //$proses[] = $this->model->updateStok($order['jml'],$order['idproduk']);
			 
			 $cart = $this->addCart($data);
			 
		   
		} // end foreach bongkar
		
		$idmember   = $data['idmember'];
		$idgrup     = $data['idgrup'];
		$_SESSION['hsadmincart'][$idmember] = isset($_SESSION['hsadmincart'][$idmember]) ? $_SESSION['hsadmincart'][$idmember]: array();
		
		$keranjang  = $this->showminiCart($_SESSION['hsadmincart'][$idmember],$idgrup,$idmember);
		$totalitem  = $keranjang['items'];
		$cart 		= $keranjang['carts'];
		$subtotal 	= 0;
	    $i      	= 0;
	    $totberat 	= 0;
	    $totjumlah	= 0;
		
		 //looping cartnya
		 foreach($cart as $c){
		   $this->data['pid']   	= $c['product_id'];
		   $this->data['jml'] 	 	= (int)$c['qty'];
		   $this->data['berat'] 	= $c['berat'];
		   $this->data['harga'] 	= $c['harga'];
		   $this->data['total'] 	= $c['total'];
		
		   $this->data['idwarna']  = $c['warna'];
		   $this->data['idukuran'] = $c['ukuran'];
		 
		   
		   if($this->data['idwarna'] !='') $warna	= $this->Fungsi->fcaridata('_warna','warna','idwarna',$this->data['idwarna']);
		   else $warna = '';
		
		   if($this->data['idukuran'] != '') $ukuran = $this->Fungsi->fcaridata('_ukuran','ukuran','idukuran',$this->data['idukuran']);
		   else $ukuran = '';
		
		
		   $nama_produk 			= $c['product'];
		   $this->data['satuan']	= $c['hargasatuan'];
		   
		   $this->data['ukuranlama'] = $this->data['idukuran'];
		   $this->data['warnalama'] = $this->data['idwarna'];
		   
		   $this->data['nopesan'] = $data['nopesanan'];
		   
		   
		   
		   
	 	   if(($this->data['idwarna'] != '' && $this->data['idwarna'] != '0') || ($this->data['idukuran'] != '' && $this->data['idukuran'] != '0')) {
			  //$proses[] = $this->model->UpdateStokOptionberKurang($this->data);
			  $proses[] = $this->model->updateOrderProdukOption($this->data);
		   } else {
		      $proses[] = $this->model->updateOrderProduk($this->data);
		   }
		
		   //$proses[] = $this->model->UpdateStokberKurang($this->data);
   		
		   $subtotal	+= $this->data['total'];
		   $totberat   += $this->data['berat'];
		   $totjumlah  += (int)$c['qty'];
	
		  
		   $i++;
		
	    } // end foreach looping
		/*
		$this->data['subtotal'] = $subtotal-(int)$data['totlama'];
	    $this->data['totjumlah'] = $totjumlah-$data['qtylama'];
		*/
	    $this->data['subtotal'] = $subtotal;
	    $this->data['totjumlah'] = $totjumlah;
	    $tarifkurir = $this->dataShipping->getTarif($servisid,$negara,$propinsi,$kota,$kecamatan,$totberat,$minkilo,$tabeltarif,$detekkdpos,$namashipping);
	  
		/*if($tabeldiskon != Null || $tabeldiskon != '') {
		  $zzdizkon = explode("::",$tabeldiskon);
		  $tabel = $zzdizkon[0];
		  $fieldambil = $zzdizkon[1];
          $where = " $zzdizkon[2]='".$servisid."' AND $zzdizkon[3]=1";
					
		  $dtdiskon = $this->Fungsi->fcaridata2($tabel,$fieldambil,$where);
		  $zdiskon = $dtdiskon[0] / 100;
	    } else {*/
		  $zdiskon = 0;
	    //}
	    $tarifkurir[1] = $tarifkurir[1] - ($tarifkurir[1]*$zdiskon);
	    $tarifkurir[4] = $tarifkurir[4] - ($tarifkurir[4]*$zdiskon);
	  
	    if($tarifkurir[1] > 0) {
	     $this->data['tarifkurir'] = $tarifkurir[1];
		 $this->data['satuantarifkurir'] = $tarifkurir[4];
	    } else {
	     $this->data['tarifkurir'] = $zhrgkurir;
		 $this->data['satuantarifkurir'] = 0;
	     }
	    
	    $this->data['kurir'] = $namashipping;
	    $this->data['kurirservis'] = $servisid;
	    $this->data['nopesanan'] = $data['nopesanan'];
		$this->data['pelangganid'] = $idmember;
		$this->data['nopesan'] = $data['nopesanan'];
		$this->data['tgl'] = date('Y-m-d H:i:s');
	    if($subtotal < 1) {
		   $subtotal = $this->Fungsi->fcaridata("_order","pesanan_subtotal","pesanan_no",$data['nopesanan']);
		}
	    $grandtot = $subtotal + $this->data['tarifkurir'];
		
		/* mengupdate potongan poin */
		if($data['potpoinlama'] > 0 ) {
		  if($data['potpoinlama'] > $grandtot) {
		     $potpoinbaru = $data['potpoinlama'] - $grandtot;
			
		  } else {
		     $sisapoin = $this->Fungsi->fcaridata("_customer_point","cp_poin","cp_cust_id",$idmember);
		     $potpoinbaru = $sisapoin + $data['potpoinlama'];
			
	      }
		
		  $this->data['totpoin'] = $potpoinbaru;
		 
		  
	      $cekpoinhistory = $this->model->checkPoinHistory($this->data,'OUT');
		  if($cekpoinhistory > -1 ){
		     if($bongkar) {
		        $proses[] = $this->model->updateGetPoin($this->data,'OUT');
		     } else {
			    $proses[] = $this->model->deleteGetPoin($this->data,'OUT');
			 }
		  }
		  if($bongkar) {
		     $this->data['totpoin'] = $potpoinbaru - $data['potpoinlama'];
		  }
		  $proses[] = $this->model->updatePoinPelanggan($this->data);
		   $this->data['potpoinbaru']=$potpoinbaru;
		} else {
		   $this->data['potpoinbaru']=0;
		}
	    /* end mengupdate potongan poin */
		
		/* mengupdate deposito */
		if($potdepositolama > 0 ) {
		    $grandtot = $subtotal + $this->data['tarifkurir'] - $potpoinbaru;
			if($potdepositolama > $grandtot) {
			   $potdepositobaru = $potdepositolama - $grandtot;
			} else {
			   $sisadeposito = $this->Fungsi->fcaridata("_customer_deposito","cd_deposito","cd_cust_id",$idmember);
			   $potdepositobaru = $sisadeposito + $potdepositolama;
			}
			$this->data['totdeposito'] = $potdepositobaru;
		 
		  
	        $cekdepositohistory = $this->dataCustomer->checkDepositoHistory($this->data,'OUT');
		    if($cekdepositohistory > -1 ){
		      $proses[] = $this->dataCustomer->updateDepositoHistory($this->data,'OUT');
		    }
		    $this->data['totdeposito'] = $potdepositobaru - $potdepositolama;
			$this->data['iddata'] = $idmember;
			$this->data['tglupdate'] = date('Y-m-d H:i:s');
		    $proses[] = $this->model->updateDeposito($this->data);
			$this->data['potdepositobaru'] = $potdepositobaru;
		} else {
		   $this->data['potdepositobaru']=0;
		}
		
		/* end mengupdate deposito */
	 
		$proses[] = $this->model->UpdateOrder($this->data);
		
		if($this->model->prosesTransaksi($proses)) {
		 //  $this->model->prosesTransaksi($proses);
		   $wherejml = " WHERE pesanan_no = '". $data['nopesanan']."'";
		   $jml = $this->Fungsi->fjumlahdata('_order_detail',$wherejml);
		   if($jml < 1) {
		     if(!$this->model->hapusOrder($data['nopesanan'])) {
			    $pesan = 'gagal|Proses Menyimpan Gagal';
			 } else {
			    $pesan = 'sukses|no'; 
			 }
		   } else {
		      $pesan = 'sukses|ya';
		   }
		   
		} else {
		   $pesan = 'gagal|Proses Penyimpanan Gagalss';
		}
		
	 }
	 return $pesan;
   }
   
   public function addprodukorder(){
	 $data = array();
	 $error = false;
	 $pesan = '';
	 $pesans = array();
	 $nopesan  = isset($_POST['anopesan']) ? $_POST['anopesan']:'0';
     $idproduk = isset($_POST['aidproduk']) ? $_POST['aidproduk']:'0';
	 $idgrup   = isset($_POST['aidgrup']) ? $_POST['aidgrup']:'0';
	 $idmember = isset($_POST['aidmember']) ? $_POST['aidmember']:'0';
	 $qty      = isset($_POST['aqty']) ? $_POST['aqty']:0;
	 $zwarna    = isset($_POST['awarna']) ? $_POST['awarna']:0;
	 $zukuran   = isset($_POST['aukuran']) ? $_POST['aukuran']:0;
	 $zhrgkurir   = isset($_POST['zhrgkurir']) ? $_POST['zhrgkurir']:0;
	 $proses = array();
	 if($nopesan == '0'){
	    $pesan = 'No. Pesan tidak ditemukan';
		$error = true;
	 } elseif ((int)$qty < 1){
	    $pesan = 'Jumlah harus lebih dari 0 '.$qty ;
		$error = true;
	 }
	 $tabelcek = '_order_detail INNER JOIN _order_detail_option ON _order_detail.iddetail = _order_detail_option.iddetail';
	 $wherecek = "WHERE pesanan_no = '".$nopesan."' AND produk_id='".$idproduk."' AND warnaid='".$zwarna."' AND ukuranid='".$zukuran."'";
	 $ceklist  = $this->Fungsi->fjumlahdata($tabelcek,$wherecek);
	 
	 if($ceklist > 0){
	   $pesan = 'Produk sudah ada di List Order';
	   $error = true;
	 }
	 
	 if($error) {
	    $pesan = 'gagal|'.$pesan;
	 } else {
	    //Kurir
		$wdtorder  = "pesanan_no = '".$nopesan."'"; 
		$dataord   = $this->Fungsi->fcaridata2('_order','kurir,servis_kurir',$wdtorder);
		
		$dataordpenerima  = $this->Fungsi->fcaridata2('_order_penerima','negara_penerima,propinsi_penerima,kota_penerima,kecamatan_penerima',$wdtorder);
		$negara    = $dataordpenerima[0];
		$propinsi  = $dataordpenerima[1];
		$kota      = $dataordpenerima[2];
		$kecamatan = $dataordpenerima[3];
		$idkurir   = $dataord[0];
		$idservis  = $dataord[1];
					  
		$kurir		= $this->dataShipping->getShippingbyName($idkurir);
		$namashipping = $kurir['nama_shipping'];
		$tabelservis	= $kurir['tabel_servis'];
		$tabeltarif	= $kurir['tabel_tarif'];
		$tabeldiskon	= $kurir['tabel_diskon'];
	    $logoshipping	= $kurir['logo'];
	    $detekkdpos   = $kurir['detek_kdpos'];
	  
		$servisdata   = $this->dataShipping->getServisbyId($tabelservis,$idservis);
		$namaservis	= $servisdata[1];
		$servisid		= $servisdata[0];
		$minkilo		= $servisdata[3];
		
		$produk       	  = $this->dataProduk->dataProdukByID($idproduk);
		$data['tipe'] 	  = $idgrup;
		$data['stok']	  = $produk['jml_stok'];
	    $data['produk']	  = $produk['nama_produk'];
		$data['pid']      = $idproduk;
		$data['jml']	  = $qty;
		$data['option']   = array($zukuran,$zwarna);
		$data['idmember'] = $idmember;
		$this->addCart($data);
		$bongkar = $this->model->getProdukOrderOption('','','',$nopesan);
		
		foreach($bongkar as $order) {
		   $produk = $this->dataProduk->dataProdukByID($order['idproduk']);
		   $data['stok']   = $produk['jml_stok'];
		   $data['produk'] = $produk['nama_produk'];
		   $data['pid']    = $order['idproduk'];
		   $data['jml']	   = $order['jml'];
		   $data['option'] = array($order['ukuran'],$order['warna']);
		 
		   // stok produk optionnya nya dibalikin dulu
		   $proses[] = $this->model->updateStokOption($nopesan,$order['jml'],$order['warna'],$order['ukuran'],$order['idproduk']);
		   
		     
			// stok nya dibalikin dulu
		   $proses[] = $this->model->updateStok($order['jml'],$order['idproduk']);
			 
			 $this->addCart($data);
		   
		 } // end foreach bongkar
		 
		 $keranjang = $this->showminiCart($_SESSION['hsadmincart'][$idmember],$idgrup,$idmember);
		 $totalitem   = $keranjang['items'];
		 $cart 		= $keranjang['carts'];
		 $subtotal 	= 0;
	     $i      	= 0;
	     $totberat 	= 0;
	     $totjumlah	= 0;
		 
		 $this->data['iddetail'] 	= (int)$this->Fungsi->fIdAkhir('_order_detail','iddetail') + 1;
		 
		 //looping cartnya
		 foreach($cart as $c){
		   $this->data['pid']   	= $c['product_id'];
		   $this->data['jml'] 	 	= $c['qty'];
		   $this->data['berat'] 	= $c['berat'];
		   $this->data['harga'] 	= $c['harga'];
		   $this->data['total'] 	= $c['total'];
		
		   $this->data['idwarna']  = $_SESSION['wrnadmincart'][$idmember][$this->data['pid']][$i];
		   $this->data['idukuran'] = $_SESSION['ukradmincart'][$idmember][$this->data['pid']][$i];
		   
		   if($this->data['idwarna'] !='') $warna	= $this->Fungsi->fcaridata('_warna','warna','idwarna',$this->data['idwarna']);
		   else $warna = '';
		
		   if($this->data['idukuran'] != '') $ukuran = $this->Fungsi->fcaridata('_ukuran','ukuran','idukuran',$this->data['idukuran']);
		   else $ukuran = '';
		
		
		   $nama_produk 			= $c['product'];
		   $where 					= "idproduk='".$this->data['pid']."'";
		   $prods					= $this->Fungsi->fcaridata2('_produk','hrg_jual,hrg_beli',$where);
		   $this->data['satuan']	= $prods[0];
		   $this->data['hrgbeli']	= $prods[1];
		   
		   	   
		   $this->data['ukuranlama'] = $this->data['idukuran'];
		   $this->data['warnalama'] = $this->data['idwarna'];
		   
		   $this->data['nopesan'] = $nopesan;
		   $this->data['nopesanan'] = $nopesan;
		   if($zukuran == $this->data['idukuran'] && $zwarna == $this->data['idwarna'] && $idproduk == $this->data['pid']) {
			   if(!$this->model->SimpanOrderDetail($this->data)) {
			      $proses[] = $this->model->SimpanOrderDetail($this->data);
			      $pesans[] = 'error simpan order detail';
			   }
			   if(!$this->model->SimpanOrderDetailOption($this->data)) { 
			      $proses[] = $this->model->SimpanOrderDetailOption($this->data);
			      $pesan[] = 'error simpan detail option' ;
			   }
		   } else {
		      if(!$this->model->updateOrderProduk($this->data)) {
			     $proses[] = $this->model->updateOrderProduk($this->data);
			     $pesans[] = 'error simpan detail';
			  }
		   }
	
	 	   if($this->data['idwarna'] != '' || $this->data['idwarna'] != '0' || $this->data['idukuran'] != '' || $this->data['idukuran'] != '0') {
			  //if(!$this->model->SimpanOrderDetailOption($this->data)) $pesan[] = 'error simpan detail option' ;
			  if(!$this->model->UpdateStokOptionberKurang($this->data)) {
			      $proses[] = $this->model->UpdateStokOptionberKurang($this->data);
			      $pesans[] = 'error update stok option' ;
			  }
		   }
		
		   if(!$this->model->UpdateStokberKurang($this->data)) {
		      $proses[] = $this->model->UpdateStokberKurang($this->data);
		      $pesan[] = 'error update stok' ;
		   }
		
		   $subtotal	+= $this->data['total'];
		   $totberat   += $this->data['berat'];
		   $totjumlah  += (int)$c['qty'];
	
		   $this->data['iddetail']++;
		   $i++;
		
	    } // end foreach looping
		
		$this->data['subtotal'] = $subtotal;
	    $this->data['totjumlah'] = $totjumlah;
	  
	    $tarifkurir = $this->dataShipping->getTarif($servisid,$negara,$propinsi,$kota,$kecamatan,$totberat,$minkilo,$tabeltarif,$detekkdpos,$namashipping);
	    /*
		$tabel = "_servis_jne_diskon";
	    $fieldambil = 'jml_disk';
	    $where = " servis_id='".$servisid."' AND stsdisk=1";
	    $dtdiskon = $this->Fungsi->fcaridata2($tabel,$fieldambil,$where);
	    $zdiskon = $dtdiskon[0] / 100;
		*/
		if($tabeldiskon != Null || $tabeldiskon != '') {
		  $zzdizkon = explode("::",$tabeldiskon);
		  $tabel = $zzdizkon[0];
		  $fieldambil = $zzdizkon[1];
          $where = " $zzdizkon[2]='".$servisid."' AND $zzdizkon[3]=1";
					
		  $dtdiskon = $this->Fungsi->fcaridata2($tabel,$fieldambil,$where);
		  $zdiskon = $dtdiskon[0] / 100;
	    } else {
		  $zdiskon = 0;
	    }
	    $tarifkurir[1] = $tarifkurir[1] - ($tarifkurir[1]*$zdiskon);
	    $tarifkurir[4] = $tarifkurir[4] - ($tarifkurir[4]*$zdiskon);
	    
		if($tarifkurir[1] > 0) {
	       $this->data['tarifkurir'] = $tarifkurir[1];
		   $this->data['satuantarifkurir'] = $tarifkurir[4];
	    } else {
	       $this->data['tarifkurir'] = $zhrgkurir;
		   $this->data['satuantarifkurir'] = 0;
	    }
	    //$this->data['tarifkurir'] = $tarifkurir[1];
	    //$this->data['satuantarifkurir'] = $tarifkurir[4];
	    $this->data['kurir'] = $namashipping;
	    $this->data['kurirservis'] = $servisid;
	    $this->data['nopesanan'] = $nopesan;
	    if(!$this->model->UpdateOrder($this->data)) {
		   $proses[] = $this->model->UpdateOrder($this->data);
		   $pesans[] = 'error simpan order';
		}
		
		if(count($pesans) == 0 ) {
		   $pesan = 'sukses|Berhasil';
		   $this->model->prosesTransaksi($proses);
		} else {
		   $pesan = 'gagal|gagal cuy';
		}
		
	 }
	 return $pesan;
   }
   
   public function editprodukorder(){
     $data = array();
	 $error = false;
	 $pesan = '';
	 $pesans = array();
     $nopesan  = isset($_POST['enopesan']) ? $_POST['enopesan']:'0';
     $idproduk = isset($_POST['eidproduk']) ? $_POST['eidproduk']:'0';
     $iddetail = isset($_POST['eiddetail']) ? $_POST['eiddetail']:'';
	 $idgrup   = isset($_POST['eidgrup']) ? $_POST['eidgrup']:'0';
	 $idmember = isset($_POST['eidmember']) ? $_POST['eidmember']:'0';
	 $qtylama  = isset($_POST['eqtylama']) ? $_POST['eqtylama']:0;
	 $qty      = isset($_POST['eqty']) ? $_POST['eqty']:0;
	 $wnlama   = isset($_POST['ewnlama']) ? $_POST['ewnlama']:0;
	 $uklama   = isset($_POST['ewnlama']) ? $_POST['euklama']:0;
	 $zwarna    = isset($_POST['ewarna']) ? $_POST['ewarna']:0;
	 $zukuran   = isset($_POST['eukuran']) ? $_POST['eukuran']:0;
	 $zhrgkurir   = isset($_POST['zhrgkurir']) ? $_POST['zhrgkurir']:0;
	 $idkurir   = isset($_POST['idkurir']) ? $_POST['idkurir']:0;
	 $idservis   = isset($_POST['idserviskurir']) ? $_POST['idserviskurir']:0;
	 $poinlama   = isset($_POST['poin']) ? $_POST['poin']:0;
	 $potpoinlama   = isset($_POST['potpoinlama']) ? $_POST['potpoinlama']:0;
	 $potdepositolama   = isset($_POST['potdepositolama']) ? $_POST['potdepositolama']:0;
	 $zwarna = $zwarna=='undefined'?0:$zwarna;
	 $zukuran = $zukuran=='undefined'?0:$zukuran;
	 //echo 'zwarna = '.$zwarna.', zukuran = '.$zukuran;
	 $proses = array();
	 $proses1= array();
	 $proses2= array();
	 if($nopesan == '0'){
	    $pesan = 'No. Pesan tidak ditemukan';
		$error = true;
	 } elseif ((int)$qty < 1){
	    $pesan = 'Masukkan Jumlah';
		$error = true;
	 }
	 if($error) {
	    $pesan = 'gagal|'.$pesan;
	 } else {
	    $wdtorder  = "pesanan_no = '".$nopesan."'"; 
        $dataordpenerima  = $this->Fungsi->fcaridata2('_order_penerima','negara_penerima,propinsi_penerima,kota_penerima,kecamatan_penerima',$wdtorder);
		$negara    = $dataordpenerima[0];
		$propinsi  = $dataordpenerima[1];
		$kota      = $dataordpenerima[2];
		$kecamatan = $dataordpenerima[3];
		//$idkurir   = $dataord[0];
		//$idservis  = $dataord[1];
					  
		$kurir		= $this->dataShipping->getShippingbyName($idkurir);
		$namashipping = $kurir['nama_shipping'];
		$tabelservis	= $kurir['tabel_servis'];
		$tabeltarif	= $kurir['tabel_tarif'];
		$tabeldiskon	= $kurir['tabel_diskon'];
	    $detekkdpos   = $kurir['detek_kdpos'];
	  
		$servisdata   = $this->dataShipping->getServisbyId($tabelservis,$idservis);
		$namaservis	   = $servisdata[1];
		$servisid		= $servisdata[0];
		$minkilo		= $servisdata[3];
	    
        //$proses[] = $this->model->updatePoinLama($idmember,$poinlama);		
    	    
		
		
	    $produk       	  = $this->dataProduk->dataProdukByID($idproduk);
		$zdata['tipe'] 	  = $idgrup;
		$zdata['stok']	  = $produk['jml_stok'];
	    $zdata['produk']	  = $produk['nama_produk'];
		$zdata['pid']      = $idproduk;
		$zdata['jml']	  = $qty;
		$zdata['option']   = array($zukuran,$zwarna);
		$zdata['idmember'] = $idmember;
		$zdata['kategori'] = $produk['idkategori'];
		//$this->addCart($zdata);
		
		
		$cekstoknow = $this->dataProduk->getOption($idproduk,$zukuran,$zwarna);
	    $cekstoknow['stok'] = isset($cekstoknow['stok']) ? $cekstoknow['stok']:0;
	    if($cekstoknow['stok'] > 0) {
	       $stoknow = $cekstoknow['stok'];
	    } else {
	       $stoknow = $zdata['stok'];
	    }
	    $jmls = abs($qty - $qtylama);
		
	    if($stoknow < $jmls) {
		    return 'gagal|Maaf, stok tinggal tersedia '.$stoknow.' item';
	     }
		
		//if((int)$wnlama > 0 || (int)$uklama > 0){
		   // stok produk optionnya nya dibalikin dulu
		//   $proses1[] = $this->model->updateStokOption($nopesan,$qtylama,$wnlama,$uklama,$idproduk);
	//	}
		// stok nya dibalikin dulu
		//$proses1[] = $this->model->updateStok($qtylama,$idproduk); 
		//$this->model->prosesTransaksi($proses1);
		$z = $this->addCart($zdata);
		$cek = explode("|",$z);
		if($cek[0] == 'gagal') {
		   return $z;
		}
		$zdata['jml'] = $jmls;
		if($qty > $qtylama) {
		   
		   if((int)$wnlama > 0 || (int)$uklama > 0){
		      $zdata['idukuran'] = $zukuran;
			  $zdata['idwarna'] = $zwarna;
		      $proses[] = $this->model->UpdateStokOptionberKurang($zdata);
		   }
		   $proses[] = $this->model->UpdateStokberKurang($zdata);
		} elseif ($qty < $qtylama) {
		   if((int)$wnlama > 0 || (int)$uklama > 0){
		   // stok produk optionnya nya dibalikin
		     $proses[] = $this->model->updateStokOption($nopesan,$jmls,$wnlama,$uklama,$idproduk);
	       }
		   $proses[] = $this->model->updateStok($jmls,$idproduk); 
		}
		
		$bongkar = $this->model->getProdukOrderOption('','','',$nopesan);
		
		foreach($bongkar as $order) {
		   
		  if((int)$order['iddetail'] != (int)$iddetail){
		     //echo $order['iddetail'].'<br>';
		     $produk = $this->dataProduk->dataProdukByID($order['idproduk']);
			 $data['stok'] = $produk['jml_stok'];
			 $data['produk'] = $produk['nama_produk'];
			 $data['pid']  = $order['idproduk'];
			 $data['jml']	  = $order['jml'];
			 $data['idmember'] = $idmember;
			 $data['tipe'] 	  = $idgrup;
			 if($order['ukuran']=='' && $order['ukuran'] == null){
			    $order['ukuran'] = '0';
			 }
			 if($order['warna']=='' && $order['warna'] == null){
			    $order['warna'] = '0';
			 }
			 $data['option']   = array($order['ukuran'],$order['warna']);
			 $data['kategori'] = $produk['idkategori'];
			 //if((int)$data['wnlama'] > 0 || (int)$uklama > 0){
			 //if((int)$wnlama > 0 || (int)$uklama > 0){
		       // stok produk optionnya nya dibalikin dulu
		     //  $proses2[] = $this->model->updateStokOption($nopesan,$order['jml'],$order['warna'],$order['ukuran'],$order['idproduk']);
		     //}
		     
			 // stok nya dibalikin dulu
		     //$proses2[] = $this->model->updateStok($order['jml'],$order['idproduk']);
			 //$this->model->prosesTransaksi($proses2);
			 $this->addCart($data);
		   } 
		 } // end foreach bongkar
		 
		 $keranjang = $this->showminiCart($_SESSION['hsadmincart'][$idmember],$idgrup,$idmember);
		 $totalitem   = $keranjang['items'];
		 $cart 		= $keranjang['carts'];
		 $subtotal 	= 0;
	     $i      	= 0;
	     $totberat 	= 0;
	     $totjumlah	= 0;
		 $totgetpoin = 0;
		 
		 //looping cartnya
		 //print_r($cart);
		 foreach($cart as $c){
		   $this->data['pid']   	= $c['product_id'];
		   $this->data['jml'] 	 	= $c['qty'];
		   $this->data['berat'] 	= $c['berat'];
		   $this->data['harga'] 	= $c['harga'];
		   $this->data['total'] 	= $c['total'];
		
		   $this->data['idwarna']  = $c['warna'];
		   $this->data['idukuran'] = $c['ukuran'];
		   
		   if($this->data['idwarna'] !='') $warna	= $this->Fungsi->fcaridata('_warna','warna','idwarna',$this->data['idwarna']);
		   else $warna = '';
		
		   if($this->data['idukuran'] != '') $ukuran = $this->Fungsi->fcaridata('_ukuran','ukuran','idukuran',$this->data['idukuran']);
		   else $ukuran = '';
		
		   //echo '$this->data[idwarna] = '.$this->data['idwarna'].', $this->data[idukuran]='.$this->data['idukuran'] ;
		   $nama_produk 			= $c['product'];
		   $where 					= "idproduk='".$this->data['pid']."'";
		   $prods					= $this->Fungsi->fcaridata2('_produk','hrg_jual,hrg_beli',$where);
		   $this->data['satuan']	= $prods[0];
		   $this->data['hrgbeli']	= $prods[1];
		   
		   if($zukuran == $this->data['idukuran'] && $zwarna == $this->data['idwarna'] && $idproduk == $this->data['pid']) {
		       $this->data['ukuranlama'] = $uklama;
			   $this->data['warnalama'] = $wnlama;

			   
		   } else {
		       $this->data['ukuranlama'] = $this->data['idukuran'];
			   $this->data['warnalama'] = $this->data['idwarna'];
		   }
		   
		 
		   
		   $this->data['nopesan'] = $nopesan;
		   
		   //$proses[] = $this->model->updateOrderProduk($this->data);
		   /*
	 	   if(($this->data['idwarna'] != '' && $this->data['idwarna'] != '0') || ($this->data['idukuran'] != '' && $this->data['idukuran'] != '0')) {
			   $proses[] = $this->model->UpdateStokOptionberKurang($this->data);
		   }
		   */
		   		   
	 	   if(($this->data['idwarna'] != '' && $this->data['idwarna'] != '0') || ($this->data['idukuran'] != '' && $this->data['idukuran'] != '0')) {
			  //$proses[] = $this->model->UpdateStokOptionberKurang($this->data);
			  $proses[] = $this->model->updateOrderProdukOption($this->data);
		   } else {
		      $proses[] = $this->model->updateOrderProduk($this->data);
		   }
		
		   //$proses[] = $this->model->UpdateStokberKurang($this->data);
		   $poinku  = (int)$c['poin'] * (int)$c['qty'];
		   $subtotal	+= $this->data['total'];
		   $totberat   += $this->data['berat'];
		   $totjumlah  += (int)$c['qty'];
	       $totgetpoin += (int)$poinku;
		  
		   $i++;
		
	    } // end foreach looping
		
		$this->data['subtotal'] = $subtotal;
	    $this->data['totjumlah'] = $totjumlah;
		$this->data['totgetpoin'] = $totgetpoin;
	  
	    $tarifkurir = $this->dataShipping->getTarif($servisid,$negara,$propinsi,$kota,$kecamatan,$totberat,$minkilo,$tabeltarif,$detekkdpos,$namashipping);
	  
	    /*if($tabeldiskon != Null || $tabeldiskon != '') {
		  $zzdizkon = explode("::",$tabeldiskon);
		  $tabel = $zzdizkon[0];
		  $fieldambil = $zzdizkon[1];
          $where = " $zzdizkon[2]='".$servisid."' AND $zzdizkon[3]=1";
					
		  $dtdiskon = $this->Fungsi->fcaridata2($tabel,$fieldambil,$where);
		  $zdiskon = $dtdiskon[0] / 100;
		} else {*/
		  $zdiskon = 0;
	    //}
		
		$tarifkurir[1] = $tarifkurir[1] - ($tarifkurir[1]*$zdiskon);
	    $tarifkurir[4] = $tarifkurir[4] - ($tarifkurir[4]*$zdiskon);
		
		if($tarifkurir[1] > 0) {
	       $this->data['tarifkurir'] = $tarifkurir[1];
		   $this->data['satuantarifkurir'] = $tarifkurir[4];
	    } else {
	       $this->data['tarifkurir'] = $zhrgkurir;
		   $this->data['satuantarifkurir'] = 0;
	    }
		
	    $this->data['kurir'] = $namashipping;
	    $this->data['kurirservis'] = $servisid;
	    $this->data['nopesanan'] = $nopesan;
		
		$grandtot = $subtotal + $this->data['tarifkurir'];
		$this->data['pelangganid'] = $idmember;
		$this->data['nopesan'] = $nopesan;
		$this->data['tgl'] = date('Y-m-d H:i:s');
		/* mengupdate potongan poin */
		if($potpoinlama > 0 ) {
		  if($potpoinlama > $grandtot) {
		     $potpoinbaru = $potpoinlama - $grandtot;
		  } else {
		     $sisapoin = $this->Fungsi->fcaridata("_customer_point","cp_poin","cp_cust_id",$idmember);
		     $potpoinbaru = $sisapoin + $potpoinlama;
			 
	      }
		  
		  $this->data['totpoin'] = $potpoinbaru;
		 
		  
	      $cekpoinhistory = $this->model->checkPoinHistory($this->data,'OUT');
		  if($cekpoinhistory > -1 ){
		     $proses[] = $this->model->updateGetPoin($this->data,'OUT');
		  }
		  $this->data['totpoin'] = $potpoinbaru - $potpoinlama;
		  $proses[] = $this->model->updatePoinPelanggan($this->data);
		  $this->data['potpoinbaru'] = $potpoinbaru;
		} else {
		   $this->data['potpoinbaru'] = 0;
		}
	    /* end mengupdate potongan poin */
		
		/* mengupdate deposito */
		if($potdepositolama > 0 ) {
		    $grandtot = $subtotal + $this->data['tarifkurir'] - $potpoinbaru;
			if($potdepositolama > $grandtot) {
			   $potdepositobaru = $potdepositolama - $grandtot;
			} else {
			   $sisadeposito = $this->Fungsi->fcaridata("_customer_deposito","cd_deposito","cd_cust_id",$idmember);
			   $potdepositobaru = $sisadeposito + $potdepositolama;
			}
			$this->data['totdeposito'] = $potdepositobaru;
		 
		  
	        $cekdepositohistory = $this->dataCustomer->checkDepositoHistory($this->data,'OUT');
		    if($cekdepositohistory > -1 ){
		      $proses[] = $this->dataCustomer->updateDepositoHistory($this->data,'OUT');
		    }
		    $this->data['totdeposito'] = $potdepositobaru - $potdepositolama;
			$this->data['iddata'] = $idmember;
			$this->data['tglupdate'] = date('Y-m-d H:i:s');
		    $proses[] = $this->model->updateDeposito($this->data);
			$this->data['depositobaru'] = $potdepositobaru;
		} else {
		   $this->data['depositobaru']=0;
		}
		
		
		/* end mengupdate deposito */
		
		
		
		$proses[] = $this->model->UpdateOrder($this->data);
		//print_r($proses);
		if($this->model->prosesTransaksi($proses)) {
		   $pesan = 'sukses|Berhasil Menyimpan data';
		} else {
		   $pesan = 'gagal|Gagal Menyimpan data';
		}
		
		
		
		
	 }
	 
	 return $pesan;
	 
   }
   public function tampildata(){
	$this->page 	    = isset($_GET['page']) ? intval($_GET['page']) : 1;
	$this->rows			=  10;
	
	$result 			= array();
	$filter				= array();
	$where 				= '';
	
	$data['sortir']				= isset($_GET['sort']) ? $_GET['sort']:'';
	
	$data['status']		= isset($_GET['status']) ? $_GET['status']:'';
	$data['caridata']	= isset($_GET['datacari']) ? $_GET['datacari']:'';

	
	$result["total"] = 0;
	$result["rows"] = '';
	$this->offset = ($this->page-1)*$this->rows;

	$result["total"]   = $this->model->totalOrder($data);
	$result["rows"]    = $this->model->getOrder($this->offset,$this->rows,$data);
	$result["page"]    = $this->page; 
	$result["baris"]   = $this->rows;
	$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
	
	return $result;
  }
    
  public function dataOrderByID($noorder){
	return $this->model->getOrderByID($noorder);
  }
  
  public function dataOrderDetail($noorder){
	return $this->model->getOrderDetail($noorder);
  }
  
  public function dataOrderStatus($noorder){
	return $this->model->getOrderStatus($noorder);
  }
  
  public function dataOrderKonfirmasi($noorder){
	return $this->model->getOrderKonfirmasi($noorder);
  }
  
  public function dataOrderAlamat($noorder){
	return $this->model->getOrderAlamat($noorder);
  }
   public function dataOrderPoin($noorder,$customer){
	return $this->model->getOrderPoin($noorder,$customer);
  }
  public function getShippingbyName($nama){
     return $this->model->getShippingbyName($nama);
  }
  public function getServisbyId($tabel,$id){
     return $this->model->getServisbyId($tabel,$id);
  }
  public function getProdukOrderStokOption($idproduk,$idwarna,$idukuran,$nopesan){
    $data = array();
	$datawn = array();
	$datauk = array();
    /*
	$ukuran = $this->dataProduk->getProdukOption($idproduk,'ukuran');
	$uk = isset($ukurans[0]['id']) ? $ukurans[0]['id']:0;
	$warna = $this->dataProduk->getProdukWarnaByUkuran($idproduk,$uk);
	*/
	$jmlstok = $this->Fungsi->fcaridata('_produk','jml_stok','idproduk',$idproduk);
	$dataorderproduk =  $this->model->getProdukOrderOption($idproduk,$idwarna,$idukuran,$nopesan);
	if($dataorderproduk) {
	   foreach($dataorderproduk as $dtOprod) {
	      $datawn[] = $dtOprod['warna'];
		  $datauk[] = $dtOprod['ukuran'];
	   }
	}
	//$ukuran = $this->model->getProdOption($idproduk,$datauk,'ukuran');
	//$warna = $this->model->getProdOption($idproduk,$datauk,'warna');
	
	
  }
  public function getProdukOrderOption($idproduk,$idwarna,$idukuran,$nopesan) {
    return $this->model->getProdukOrderOption($idproduk,$idwarna,$idukuran,$nopesan);
  }
  public function generateinvoice($noorder) {
      
	  $prefix					= $this->Fungsi->fcaridata('_setting_toko','prefix_inv','','');
	  $kodeakhir 				= $this->Fungsi->fIdAkhir('_order',"CONVERT(RIGHT(no_invoice,5),SIGNED)");
  	  $kodenext					= sprintf('%05s', (int)$kodeakhir+1);
	  $this->data['invoice'] 	= $prefix.$kodenext;
	  $this->data['noorder']	= $noorder;
      $this->model->generateInvoice($this->data);
	  
	  return $this->data['invoice'];
  }
   function hapusdata(){
	$id = isset($_POST['id']) ? $_POST['id']:'';
	$dataId = explode(":",$id);
	$dataError=array();
	$modul = "hapus";
	$pesan = '';
	$cek = $this->Fungsi->cekHak(folder,"del",1);
	$stspending  = $this->Fungsi->fcaridata2('_setting','setting_value',"setting_key= 'config_orderstatus'");
	$pending	 = $stspending[0];
	$tgl	   = date('Y-m-d H:i:s');
	$proses    = array();
	if($cek) {
		$pesan =" Anda tidak mempunyai Akses untuk menghapus ";
	} else {
		foreach($dataId as $data){
			//$iddetail = $this->Fungsi->fcaridata('_order_detail','iddetail','pesanan_no',$data);
			$datast = $this->model->getQytOrder($data);
			$cekstatus = $this->Fungsi->fcaridata('_order','status_id','pesanan_no',$data);
			$dtorder = $this->Fungsi->fcaridata2('_order','dari_deposito,dari_poin,pelanggan_id','pesanan_no='.$data);
			$deposito = $dtorder[0];
			$poin = $dtorder[1];
			$reseller   = $dtorder[2];
			if($datast){
				foreach($datast as $dt){
				   if($cekstatus == $pending){
						if($dt['warna'] > 0 || $dt['ukuran'] > 0) {
							$proses[] = $this->model->updateStokOption($data,$dt['jml'],$dt['warna'],$dt['ukuran'],$dt['produk']);
						}
						$proses[] = $this->model->updateStok($dt['jml'],$dt['produk']);
						
				   }
				  
				   $proses[] = $this->model->hapusOrderDetailOption($dt['iddetail']);
				  
				}
			}
			
			if($cekstatus == $pending) {
				  $datas['deposito'] = $deposito;
				  $datas['iddata'] = $reseller;
				  $datas['tglupdate'] = $tgl;
				  $datas['tgl'] = $tgl;
				  $datas['totpoin'] = $poin;
				  $datas['pelangganid'] = $reseller;
				  $datas['nopesan'] = $data;
				  $proses[] = $this->dataCustomer->UpdateDeposito($datas);
				  //if($tabungan > 0){
				  $proses[] = $this->dataCustomer->DeleteDepositDetail($reseller,$data,'OUT');
				  
				  $proses[] = $this->model->updatePoinPelanggan($datas);
				  $proses[] = $this->model->deleteGetPoin($datas,'OUT');
				  //}
				  //echo $tabungan;
			}
			//}
		 
			$proses[] = $this->model->hapusOrder($data);
   		
			
			
		}
	}
	if($pesan!='') {
	    $pesan="gagal|".$pesan;
    } else {
	    $this->model->prosesTransaksi($proses);
	} 
	return $pesan;
	
  }
  function OrderEksekusi($masa_belanja,$order_status){
    $data = array();
	$data['tgl']		= date('Y-m-d H:i:s');
    $order = $this->model->getOrderEksekusi($masa_belanja,$order_status,$data['tgl']);
    $proses = array();
	$pesan = array();
	$pesans = 'tidak ada error';
	foreach($order as $data){
		$datast = $this->model->getQytOrder($data['pesanan_no']);
			if($datast){
				foreach($datast as $dt){
					if($dt['warna'] > 0 || $dt['ukuran'] > 0) {
						$proses[] = $this->model->updateStokOption($data,$dt['jml'],$dt['warna'],$dt['ukuran'],$dt['produk']);
					}
					$proses[] = $this->model->updateStok($dt['jml'],$dt['produk']);
					if(!$this->model->hapusOrderDetailOption($dt['iddetail'])) {
					   $proses[] = $this->model->hapusOrderDetailOption($dt['iddetail']);
					   $pesan []= 'SQL hapus order detail option salah';
					}
				}
				
			}
		    if(!$this->model->hapusOrder($data['pesanan_no'])) {
			   $proses[] = $this->model->hapusOrder($data['pesanan_no']);
			   $pesan[] = 'SQL hapus order salah';
			}
			
			
			
	}
	if(count($pesan) > 0){
	   $pesans = implode("<br>",$pesan);
	} else {
	   $this->model->prosesTransaksi($proses);
	}
	//tes = "SELECT cust_id,cust_kode,cust_nama, cust_grup,rs_hrgregister,cust_tglupdated,NOW() AS tglskarang, (cust_tglupdated + INTERVAL 3 DAY) AS tgl3hari FROM _reseller INNER JOIN _cust_grup ON _reseller.cust_grup = _reseller.cust_grup WHERE stsapprove='0' AND rs_hrgregister > 0 AND (cust_tglupdated + INTERVAL $masa_approve DAY) < '$tglupdate'";
	return $pesans;
  }
  
  //Cart
  public function addCart($data){
	  $pesan     = '';
	  $item 	 = $data['pid'];
      $qty  	 = $data['jml'];
	  $idmember  = $data['idmember'];
	  $kat       = $data['kategori'];
	  //print_r($data);
	 // echo $qty;
	 
	  if (!$data['option']) {
    	$key = (int)$item;
		
      } else {
	    $option = implode(",",$data['option']);
    	$key = (int)$item . ':' . $option;
		
      }
	  $key .= ':'.$kat;
      
	  if ((int)$qty && ((int)$qty > 0)) {
	    if (!isset($_SESSION['qtyadmincart'][$idmember][$item])) {
      		$_SESSION['qtyadmincart'][$idmember][$item] = (int)$qty;
			
    	} else {
      		$_SESSION['qtyadmincart'][$idmember][$item] += (int)$qty;
			
    	}
		
    	if (!isset($_SESSION['hsadmincart'][$idmember][$key])) {
      		$_SESSION['hsadmincart'][$idmember][$key] = (int)$qty;
    	} else {
      		$_SESSION['hsadmincart'][$idmember][$key] += (int)$qty;
    	}
		
		if (!isset($_SESSION['katadmincart'][$kat])) {
      		$_SESSION['katadmincart'][$idmember][$kat] = (int)$qty;
    	} else {
      		$_SESSION['katadmincart'][$idmember][$kat] += (int)$qty;
    	}
		
	  }
	  /*
	  $cekstok = $this->dataProduk->getOption($item,$data['option'][1],$data['option'][0]);
	  $cekstok['stok'] = isset($cekstok['stok']) ? $cekstok['stok']:0;
	  if($cekstok['stok'] > 0) {
	     $stok = $cekstok['stok'];
	  } else {
	     $stok = $data['stok'];
	  }
	  
	  if($stok < $_SESSION['hsadmincart'][$idmember][$key]) $pesan = 'gagal|Maaf, stok tinggal tersedia '.$stok.' item';
	  */
	  if($pesan == ''){
	     $miniCart = $this->showminiCart($_SESSION['hsadmincart'][$idmember],$data['tipe'],$idmember);
		 $totalitem = $miniCart['items'];
		 $cart = $miniCart['carts'];
		 
		 $jml = 0;
		 $total = 0;
		 foreach($cart as $c){
            $jml += $c['qty'];
			$total += $c['total'];
		 }
		 $pesan = 'sukses|Anda menambah '.$data['produk'].' kedalam keranjang belanja Anda|'.$jml.' item - '.$this->Fungsi->fFormatuang($total);
		 //$pesan = 'sukses|'.$option;
	  } else {
	     $_SESSION['qtyadmincart'][$idmember][$item] -= (int)$qty;
		 $_SESSION['hsadmincart'][$idmember][$key] -= (int)$qty;
		 if($_SESSION['qtyadmincart'][$idmember][$item]== 0) {
		    unset($_SESSION['qtyadmincart'][$idmember][$item]);
		 }
		 if($_SESSION['hsadmincart'][$idmember][$key] == 0) {
		    unset($_SESSION['hsadmincart'][$idmember][$key]);
		 }
		 if($_SESSION['katadmincart'][$idmember][$kat] == 0){
		    unset($_SESSION['katadmincart'][$idmember][$kat]);
		 }
	  }
	  return $pesan;
   }
   
   public function showminiCart($cartitem,$tipe,$idmember){
      $options = array();
	  $carts = array();
	  $i = 0;
	  $stoks = 0;
	  $jmlerror = 0;
	  $xx = 0;
	  $wheretipe = "cg_id='".$tipe."'";
	  $grupcust = $this->Fungsi->fcaridata2('_customer_grup','cg_min_beli,cg_min_beli_syarat',$wheretipe);
	  
	  $minbeli = $grupcust[0];
	  $syarat = $grupcust[1];
      foreach ($cartitem as $key => $quantity) {
		$product = explode(':', $key);
		$id = $product[0];
		$option = $product[1];
		$kat = $product[2];
		$ukuran = '';
		$warna = '';
		$options = explode(",",$option);
		
		if(!isset($_SESSION['ukradmincart'][$idmember][$id]))
   		    $_SESSION['ukradmincart'][$idmember][$id] = array();
		
		if(!isset($_SESSION['wrnadmincart'][$idmember][$id])) 
		     $_SESSION['wrnadmincart'][$idmember][$id] = array();
		
		$_SESSION['ukradmincart'][$idmember][$id][$i] = $options[0];
		$_SESSION['wrnadmincart'][$idmember][$id][$i] = $options[1];
		
		$jmlitem = (int)$_SESSION['qtyadmincart'][$idmember][$id];
		$jmlcart = $quantity;
		$xx = $xx + $jmlitem;
		$jmlitemkat = (int)$_SESSION['katadmincart'][$idmember][$kat];
		
		$prod = $this->dataProduk->dataProdukByID($id);
		//$prodDiskon = $this->dataProduk->getProdukDiskons($id,$tipe);
		//$getpoin = $this->dataProduk->getPoinProdukByPIDgCust($id,$tipe);
		$getpoin = $prod['poin']===null ? 0:$prod['poin'];
	    
		$wheredisk = "product_id='".$id."' AND customer_group_id = '".$tipe."'";
		$carihargadisk = $this->Fungsi->fcaridata2('_produk_diskons','harga',$wheredisk);
		if($carihargadisk) {
		   $hrgdiskon = $carihargadisk[0];
		} else {
		   $hrgdiskon = 0;
		}
		 
		$cariharga = $this->Fungsi->fcaridata2('_produk_harga','harga',$wheredisk);
		if($cariharga) {
		   $hrgjual = $cariharga[0];
		} else {
		   $hrgjual = 0;
		}
		
	    $hrgsatuan = $prod['hrg_jual'];
		$hrgdisksatuan = $prod['hrg_diskon'];
	    $stok       = $this->dataProduk->getOption($id,$options[1],$options[0]);
		$allstok    = $prod['jml_stok'];
		$stok_option = isset($stok['stok']) ? $stok['stok']:0; 
		$stoks = $allstok;
		$tambahanhrg = isset($stok['tambahan_harga']) ? $stok['tambahan_harga']:0;
		
		if($syarat == '1') {
		   $jmlsyarat = $jmlitemkat;
		} else  {
		   $jmlsyarat = $jmlitem;
		}
		
		if($minbeli < $jmlsyarat + 1) {
		   if($hrgdiskon > 0) {
		     $harga = $hrgdiskon;
		   } else {
		     $harga = $hrgjual;
		   }
		} else {
		   if($hrgdisksatuan > 0){
		      $harga = $hrgdisksatuan;
		   } else {
		      $harga = $hrgsatuan;
		   }
		}
	
		
		$harga = $harga + $tambahanhrg;
		
		$item_total = ((int)$harga) * (int)$jmlcart;
		$berat = ((int)$prod['berat_produk']) * (int)$jmlcart;
		
		if($stok_option < 1) {
		   $stoknya = $allstok;
		} else {
		   $stoknya = $stok_option;
		}
		
		if($stoknya < $jmlcart ) {
		   $pesan[] = 'Stok '.$prod['nama_produk'].' tersedia '.$stoks.' Pcs';
		   $jmlerror++;
		} 
		$carts[] = array(
			"product_id"	=> $id, 
			"product"		=> $prod['nama_produk'],
			"qty"			=> $jmlcart,
			"harga"			=> $harga,
			"hargasatuan"   => $hrgsatuan,
			"total"			=> $item_total,
			"satuanberat"	=> (int)$prod['berat_produk'],
			"berat"			=> $berat,
			"aliasurl"		=> $prod['alias_url'],
			"katid"         => $prod['idkategori'],
			"stok"			=> $stoks,
			"poin"          => $getpoin,
			"warna"			=> $options[1],
			"ukuran"	    => $options[0]
		);				

		$i++;
	  }
	  $carts = array(
		'carts'	=> $carts,
		'items'	=> count($_SESSION['hsadmincart'][$idmember]),
		'idmember' => $idmember
	  );
	  //echo $xx;
	  return $carts;
   }
    public function updateCart(){
       $totbeli = array();
	   $pesan = array();
	   $hasil = '';
       if(isset($_POST['jumlah'])){
	      foreach ($_POST['jumlah'] as $key => $value) {
		     $data = explode("::",$key);
			 $pid = $data[0];
			 
			 $options = unserialize(base64_decode($data[1]));
			 $option = implode(",",$options);
			 if(!$options){
		        $keys = $pid;
			 } else {
				$keys = $pid.':'.$option;
			 }
			 $qty = $value;
			 $idmember = $data[2];
			 $produk  = $this->dataProduk->dataProdukByID($pid);
			 $stokall = $produk['jml_stok'];
			 if ((int)$qty && ((int)$qty > 0)) {
			    $cekstok = $this->dataProduk->getOption($pid,$options[1],$options[0]);
				if($cekstok['stok'] > 0) {
					$stok = $cekstok['stok'];
				} else {
					$stok = $stokall;
				}
				if(!isset($totbeli[$pid]))  $totbeli[$pid] = $qty;
				else $totbeli[$pid] += $qty;
				
				if($stok < $totbeli[$pid]) $pesan[] = 'stok '.$produk['nama_produk'].' tinggal tersedia '.$stok.' item';	
			 } else {
			    if($options[1] != '')
				    $zwarna = '->'.$this->Fungsi->fcaridata('_warna','warna','idwarna',$options[1]).' ';
				else
				    $zwarna = '';
			   
			    if($options[0] != '')
				    $zukuran = '->'.$this->Fungsi->fcaridata('_ukuran','ukuran','idukuran',$options[1]).' ';
				else
				    $zukuran = '';
					
			    $pesan[]= 'Produk '.$produk['nama_produk'].$zwarna.$zukuran.', silahkan masukkan jumlah';
			 }
			 if(!$pesan){
			    $_SESSION['hsadmincart'][$idmember][$keys] = $qty;
				$_SESSION['qtyadmincart'][$idmember][$pid] = $totbeli[$pid];
				$hasil = 'sukses';
			 } else {
			    $hasil = implode("<br>",$pesan);
				$hasil = 'gagal|'.$hasil;
			 }

		  }
	   } else {
	      $hasil = 'gagal|Tidak ada data';
	   }
	   return $hasil;
   }
   
   public function delCart($data){
      
      if($data != ''){
	     $data = explode("::",$data);
		 $pid = $data[0];
		 $options = unserialize(base64_decode($data[1]));
		 $option = implode(",",$options);
		 $jml    = $data[3];
		 $idmember = $data[2];
		 if(!$options){
		    $key = $pid;
		 } else {
		    $key = $pid.':'.$option;
		 }
		 unset($_SESSION['hsadmincart'][$idmember][$key]);
		 //$_SESSION['qtyadmincart'][$idmember][$pid]--;
		 $_SESSION['qtyadmincart'][$idmember][$pid] = $_SESSION['qtyadmincart'][$idmember][$pid]- $jml;
        /*if(count($_SESSION['qtyadmincart'][$idmember])==0){
		     unset($_SESSION['qtyadmincart'][$idmember][$pid]);
		 }
	    */	 
		 if($_SESSION['qtyadmincart'][$idmember][$item]== 0) {
		    unset($_SESSION['qtyadmincart'][$idmember][$item]);
		 }
		 if($_SESSION['hsadmincart'][$idmember][$key] == 0) {
		    unset($_SESSION['hsadmincart'][$idmember][$key]);
		 }
		 if($_SESSION['katadmincart'][$idmember][$kat] == 0){
		    unset($_SESSION['katadmincart'][$idmember][$kat]);
		 }
		 
		// echo ' session jumlah nya '.$_SESSION['qtyadmincart'][$idmember][$pid].' ';
	  }
	  //$data = unserialize($data);
	  return 'sukses';
   }
   
   public function buyCart($orderstatus){
     $pesan = array();
	 $hasil = array();
	 $result = array();
	 $proses = array();
     //if(isset($idmember)) {
	   
	  /* Petugas */
      //$this->data['userid']	   = isset($_SESSION["idlogin"]) ? $_SESSION["idlogin"]:'';
	  $this->data['userid']	    = isset($_POST['user_idlogin']) ? $_POST['user_idlogin']:'';
	 
      //Penerima
      
	  $this->data['nama'] 		= isset($_POST['pnama']) ? mysql_real_escape_string($_POST['pnama']):'';
	  $this->data['telp'] 		= isset($_POST['pnotelp']) ? $_POST['pnotelp']:'';
	  $this->data['hp']   		= isset($_POST['pnohp']) ? $_POST['pnohp']:'';
	  $this->data['alamat']		= isset($_POST['palamat']) ? mysql_real_escape_string($_POST['palamat']):'';
	  $this->data['negara']		= isset($_POST['pnegara']) ? $_POST['pnegara']:'';
	  $this->data['propinsi']	= isset($_POST['ppropinsi']) ? $_POST['ppropinsi']:'';
	  $this->data['kabupaten'] 	= isset($_POST['pkabupaten']) ? $_POST['pkabupaten']:'';
	  $this->data['kecamatan']	= isset($_POST['pkecamatan']) ? $_POST['pkecamatan']:'';
	  $this->data['kelurahan']	= isset($_POST['pkelurahan']) ? $_POST['pkelurahan']:'';
	  $this->data['kodepos']	= isset($_POST['pkodepos']) ? $_POST['pkodepos']:'';
	  
	  //Pengirim
	  $this->data['idmember']			= isset($_POST['idreseller']) ? $_POST['idreseller']:'';
	  $this->data['kdreseller']			= isset($_POST['kdreseller']) ? $_POST['kdreseller']:'';
	  $this->data['namapengirims'] 		= isset($_POST['namareseller']) ? mysql_real_escape_string($_POST['namareseller']):'';
	  $this->data['telppengirims'] 		= isset($_POST['notelp']) ?$_POST['notelp'] : '';
	  $this->data['hppengirims'] 		= isset($_POST['nohp']) ? $_POST['nohp']: '';
	  $this->data['alamatpengirims'] 	= isset($_POST['alamat']) ? mysql_real_escape_string($_POST['alamat']):'';
	  $this->data['negarapengirims'] 	= isset($_POST['idnegara']) ? $_POST['idnegara']:'0';
	  $this->data['propinsipengirims'] 	= isset($_POST['idpropinsi']) ? $_POST['idpropinsi']:'0';
	  $this->data['kabupatenpengirims'] = isset($_POST['idkabupaten']) ? $_POST['idkabupaten']:'0';
	  $this->data['kecamatanpengirims'] = isset($_POST['idkecamatan']) ? $_POST['idkecamatan']:'0';
	  $this->data['kelurahanpengirims'] = isset($_POST['kelurahan']) ? $_POST['kelurahan']:'0';
	  $this->data['kodepospengirims'] 	= isset($_POST['kodepos']) ? $_POST['kodepos']:'';
	  $this->data['emailpengirims'] 	= isset($_POST['email']) ? $_POST['email']:'';
	  
	  $this->data['cust_grup'] 		= isset($_POST['jenis']) ? $_POST['jenis'] : '';
	  
	  $zfield 			= 'rs_dropship';
	  $ztabel 			= '_reseller INNER JOIN _cust_grup ON _reseller.cust_grup = _cust_grup.rs_grupid';
	  $zwhere 			= "cust_id = '". $this->data['idmember']."'";
	  $zreseller 		= $this->Fungsi->fcaridata2($ztabel,$zfield,$zwhere);
	  
	  $dropship		    = $zreseller[0];
	  
	  
	  //Infaq
	  $this->data['infaq'] = isset($_POST['infaq']) ? $_POST['infaq']:0;
	  
	  //Deposit
	  $this->data['deposit'] = isset($_POST['deposit']) ? $_POST['deposit']:0;
	  
	  $servis 		= isset($_POST['serviskurir']) ? $_POST['serviskurir']:'';
	  $zzhrgkurir  	= isset($_POST['zhrgkurir']) ? $_POST['zhrgkurir']:0;
	  
	  $zdata		= explode(":",$servis);
	  $idservis		= $zdata[0];
	  $idkurir		= $zdata[1];
	  $kurir		= $this->dataShipping->getShippingbyName($idkurir);
	  $namashipping = $kurir['nama_shipping'];
	  $tabelservis	= $kurir['tabel_servis'];
	  $tabeltarif	= $kurir['tabel_tarif'];
	  $tabeldiskon	= $kurir['tabel_diskon'];
	  $logoshipping	= $kurir['logo'];
	  $detekkdpos   = $kurir['detek_kdpos'];
			
	  $servisdata   = $this->dataShipping->getServisbyId($tabelservis,$idservis);
	  $namaservis	= $servisdata[1];
	  $servisid		= $servisdata[0];
	  $minkilo		= $servisdata[3];
	  
	  $this->data['iddetail'] 	= (int)$this->Fungsi->fIdAkhir('_order_detail','iddetail') + 1;
	  $kodeakhir 	= $this->Fungsi->fIdAkhir('_order','CONVERT(pesanan_no,SIGNED)');
	  $this->data['nopesanan'] = sprintf('%08s', $kodeakhir+1);
	  
	  $keranjang 	= $this->showminiCart($_SESSION['hsadmincart'][$this->data['idmember']],$this->data['cust_grup'],$this->data['idmember']);
	  $totalitem 	= $keranjang['items'];
	  $cart 		= $keranjang['carts'];
	  $subtotal 	= 0;
	  $i 			= 0;
	  $totberat 	= 0;
	  $totjumlah	= 0;
	  $tabel 		= '<table style="border-collapse: collapse;width: 100%;margin-bottom: 15px;border-top: 1px solid #DDDDDD;border-left: 1px solid #DDDDDD;border-right: 1px solid #DDDDDD;">';
	  $tabel	   .= '<thead><tr><td style="font-weight: bold;border-bottom: 1px solid #DDDDDD;text-align: center;"></td>';
	  $tabel	   .= '<td style="font-weight: bold;border-bottom: 1px solid #DDDDDD;text-align: left;">Nama Produk</td>';
	  $tabel	   .= '<td style="font-weight: bold;border-bottom: 1px solid #DDDDDD;text-align: center;">Jumlah</td>';
	  $tabel	   .= '<td style="font-weight: bold;border-bottom: 1px solid #DDDDDD;text-align: right;">Berat</td>';
	  $tabel	   .= '<td style="font-weight: bold;border-bottom: 1px solid #DDDDDD;text-align: right;">Harga</td>';
      $tabel       .= '<td style="font-weight: bold;border-bottom: 1px solid #DDDDDD;text-align: right;">Sub Total</td></tr></thead><tbody>';
	  
	  foreach($cart as $c){
		$this->data['pid']   	= $c['product_id'];
		$this->data['jml'] 	 	= $c['qty'];
		$this->data['berat'] 	= $c['berat'];
		$this->data['harga'] 	= $c['harga'];
		$this->data['total'] 	= $c['total'];
		
		$this->data['idwarna']  = $_SESSION['wrnadmincart'][$this->data['idmember']][$this->data['pid']][$i];
		$this->data['idukuran'] = $_SESSION['ukradmincart'][$this->data['idmember']][$this->data['pid']][$i];
		if($this->data['idwarna'] !='') $warna	= $this->Fungsi->fcaridata('_warna','warna','idwarna',$this->data['idwarna']);
		else $warna = '';
		
		if($this->data['idukuran'] != '') $ukuran = $this->Fungsi->fcaridata('_ukuran','ukuran','idukuran',$this->data['idukuran']);
		else $ukuran = '';
		
		//$gbr 		 			= $c['gbr'];
		$nama_produk 			= $c['product'];
		$where 					= "idproduk='".$this->data['pid']."'";
		$prods					= $this->Fungsi->fcaridata2('_produk','hrg_jual,hrg_beli',$where);
		$this->data['satuan']	= $prods[0];
		$this->data['hrgbeli']	= $prods[1];
		//$this->data['satuan'] 	= $this->Fungsi->fcaridata('_produk','hrg_jual','idproduk',$this->data['pid']);
		//$this->data['hrgbeli'] 	= $this->Fungsi->fcaridata('_produk','hrg_beli','idproduk',$this->data['pid']);
		if(!$this->model->SimpanOrderDetail($this->data)) {
		   $proses[] = $this->model->SimpanOrderDetail($this->data);
		   $pesan[] = 'error simpan detail';
		} 
		
		if($this->data['idwarna'] != '' || $this->data['idwarna'] != '0' || $this->data['idukuran'] != '' || $this->data['idukuran'] != '0') {
			if(!$this->model->SimpanOrderDetailOption($this->data)) {
			   $proses[] = $this->model->SimpanOrderDetailOption($this->data);
			   $pesan[] = 'error simpan detail option' ;
			}
			if(!$this->model->UpdateStokOptionberKurang($this->data)) {
			   $proses[] = $this->model->UpdateStokOptionberKurang($this->data);
			   $pesan[] = 'error update stok option' ;
			}
		}
		
		if(!$this->model->UpdateStokberKurang($this->data)) {
		   $proses[] = $this->model->UpdateStokberKurang($this->data);
		   $pesan[] = 'error update stok' ;
		}
		
		$subtotal	+= $this->data['total'];
		$totberat   += $this->data['berat'];
		$totjumlah  += (int)$c['qty'];
		
		//$tabel		.= '<tr><td style="vertical-align: top;border-bottom: 1px solid #DDDDDD;font-size:11px;text-align: center;"><img src="http://hijabsupplier.com'.URL_IMAGE.'_small/small_'.$gbr.'"></td>';
		//$tabel		.= '<td style="vertical-align: top;border-bottom: 1px solid #DDDDDD;font-size:11px;text-align: left;">'.$nama_produk.'<br>'.$warna.'<br>'.$ukuran.'</td>';
		//$tabel		.= '<td style="vertical-align: top;border-bottom: 1px solid #DDDDDD;font-size:11px;text-align: right;">'.$this->data['jml'].'</td>';
		//$tabel		.= '<td style="vertical-align: top;border-bottom: 1px solid #DDDDDD;font-size:11px;text-align: right;">'. $this->data['berat'].'Gram</td>';
		//$tabel		.= '<td style="vertical-align: top;border-bottom: 1px solid #DDDDDD;font-size:11px;text-align: right;">'. $this->Fungsi->fFormatuang($this->data['harga']).'</td>';
		//$tabel		.= '<td style="vertical-align: top;border-bottom: 1px solid #DDDDDD;font-size:11px;text-align: right;">'. $this->Fungsi->fFormatuang($this->data['total']).'</td>';
		//$tabel		.= '</tr>';
		
		$this->data['iddetail']++;
		$i++;
		
	  }
		  
	  
	  $this->data['subtotal'] = $subtotal;
	  $this->data['totjumlah'] = $totjumlah;
	  
	  $tarifkurir = $this->dataShipping->getTarif($servisid,$this->data['negara'],$this->data['propinsi'],$this->data['kabupaten'],$this->data['kecamatan'],$totberat,$minkilo,$tabeltarif,$detekkdpos,$namashipping);
	  if($tabeldiskon != Null || $tabeldiskon != '') {
		  $zzdizkon = explode("::",$tabeldiskon);
		  $tabel = $zzdizkon[0];
		  $fieldambil = $zzdizkon[1];
          $where = " $zzdizkon[2]='".$servisid."' AND $zzdizkon[3]=1";
					
		  $dtdiskon = $this->Fungsi->fcaridata2($tabel,$fieldambil,$where);
		  $zdiskon = $dtdiskon[0] / 100;
	  } else {
		  $zdiskon = 0;
	  }
	 
	 /* $tabel = "_servis_jne_diskon";
	  $fieldambil = 'jml_disk';
	  $where = " servis_id='".$servisid."' AND stsdisk=1";
	  $dtdiskon = $this->Fungsi->fcaridata2($tabel,$fieldambil,$where);
	  $zdiskon = $dtdiskon[0] / 100;
	  
	  */
	
	  //if($this->data['nama'] != $this->data['namapengirims'] && $this->data['alamat'] != $this->data['alamatpengirims'] && $this->data['propinsi'] != $this->data['propinsipengirims'] && $this->data['kabupaten'] != $this->data['kabupatenpengirims'] && $this->data['kecamatan'] != $this->data['kecamatanpengirims'] && $dropship=='1') {
	//	$zdiskon = 0;
	  //} else {
	//	$zdiskon = $dtdiskon[0] / 100;
	  //}
					
	  
	  $tarifkurir[1] = $tarifkurir[1] - ($tarifkurir[1]*$zdiskon);
	  $tarifkurir[4] = $tarifkurir[4] - ($tarifkurir[4]*$zdiskon);
	  if($tarifkurir[1] > 0) {
	     $this->data['tarifkurir'] = $tarifkurir[1];
		 $this->data['satuantarifkurir'] = $tarifkurir[4];
	  } else {
	     $this->data['tarifkurir'] = $zzhrgkurir;
		 $this->data['satuantarifkurir'] = 0;
	  }
	  
	  $this->data['kurir'] = $namashipping;
	  $this->data['kurirservis'] = $servisid;
	  $this->data['orderstatus'] = $orderstatus;
	  //$this->data['idmember'] = $idmember;
	  $this->data['tgltrans'] = date('Y-m-d H:i:s');
	  $this->data['bayaregis']	= $this->Fungsi->fcaridata2('_cust_invoice','biaya',"stsbayar='0' AND idmember='".$this->data['idmember']."'");
	  //$this->data['bayaregis']	= $this->fcaridata2('_cust_invoice','biaya',"stsbayar='0' AND idmember='".$this->data['idmember']."' AND member_grup='".$this->data['cust_grup']."'");
	  
	  if(!$this->model->SimpanOrder($this->data)) {
	     $proses[] = $this->model->SimpanOrder($this->data);
	     $pesan[] = 'error simpan order';
		 
      }
	  if(!$this->model->SimpanOrderPenerima($this->data)) {
	      $proses[] = $this->model->SimpanOrderPenerima($this->data);
    	  $pesan[] = 'error simpan order penerima';
	  }
	  if(!$this->model->SimpanOrderPengirim($this->data)) {
	     $proses[] = $this->model->SimpanOrderPengirim($this->data);
	     $pesan[] = 'error simpan order pengirim';
	  }
	  if(!$this->model->SimpanOrderStatus($this->data)) {
	     $proses[] = $this->model->SimpanOrderStatus($this->data);
	     $pesan[] = 'error simpan order status';
	  }
	  
	  /* tambahan untuk intensif bonus admin input */
	  $zfieldbns 			    = 'intensif_per_order,intensif_batas';
	  $ztabelbns 			    = '_setting_toko';
	  $jmlbonus					= $this->Fungsi->fcaridata2($ztabelbns,$zfieldbns,"1=1");
	  //$this->data['jmlbonus'] = $this->Fungsi->fcaridata($ztabelbns,$zfieldbns,'1','1');
	  $this->data['jmlbonus']   = $jmlbonus[0];
      $this->data['btsbonus']   = $jmlbonus[1];
	  
	  if(!$this->model->SimpanIntensif($this->data)) {
	     $proses[] = $this->model->SimpanIntensif($this->data);
	     $pesan[] = 'error simpan intensif bonus';
	   }
	  /* @end intensif bonus admin input */
	  
	  if((int)$this->data['deposit'] > 0){
		if(!$this->model->UpdateDepositBerkurang($this->data)) {
		   $proses[] = $this->model->UpdateDepositBerkurang($this->data);
		   $pesan[] = 'error update deposit';
		}
		if(!$this->model->InsertDepositDetail($this->data)) {
		   $proses[] = $this->model->InsertDepositDetail($this->data);
		   $pesan[] = 'error insert deposit detail';
		}
	  }
	  $result['idmember'] = $this->data['idmember'];
	  $result['nama']	= $this->data['namapengirims'];
	  $result['totalbelanja'] = ((int)$this->data['subtotal'] + (int)$this->data['tarifkurir']+(int)$this->data['bayaregis'][0]+(int)$this->data['infaq']) - (int)$this->data['deposit'];
	  $result['noorder'] = $this->data['nopesanan'];
	  $result['kdreseller'] = $this->data['kdreseller'];
	  //$result['sukseskonfirm'] = 'ya';
      //print_r($pesan);
	  if(count($pesan)==0) {
	      //$hasil = "sukses";
		  $this->model->prosesTransaksi($proses);
		  $hasil['sts'] = "sukses";
		  $hasil['data'] = $result;
	  } else {
	      $hasil = implode("<br>",$pesan);
		  //$hasil = 'gagal|'.$hasil;
		  $hasil['sts'] = "gagal";
		  $hasil['data'] = implode("\n",$pesan);
	  }
	  return $hasil;
	  //exit;
	 
   }
   
	public function editKurir(){
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data = [];
			foreach ($_POST as $key => $value) {
				$data["$key"]	= isset($_POST["$key"]) ? $value : '';
			}
			if($this->validasisimpankurir($data)){
				$dataservis 	= $this->dataShipping->getServisByIdserv($data);
				$datashipping 	= $this->dataShipping->getShippingByIdServ($data);
				
				$data['kurir'] 	= $datashipping['shipping_kode'];
				$data['kurir_perkilo'] = isset($dataservis['hrg_perkilo']) ? $dataservis['hrg_perkilo'] : 0;
				
				$totberat = (int)$data['totberat'] / 1000;
				if($totberat < 1) $totberat = 1;
				$jarakkoma = 0;
				if($totberat > 1) {
					$berat = floor($totberat);
					$jarakkoma = $totberat - $berat;
				}
				$batas = isset($dataservis['shipping_bataskoma']) ? $dataservis['shipping_bataskoma'] : 0;
				if($datashipping['shipping_konfirmadmin'] == '0') {
					if($jarakkoma > $batas) $totberat = ceil($totberat);
					else $totberat = floor($totberat);
					$tarif = $totberat * $data['kurir_perkilo'];
					$data['tarifkurir'] = $tarif;
					$data['konfirm_admin'] = '0';
				} else {
					if($data['tarifkurir'] != '' && $data['tarifkurir'] != 0) {
						$data['konfirm_admin'] = '0';
					} else {
						$data['konfirm_admin'] = '1';
						$data['tarifkurir'] = 0;
					}
					
				}
				
				$simpan = $this->model->simpanEditKurir($data);
				if($simpan) {
					$status = 'success';
					$pesan = 'Berhasil mengubah tarif kurir';
				} else {
					$status = 'error';
					$pesan = 'Proses mengubah tarif kurir gagal';
				}
			} else {
				$status = 'error';
				$pesan = implode("<br>",$this->error);
			}
		} else {
			$status = 'error';
			$pesan = 'Data tidak valid';
		}
		echo json_encode(array("status"=>$status,"result"=>$pesan));
	  
	}
	
	private function validasisimpankurir($data){
		if($data['nopesanan'] == '' || !$this->model->cekOrder($data['nopesanan'])) {
			$this->error[] = 'No. Pesanan tidak valid';
		}
		if($data['serviskurir'] == '' && $data['serviskurir'] == '0') {
			$this->error[] = 'Pilih Servis Kurir';
		}
		if($data['propinsi_penerima'] == '' && $data['propinsi_penerima'] == '0') {
			$this->error[] = 'Propinsi tidak ada';
		}
		if($data['kabupaten_penerima'] == '' && $data['kabupaten_penerima'] == '0') {
			$this->error[] = 'Kotamadya/Kabupaten tidak ada';
		}
		if($data['kecamatan_penerima'] == '' && $data['kecamatan_penerima'] == '0') {
			$this->error[] = 'Kecamatan tidak ada';
		}
		if(count($this->error) > 0) {
			return false;
		} else {
			return true;
		}
	}
	
   public function dataOrderNolKurir(){
      return $this->model->getOrderNolKurir();
   }
   public function getTotalOrderPending($status) {
      return $this->model->getTotalOrderPending($status);
   }
	public function simpanstatusorder(){
		$data = [];
		$pesan = '';
		$status = '';
		foreach ($_POST as $key => $value) {
			$data["$key"]	= isset($_POST["$key"]) ? $value : '';
		}
		$data['kirimmailsudahbayar'] = isset($data['kirimmailsudahbayar']) ? $data['kirimmailsudahbayar'] : '';
		$data['tgl']         = date('Y-m-d H:i:s');
		$data['ipdata'] = $this->Fungsi->get_client_ip();
		$data['simpanstatusorder'] = false;
		if($data['orderstatus'] != $data['stsnow']) {
		
			if($data['orderstatus'] != '' && $data['orderstatus'] != '0' && $data['nopesanan']!= '') {
				$data['simpanstatusorder'] = true;
				//$proses[] = $this->model->simpanStatusOrder($data);
			  
			} else {
			   $error = true;
			   $data['simpanstatusorder'] = false;
			}
	
		}
		$data['konfirmasiorder'] = '';
		if($data['orderstatus'] == $data['stskonfirm']) {
			if($data['modekonfirm']=='inputkonfirm') {
				
				//$proses[] = $this->model->addKonfirmasiOrder($this->data);
				$data['konfirmasiorder'] = 'add';
			} else {
				$data['konfirmasiorder'] = 'edit';
				//$proses[] = $this->model->editKonfirmasiOrder($this->data);
			  
			}
		}
		//$proses[] = $this->model->updateOrderStatus($this->data);
		$checkpoin = $this->model->checkPoinHistory($data,'IN');
		$data['simpangetpoin'] = '';
		//$data['insertgetpoin'] = false;
		$statusgetpoin = explode("::",$data['stsgetpoin']);
		if(in_array($data['orderstatus'],$statusgetpoin)) {
			if($checkpoin > -1){
				//$proses[] = $this->model->updateGetPoin($this->data,'IN');
				$data['simpangetpoin'] = 'update';
				$data['totpoindapat'] = $checkpoin - $data['totpoindapat'];
			} else {
				if($data['totpoindapat'] > 0 ) {
					//$proses[] = $this->model->insertGetPoin($this->data,'IN');
					$data['simpangetpoin'] = 'insert';
				}
			}
		} 
		$cekcustomerpoin = $this->model->checkCustomerPoin($data['pelangganid']);
		$data['customerpoin'] = '';
		if($cekcustomerpoin > 0) {
			$data['customerpoin'] = 'update';
		} else {
			$data['customerpoin'] = 'insert';
		}
		//$proses[] = $this->model->updatePoinPelanggan($this->data);
		$simpan = $this->model->simpaneditstatus($data);
		$data['kirimmailship'] = isset($data['kirimmailship']) ? $data['kirimmailship'] : '';
		if($simpan['status'] == 'success'){
			$status = 'success';
			$pesan = 'Berhasil mengubah status order';
			if($data['stsshipping'] == $data['orderstatus']) {
				if($data['kirimmailship'] == '1') {
					if(!$this->kirimInvoice($data)) {
						$status = 'error';
						$pesan = 'Kirim email tidak berhasil';
					} 
				}
			}
			
			if($data['stssudahbayar'] == $data['orderstatus']) {
			   if($data['kirimmailsudahbayar'] == '1') {
				if(!$this->kirimNotifBayar($this->data)) {
					$status = 'error';
					$pesan = 'Kirim email tidak berhasil';
				} 
			  }
			}
			
		} else {
			$status = 'error';
			$pesan = 'Gagal Menyimpan Status Order';
		}
		
		echo json_encode(array("status"=>$status,"result"=>$pesan));
	}
   
   public function kirimNotifBayar($data) {
      $this->kirimemail = new PHPMailer();
	  $pelangganid = $data['pelangganid'];
	  $pesanan_no = $data['nopesanan'];
	  $wherecust = "cust_id='".$pelangganid."'";
	  $datapelanggan = $this->Fungsi->fcaridata2('_customer','cust_nama,cust_email',$wherecust);
	  $datakonfirmasi = $this->dataOrderKonfirmasi($pesanan_no);
	  
	  $cekbanktujuan = $this->Fungsi->fcaridata2("_bank_rekening INNER JOIN _bank ON _bank_rekening.bank_id = _bank.bank_id","rekening_atasnama,rekening_no,rekening_cabang,bank_nama ","_bank_rekening.bank_id = '".$datakonfirmasi['bank_rek_tujuan']."'");
	  
	  $message   = $this->Fungsi->fcaridata('_setting','setting_value','setting_key','config_infosudahbayar');
	  
	  $from      = $this->Fungsi->fcaridata('_setting','setting_value','setting_key','config_emailnotif');
	  $from_name = $this->Fungsi->fcaridata('_setting','setting_value','setting_key','config_namatoko');
	  $subject   = 'INFO STATUS PEMBAYARAN '.sprintf('%06s',$pesanan_no);
	  $to = $datapelanggan[1];
	  $namapelanggan = $datapelanggan[0];
	  
	  /*
	  $detailpembayaran  = '<h3>BANK ASAL</h3>';
	  $detailpembayaran .= '<table>';
	  $detailpembayaran .= '<tr><td>NAMA BANK</td><td> : </td><td>'.$datakonfirmasi['bank_dari'].'</td></tr>';
	  $detailpembayaran .= '<tr><td>NO. REK</td><td> : </td><td>'.$datakonfirmasi['bank_rek_dari'].'</td></tr>';
	  $detailpembayaran .= '<tr><td>ATAS NAMA</td><td> : </td><td>'.$datakonfirmasi['bank_atasnama_dari'].'</td></tr>';
	  $detailpembayaran .= '</table>';
	  $detailpembayaran .= '<h3>BANK TUJUAN</h3>';
	  $detailpembayaran .= '<table>';
	  $detailpembayaran .= '<tr><td>NAMA BANK</td><td> : </td><td>'.$cekbanktujuan[3].'</td></tr>';
	  $detailpembayaran .= '<tr><td>NO. REK</td><td> : </td><td>'.$cekbanktujuan[1].'</td></tr>';
	  $detailpembayaran .= '<tr><td>ATAS NAMA</td><td> : </td><td>'.$cekbanktujuan[0].'</td></tr>';
	  $detailpembayaran .= '</table>';
	  */
	  $message   = str_replace("[PELANGGAN]",$namapelanggan,$message);
	  $message   = str_replace("[No Order]", sprintf('%06s',$pesanan_no),$message);
	  $message   = str_replace("[NAMA BANK DARI]",$datakonfirmasi['bank_dari'],$message);
	  $message   = str_replace("[NO REK DARI]",$datakonfirmasi['bank_rek_dari'],$message);
	  $message   = str_replace("[ATASNAMA DARI]",$datakonfirmasi['bank_atasnama_dari'],$message);
	  $message   = str_replace("[NAMA BANK TUJUAN]",$cekbanktujuan[3],$message);
	  $message   = str_replace("[NO REK TUJUAN]",$cekbanktujuan[1],$message);
	  $message   = str_replace("[ATASNAMA TUJUAN]",$cekbanktujuan[0],$message);
	  
	  $this->kirimemail->IsHTML(true);
	  $this->kirimemail->SetFrom($from, $from_name);
	  $this->kirimemail->Subject = $subject;
	  $this->kirimemail->WordWrap = 50;
	  $this->kirimemail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	  $this->kirimemail->MsgHTML($message);
	  $this->kirimemail->CharSet="UTF-8";
	  $this->kirimemail->AddAddress($to,$namapelanggan);
	  
	  if($this->kirimemail->Send()) {
	     return true;
	  } else {
	     return false;
	  } 
	  
   }
   
	public function kirimInvoice($data) {
		$this->kirimemail = new PHPMailer();
		$nopesan = $data['nopesanan'];
		$noawb = $data['noawb'];
		$tglkirim = $data['tglkirim'];
		$pelangganid = $data['pelangganid'];
		$namashipping = $data['namashipping'];
		$from      = $this->Fungsi->fcaridata('_setting','setting_value','setting_key','config_emailnotif');
	
	  
		$from_name = $this->Fungsi->fcaridata('_setting','setting_value','setting_key','config_namatoko');
	  
		$subject   = 'INFO PENGIRIMAN '.sprintf('%06s',$nopesan);
		$message   = $this->Fungsi->fcaridata('_setting','setting_value','setting_key','config_infoshipping');
	  
		$wherecust = "cust_id='".$pelangganid."'";
		$datapelanggan = $this->Fungsi->fcaridata2('_customer','cust_nama,cust_email',$wherecust);
	  
		$to = $datapelanggan['cust_email'];
		$namapelanggan = $datapelanggan['cust_nama'];
	  
		$message   = str_replace("[PELANGGAN]",$namapelanggan,$message);
		$message   = str_replace("[No Order]", sprintf('%06s',$nopesan),$message);
		$message   = str_replace("[Kurir]", $namashipping,$message);
		$message   = str_replace("[Tgl Kirim]", $tglkirim,$message);
		$message   = str_replace("[No Awb]", $noawb,$message);
	  
		$this->kirimemail->IsHTML(true);
		$this->kirimemail->SetFrom($from, $from_name);
		$this->kirimemail->Subject = $subject;
		$this->kirimemail->WordWrap = 50;
		$this->kirimemail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$this->kirimemail->MsgHTML($message);
		$this->kirimemail->CharSet="UTF-8";
		$this->kirimemail->AddAddress($to,$namapelanggan);
		$attachinvoice = $this->attachInvoice($data);
		$namareport = "invoice".$nopesan.".pdf";
		$this->kirimemail->AddAttachment($attachinvoice, $namareport);
		if($this->kirimemail->Send()) {
			if(file_exists(DIR_INCLUDE.$namareport)) {
				unlink(DIR_INCLUDE.$namareport);
			}
			return true;
		} else {
		 
			return false;
		} 
	  
   }
   
   
	public function attachInvoice($data){
		//$this->data['nopesan'] = $data['nopesanan'];
		$order = $this->model->getOrderByID($data['nopesanan']);
	   //untuk report
		$data['tglorder'] = $data['tglorder'];
		$data['nmreseller'] = $data['nmreseller'];
		$data['nmgrpreseller'] = $data['nmgrpreseller'];
		$data['alamatreport'] = $data['alamatreport'];
	  
		$datadetail 	= $this->model->getOrderDetail($data['nopesanan']);
		
	
		$data['namaservis'] = $data['servis'];
		$data['detailproduk'] = $datadetail;
		$data['order'] = $order;
	  
		return $this->cetakInvoicePDF($tdata,'attach');
	  
	}
	
	
	
	public function cetakInvoicePDFBackup($data,$jenis){
	
		$tabel = "_setting";
		$fieldambil = "setting_key,setting_value";
		$where = "setting_key='config_namatoko' OR setting_key='config_alamattoko' OR setting_key='config_alamatsite'";
		$toko = $this->Fungsi->fcaridata3($tabel,$fieldambil,$where);
		
		foreach($toko as $tk) {
			if($tk['setting_key'] == 'config_namatoko') {
				$data['nama_toko'] = $tk['setting_value'];
			}
			if($tk['setting_key'] == 'config_alamattoko') {
				$data['alamat_toko'] = $tk['setting_value'];
			}
			if($tk['setting_key'] == 'config_alamatsite') {
				$data['alamat_site'] = $tk['setting_value'];
			}
		}
	  
		$nama_report = 'invoice'.$data['nopesanan'].'.pdf';
		$pdf = new PDFTable();
		$pdf->SetMargins(10,10,10,10);
		$pdf->AddPage("L","A5");
		/*
		$pdf->SetLeftMargin(10);
		$pdf->SetRightMargin(10);
		*/
		//$pdf->SetMargins(10,10,10,10);
		$pdf->defaultFontFamily = 'Arial';
		$pdf->defaultFontStyle  = '';
		$pdf->defaultFontSize   = 7;
		$pdf->SetFont($pdf->defaultFontFamily, $pdf->defaultFontStyle, $pdf->defaultFontSize);
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		
		$pdf->SetLineWidth(0.2);
		$width = $pdf->w - $pdf->lMargin-$pdf->rMargin;
	  
		$tabelheader = '<table width="100%"><tr><td align="center" valign="middle"><font style="bold" size="15">'.strtoupper($data['nama_toko']).' '.$pdf->w.'</font></td></tr>';
		$tabelheader .= "<tr><td align=\"center\" valign=\"middle\"><font size=\"8\">".$data['alamat_toko']."</font></td></tr>";
		$tabelheader .= "<tr><td align=\"center\" valign=\"middle\"><font size=\"8\">".$data['alamat_site']."</font></td></tr><tr></table>";
		$pdf->Line($x,42,$x+$width,42);
		$pdf->htmltable($tabelheader);
		$tabelheader2 = "<table width=\"100%\">";
		$tabelheader2 .= "<tr><td valign=\"middle\" width=\"20%\"><font style=\"bold\">No Order</font></td>";
		$tabelheader2 .= "<td valign=\"middle\">: #".sprintf('%08s', $data['nopesanan'])."</td>";
		$tabelheader2 .= "</tr><tr><td valign=\"middle\" ><font style=\"bold\">Tgl Order</font></td>";
		$tabelheader2 .= "<td valign=\"middle\">: ".$this->Fungsi->ftanggalFull1($data['tglorder'])."</td>";
		$tabelheader2 .= "</tr>
					   <tr>
						 <td valign=\"middle\"><font style=\"bold\">Pelanggan</font></td>
						 <td valign=\"middle\">: ".$data['nmreseller']." ( ".$data['nmgrpreseller']." )</td>
					   </tr></table>";
		$pdf->htmltable($tabelheader2);
		$pdf->Line($x,62,$x+$width,62);
		$pdf->setFont('Arial','B',9);
		$pdf->Text($x,70,"Alamat Pengirim");
		$pdf->Text(111,70,"Alamat Penerima");
		$pdf->setFont('Arial','',7);
		
		
		$pdf->setXY($x,72);
		$pdf->MultiCell(90,5,stripslashes(ucwords($data['order']['nama_pengirim'])),0,'L');
		$pdf->setXY($x+90,72);
		$pdf->MultiCell(80,5,stripslashes(ucwords($data['order']['nama_penerima'])),0,'L');
		//$pdf->Ln();
		$pdf->setXY($x,77);
		$pdf->MultiCell(90,5,stripslashes(ucwords($data['order']['alamat_pengirim'])).'. '.stripslashes(ucwords($data['order']['propinsinm_pengirim'])).', '.stripslashes($data['order']['kotanm_pengirim']),0,'L');
		$pdf->setXY($x+90,77);
		$pdf->MultiCell(90,5,stripslashes(ucwords($data['order']['alamat_penerima'])).'. '.stripslashes(ucwords($data['order']['propinsinm_penerima'])).', '.stripslashes($data['order']['kotanm_penerima']),0,'L');
	
		$ketKecKelTagihan = 'Kec. '.stripslashes(ucwords($data['order']['kecamatannm_pengirim']));
		$ketKecKelTerima = 'Kec. '.stripslashes(ucwords($data['order']['kecamatannm_penerima']));
		if(trim($data['order']['kelurahan_pengirim']) != '' && $data['order']['kelurahan_pengirim'] != null) {
			$ketKecKelTagihan .= ' , Kel. '.stripslashes(ucwords($data['order']['kelurahan_pengirim']));
		}
		$pdf->Cell(90,5,$ketKecKelTagihan,0,'L');
		if(trim($data['order']['kelurahan_penerima']) != '' && $data['order']['kelurahan_penerima'] != null) {
		 
			$ketKecKelTerima .= ' , Kel. '.stripslashes(ucwords($data['order']['kelurahan_penerima']));
		}
		$pdf->Cell(80,5,$ketKecKelTerima,0,'L');
		$pdf->Ln();
		$pdf->Cell(90,5,stripslashes(ucwords($data['order']['negaranm_pengirim'])).' '.$data['order']['kodepos_pengirim'],0,'L'); 
		$pdf->Cell(80,5,stripslashes(ucwords($data['order']['negaranm_penerima'])).' '.$data['order']['kodepos_penerima'],0,'L'); 
		$pdf->Ln();
		if($data['order']['hp_pengirim'] !='') {
			$pdf->Cell(90,5,'Telp. '.stripslashes(ucwords($data['order']['hp_pengirim'])),0,'L'); 
		}
		if($data['order']['hp_penerima'] !='') {
			$pdf->Cell(80,5,'Telp. '.stripslashes(ucwords($data['order']['hp_penerima'])),0,'L');
		}
		$pdf->Ln();
		
		/* detail produk */
		$x = $pdf->GetX();
		$pdf->setXY($x,105);
		$pdf->setFont('Arial','',8);
	  
		$tabelproduk  = "<table width=100% border=1>";
		$tabelproduk .= "<tr><td width=5%>No</td><td>Produk</td><td width=15% align=center>Jumlah</td><td width=20% align=center>Berat</td><td align=right>Harga</td><td align=right>Total</td>";
		$totberat = 0;
		$totpoin = 0;
		$no = 0;
		foreach($data['detailproduk'] as $dt) {
			$no = $no+1;
			$totberat = $totberat + $dt['berat'];
			$dt['poin'] = isset($dt['poin']) && $dt['poin'] != null && $dt['poin'] != '' ? $dt['poin']:'0';
			$totpoin = $totpoin + (int)$dt['poin'];
			
			$datapoin = $this->Fungsi->fcaridata('_produk','poin','idproduk',$dt['produk_id']);
			$datapoin = $datapoin == '' && $datapoin == null ? '0' : $datapoin;
			$tabelproduk .= "<tr><td>".$no."</td><td><font style=bold>".$dt['nama_produk']."</font>";
			if($dt['ukuran'] != '') {
			  $tabelproduk .= "<font size=11> Ukuran : ".$dt['ukuran']."</font>";
			}
			if($dt['warna'] != '') {
			  $tabelproduk .= "<font size=11> Warna : ".$dt['warna']."</font>";
			}
			$tabelproduk .= "<td align=right>".$dt['jml']."</td>";
			$tabelproduk .= "<td align=right>".$dt['berat']." Gram </td>";
			$tabelproduk .= "<td align=right>".$this->Fungsi->fFormatuang($dt['harga'])."</td>";
			$tabelproduk .= "<td align=right>".$this->Fungsi->fFormatuang(((int)$dt['jml']) * (int)$dt['harga'])."</td>";
		}
		
		$tabelproduk .= "<tr><td colspan=6 align=center> Total Berat ".$totberat .' Gram / '.($totberat/1000).' Kg'."</td></tr>";

		$tabelproduk .= "<tr><td colspan=5 align=right><font style=\"bold\">Sub Total</font></td><td align=right><font style=\"bold\">".$this->Fungsi->fFormatuang($data['order']['pesanan_subtotal'])."</font></td></tr>";
		$tabelproduk .= "<tr><td colspan=5 align=right><font style=\"bold\">".$data['namaservis']."</font></td><td align=right><font style=\"bold\">".$this->Fungsi->fFormatuang($data['order']['pesanan_kurir'])."</font></td>";
		$tabelproduk .= "<tr><td colspan=5 align=right><font style=\"bold\">Potongan Poin</font></td><td align=right><font style=\"bold\">(".$this->Fungsi->fFormatuang($data['order']['dari_poin']).")</font></td>";
		if($data['order']['dari_deposito'] > 0) {
			$tabelproduk .= "<tr><td colspan=5 align=right><font style=\"bold\">Potongan Deposito</font></td><td align=right><font style=\"bold\">(".$this->Fungsi->fFormatuang($data['order']['dari_deposito']).")</font></td>";
		}
		$grandtotal = ((int)$data['order']['pesanan_subtotal'] + (int)$data['order']['pesanan_kurir'])-(int)$data['order']['dari_poin']-(int)$data['order']['dari_deposito'];
		$tabelproduk .= "<tr><td colspan=5 align=right><font style=\"bold\">TOTAL YANG HARUS DIBAYAR</font></td><td align=right><font style=\"bold\">".$this->Fungsi->fFormatuang($grandtotal) ."</font></td>";
		$tabelproduk .= "</table>";
		$tabelproduk .= "<table width=100% border=1>";
		$tabelproduk .= "<tr><td align=center bgcolor=#cccccc><font style=bold size=7>".ucwords($this->Fungsi->kekata($grandtotal))." Rupiah</font></td></tr>";
		$tabelproduk .= "<tr><td border=0 align=right>Hormat Kami</td></tr>";
		$tabelproduk .= "<tr><td border=0 align=right></td></tr>";
		$tabelproduk .= "<tr><td border=0 align=right></td></tr>";
		$tabelproduk .= "<tr><td border=0 align=right></td></tr>";
		$tabelproduk .= "<tr><td border=0 align=right>(".$data['nama_toko'].")</td></tr>";
		$tabelproduk .= "</table>";
		$pdf->htmltable($tabelproduk);
		/* end detail produk */
	  
		if($jenis == 'report') {
			$pdf->output($nama_report,"I");
		} else {
			$pdf->output(DIR_INCLUDE.$nama_report,"F");
			return DIR_INCLUDE.$nama_report;
		}
		//echo file_exists ('../../pdf/pdftable/lib/pdftable.inc.php');
	  
	}
   
	public function cetakInvoicePDF($data,$jenis) {
		
		$tabel = "_setting";
		$fieldambil = "setting_key,setting_value";
		$where = "setting_key='config_namatoko' OR setting_key='config_alamattoko' OR setting_key='config_alamatsite'";
		$toko = $this->Fungsi->fcaridata3($tabel,$fieldambil,$where);
		
		foreach($toko as $tk) {
			if($tk['setting_key'] == 'config_namatoko') {
				$data['nama_toko'] = $tk['setting_value'];
			}
			if($tk['setting_key'] == 'config_alamattoko') {
				$data['alamat_toko'] = $tk['setting_value'];
			}
			if($tk['setting_key'] == 'config_alamatsite') {
				$data['alamat_site'] = $tk['setting_value'];
			}
		}
	  
		$nama_report = 'invoice'.$data['nopesanan'].'.pdf';
		$pdf = new FPDF("P","mm","A4");
		$pdf->SetMargins(5,5,5,5);
		$pdf->AliasNbPages();
		$pdf->AddPage();
		
		/* Kop Nota */
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(200,7,strtoupper($data['nama_toko']),0,1,'C');
		$pdf->SetFont('Arial','B',7);
		$pdf->Cell(200,7,strtoupper($data['alamat_toko']),0,1,'C');
		$pdf->SetFont('Arial','B',8);
		
		$pdf->Ln(5);
		$pdf->Line(5,20,5+200,20);
		$pdf->Cell(20,5,'No. Order',0,0,'L');
		$pdf->Cell(80,5,': '. sprintf('%08s', $data['nopesanan']),0,0,'L');
		$pdf->Cell(30,5,'Pelanggan',0,0,'L');
		$pdf->Cell(70,5,': '. ucwords($data['order']['cust_nama']),0,1,'L');
		$pdf->Cell(20,5,'Tgl. Order',0,0,'L');
		$pdf->Cell(80,5,': '. $this->Fungsi->ftanggalFull1($data['tglorder']),0,0,'L');
		$pdf->Cell(30,5,'Grup Pelanggan',0,0,'L');
		$pdf->Cell(70,5,': '. $data['order']['grup_cust'],0,1,'L');
		$pdf->Ln(4);
		$pdf->Line(5,37,5+200,37);
		if($data['order']['dropship'] == '1') {
			if($data['order']['nama_penerima'] != $data['order']['nama_pengirim'] && $data['order']['alamat_penerima'] != $data['order']['alamat_pengirim']){
				$width = 100;
				$pdf->setFont('Arial','B',8);
				$pdf->Cell($width,5,'Alamat Pengirim',0,0,'L');
				$pdf->Cell($width,5,'Alamat Tujuan',0,1,'R');
				
				$pdf->Cell($width,5,stripslashes(ucwords($data['order']['nama_pengirim'])),0,0,'L');
				$pdf->Cell($width,5,stripslashes(ucwords($data['order']['nama_penerima'])),0,1,'R');
				
				$wilayah_pengirim = $data['order']['alamat_pengirim'].', '.$data['order']['kecamatannm_pengirim'];
				if($data['order']['kelurahan_pengirim'] != '') {
					$wilayah_pengirim .= ', '.$data['order']['kelurahan_pengirim'];
				}
				$wilayah_pengirim2 = $data['order']['kotanm_pengirim'].', '.$data['order']['propinsinm_pengirim'];
				if($data['order']['kodepos_pengirim'] != '') {
					$wilayah_pengirim2 .= ' '.$data['order']['kodepos_pengirim'];
				}
				
				$pdf->setFont('Arial','',8);
				$pdf->Cell($width,5,stripslashes(ucwords($wilayah_pengirim)),0,0,'L');
				
				$wilayah_penerima = $data['order']['alamat_penerima'].', '.$data['order']['kecamatannm_penerima'];
				if($data['order']['kelurahan_penerima'] != '') {
					$wilayah_penerima .= ', '.$data['order']['kelurahan_penerima'];
				}
				$wilayah_penerima2 = $data['order']['kotanm_penerima'].', '.$data['order']['propinsinm_penerima'];
				if($data['order']['kodepos_penerima'] != '') {
					$wilayah_penerima2 .= ' '.$data['order']['kodepos_penerima'];
				}
				$pdf->Cell($width,5,stripslashes(ucwords($wilayah_penerima)),0,1,'R');
				
				$pdf->Cell($width,5,stripslashes(ucwords($wilayah_pengirim2)),0,0,'L');
				$pdf->Cell($width,5,stripslashes(ucwords($wilayah_penerima2)),0,1,'R');
				
				$pdf->Cell($width,5,stripslashes(ucwords('Hp. '.$data['order']['hp_pengirim'])),0,0,'L');
				$pdf->Cell($width,5,stripslashes(ucwords('Hp. '.$data['order']['hp_penerima'])),0,1,'R');
			}
		} else {
			$width = 200;
			
			$wilayah_penerima = $data['order']['alamat_penerima'].', '.$data['order']['kecamatannm_penerima'];
			if($data['order']['kelurahan_penerima'] != '') {
				$wilayah_penerima .= ', '.$data['order']['kelurahan_penerima'];
			}
			$wilayah_penerima2 = $data['order']['kotanm_penerima'].', '.$data['order']['propinsinm_penerima'];
			if($data['order']['kodepos_penerima'] != '') {
				$wilayah_penerima2 .= ' '.$data['order']['kodepos_penerima'];
			}
			$pdf->setFont('Arial','B',8);
			$pdf->Cell($width,5,'Alamat Tujuan',0,1,'R');
			$pdf->setFont('Arial','',8);
			
			$pdf->Cell($width,5,stripslashes(ucwords($wilayah_penerima)),0,1,'R');
			$pdf->Cell($width,5,stripslashes(ucwords($wilayah_penerima2)),0,1,'R');
			$pdf->Cell($width,5,stripslashes(ucwords('Hp. '.$data['order']['hp_penerima'])),0,1,'R');
			
		}	
		$pdf->Ln(3);
		$pdf->setFont('Arial','B',8);
		$pdf->SetFillColor(245,245,245);
		$pdf->Cell(61,7,'Produk',1,0,'C',1);
		$pdf->Cell(30,7,'Jumlah',1,0,'C',1);
		$pdf->Cell(27,7,'Berat (Gr)',1,0,'C',1);
		$pdf->Cell(41,7,'Harga',1,0,'R',1);
		$pdf->Cell(41,7,'Total',1,1,'R',1);
		
		$totberat = 0;
		$totpoin = 0;
		$pdf->setFont('Arial','',7);
		$x = 0;
		foreach($data['detailproduk'] as $dt) {
			$totberat = $totberat + $dt['berat'];
			$dt['poin'] = isset($dt['poin']) && $dt['poin'] != null && $dt['poin'] != '' ? $dt['poin']:'0';
			$totpoin = $totpoin + (int)$dt['poin'];
			
			$datapoin = $this->Fungsi->fcaridata('_produk','poin','idproduk',$dt['produk_id']);
			$datapoin = $datapoin == '' && $datapoin == null ? '0' : $datapoin;
			$nama_produk = $dt['nama_produk'];
			if($dt['ukuran']!='' || $dt['warna']!=''){
				$nama_produk .= '(';
				if($dt['ukuran'] != '') $nama_produk .= ' Ukuran : '.$dt['ukuran'];
				if($dt['ukuran'] != '' && $dt['warna'] != '') $nama_produk .= ', ';
				if($dt['warna'] != '') $nama_produk .= ' Warna : '.$dt['warna'];
				$nama_produk .= ')';
			}
			$pdf->Cell(61,6,$nama_produk,1,0);
			
			$pdf->Cell(30,6,$dt['jml'],1,0,'C');
			$pdf->Cell(27,6,$dt['berat'],1,0,'C');
			$pdf->Cell(41,6,$this->Fungsi->fFormatuang($dt['harga']),1,0,'R');
			
			$pdf->Cell(41,6,$this->Fungsi->fFormatuang($dt['harga'] * $dt['jml']),1,1,'R');
		}
		$pdf->SetFillColor(245,245,245);
		$pdf->setFont('Arial','B',7);
		$pdf->Cell(200,6,'Total Berat '.$totberat.' Gr ('.$totberat/1000 .' Kg)',1,1,'C',1);
		
		$pdf->Cell(159,6,'Sub Total',1,0,'R');
		$pdf->Cell(41,6,$this->Fungsi->fFormatuang($data['order']['pesanan_subtotal']),1,1,'R');
		$pdf->Cell(159,6,$data['namaservis'],1,0,'R');
		$pdf->Cell(41,6,$this->Fungsi->fFormatuang($data['order']['pesanan_kurir']),1,1,'R');
		
		if($data['order']['dari_poin'] > 0) {
			$pdf->Cell(159,6,'Potongan Poin',1,0,'R');
			$pdf->Cell(41,6,$this->Fungsi->fFormatuang($data['order']['dari_poin']),1,1,'R');
		}
		if($data['order']['dari_deposito'] > 0) {
			$pdf->Cell(159,6,'Potongan Deposito',1,0,'R');
			$pdf->Cell(41,6,$this->Fungsi->fFormatuang($data['order']['dari_deposito']),1,1,'R');
		}
		$grandtotal = ((int)$data['order']['pesanan_subtotal'] + (int)$data['order']['pesanan_kurir'])-(int)$data['order']['dari_poin']-(int)$data['order']['dari_deposito'];
		$pdf->setFont('Arial','B',8);
		$pdf->Cell(159,6,'Total Yang Harus Dibayar (#'.sprintf('%08s', $data['nopesanan']).')',1,0,'R');
		$pdf->Cell(41,6,$this->Fungsi->fFormatuang($grandtotal),1,1,'R');
		$pdf->setFont('Arial','',7);
		$pdf->Cell(200,6,'Hormat Kami, '.date('d M Y'),0,1,'R');
		$pdf->Ln(7);
		$pdf->Cell(200,6,'('.strtoupper($data['nama_toko']).')',0,1,'R');
		if($jenis == 'report') {
			$pdf->output($nama_report,"I");
		} else {
			$pdf->output(DIR_INCLUDE.$nama_report,"F");
			return DIR_INCLUDE.$nama_report;
		}
		
	}
	
	public function cetakInvoice(){
		$data['nopesanan'] = isset($_GET['pid']) ? $_GET['pid'] :'';
		$order = $this->model->getOrderByID($data['nopesanan']);
		if($order){
			$data['tglorder'] = $order['pesanan_tgl'];
			$data['nmstatus'] = $order['status_nama'];
			$datadetail 	= $this->model->getOrderDetail($data['nopesanan']);
			
			$data['namaservis'] = $order['kurir'].' - '.$order['servis_code'];
			$data['detailproduk'] = $datadetail;
			$data['order'] = $order;
			$field 			= 'cust_nama,cg_nm';
			$tabel 			= '_customer INNER JOIN _customer_grup ON _customer.cust_grup_id = _customer_grup.cg_id';
			$where 			= "cust_id = '".$order['pelanggan_id']."'";
			$reseller 		= $this->Fungsi->fcaridata2($tabel,$field,$where);
				
			$data['nmreseller'] =  $reseller['cust_nama'];
			$data['nmgrpreseller'] = $reseller['cg_nm'];
			$this->cetakInvoicePDF($data,'report');
		}
	}
	
	public function cetakLabel()
	{
		$data['nopesanan'] = isset($_GET['pid']) ? $_GET['pid'] :'';
		$order = $this->model->getOrderByID($data['nopesanan']);
		$tabel = "_setting";
		$fieldambil = "setting_key,setting_value";
		$where = "setting_key='config_namatoko' OR setting_key='config_alamattoko' OR setting_key='config_alamatsite' OR setting_key='config_telp'";
		$toko = $this->Fungsi->fcaridata3($tabel,$fieldambil,$where);
		
		foreach($toko as $tk) {
			if($tk['setting_key'] == 'config_namatoko') {
				$data['nama_toko'] = ucwords($tk['setting_value']);
			}
			if($tk['setting_key'] == 'config_alamattoko') {
				$data['alamat_toko'] = $tk['setting_value'];
			}
			if($tk['setting_key'] == 'config_alamatsite') {
				$data['alamat_site'] = $tk['setting_value'];
			}
			if($tk['setting_key'] == 'config_telp') {
				$data['hp_toko'] = $tk['setting_value'];
			}
		}
		$pdf = new FPDF("P","mm","A5");
		$pdf->SetMargins(5,5,5,5);
		$pdf->AliasNbPages();
		$pdf->AddPage();
		if($order['dropship'] == '1') {
			$width = 69;
			$pdf->setFont('Arial','B',8);
			$wilayah_pengirim 	= "Alamat Pengirim :\n";
			$wilayah_pengirim .= $order['nama_pengirim']."\n";
			$wilayah_pengirim .= $order['alamat_pengirim'].", \n".$order['kecamatannm_pengirim'];
			if($order['kelurahan_pengirim'] != '') {
				$wilayah_pengirim .= ', '.$order['kelurahan_pengirim'];
			}
			$wilayah_pengirim .= "\n".$order['kotanm_pengirim'].', '.$order['propinsinm_pengirim'];
			if($order['kodepos_pengirim'] != '') {
				$wilayah_pengirim .= ' '.$order['kodepos_pengirim'];
			}
			$wilayah_pengirim .= "\nHp. ".$order['hp_pengirim'];
			
			
			
		} else {
			$width = 69;
			$pdf->setFont('Arial','B',8);
			$wilayah_pengirim 	= "Alamat Pengirim :\n";
			$wilayah_pengirim .= $data['nama_toko']."\n";
			$wilayah_pengirim .= $data['alamat_toko'].", ";
			$wilayah_pengirim .= "Hp. ".$data['hp_toko'];
			
			
			
		}
		
		$wilayah_penerima = "Alamat Penerima :\n";
		$wilayah_penerima .= $order['nama_penerima']."\n";
		$wilayah_penerima .= $order['alamat_penerima'].", \n".$order['kecamatannm_penerima'];
		if($order['kelurahan_penerima'] != '') {
			$wilayah_penerima .= ', '.$order['kelurahan_penerima'];
		}
		$wilayah_penerima .= "\n".$order['kotanm_penerima'].', '.$order['propinsinm_penerima'];
		if($order['kodepos_penerima'] != '') {
			$wilayah_penerima .= ' '.$order['kodepos_penerima'];
		}
		$wilayah_penerima .= "\nHp. ".$order['hp_penerima'];
		
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$pdf->MultiCell($width,4,$wilayah_pengirim,1,'L');
		$pdf->SetXY($x + 69, $y);
		$pdf->MultiCell($width,4,$wilayah_penerima,1,'L');
		$pdf->output();
	}
   
}

?>
