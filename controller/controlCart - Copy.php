<?php

class controller_Cart {
   private $page;
   private $rows;
   private $offset;
   private $dataModel;
   private $dataShipping;
   private $Fungsi;
   private $dataProduk;
   private $dataRegister;
   private $tipemember;
   private $kirim_email;
   private $bank;
   private $data=array();
   
   
   public function __construct(){
	    require_once 'panelnibras/pdf/pdftable/lib/pdftable.inc.php';
		$this->dataModel= new model_Cart();
		$this->dataShipping= new model_Shipping();
		$this->Fungsi= new FungsiUmum();
		$this->dataProduk = new controller_Produk();
		$this->kirim_email = new PHPMailer();
		$this->bank = new model_Bank();
		
		if(!isset($_SESSION['hscart']))
           $_SESSION['hscart'] = array();
		   
		if(!isset($_SESSION['hscartsementara']))
           $_SESSION['hscartsementara'] = array();   
		
		if(!isset($_SESSION['qtycart']))
           $_SESSION['qtycart'] = array();
		
		if(!isset($_SESSION['katcart']))
           $_SESSION['katcart'] = array();
		   
		if(!isset($_SESSION['qtycartsementara']))
           $_SESSION['qtycartsementara'] = array();
		
		if(!isset($_SESSION['wrncart']))
           $_SESSION['wrncart'] = array();
		   
		if(!isset($_SESSION['wrncartsementara']))
           $_SESSION['wrncartsementara'] = array();
		
		if(!isset($_SESSION['ukrcart']))
           $_SESSION['ukrcart'] = array();
		   
		if(!isset($_SESSION['ukrcartsementara']))
           $_SESSION['ukrcartsementara'] = array();
		
		
	}
   
   public function delCart(){
      $data = isset($_POST['data']) ? $_POST['data']:'';
      if($data != ''){
	     $data = explode("::",$data);
		 $pid = $data[0];
		 $kat = $data[3];
		 $options = unserialize(base64_decode($data[1]));
		 $option = implode(",",$options);
		 $jml = $data[2];
		 if(!$options){
		    $key = $pid;
		 } else {
		    $key = $pid.':'.$option;
		 }
		 $key .= ':'.$kat;
		 unset($_SESSION['hscart'][$key]);
		// unset($_SESSION['katcart'][$kat]);
		 //echo $key;
		 $_SESSION['qtycart'][$pid] = $_SESSION['qtycart'][$pid]-$jml;
		 $_SESSION['katcart'][$kat] = $_SESSION['katcart'][$kat]-$jml;
		 
		  if($_SESSION['qtycart'][$pid]<1){
		     unset($_SESSION['qtycart'][$pid]);
		 }
		 
		 if(count($_SESSION['qtycart'])<1){
		     unset($_SESSION['qtycart']);
		 }
		 
		 if($_SESSION['katcart'][$kat]<1){
		     unset($_SESSION['katcart'][$kat]);
		 }
		 
		 if(count($_SESSION['katcart']) < 1){
		    unset($_SESSION['katcart']);
		 }
		 
		 echo json_encode(array("status"=>"sukses","keterangan"=>"Berhasil dihapus"));
	  } else {
	     echo json_encode(array("status"=>"gagal","keterangan"=>"Data tidak ditemukan"));
	  }
	  //$data = unserialize($data);
	  
   }
	public function updateCart(){
		$totbeli = array();
		$pesan = array();
	   
		$qtycat = array();
		$hasilcart = array();
		if(isset($_POST['jumlah'])){
			
			foreach ($_POST['jumlah'] as $key => $value) {
				$data = explode("::",$key);
				
				$pid = $data[0];
				$options = unserialize(base64_decode($data[1]));
				
				$option = implode(",",$options);
				$jmllama = $data[2];
				$imageproduk = $data[3];
				
				if(!$options){
					$keys = $pid.'::'.$imageproduk;
				} else {
					$keys = $pid.':'.$option.'::'.$imageproduk;
				}
				//print_r($keys);
				$qty = (int)$value;
			 
				$produk  = $this->dataProduk->dataProdukByID($pid);
				$stokall = $produk['jml_stok'];
				
				if ((int)$qty && ((int)$qty > 0)) {
					if (!isset($_SESSION['qtycart'][$pid])) {
						$_SESSION['qtycart'][$pid] = (int)$qty;
					} else {
						$_SESSION['qtycart'][$pid] += (int)$qty;
					}
					
					if (!isset($_SESSION['hscart'][$keys])) {
						$_SESSION['hscart'][$keys] = (int)$qty;
					} else {
						$_SESSION['hscart'][$keys] += (int)$qty;
						$cekstok = $this->dataProduk->getOption($pid,$options[1],$options[0]);
						$cekstok['stok'] = isset($cekstok['stok']) ? $cekstok['stok']:0;
			 
						if($cekstok['stok'] > 0) {
							$stok = $cekstok['stok'];
						} else {
							$stok = $stokall;
						}
						$totprodukini = isset($_SESSION['hscart'][$keys]) ? (int)$_SESSION['hscart'][$keys]-1:0;
						if($stok < $_SESSION['hscart'][$keys]) {
							$pesan = 'Maaf, stok tinggal tersedia '.$stok.' item. Anda telah memesan produk ini sebanyak '.$totprodukini.' item ';
							$status='error';
							
							$_SESSION['qtycart'][$pid] -= (int)$qty;
							$_SESSION['hscart'][$keys] -= (int)$qty;
							if($_SESSION['qtycart'][$pid]== 0) {
								unset($_SESSION['qtycart'][$pid]);
							}
							if($_SESSION['hscart'][$keys] == 0) {
								unset($_SESSION['hscart'][$keys]);
							}
						}
					}
				} else {
					
					if($options[1] != '') {
						$zwarna = '->'.$this->Fungsi->fcaridata('_warna','warna','idwarna',$options[1]).' ';
					} else {
						$zwarna = '';
					}
					if($options[0] != '') {
						$zukuran = '->'.$this->Fungsi->fcaridata('_ukuran','ukuran','idukuran',$options[1]).' ';
					} else {
						$zukuran = '';
					}
					
					$pesan[]= 'Produk <b>'.$produk['nama_produk'].$zwarna.$zukuran.'</b>, silahkan masukkan jumlah';
					$_SESSION['qtycart'][$pid] -= (int)$qty;
					$_SESSION['hscart'][$keys] -= (int)$qty;
					if($_SESSION['qtycart'][$pid]== 0) {
						unset($_SESSION['qtycart'][$pid]);
					}
					if($_SESSION['hscart'][$keys] == 0) {
						unset($_SESSION['hscart'][$keys]);
					}
					
				}
				
				if(!$pesan){
					$hasilcart = array("status"=>"success","msg"=>"Berhasil Mengubah Data Belanja");
				} else {
					$hasilcart = array(
						"status"=>"error",
						"msg"=>implode("<br>",$pesan)
					);
				}
			}
		} else {
			$hasilcart = array("status"=>"error","msg"=>"Tidak ada data");
		}
		echo json_encode($hasilcart);
	}
	public function addCart($data){
		$pesan = '';
		$jml = 0;
		$total = 0;
		$item = $data['product_id'];
		$qty  = $data['jumlah'];
		
	
		if (!$data['option']) {
			$key = (int)$item.'::'.$data['image_product'];
		} else {
			$option = implode(",",$data['option']);
			$key = (int)$item . ':' . $option.'::'.$data['image_product'];
		}
	  
		if ((int)$qty && ((int)$qty > 0)) {
			if (!isset($_SESSION['qtycart'][$item])) {
				$_SESSION['qtycart'][$item] = (int)$qty;
			} else {
				$_SESSION['qtycart'][$item] += (int)$qty;
			}
			
			if (!isset($_SESSION['hscart'][$key])) {
				$_SESSION['hscart'][$key] = (int)$qty;
			} else {
				$_SESSION['hscart'][$key] += (int)$qty;
			}
		}
		$cekstok = $this->dataProduk->getOption($item,$data['option'][1],$data['option'][0]);
		$cekstok['stok'] = isset($cekstok['stok']) ? $cekstok['stok']:0;
		if($cekstok['stok'] > 0) {
			$stok = $cekstok['stok'];
		} else {
			$stok = $data['stok'];
		}
	  
		$totprodukini = isset($_SESSION['hscart'][$key]) ? (int)$_SESSION['hscart'][$key]-1:0;
		if(isset($_SESSION['hscart'][$key])) {
			if($stok < $_SESSION['hscart'][$key]) {
				$pesan = 'Maaf, stok tinggal tersedia '.$stok.' item. Anda telah memesan produk ini sebanyak '.$totprodukini.' item ';
				$status='error';
				
				$_SESSION['qtycart'][$item] -= (int)$qty;
				$_SESSION['hscart'][$key] -= (int)$qty;
				if($_SESSION['qtycart'][$item]== 0) {
					unset($_SESSION['qtycart'][$item]);
				}
				if($_SESSION['hscart'][$key] == 0) {
					unset($_SESSION['hscart'][$key]);
				}
				
				
			} else {
				$miniCart = $this->showminiCart($_SESSION['hscart'],$data);
				$totalitem = $miniCart['items'];
				$cart = $miniCart['carts'];
				
				foreach($cart as $c){
					$jml += $c['qty'];
					$total += $c['total'];
				}
				$pesan = 'Anda memasukkan '.$data['produk'].' kedalam keranjang belanja Anda';
				$status='success';
			}
		} else {
			$pesan = 'Proses Pesan Gagal';
			$status='error';
		}
	  
		return array("status"=>$status,"msg"=>$pesan,"qty"=>$jml);
	}
  
	public function pesanCart($dt=array()) {
		$pesan       = array();
		$hasilpesan  = array();
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			
			foreach ($_POST as $key => $value) {
				$data["$key"]	= isset($_POST["$key"]) ? $value : '';
			}
			if($data['jumlah'] < 0) {
				$pesan[] = 'Masukkan Jumlah Pesanan Anda';
			}
			if($data['product_id'] < 1) {
				$pesan[] = 'Tidak Ada Produk';
			}
			if($data['warna']=='0'){
				 $pesan[] = 'Pilih Warna';
			}
			
			if($data['ukuran'] == '0') {
				$pesan[] = 'Pilih Jenis Ukuran';
			}
			if(count($pesan) > 0) {
				$hasil = implode("<br>",$pesan);
				$status = 'error';
				$qty = '';
			} else {
				$datacart        = array();
		   
				$produk       	= $this->dataProduk->dataProdukByID($data['product_id']);
				$data['tipe'] 	= $dt['tipemember'];
				$data['stok']	= $produk['jml_stok'];
				$data['produk']	= $produk['nama_produk'];
				$data['option']  = array($data['ukuran'],$data['warna']);
				$data['total_awal'] = $dt['grup_totalawal'];
				$data['min_beli'] = $dt['grup_min_beli'];
				$data['min_beli_syarat'] = $dt['grup_min_beli_syarat']; /* Jika 1, per jenis produk. Jika 2, Bebas campur produk */
				$data['min_beli_wajib'] = $dt['grup_min_beli_wajib']; /* jika wajib, misal qty 3. maka ia harus beli minimal 3 */
				$data['diskon_grup'] = $dt['grup_diskon'];
				$datacart = $this->addCart($data);
				$status = $datacart['status'];
				$hasil= $datacart['msg'];
				$qty = isset($datacart['qty']) ? $datacart['qty']: '';
			}
		} else {
			$status = 'error';
			$qty = '';
			$hasil ='Data tidak valid';
		}

		echo json_encode(array("status"=>$status,"msg"=>$hasil,"qty"=>$qty));
	}
	public function showminiCart($cartitem,$data){
		$options = array();
		$carts = array();
		$i = 0;
		$stoks = 0;
		$pesan = array();
		$jmlerror = 0;
		$stoknya = 0;
		
		$minbeli = $data['min_beli'];
		$syarat = $data['min_beli_syarat'];
		$diskoncust = $data['diskon_grup'];
		
		$jmlall = array_sum($cartitem);
		
		foreach ($cartitem as $key => $quantity) {
			$datacart = explode('::', $key);
			$image_produk = $datacart[1];
			$product = explode(':', $datacart[0]);
			$id = $product[0];
			//$dataoption = explode('::',$product[1]);
			//$option = $dataoption[0];
			//print_r($dataoption);
			
			
			$ukuran = '';
			$warna = '';
			
			$options = explode(",",$product[1]);
			
			if(!isset($_SESSION['ukrcart'][$id]))
				$_SESSION['ukrcart'][$id] = array();
		
			if(!isset($_SESSION['wrncart'][$id])) 
				 $_SESSION['wrncart'][$id] = array();
		
			$_SESSION['ukrcart'][$id][$i] = $options[0];
			$_SESSION['wrncart'][$id][$i] = $options[1];
		
			$jmlitem = (int)$_SESSION['qtycart'][$id];
			$jmlcart = $quantity;
			//$jmlitemkat = (int)$_SESSION['katcart'][$kat];
		
	
			$prod = $this->dataProduk->dataProdukByID($id);
			$getpoin = $prod['poin'];
			$hrgdiskon = $prod['hrg_diskon'];
			$hrgsatuan = $prod['hrg_jual'];
			
			$hrgjual = $hrgsatuan - (($hrgsatuan * $diskoncust)/100);
			$hrgjualdiskon = $hrgdiskon - (($hrgdiskon * $diskoncust)/100);
			
			$stok       = $this->dataProduk->getOption($id,$options[1],$options[0]);
			
			$allstok    = $prod['jml_stok'];
			$stok_option = isset($stok['stok']) ? $stok['stok']:0; 
			$stoks = $allstok;
			$tambahanhrg = isset($stok['tambahan_harga']) ? $stok['tambahan_harga']:0;
		
			if($syarat == '1') {
			   $jmlsyarat = $jmlitem;
			} else  {
			   $jmlsyarat = $jmlall;
			}
		
			if($minbeli < $jmlsyarat + 1) {
				if($hrgdiskon > 0) {
					$harga = $hrgjualdiskon;
				} else {
					$harga = $hrgjual;
				}
			} else {
				if($hrgdiskon > 0){
					$harga = $hrgdiskon;
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
				"stok"			=> $stoks,
				"poin"          => $getpoin,
				"warna"			=> $options[1],
				"warna_nama"	=> $this->Fungsi->fcaridata('_warna','warna','idwarna',$options[1]),
				"ukuran"	    => $options[0],
				"ukuran_nama"	=> $this->Fungsi->fcaridata('_ukuran','ukuran','idukuran',$options[0]),
				"image_produk"	=> $image_produk
			);				

			$i++;
		}
		$carts = array(
			'carts'	=> $carts,
			'items'	=> count($_SESSION['hscart']),
			'jmlerror' => $jmlerror,
			'pesan' => $pesan
		);
	  
		return $carts;
	}
   
   public function buyCart($orderstatus,$idmember,$tipemember,$reseller,$grupreseller){
     $pesan = array();
	 $hasil = '';
	 $proses = array();
     if(isset($idmember)) {
      //Penerima
      $this->data['nama'] 		= isset($_SESSION['frmnama']) ? $_SESSION['frmnama']:'';
	  $this->data['telp'] 		= isset($_SESSION['frmtelp']) ? $_SESSION['frmtelp']:'';
	  $this->data['alamat']		= isset($_SESSION['frmalamat']) ? $_SESSION['frmalamat']:'';
	  $this->data['negara']		= isset($_SESSION['frmnegara']) ? $_SESSION['frmnegara']:'';
	  $this->data['propinsi']	= isset($_SESSION['frmpropinsi']) ? $_SESSION['frmpropinsi']:'';
	  $this->data['kabupaten'] 	= isset($_SESSION['frmkabupaten']) ? $_SESSION['frmkabupaten']:'';
	  $this->data['kecamatan']	= isset($_SESSION['frmkecamatan']) ? $_SESSION['frmkecamatan']:'';
	  $this->data['kelurahan']	= isset($_SESSION['frmkelurahan']) ? $_SESSION['frmkelurahan']:'';
	  $this->data['kodepos']	= isset($_SESSION['frmkodepos']) ? $_SESSION['frmkodepos']:'';
	  
	  //Pengirim
	  $this->data['namapengirim'] = $reseller['cust_nama'];
	  $this->data['telppengirim'] = $reseller['cust_telp'];
	  $this->data['alamatpengirim'] = $reseller['cust_alamat'];
	  $this->data['negarapengirim'] = $reseller['cust_negara'];
	  $this->data['propinsipengirim'] = $reseller['cust_propinsi'];
	  $this->data['kabupatenpengirim'] = $reseller['cust_kota'];
	  $this->data['kecamatanpengirim'] = $reseller['cust_kecamatan'];
	  $this->data['kelurahanpengirim'] = $reseller['cust_kelurahan'];
	  $this->data['kodepospengirim'] = $reseller['cust_kdpos'];
	  $this->data['emailpengirim'] = $reseller['cust_email'];
	  
	  $this->data['cust_grup'] = $reseller['cust_grup_id'];
	  
	  $this->data['ipaddress']		= $this->Fungsi->get_client_ip();
	  
	  $this->data['potongan_kupon'] = 0;
	  $this->data['kode_kupon'] = '-';
	  $servis = isset($_POST['serviskurir']) ? $_POST['serviskurir']:'';
	  $this->data['poin'] = isset($_POST['poinku']) ? $_POST['poinku']:0;
	
	  if($servis == '') {
		return 'gagal|Pilih Servis Kurir';
		exit;
	  }
	 /*
	  if( $this->data['poin'] != '' && $this->data['poin'] != '0' ) {
		$checkpoin = $this->dataModel->checkPoin($idmember);		
         if(isset($checkpoin) && $checkpoin == ''){
            return 'gagal|Anda tidak memiliki poins';
		    exit;
         }		  
	     if($checkpoin != '') {
	        $this->data['sisapoin'] = $checkpoin - (int) $this->data['poin'];
	        if($this->data['sisapoin'] < 0 ){
	           return 'gagal|Anda hanya memiliki poin sebesar '.$checkpoin;
		       exit;
	        }		
		 }
	  }
	  */
	  //$checkpoin = isset($checkpoin) ? $checkpoin: '';
	  $this->data['sisapoin'] = 0;
      $checkpoin = $this->dataModel->checkPoin($idmember);
	  if( $this->data['poin'] != '' && $this->data['poin'] != '0' ) {
	     if($checkpoin == ''){
		    return 'gagal|Anda tidak memiliki poin';
		    exit;
		 } else {
		    $this->data['sisapoin'] = (int)$checkpoin - (int) $this->data['poin'];
	        if($this->data['sisapoin'] < 0 ){
	           return 'gagal|Anda hanya memiliki poin sebesar '.$checkpoin;
		       exit;
	        }	
		 }
	  }
	  
	  $zdata		= explode(":",$servis);
	  $idservis		= $zdata[0];
	  $idkurir		= $zdata[1];
	  $kurir		= $this->dataShipping->getShippingbyName($idkurir);
	  $namashipping = $kurir['nama_shipping'];
	  $tabelservis	= $kurir['tabel_servis'];
	  $tabeltarif	= $kurir['tabel_tarif'];
	  $logoshipping	= $kurir['logo'];
	  $tabeldiskon	= $kurir['tabel_diskon'];
	  $detekkdpos   = $kurir['detek_kdpos'];
			
	  $servisdata   = $this->dataShipping->getServisbyId($tabelservis,$idservis);
	  $namaservis	= $servisdata['servis_nama'];
	  $servisid		= $servisdata['ids'];
	  $minkilo		= $servisdata['min_kilo'];
	  
	  $this->data['iddetail'] 	= (int)$this->Fungsi->fIdAkhir('_order_detail','iddetail') + 1;
	  $kodeakhir 	= $this->Fungsi->fIdAkhir('_order','CONVERT(pesanan_no,SIGNED)');
	  $this->data['nopesanan'] = sprintf('%08s', $kodeakhir+1);
	  
	  $keranjang 	= $this->showminiCart($_SESSION['hscart'],$tipemember);
	  $totalitem 	= $keranjang['items'];
	  $cart 		= $keranjang['carts'];
	  $subtotal 	= 0;
	  $i 			= 0;
	  $totberat 	= 0;
	  $totjumlah	= 0;
	  $totgetpoin   = 0;
	
	  /* tabel header keranjang belanja */
	  
	  /*$tabel  = '<table style="border-collapse: collapse;width: 100%;margin-bottom: 15px;border-top: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;">';
	  $tabel .= '<tr>
		           <td style="text-align:center;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;"><b>Nama Produk</b></td>
		           <td style="text-align:center;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;"><b>Jumlah</b></td>
		           <td style="text-align:center;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;"><b>Berat</b></td>
		           <td style="text-align:center;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;"><b>Harga</b></td>
		           <td style="text-align:center;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;"><b>Sub Total</b></td>
	              </tr>';
	
       */
	  /*
	  $tabel  = '<table style="border-collapse: collapse;width: 100%;margin-bottom: 15px;border-top: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;">';
	  $tabel .= '<thead>';
	  $tabel .= '<tr>';
	  $tabel .= '<th style="margin:5px;text-align:center;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;">Nama Produk</th>';
	  $tabel .= '<th style="text-align:center;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;">Jumlah</th>';
	  $tabel .= '<th style="text-align:center;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;">Berat</th>';
	  $tabel .= '<th style="text-align:center;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;">Harga</th>';
	  $tabel .= '<th style="text-align:center;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;">Sub Total</th>';
	  $tabel .= '</tr>';
	  $tabel .= '</thead>';
	  */
	  $tabel = "<table style=\"font-size:13px;font-family:'Helvetica Neue',
	             'Helvetica',Helvetica,Arial,sans-serif;max-width:100%;
				 border-collapse:collapse;border-spacing:0;
				 width:100%;background-color:#ffffff;margin:0;padding:0\" 
				 width=\"100%\" bgcolor=\"#ffffff\" 
				 border=\"1\" cellpadding=\"3\" cellspacing=\"3\">";
	  $tabel .= "<thead style=\"margin:0;padding:0\">";
	  $tabel .= "<tr style=\"margin:0;padding:0\">";
	  $tabel .= "<th style=\"text-align:center;background-color:#ffffff;margin:0;padding:5px 10px\" align=\"center\" 
	                 bgcolor=\"#ffffff\">Nama Produk</th>";
	  $tabel .= "<th style=\"text-align:center;background-color:#ffffff;margin:0;padding:5px 10px\" align=\"center\" bgcolor=\"#ffffff\">Jumlah</th>";
	  $tabel .= "<th style=\"text-align:center;background-color:#ffffff;margin:0;padding:5px 10px\" align=\"center\" bgcolor=\"#ffffff\">Berat</th>";
	  $tabel .= "<th style=\"text-align:center;background-color:#ffffff;margin:0;padding:5px 10px\" align=\"center\" bgcolor=\"#ffffff\">Harga</th>";
	  $tabel .= "<th style=\"text-align:center;background-color:#ffffff;margin:0;padding:5px 10px\" align=\"center\" bgcolor=\"#ffffff\">Subtotal</th>";
	  $tabel .= "</tr></thead>";
	  
	  
	  /*
	  $tabel 		= '<table style="border-collapse: collapse;width: 100%;margin-bottom: 15px;border-top: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;">';
	  $tabel	   .= '<thead><tr>';
	  $tabel	   .= '<td style="font-weight: bold;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;text-align: left;">Nama Produk</td>';
	  $tabel	   .= '<td style="font-weight: bold;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;text-align: center;">Jumlah</td>';
	  $tabel	   .= '<td style="font-weight: bold;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;text-align: right;">Berat</td>';
	  $tabel	   .= '<td style="font-weight: bold;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;text-align: right;">Harga</td>';
      $tabel       .= '<td style="font-weight: bold;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;text-align: right;">Sub Total</td></tr></thead><tbody>';
	  */
	  /* @end tabel header keranjang belanja */
	  //$tabel  .= '<tbody>';
	  $tabel .= "<tbody style=\"margin:0;padding:0\">";
	  foreach($cart as $c){
		$this->data['pid']   	= $c['product_id'];
		$this->data['jml'] 	 	= $c['qty'];
		$this->data['berat'] 	= $c['berat'];
		$this->data['harga'] 	= $c['harga'];
		$this->data['total'] 	= $c['total'];
		$this->data['idwarna']  = $c['warna'] != 'undefined' ? $c['warna']:'';
		$this->data['idukuran'] = $c['ukuran'] != 'undefined' ? $c['ukuran']:'';
		
		if($this->data['idwarna'] !='') $warna	= $this->Fungsi->fcaridata('_warna','warna','idwarna',$this->data['idwarna']);
		else $warna = '';
		
		if($this->data['idukuran'] != '') $ukuran = $this->Fungsi->fcaridata('_ukuran','ukuran','idukuran',$this->data['idukuran']);
		else $ukuran = '';
		
		//$gbr 		 			= $c['gbr'];
		$nama_produk 			= $c['product'];
		//$where 					= "idproduk='".$this->data['pid']."'";
		//$prods					= $this->Fungsi->fcaridata2('_produk','hrg_jual,hrg_beli',$where);
		//$this->data['satuan']	= $prods[0];
		//$this->data['hrgbeli']	= $prods[1];
		$this->data['satuan'] = $c['hargasatuan'];
		
		//if(!$this->dataModel->SimpanOrderDetail($this->data)) {
		$proses[] = $this->dataModel->SimpanOrderDetail($this->data);
		  // $pesan[] = 'error simpan detail';
		//}
		if(($this->data['idwarna'] != '' && $this->data['idwarna'] != '0' && $this->data['idwarna'] != 'undefined') || ($this->data['idukuran'] != '' && $this->data['idukuran'] != '0' && $this->data['idukuran'] != 'undefined')) {
			//if(!$this->dataModel->SimpanOrderDetailOption($this->data)) {
			   $proses[] = $this->dataModel->SimpanOrderDetailOption($this->data);
			//   $pesan[] = 'error simpan detail option' ;
			//}
			//if(!$this->dataModel->UpdateStokOption($this->data)) {
			   $proses[] = $this->dataModel->UpdateStokOption($this->data);
			  // $pesan[] = 'error update stok option' ;
			//}
		}
		
		//if(!$this->dataModel->UpdateStok($this->data)) { 
		   $proses[] = $this->dataModel->UpdateStok($this->data);
		//   $pesan[] = 'error update stok' ;
		//}
		$poinku  = (int)$c['poin'] * (int)$c['qty'];
		$subtotal	+= $this->data['total'];
		$totberat   += $this->data['berat'];
		$totjumlah  += (int)$c['qty'];
		$totgetpoin += (int)$poinku;
		
		/*
		$tabel  .= '<tr>';
		$tabel  .= '<td style="border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;text-align: left;">'.$nama_produk;
		if( $warna != '') {
		$tabel  .= '<br>'.$warna;
		}
		if($ukuran != '') {
		$tabel  .= '<br>'.$ukuran;
		}
		$tabel  .='</td>';
		$tabel  .= '<td style="margin:5px;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;" align=right>'.$this->data['jml'].'</td>';
		$tabel  .= '<td style="margin:5px;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;" align=right>'. $this->data['berat'].' Gram</td>';
		$tabel  .= '<td style="margin:5px;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;" align=right>'. $this->Fungsi->fFormatuang($this->data['harga']).'</td>';
		$tabel  .= '<td style="margin:5px;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;" align=right>'. $this->Fungsi->fFormatuang($this->data['total']).'</td>';
		$tabel  .= '</tr>';
		*/
		$tabel .= "<tr style=\"margin:0;padding:0\">";
		$tabel .= "<td style=\"margin:0;padding:10px;\" valign=\"top\">".$nama_produk;
		if( $warna != '') {
		$tabel  .= '<br style="margin:0;padding:0">'.$warna;
		}
		if($ukuran != '') {
		$tabel  .= '<br style="margin:0;padding:0">'.$ukuran;
		}
		$tabel .= "</td>";
		$tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\">".$this->data['jml']."</td>";
		$tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\">".$this->data['berat']." Gram</td>";
		$tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\">".$this->Fungsi->fFormatuang($this->data['harga'])."</td>";
		$tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\">".$this->Fungsi->fFormatuang($this->data['total'])."</td>";
		$tabel .= "</tr>";
		          
		$this->data['iddetail']++;
		$i++;
		
	  }
		  
	  
	  $this->data['subtotal'] = $subtotal;
	  $this->data['totjumlah'] = $totjumlah;
	  $this->data['totgetpoin'] = $totgetpoin;
	  
	  $tarifkurir = $this->dataShipping->getTarif($servisid,$this->data['negara'],$this->data['propinsi'],$this->data['kabupaten'],$this->data['kecamatan'],$totberat,$minkilo,$tabeltarif,$detekkdpos,$namashipping);
	
	  if($tabeldiskon != Null || $tabeldiskon != '') {
		  $zzdizkon = explode("::",$tabeldiskon);
		  $tabels = $zzdizkon[0];
		  $fieldambil = $zzdizkon[1];
          $where = " $zzdizkon[2]='".$servisid."' AND $zzdizkon[3]=1";
					
		  $dtdiskon = $this->Fungsi->fcaridata2($tabels,$fieldambil,$where);
		  $dtdiskon['$fieldambil'] = isset($dtdiskon['$fieldambil']) ? $dtdiskon['$fieldambil']:0;
		  $zdiskon = $dtdiskon['$fieldambil'] / 100;
	  } else {
		  $zdiskon = 0;
	  }
	  
	  $tarifkurir[1] = $tarifkurir[1] - ($tarifkurir[1]*$zdiskon);
	  $tarifkurir[4] = $tarifkurir[4] - ($tarifkurir[4]*$zdiskon);
	  
	  $this->data['tarifkurir'] = $tarifkurir[1];
	   if($tarifkurir[1] > 0) {
		 $this->data['satuantarifkurir'] = $tarifkurir[4];
	  } else {
		 $this->data['satuantarifkurir'] = 0;
	  }
	  $this->data['kurir'] = $namashipping;
	  $this->data['kurirservis'] = $servisid;
	  $this->data['orderstatus'] = $orderstatus;
	  $this->data['idmember'] = $idmember;
	  $this->data['tgltrans'] = date('Y-m-d H:i:s');
	  $this->data['potdeposito'] = $reseller['cd_deposito'];
	  //$this->data['bayaregis']	= $this->Fungsi->fcaridata2('_cust_invoice','biaya',"stsbayar='0' AND idmember='".$this->data['idmember']."'");
	  //$this->data['bayaregis']	= $this->fcaridata2('_cust_invoice','biaya',"stsbayar='0' AND idmember='".$this->data['idmember']."' AND member_grup='".$this->data['cust_grup']."'");
	  
	  //if(!$this->dataModel->SimpanOrder($this->data)) {
	     //$proses[] = $this->dataModel->SimpanOrder($this->data);
	  //   $pesan[] = 'error simpan order';
      //}
	  //if(!$this->dataModel->SimpanOrderPenerima($this->data)) {
	    $proses[] = $this->dataModel->SimpanOrderPenerima($this->data);
	    //$pesan[] = 'error simpan order penerima';
      //}
	  //if(!$this->dataModel->SimpanOrderPengirim($this->data)) {
	    $proses[] = $this->dataModel->SimpanOrderPengirim($this->data);
	    //$pesan[] = 'error simpan order pengirim';
	   //}
	  //if(!$this->dataModel->SimpanOrderStatus($this->data)) {
	     $proses[] = $this->dataModel->SimpanOrderStatus($this->data);
	    // $pesan[] = 'error simpan order status';
	  //}
	  if((int)$this->data['poin'] > 0){
		//if(!$this->dataModel->UpdateDeposit($this->data)) {
		   $proses[] = $this->dataModel->UpdatePoinCustomer($this->data);
		//   $pesan[] = 'error update deposit';
		//}
		//if(!$this->dataModel->InsertDepositDetail($this->data)) {
		   $proses[] = $this->dataModel->InsertPoinDetail($this->data);
		//   $pesan[] = 'error insert deposit detail';
		//}
	  } else {
	    $this->data['poin'] = 0;
	  }
	  
	  /*if((int)$totgetpoin > 0) {
	     if($checkpoin != '') {
		    $proses[] = $this->dataModel->UpdateGetPoin($this->data);
		 } else {
		    $proses[] = $this->dataModel->SimpanGetPoin($this->data);
	     }
		 $proses[] = $this->dataModel->SimpanDetailGetPoin($this->data);
	  }
	  */
	  $totalnya = ((int)$this->data['subtotal'] + (int)$this->data['tarifkurir']) - (int)$this->data['poin'];
	  $gunakandeposito = 0;
	  if($grupreseller['cg_deposito'] == '1') { 
	    if($reseller['cd_deposito'] > 0) {
		    
		
	        if($totalnya > $reseller['cd_deposito'] || $totalnya == $potdeposito) {
	           $totalygdibayar = $totalnya - $reseller['cd_deposito'];
			   $this->data['potdeposito'] = $reseller['cd_deposito'];
		       //$this->data['sisadeposito'] = $reseller['cd_deposito'] - $this->data['potdeposito'];
	        } else {
	           
			   $this->data['potdeposito'] = $totalnya;
			   
		       $totalygdibayar = 0;
	        }
			$this->data['sisadeposito'] = $reseller['cd_deposito'] - $this->data['potdeposito'];
			$this->data['keterangandeposito'] = "Menggunakan Deposito Pada Order : ". $this->data['nopesanan'];
			$proses[] = $this->dataModel->UpdateDepositoCustomer($this->data);
			$proses[] = $this->dataModel->InsertDepositoDetail($this->data);
			$gunakandeposito = 1;
		}
		
	  }
	  $proses[] = $this->dataModel->SimpanOrder($this->data);
	  $_SESSION['totalbelanja'] = $totalnya;
	  $_SESSION['noorder'] = $this->data['nopesanan'];
	  $_SESSION['sukseskonfirm'] = 'ya';
	  $_SESSION['potdeposito'] = $this->data['potdeposito'];
	  
	  //Kirim Email ke email pelanggan
	  $from = $this->Fungsi->fcaridata('_setting','setting_value','setting_key','config_emailnotif');
	  $from_name = $this->Fungsi->fcaridata('_setting','setting_value','setting_key','config_namatoko');
	  //$from = 'info@taskerjawanita.com'; //alamat toko
	  //$from_name = 'taskerjawanita.com';
	  $subject = 'Nota Tagihan '.$this->data['nopesanan'];
	  
	  $message   = $this->Fungsi->fcaridata('_setting','setting_value','setting_key','config_notabelanja');
	  /* subtotal */
	  $tabel .= "<tr style=\"margin:0;padding:0\">";
	  //$tabel	.= "<td colspan=\"4\" style=\"margin:5px;font-weight:bold;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;text-align: right;\">Sub Total : </td>";
	  $tabel .= "<td colspan=\"4\" style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>Sub Total</b></td>";
	  //$tabel .= '<td style="margin:5px;font-weight:bold;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;text-align: right;">'.$this->Fungsi->fFormatuang($subtotal).'</td>';
	  $tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>".$this->Fungsi->fFormatuang($subtotal)."</b></td>";
	  $tabel .= '</tr>';
	  
	  /* kurir */
	  $tabel	.= "<tr style=\"margin:0;padding:0\">";
	  //$tabel .= '<td colspan="4" style="font-weight:bold;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;text-align: right;">'.$namaservis.' : </td>';
	   $tabel .= "<td colspan=\"4\" style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>".$namaservis."</b> </td>";
	  if($this->data['tarifkurir'] > 0){
	  //$tabel .= '<td style="margin:5px;font-weight:bold;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;text-align: right;">'.$this->Fungsi->fFormatuang($this->data['tarifkurir']).'</td>';
	   $tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>".$this->Fungsi->fFormatuang($this->data['tarifkurir'])."</b></td>";
	  } else {
	  //$tabel .= '<td style="margin:5px;font-weight:bold;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;text-align: right;">Menunggu Konfirmasi Admin</td>';
	  $tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>Menunggu Konfirmasi Admin</b></td>";
	  }
	  $tabel .= '</tr>';
	  
	  /* poin jika ada */
	  if((int)$this->data['poin']> 0) {
	    $tabel .= '<tr style=\"margin:0;padding:0\">';
	    $tabel .= '<td colspan="3"></td>';
	    $tabel .= '<td class="text-right"><b>Potongan dari Poin : </b> </td>';
	    $tabel .= '<td class="text-right">'.$this->Fungsi->fFormatuang((int)$this->data['poin']).'</td>';
	    $tabel .= '</tr>';
	  }
	  
	  /* deposito jika ada */
	  if($gunakandeposito == 1) {
	     $tabel .= '<tr style=\"margin:0;padding:0\">';
	     $tabel .= '<td colspan="3"></td>';
    	     $tabel .= '<td class="text-right"><b>Potongan dari Deposito : </b> </td>';
	     $tabel .= '<td class="text-right">'.$this->Fungsi->fFormatuang((int)$this->data['potdeposito']).'</td>';
	     $tabel .= '</tr>';
	  }
	  
	  /* total */
	  $tabel	.= '<tr>';
	  //$tabel .= "<td colspan=\"4\" style=\"font-weight:bold;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;text-align: right;\">Total : </td>";
	  $tabel .= "<td colspan=\"4\" style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>Total</b></td>";
	  if($this->data['tarifkurir'] > 0) {
	    //$tabel    .= '<td style="text-align: right;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;"><b>'.$this->Fungsi->fFormatuang(((int)$this->data['tarifkurir']+(int)$subtotal) - (int)$this->data['poin'] - (int)$this->data['potdeposito']).'</b></td>';
	    $totalsemua = $this->Fungsi->fFormatuang(((int)$this->data['tarifkurir']+(int)$subtotal) - (int)$this->data['poin'] - (int)$this->data['potdeposito']);
	  } else {
	    //$tabel    .= '<td style="text-align: right;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;"><b>Menunggu Konfirmasi Admin</b></td>';
		$totalsemua = 'Menunggu Konfirmasi Admin';
	  }
	  //$tabel .= '<td style="margin:5px;font-weight:bold;border-bottom: 1px solid #000000;border-left: 1px solid #000000;border-right: 1px solid #000000;border-top: 1px solid #000000;text-align: right;">'.$totalsemua.'</td>';
	  $tabel .= "<td style=\"text-align:right;margin:0;padding:10px\" align=\"right\" bgcolor=\"#ffffff\"><b>".$totalsemua."</b></td>";
	  $tabel	.= '</tr>';
	  $tabel .= '</tbody>';
	  $tabel .= '</table>';
	  
	  $alamatpengirim  = $this->data['alamatpengirim'].', <br>';
	  $alamatpengirim .= $this->Fungsi->fcaridata('_provinsi','provinsi_nama','provinsi_id', $this->data['propinsipengirim']).', ';
	  $alamatpengirim .= $this->Fungsi->fcaridata('_kabupaten','kabupaten_nama','kabupaten_id',$this->data['kabupatenpengirim']).'<br>';
	  $alamatpengirim .= 'Kec. '.$this->Fungsi->fcaridata('_kecamatan','kecamatan_nama','kecamatan_id',$this->data['kecamatanpengirim']).', ';
	  if($this->data['kelurahanpengirim'] != '') {
	  $alamatpengirim .= 'Kel. '.$this->data['kelurahanpengirim'];
	  }
	  $alamatpengirim .= '<br>';
	  $alamatpengirim .= $this->Fungsi->fcaridata('_negara','negara_nama','negara_id',$this->data['negarapengirim']).' '.$this->data['kodepospengirim'].'<br>';
	  $alamatpengirim .= 'Phone. '. $this->data['telppengirim'];
	  
	  $alamatpenerima  = $this->data['alamat'].',<br>';
	  $alamatpenerima .= $this->Fungsi->fcaridata('_provinsi','provinsi_nama','provinsi_id', $this->data['propinsi']).', ';
	  $alamatpenerima .= $this->Fungsi->fcaridata('_kabupaten','kabupaten_nama','kabupaten_id',$this->data['kabupaten']).'<br>';
	  $alamatpenerima .= 'Kec. '.$this->Fungsi->fcaridata('_kecamatan','kecamatan_nama','kecamatan_id',$this->data['kecamatan']).', ';
	  if($this->data['kelurahan'] != '') {
	  $alamatpenerima .= 'Kel. '.$this->data['kelurahan'];
	  }
	  $alamatpenerima .= '<br>';
	  $alamatpenerima .= $this->Fungsi->fcaridata('_negara','negara_nama','negara_id',$this->data['negara']).' '.$this->data['kodepos'].'<br>';
	  $alamatpenerima .= 'Phone. '. $this->data['telp'];
	  
	  
	  $databank = $this->bank->getBank();
	  $databanks = '';
	  foreach($databank as $b) {
		 $rekening = $this->bank->getRekening($b['ids']);
		 foreach($rekening as $rek) {
		    $databanks .= $rek['bank'].'<br>No. Rek. '.$rek['norek'].'<br>A/n '.$rek['atasnama'].'<br>Cabang. '.$rek['cabang'].'<br><br>';
		 }
	  }
	  
	  $message   = str_replace("[PELANGGAN]",$this->data['namapengirim'],$message);
	  $message   = str_replace("[No Order]", $this->data['nopesanan'],$message);
	  $message   = str_replace("[DATA ORDER]", $tabel,$message);
	  $message   = str_replace("[NAMA PENGIRIM]", $this->data['namapengirim'],$message);
	  $message   = str_replace("[ALAMAT PENGIRIM]", $alamatpengirim,$message);
	  $message   = str_replace("[NAMA PENERIMA]", $this->data['nama'],$message);
	  $message   = str_replace("[ALAMAT PENERIMA]", $alamatpenerima,$message);
	  $message   = str_replace("[DATA BANK]", $databanks,$message);
	  //$message   = str_replace("< div", "<div",$message);
	  
	  
	  //$bodys  = '<html>';
	  //$bodys .= '<head>';
	  //$bodys .= '<link href="http://goetnik.com/view/goetnik/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">';
	  //$bodys .= '</head>';
	  //$bodys	.= '<body style="margin: 10px;">';
	  //$bodys	.= $message;
	  //$bodys	.= '</body>';
	  //$bodys	.= '</html>';
	  //$bodys	= '<div style="margin:10;padding:10">';
	  $bodys = "<div bgcolor=\"#FFFFFF\" style=\"font-family:'Helvetica Neue','Helvetica',Helvetica,Arial,sans-serif;width:100%!important;min-height:100%;font-size:14px;color:#404040;margin:0;padding:0\">";
	  $bodys	.= $message;
	  $bodys .= '</div>';
	 
	  $to = $this->data['emailpengirim'];
	  $this->kirim_email->IsHTML(true);
	  $this->kirim_email->SetFrom($from, $from_name);
	  $this->kirim_email->Subject = $subject;
	  $this->kirim_email->WordWrap = 500;
	  $this->kirim_email->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	  //$this->kirim_email->MsgHTML($bodys);
	  $this->kirim_email->Body = $bodys;
	  $this->kirim_email->CharSet="UTF-8";
	  $this->kirim_email->AddAddress($to,$this->data['namapengirim']);
      $this->kirim_email->Send();
	  /*if(!$this->kirim_email->Send()) {
	     $pesan[]= 'Mail error: '.$this->kirim_email->ErrorInfo;
	  }
	  */
	  echo $bodys;
	  $prosestransaksi = $this->dataModel->prosesTransaksi($proses);
	  if(!$prosestransaksi){
	     $pesan[] = 'Proses Penyimpanan Gagal. Periksa kembali jaringan internet Anda';
	  }
	  if(count($pesan)==0) {
	      $hasil = "sukses";
		  
	  } else {
	      $hasil = 'gagal|'.implode("<br>",$pesan);
		  
	  }
	  return $hasil;
	 }
   }
   
   private function cetakInvoicePDF($data){
	  $nama_report = 'invoice'.$data['nopesanan'].'.pdf';
	  $pdf = new PDFTable();
	  $pdf->AddPage("P","A4");
   }
   
   public function editPenerimaOrder() {
      $this->data['dkurir'] = isset($_POST['epdkurir']) ? $_POST['epdkurir']:'';
	  $this->data['hrgkurir'] = isset($_POST['ephrgkurir']) ? $_POST['ephrgkurir']:0;
	  $this->data['nopesan'] = isset($_POST['noorder']) ? $_POST['noorder']:0;
	  $this->data['nama'] = isset($_POST['epnama']) ? $_POST['epnama']:'';
	  $this->data['alamat'] = isset($_POST['epalamat']) ? $_POST['epalamat']:'';
	  $this->data['negara'] = isset($_POST['epnegara']) ? $_POST['epnegara']:'';
	  $this->data['propinsi'] = isset($_POST['eppropinsi']) ? $_POST['eppropinsi']:'';
	  $this->data['kota'] = isset($_POST['epkabupaten']) ? $_POST['epkabupaten']:'';
	  $this->data['kecamatan'] = isset($_POST['epkecamatan']) ? $_POST['epkecamatan']:'';
	  $this->data['kelurahan'] = isset($_POST['epkelurahan']) ? $_POST['epkelurahan']:'';
	  $this->data['kodepos'] = isset($_POST['epkdpos']) ? $_POST['epkdpos']:'';
	  $this->data['nohp'] = isset($_POST['ephandphone']) ? $_POST['ephandphone']:'';
	  $this->data['notelp'] = isset($_POST['eptelp']) ? $_POST['eptelp']:'';
	  $this->data['totberat'] = isset($_POST['ntotberat']) ? $_POST['ntotberat']:0;
	  $hasil = '';
	  $proses = array();
	  $pesan = array();
	  //print_r($this->data['dkurir']);
	  $zdata		= explode(":",$this->data['dkurir']);
	  $idservis		= $zdata[0];
	  $idkurir		= $zdata[1];
	  $kurir		= $this->dataShipping->getShippingbyName($idkurir);
	  $namashipping = $kurir['nama_shipping'];
	  $tabelservis	= $kurir['tabel_servis'];
	  $tabeltarif	= $kurir['tabel_tarif'];
	  $tabeldiskon	= $kurir['tabel_diskon'];
	  
	  $detekkdpos   = $kurir['detek_kdpos'];
	  $servisdata   = $this->dataShipping->getServisbyId($tabelservis,$idservis);
	  $namaservis	= $servisdata[1];
	  $servisid		= $servisdata[0];
	  $minkilo		= $servisdata[3];
	  
	  $tarifkurir = $this->dataShipping->getTarif($servisid,$this->data['negara'],$this->data['propinsi'],$this->data['kota'],$this->data['kecamatan'],$this->data['totberat'],$minkilo,$tabeltarif,$detekkdpos,$namashipping);
	  if($tabeldiskon != Null || $tabeldiskon != '') {
		  $zzdizkon = explode("::",$tabeldiskon);
		  $tabels = $zzdizkon[0];
		  $fieldambil = $zzdizkon[1];
          $where = " $zzdizkon[2]='".$servisid."' AND $zzdizkon[3]=1";
					
		  $dtdiskon = $this->Fungsi->fcaridata2($tabels,$fieldambil,$where);
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
	     $this->data['tarifkurir'] = $this->data['hrgkurir'];
		 $this->data['satuantarifkurir'] = 0;
	  }
	  
	 
	  $this->data['kurir'] = $namashipping;
	  $this->data['kurirservis'] = $servisid;
	  $this->data['nopesanan'] = $this->data['nopesan'];
	  
	  if(!$this->dataModel->simpanEditPenerimaOrder($this->data)){
	     $proses[] = $this->dataModel->simpanEditPenerimaOrder($this->data);
	     $pesan[] = 'Gagal Mengubah Alamat Penerima';
	  } 
	  if(!$this->dataModel->simpanEditKurir($this->data)){
	     $proses[] = $this->dataModel->simpanEditKurir($this->data);
	     $pesan[] = 'Gagal Mengubah Tarif Kurir';
	  } 
	  if(count($pesan) == 0){
	      $this->dataModel->prosesTransaksi($proses);
		  $hasil = 'sukses|Berhasil Ubah Alamat Penerima';
	  } else {
	      $hasil = 'gagal|'.implode(", ",$pesan);
	     
	  }
	  
	  return $hasil;
	  
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
	 $proses = array();
	 
	 $produk  = $this->dataProduk->dataProdukByID($idproduk);
	 $stok	  = $produk['jml_stok'];
	 $data['option']   = array($zukuran,$zwarna);
		
	 $cekstok = $this->dataProduk->getOption($idproduk,$data['option'][1],$data['option'][0]);
	 if($cekstok['stok'] > 0) {
	    $stok = $cekstok['stok'];
	 } 
	  
	 if($stok < $qty) {
	    $pesan = 'Maaf, stok tinggal tersedia '.$stok.' item';
		$error = true;
      }
	 
	 if($nopesan == '0'){
	    $pesan = 'No. Pesan tidak ditemukan';
		$error = true;
	 } elseif ((int)$qty < 1){
	    $pesan = 'Jumlah harus lebih dari 0';
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
	    $detekkdpos   = $kurir['detek_kdpos'];
	  
		$servisdata   = $this->dataShipping->getServisbyId($tabelservis,$idservis);
		$namaservis	   = $servisdata[1];
		$servisid		= $servisdata[0];
		$minkilo		= $servisdata[3];
	 
	    
		if((int)$wnlama > 0 || (int)$uklama > 0){
		   // stok produk optionnya nya dibalikin dulu
		   $proses[] = $this->dataModel->updateStokOptionBertambah($nopesan,$qtylama,$wnlama,$uklama,$idproduk);
		}
		// stok nya dibalikin dulu
		$data['jml'] = $qtylama;
		$data['pid'] = $idproduk;
		$proses[] = $this->dataModel->updateStokBertambah($qtylama,$idproduk); 
		
	    $produk       	  = $this->dataProduk->dataProdukByID($idproduk);
		$data['tipe'] 	  = $idgrup;
		$data['stok']	  = $produk['jml_stok'];
	    $data['produk']	  = $produk['nama_produk'];
		$data['pid']      = $idproduk;
		$data['jml']	  = $qty;
		$data['option']   = array($zukuran,$zwarna);
		$data['idmember'] = $idmember;
		/*
		 $cekstok = $this->dataProduk->getOption($idproduk,$data['option'][1],$data['option'][0]);
	     if($cekstok['stok'] > 0) {
	       $stok = $cekstok['stok'];
	     } else {
	       $stok = $data['stok'];
	     }
	  
	     if($stok < $qty) $pesan = 'gagal|Maaf, stok tinggal tersedia '.$stok.' item';
		*/
		$this->addCart($data);
		$bongkar = $this->dataModel->getProdukOrderOption('','','',$nopesan);
		//echo $iddetail;
		foreach($bongkar as $order) {
		  
		  if((int)$order['iddetail'] != (int)$iddetail){
		     
		     $produk = $this->dataProduk->dataProdukByID($order['idproduk']);
			 $data['stok'] = $produk['jml_stok'];
			 $data['produk'] = $produk['nama_produk'];
			 $data['pid']  = $order['idproduk'];
			 $data['jml']	  = $order['jml'];
			 $data['option']   = array($order['ukuran'],$order['warna']);
			 if((int)$wnlama > 0 || (int)$uklama > 0){
		       // stok produk optionnya nya dibalikin dulu
		       $proses[] = $this->dataModel->updateStokOptionBertambah($nopesan,$order['jml'],$order['warna'],$order['ukuran'],$order['idproduk']);
		     }
		     
			 // stok nya dibalikin dulu
		     $proses[] = $this->dataModel->updateStokBertambah($order['jml'],$order['idproduk']);
			 
			 $this->addCart($data);
		   }
		 } // end foreach bongkar
		 
		 $keranjang = $this->showminiCart($_SESSION['hscart']);
		 $totalitem   = $keranjang['items'];
		 $cart 		= $keranjang['carts'];
		 $subtotal 	= 0;
	     $i      	= 0;
	     $totberat 	= 0;
	     $totjumlah	= 0;
		 
		 //looping cartnya
		 foreach($cart as $c){
		   $this->data['pid']   	= $c['product_id'];
		   $this->data['jml'] 	 	= $c['qty'];
		   $this->data['berat'] 	= $c['berat'];
		   $this->data['harga'] 	= $c['harga'];
		   $this->data['total'] 	= $c['total'];
		
		   $this->data['idwarna']  = $_SESSION['wrncart'][$this->data['pid']][$i];
		   $this->data['idukuran'] = $_SESSION['ukrcart'][$this->data['pid']][$i];
		   
		   if($this->data['idwarna'] !='') $warna	= $this->Fungsi->fcaridata('_warna','warna','idwarna',$this->data['idwarna']);
		   else $warna = '';
		
		   if($this->data['idukuran'] != '') $ukuran = $this->Fungsi->fcaridata('_ukuran','ukuran','idukuran',$this->data['idukuran']);
		   else $ukuran = '';
	
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
		   if(!$this->dataModel->updateOrderProduk($this->data)) {
		      $proses[] = $this->dataModel->updateOrderProduk($this->data);
		      $pesans[] = 'error simpan detail';
		   }
	 	   if($this->data['idwarna'] != '' || $this->data['idwarna'] != '0' || $this->data['idukuran'] != '' || $this->data['idukuran'] != '0') {
			  if(!$this->dataModel->UpdateStokOption($this->data)) {
			     $proses[] = $this->dataModel->UpdateStokOption($this->data);
			     $pesans[] = 'error update stok option' ;
			  }
		   }
		
		   if(!$this->dataModel->UpdateStok($this->data)) {
		      $proses[] = $this->dataModel->UpdateStok($this->data);
		      $pesan[] = 'error update stok' ;
		   }
		
		   $subtotal	+= $this->data['total'];
		   $totberat   += $this->data['berat'];
		   $totjumlah  += (int)$c['qty'];
	
		  
		   $i++;
		
	    } // end foreach looping
		
		$this->data['subtotal'] = $subtotal;
	    $this->data['totjumlah'] = $totjumlah;
	  
	    $tarifkurir = $this->dataShipping->getTarif($servisid,$negara,$propinsi,$kota,$kecamatan,$totberat,$minkilo,$tabeltarif,$detekkdpos,$namashipping);
	   
	    if($tabeldiskon != Null || $tabeldiskon != '') {
		  $zzdizkon = explode("::",$tabeldiskon);
		  $tabels = $zzdizkon[0];
		  $fieldambil = $zzdizkon[1];
          $where = " $zzdizkon[2]='".$servisid."' AND $zzdizkon[3]=1";
					
		  $dtdiskon = $this->Fungsi->fcaridata2($tabels,$fieldambil,$where);
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
		
	    $this->data['kurir'] = $namashipping;
	    $this->data['kurirservis'] = $servisid;
	    $this->data['nopesanan'] = $nopesan;
	    if(!$this->dataModel->UpdateOrder($this->data)) {
		   $proses[] = $this->dataModel->UpdateOrder($this->data);
		   $pesans[] = 'error simpan order';
		}
		
		if(count($pesans) == 0 ) {
		   $pesan = 'sukses|Berhasil';
		   $this->dataModel->prosesTransaksi($proses);
		} else {
		   $pesan = 'gagal|Proses Simpan Gagal';
		}
		
	 }
	 
	 return $pesan;
	 
   }
   
   public function hapusprodukorder() {
     $data     = array();
	 $error    = false;
	 $pesan = '';
	 $pesans = array();
	 $proses = array();
	 $data['idproduk'] = isset($_POST['didproduk']) ? $_POST['didproduk']:'';
	 $data['idmember'] = isset($_POST['didmember']) ? $_POST['didmember']:'';
	 $data['nopesan']  = isset($_POST['dnopesan'])  ? $_POST['dnopesan']:'';
	 $data['qtylama']  = isset($_POST['dqtylama'])  ? $_POST['dqtylama']:0; 
	 $data['uklama']   = isset($_POST['duklama'])   ? $_POST['duklama']:0;
	 $data['wnlama']   = isset($_POST['dwnlama'])   ? $_POST['dwnlama']:0;
	 $data['iddetail'] = isset($_POST['diddetail']) ? $_POST['diddetail']:0;
	 $data['idgrup']   = isset($_POST['didgrup']) ? $_POST['didgrup']:'';
	 $zhrgkurir = isset($_POST['zhrgkurir']) ? $_POST['zhrgkurir']:0;
	 if($data['iddetail'] == '' && $data['iddetail'] == '0'){
	    $pesan = 'Data tidak ada';
		$error = true;
	 } 
	 
	 if($error){
	    $pesan = 'gagal|'.$pesan;
	 } else {
	    //Kurir
		$wdtorder  = "pesanan_no = '".$data['nopesan']."'"; 
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
		   $proses[] = $this->dataModel->updateStokOptionBertambah($data['nopesan'],$data['qtylama'],$data['wnlama'],$data['uklama'],$data['idproduk']);
		}
		// stok nya dibalikin dulu
		$proses[] = $this->dataModel->updateStokBertambah($data['qtylama'],$data['idproduk']);
		
		// Hapus order_detail, order_Detail_option
		$proses[] = $this->dataModel->hapusProdukOrderOption($data);
		
		$bongkar = $this->dataModel->getProdukOrderOption('','','',$data['nopesan']);
	    $data['tipe'] 	  = $data['idgrup'];
		foreach($bongkar as $order) {
		 
		     $produk         = $this->dataProduk->dataProdukByID($order['idproduk']);
			 $data['stok']   = $produk['jml_stok'];
			 $data['produk'] = $produk['nama_produk'];
			 $data['pid']    = $order['idproduk'];
			 $data['jml']	 = $order['jml'];
			 $data['option'] = array($order['ukuran'],$order['warna']);
			 
			 if((int)$data['wnlama'] > 0 || (int)$data['uklama'] > 0){
			 
		       // stok produk optionnya nya dibalikin dulu
		       $proses[] = $this->dataModel->updateStokOptionBertambah($data['nopesan'],$order['jml'],$order['warna'],$order['ukuran'],$order['idproduk']);
			   
		     }
		     
			 // stok nya dibalikin dulu
		     $proses[] = $this->dataModel->updateStokBertambah($order['jml'],$order['idproduk']);
			 
			 $this->addCart($data);
		   
		} // end foreach bongkar
		
		$idmember   = $data['idmember'];
		$idgrup     = $data['idgrup'];
		$keranjang  = $this->showminiCart($_SESSION['hscart']);
		$totalitem  = $keranjang['items'];
		$cart 		= $keranjang['carts'];
		$subtotal 	= 0;
	    $i      	= 0;
	    $totberat 	= 0;
	    $totjumlah	= 0;
		
		 //looping cartnya
		 foreach($cart as $c){
		   $this->data['pid']   	= $c['product_id'];
		   $this->data['jml'] 	 	= $c['qty'];
		   $this->data['berat'] 	= $c['berat'];
		   $this->data['harga'] 	= $c['harga'];
		   $this->data['total'] 	= $c['total'];
		
		   $this->data['idwarna']  = $_SESSION['wrncart'][$this->data['pid']][$i];
		   $this->data['idukuran'] = $_SESSION['ukrcart'][$this->data['pid']][$i];
		   
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
		   
		   $this->data['nopesan'] = $data['nopesan'];
		   if(!$this->dataModel->updateOrderProduk($this->data)) {
		      $proses[] = $this->dataModel->updateOrderProduk($this->data);
		      $pesans[] = 'error simpan detail';
		   }
	 	   if($this->data['idwarna'] != '' || $this->data['idwarna'] != '0' || $this->data['idukuran'] != '' || $this->data['idukuran'] != '0') {
			  
			  if(!$this->dataModel->UpdateStokOption($this->data)) {
			     $proses[] = $this->dataModel->UpdateStokOption($this->data);
			     $pesans[] = 'error update stok option' ;
			  }
		   }
		
		   if(!$this->dataModel->UpdateStok($this->data)) {
		      $proses[] = $this->dataModel->UpdateStok($this->data);
   		      $pesans[] = 'error update stok' ;
		   }
		
		   $subtotal	+= $this->data['total'];
		   $totberat   += $this->data['berat'];
		   $totjumlah  += (int)$c['qty'];
	
		  
		   $i++;
		
	    } // end foreach looping
		
		$this->data['subtotal'] = $subtotal;
	    $this->data['totjumlah'] = $totjumlah;
	  
	    $tarifkurir = $this->dataShipping->getTarif($servisid,$negara,$propinsi,$kota,$kecamatan,$totberat,$minkilo,$tabeltarif,$detekkdpos,$namashipping);
	    
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
	    
	    $this->data['kurir'] = $namashipping;
	    $this->data['kurirservis'] = $servisid;
	    $this->data['nopesanan'] = $data['nopesan'];
	    if(!$this->dataModel->UpdateOrder($this->data)) {
		   $proses[] = $this->dataModel->UpdateOrder($this->data);
		   $pesans[] = 'error simpan order';
		}
		
		if($totjumlah == 0) {
		   if(!$this->dataModel->HapusOrder($this->data)) {
		      $proses[] = $this->dataModel->UpdateOrder($this->data);
		      $pesans[] = 'error simpan order';
		   }
		}
		
		if(count($pesans) == 0 ) {
		   $pesan = 'sukses|Berhasil';
		   $this->dataModel->prosesTransaksi($proses);
		} else {
		   $pesan = 'gagal|Proses Hapus Gagal';
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
	 $produkskrg  = $this->dataProduk->dataProdukByID($idproduk);
	 $stok	  = $produkskrg['jml_stok'];
	 $data['option']   = array($zukuran,$zwarna);
		
	 $cekstok = $this->dataProduk->getOption($idproduk,$data['option'][1],$data['option'][0]);
	 if($cekstok['stok'] > 0) {
	    $stok = $cekstok['stok'];
	 } 
	  
	 if($stok < $qty) {
	    $pesan = 'Maaf, stok tinggal tersedia '.$stok.' item';
		$error = true;
     }
	 if($nopesan == '0'){
	    $pesan = 'No. Pesan tidak ditemukan';
		$error = true;
	 } elseif ((int)$qty < 1){
	    $pesan = 'Jumlah harus lebih dari 0 '.$qty ;
		$error = true;
	 }
	 $tabelcek = '_order_detail INNER JOIN _order_detail_option ON _order_detail.iddetail = _order_detail_option.iddetail';
	 $wherecek = "WHERE pesanan_no = '".$nopesan."' AND product_id='".$idproduk."' AND warnaid='".$zwarna."' AND ukuranid='".$zukuran."'";
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
		$bongkar = $this->dataModel->getProdukOrderOption('','','',$nopesan);
		
		foreach($bongkar as $order) {
		   $produk = $this->dataProduk->dataProdukByID($order['idproduk']);
		   $data['stok']   = $produk['jml_stok'];
		   $data['produk'] = $produk['nama_produk'];
		   $data['pid']    = $order['idproduk'];
		   $data['jml']	   = $order['jml'];
		   $data['option'] = array($order['ukuran'],$order['warna']);
		 
		   // stok produk optionnya nya dibalikin dulu
		   $proses[] = $this->dataModel->updateStokOptionBertambah($nopesan,$order['jml'],$order['warna'],$order['ukuran'],$order['idproduk']);
		   
		     
			// stok nya dibalikin dulu
		   $proses[] = $this->dataModel->updateStokBertambah($order['jml'],$order['idproduk']);
			 
			 $this->addCart($data);
		   
		 } // end foreach bongkar
		 
		 $keranjang = $this->showminiCart($_SESSION['hscart']);
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
		
		   $this->data['idwarna']  = $_SESSION['wrncart'][$this->data['pid']][$i];
		   $this->data['idukuran'] = $_SESSION['ukrcart'][$this->data['pid']][$i];
		   
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
			   if(!$this->dataModel->SimpanOrderDetail($this->data)) {
			      $proses[] = $this->dataModel->SimpanOrderDetail($this->data);
			      $pesans[] = 'error simpan order detail';
			   }
			   if(!$this->dataModel->SimpanOrderDetailOption($this->data)) { 
			      $proses[] = $this->dataModel->SimpanOrderDetailOption($this->data);
			      $pesan[] = 'error simpan detail option' ;
			   }
		   } else {
		      if(!$this->dataModel->updateOrderProduk($this->data)) {
			     $proses[] = $this->dataModel->updateOrderProduk($this->data);
			     $pesans[] = 'error simpan detail';
			  }
		   }
	
	 	   if($this->data['idwarna'] != '' || $this->data['idwarna'] != '0' || $this->data['idukuran'] != '' || $this->data['idukuran'] != '0') {
			  
			  if(!$this->dataModel->UpdateStokOption($this->data)) {
			      $proses[] = $this->dataModel->UpdateStokOption($this->data);
			      $pesans[] = 'error update stok option' ;
			  }
		   }
		
		   if(!$this->dataModel->UpdateStok($this->data)) {
		      $proses[] = $this->dataModel->UpdateStok($this->data);
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
	    if(!$this->dataModel->UpdateOrder($this->data)) {
		   $proses[] = $this->dataModel->UpdateOrder($this->data);
		   $pesans[] = 'error simpan order';
		}
		
		if(count($pesans) == 0 ) {
		   $pesan = 'sukses|Berhasil';
		   $this->dataModel->prosesTransaksi($proses);
		} else {
		   $pesan = 'gagal|Gagal Proses Tambah Produk';
		}
		
	 }
	 return $pesan;
   }
   public function editKurir(){
      $dkurir = isset($_POST['dkurir']) ? $_POST['dkurir']:'';
	  $hrgkurir = isset($_POST['hrgkurir']) ? $_POST['hrgkurir']:0;
	  $nopesan = isset($_POST['nopesan']) ? $_POST['nopesan']:0;
	  $negara = isset($_POST['nnegaraid']) ? $_POST['nnegaraid']:'';
	  $propinsi = isset($_POST['npropid']) ? $_POST['npropid']:'';
	  $kota = isset($_POST['nkotaid']) ? $_POST['nkotaid']:'';
	  $kecamatan = isset($_POST['nkecid']) ? $_POST['nkecid']:'';
	  $totberat = isset($_POST['ntotberat']) ? $_POST['ntotberat']:0;
	  $hasil = '';
	  $proses = array();
	  $zdata		= explode(":",$dkurir);
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
	 // echo '$tabeltarif ='.$tabeltarif.' ';
	  $tarifkurir = $this->dataShipping->getTarif($servisid,$negara,$propinsi,$kota,$kecamatan,$totberat,$minkilo,$tabeltarif,$detekkdpos,$namashipping);
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
	  //echo '$tarifkurir[1] = '.$tarifkurir[1].' ';
	  $tarifkurir[1] = $tarifkurir[1] - ($tarifkurir[1]*$zdiskon);
	  $tarifkurir[4] = $tarifkurir[4] - ($tarifkurir[4]*$zdiskon);
	  //echo 'tarif kurir 1 ='.$tarifkurir[1].' & tarif kurir 2 ='.$tarifkurir[2];
	  if($tarifkurir[1] > 0) {
	     $this->data['tarifkurir'] = $tarifkurir[1];
		 $this->data['satuantarifkurir'] = $tarifkurir[4];
	  } else {
	     $this->data['tarifkurir'] = $hrgkurir;
		 $this->data['satuantarifkurir'] = 0;
	  }
	  
	  $this->data['kurir'] = $namashipping;
	  $this->data['kurirservis'] = $servisid;
	  $this->data['nopesanan'] = $nopesan;
	  
	  if(!$this->dataModel->simpanEditKurir($this->data)){
	     $hasil = 'gagal|Gagal Mengubah Tarif Kurir';
	  } else {
	     $this->dataModel->prosesTransaksi($proses);
	     $hasil = 'sukses|Berhasil Mengubah Tarif Kurir';
	  }
	  
	  return $hasil;
	  
	  
   }
   
   public function keranjangInfo($hcart){
     $platcart = '<ul class="dropdown-menu pull-right">';
     if($hcart) {
	     $zsubtotal = 0;
		 $platcart .='<li>';
         $platcart .='<div>';
         $platcart .='<table class="table table-striped">';
         $platcart .='<tr>';
         $platcart .='<td>Produk</td>';
         $platcart .='<td class="text-right">QTY</td>';
         $platcart .='<td class="text-right">Total</td>';
         $platcart .='</tr>';
	     foreach($hcart as $hc){
		    $zsubtotal += $hc['total'];
		    $platcart.= '<tr>';
            $platcart.= '<td>'.$hc['product'].'</td>';
            $platcart.='<td class="text-right">'.$hc['qty'].'</td>';
            $platcart.='<td class="text-right">Rp. '.$this->Fungsi->fuang($hc['total']).'</td>';
            $platcart.='</tr>';
		 } 
		 $platcart .= '</table>';
         $platcart .='</div>';
         $platcart .='</li>';
         $platcart .='<li>';
         $platcart .='<div>';
         $platcart .='<table class="table">';
         $platcart .='<tr>';
         $platcart .='<td colspan="3" class="text-right">Total</td>';
         $platcart .='<td class="text-right">Rp. '.$this->Fungsi->fuang($zsubtotal).'</td>';
		 $platcart .='</tr>';
         $platcart .='<tr>';
         $platcart .='<td colspan="4" class="text-right"><a href="'.URL_PROGRAM.'cart" class="btn btn-sm btn-primary">Lihat Keranjang Belanja</a></td>';
         $platcart .='</tr>';
         $platcart .='</table>';
         $platcart .='</div>';
         $platcart .='</li>';
      } else {
	     $platcart .= '<li><p>Masih Kosong</p></li>';
	  }
	  $platcart .='</ul>';
	  return $platcart;
   }
   
   public function keranjangKasir(){
       if(isset($_SESSION['usermember'])) {
		  $nama 		= isset($_POST['rnama']) ? $_POST['rnama']:'';
		  $telp			= isset($_POST['rtelp']) ? $_POST['rtelp']:'';
		  $alamat 		= isset($_POST['ralamat']) ? $_POST['ralamat']:'';
		  $negara		= isset($_POST['rnegara']) ? $_POST['rnegara']:'0';
		  $propinsi		= isset($_POST['rpropinsi']) ? $_POST['rpropinsi']:'0';
		  $kabupaten	= isset($_POST['rkabupaten']) ? $_POST['rkabupaten']:'0';
		  $kecamatan	= isset($_POST['rkecamatan']) ? $_POST['rkecamatan']:'0';
		  $kelurahan	= isset($_POST['rkelurahan']) ? $_POST['rkelurahan']:'';
		  $kodepos		= isset($_POST['rkdpos']) ? $_POST['rkdpos']:'';
              
		  if($nama == '') $pesan[] = 'Masukkan Nama';
		  if($alamat == '') $pesan[] = 'Masukkan Alamat';
		  if($negara == '' || $negara=='0') $pesan[] = 'Pilih Negara';
		  if($propinsi == '' || $propinsi=='0') $pesan[] = 'Pilih Propinsi';
		  if($kabupaten == '' || $kabupaten=='0') $pesan[] = 'Pilih Kabupaten';
		  if($kecamatan == '' || $kecamatan=='0') $pesan[] = 'Pilih Kecamatan';
			 
		  if(count($pesan) > 0){
			 if(count($pesan) == 11) {
			     $hasil = 'gagal|Lengkapi data alamat tujuan dibawah ini';
			 } else {
			    $hasil = implode("<br>",$pesan);
			    $hasil = 'gagal|'.$hasil;
				
			 }
		   } else {
			 $_SESSION['checkout']		= 'ya';
			 $_SESSION['frmnama'] 		= $nama;
			 $_SESSION['frmtelp'] 		= $telp;
			 $_SESSION['frmalamat'] 	= $alamat;
			 $_SESSION['frmnegara']   	= $negara;
			 $_SESSION['frmpropinsi']	= $propinsi;
			 $_SESSION['frmkabupaten']	= $kabupaten;
			 $_SESSION['frmkecamatan']	= $kecamatan;
			 $_SESSION['frmkelurahan']	= $kelurahan;
			 $_SESSION['frmkodepos']		= $kodepos;
			 $hasil = 'sukses|'.URL_PROGRAM.'cart/metode';
				  
		   }
	   } else {
	      $hasil = 'gagal|Anda belum login';
	   }
	   return $hasil;
   }
   
   public function metodePengiriman(){
      $hasil = '';
      if($_SESSION['checkout'] == 'ya' && isset($_SESSION['usermember'])) {
		$servis = isset($_POST['serviskurir']) ? $_POST['serviskurir']:'';
		$poin = isset($_POST['poinku']) ? $_POST['poinku']:'';
		$idmember = $_SESSION['idmember'];
		
		if($servis == '') {
		   $hasil = 'gagal|Pilih Servis Kurir';
		} elseif($poin != '' && $poin != '0') {
		  $checkpoin = $this->dataModel->checkPoin($idmember,$poin);
           if($checkpoin < 1){
             $hasil = 'gagal|Anda tidak memiliki poin';
           }		  
		   
		} else {
		   $_SESSION['konfirm'] = 'ya';
		   $_SESSION['frmservis'] = $servis;
		   $_SESSION['poin'] = $poin;
		   $hasil = 'sukses';
		 }
		
	  } else {
	     $hasil = 'gagal|Anda belum login';
	  }
	  return $hasil;
   }
}
?>

