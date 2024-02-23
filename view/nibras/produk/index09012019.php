<?php
$dtCart 		= new controller_Cart();
$dtProduk 		= new controller_Produk();
include path_to_includes.'bootcart.php';
if($amenu=='kategori' || $amenu=='detail' || $amenu == 'produsen' || $amenu == 'warna' || $amenu == 'ukuran' || $amenu == 'produk-sale' || $amenu == 'katalog'){
	$pid = isset($_GET['pid']) ? $_GET['pid']:'';
	$j = isset($_GET['j']) ? $_GET['j']:'';
	$alias = $j;
}

$check = false;

switch($amenu){
	case "produkhead":
		$headproduk = $dtProduk->dataHeadProdukByID($pid);
		
		if($headproduk) {
			$dataproduk = $dtProduk->getProdukByHeadproduk($headproduk['dataproduk']['head_idproduk']);
			$file = '/headproduk.php';
			$script 	= '<script type="text/javascript" src="'.URL_THEMES.'assets/js/enscroll-0.6.2.min.js"></script>';
			$script 	.= '<script type="text/javascript" src="'.URL_THEMES.'assets/js/produkhead.js"></script>';
			$warna = $dtProduk->getWarnaHeadProduk($pid);
			$breadcrumbs = '<a href="'.URL_PROGRAM.'">Home</a> | '.stripslashes($headproduk['dataproduk']['nama_produk']);
		} else{
			$folder = 'error/';
			$file = 'error.php';
		}
	break;
	case "produk":
        $modul 			= isset($_GET['modul']) ? $_GET['modul']:'';
        $check 			= true;
		$dataview  		= $dtProduk->tampildata('','');
		$total 	   		= $dataview['total'];
		$baris 	   		= $dataview['baris'];
		$page 	   		= $dataview['page'];
		$jmlpage   		= $dataview['jmlpage'];
		$ambildata 		= $dataview['rows'];
		$cari 			= isset($_GET['q']) ? urlencode($_GET['q']):'';
		$linkpage 		= '/';
		$linkcari 		= '';
		$sort			= isset($_GET['sort']) ? $_GET['sort']:'';
		if($sort!='') $link[] = 'sort='.trim(strip_tags($sort));
		if($cari!='') $link[] = 'datacari='.trim(strip_tags($cari));
		
		if(!empty($link)){
		   $linkcari = implode("&",$link);
		   $linkcari = '?'.$linkcari;
		}
		$file='/produk.php';
	break;
  
	case "kategori":
		
		$datakategori  = $dtKategori->getKategoriByIDAlias($pid,$j);
	
		if(count($datakategori) > 0) {
			$check = true;
			$link  = array();
			$dataview  		= $dtProduk->tampildata('kategori',$pid,$config_produkkategori);
			$total 	   		= $dataview['total'];
			$baris 	   		= $dataview['baris'];
			$page 	   		= $dataview['page'];
			$jmlpage   		= $dataview['jmlpage'];
			$ambildata 		= $dataview['rows'];
			$cari 			= isset($_GET['datacari']) ? $_GET['datacari']:'';
			$linkpage 		= $j.'/';
			$linkcari 		= '';
			$sort			= isset($_GET['sort']) ? $_GET['sort']:'';
			if($sort!='') $link[] = 'sort='.trim(strip_tags($sort));
			if($cari!='') $link[] = 'datacari='.trim(strip_tags($cari));
			$dtPaging 		= new Paging();
			if(!empty($link)){
			   $linkcari = implode("&",$link);
			   $linkcari = '?'.$linkcari;
			}
			$namakategori	= $datakategori['name'];
			$deskripsikategori = $datakategori['description'];
			$imagekategori = $datakategori['image'];
			$breadcrumbs = '<a href="'.URL_PROGRAM.'">Home</a> | '.stripslashes($namakategori);
			$amenu='';
			$judul = $namakategori.' | '.$config_jdlsite.' - '.$config_namatoko;
					
			$file = '/kategori.php';
		}
		$script = '';
		$file = '/kategori.php';
	break;
 
	case "detail":
		$url = '';
		$script = '';
		if(isset($_GET['load'])) {
			switch($_REQUEST['load']) {
				case "warna":
					$dtProduk->warnaProduk();
				break;
				case "stok":
					$dtProduk->stokProdukWarnaUkuran();
				break;
			}
		   
			exit; 
		   
		} else {
	      
			$detail = $dtProduk->dataProdukByID($pid);
		  
			if($detail) {
				
				$gbrdetail = $dtProduk->getProdukImagesDetail($pid);
				
				$dataukuran = $dtProduk->getProdukOption($pid,'ukuran');
				$jmlukuran  = count($dataukuran);
				$uk 		= isset($dataukuran[0]['id']) ? $dataukuran[0]['id']:0;
				$ukuran 	= isset($dataukuran[0]['nm']) ? $dataukuran[0]['nm']:'';
				$harga_tambahan = isset($dataukuran[0]['tambahan_harga']) ? $dataukuran[0]['tambahan_harga'] : 0;
				
				$warna 		= $dtProduk->getProdukWarna($pid);
				
				$hargasatuan = $detail['hrg_jual'];
				if($jmlukuran == 1) {
					$hargasatuan = $hargasatuan + $harga_tambahan;
				}
				$hargacust = 0;
				$hargacustdiskon = 0;
				//$hargasatuandiskon = 0;
				$persen = (int)$grup_diskon + (int)$detail['persen_diskon'];
				$hargasatuandiskon = $hargasatuan - (($hargasatuan * $persen)/100);
				$labelhargacust = '';
				$labelsale = '';
				
				if($idmember != '') {
					
					//$hargacust = $detail['hrg_jual'] - (($detail['hrg_jual']*$persen/100));
					$hargacust = $hargasatuan - (($hargasatuan*$persen/100));
					if($detail['sale'] == '1') {
						$labelsale 	 = '<img src="'.URL_THEMES.'assets/img/sale.png'.'">';
						//$hargasatuandiskon = $detail['hrg_diskon'];
						
					} 
					if($grup_min_beli > 1) {
						
						if($hargasatuandiskon > 0) {
							$labelhargasatuan  = '<div class="oldprice" id="caption_price_old"><small>Rp. '.$dtFungsi->fuang($hargasatuan).'</small></div>';
							$labelhargasatuan .= '<div class="persendiskon">'.$detail['persen_diskon'].'% </div>';
							$labelhargasatuan .= '<div class="newprice" id="caption_price">Rp. '.$dtFungsi->fuang($hargasatuandiskon).'</div>';
							
						} else {
							$labelhargasatuan  = '<div class="price" id="caption_price">Rp. '.$dtFungsi->fuang($hargasatuan).'</div>';
						}
					} else {
						
						$labelhargasatuan  = '<div class="oldprice" id="caption_price_old">Rp. '.$dtFungsi->fuang($hargasatuan).'</div>';
						$labelhargasatuan .= '<div class="persendiskon">'.$persen.'% </div>';
					}
					$labelhargacust = '<div class="newprice" id="caption_price">Rp. '.$dtFungsi->fuang($hargacust).'</div>';
					$labelhargasatuan .= '<input type="hidden" id="hargacust" value="'.$hargacust.'">';
				} else {
					if($detail['sale'] == '1') {
						$labelsale 	 = '<img src="'.URL_THEMES.'assets/img/sale.png'.'">';
						//$hargasatuandiskon = $detail['hrg_diskon'];
						$labelhargasatuan  = '<div class="oldprice" id="caption_price_old">Rp. '.$dtFungsi->fuang($hargasatuan).'</div>';
						$labelhargasatuan .= '<div class="persendiskon">'.$detail['persen_diskon'].'% </div>';
						$labelhargasatuan .= '<div class="newprice" id="caption_price">Rp. '.$dtFungsi->fuang($hargasatuandiskon).'</div>';
						//$labelhargasatuan .= '<input type="hidden" id="hargasatuan" value="'.$hargasatuandiskon.'">';
					} else {
						$labelhargasatuan  = '<div class="price" id="caption_price">Rp. '.$dtFungsi->fuang($hargasatuan).'</div>';
						
					}
					$labelhargasatuan .= '<input type="hidden" id="hargacust" value="'.$hargasatuan.'">';
			
				}
				$labelhargasatuan .= '<input type="hidden" id="hargasatuan" value="'.$hargasatuan.'">';
				
				if($grup_min_beli_syarat == '1') {
					$syaratbeli = 'Per Jenis Produk';
				} elseif ($grup_min_beli_syarat == '2') {
					$syaratbeli = 'Bebas Campur';
				} else {
					$syaratbeli = '';
				}
				
				$labelminbeli = '<small> (Minimal '.$grup_min_beli.' atau lebih, '.$syaratbeli.')</small>';
				
				/* metatag seo untuk detail produk */
				$config_jdlsite = stripslashes($detail['nama_produk']).' | '.stripslashes($config_namatoko);
				if( $detail['metatag_deskripsi'] != ''){
					$config_deskripsitag = stripslashes($detail['metatag_deskripsi']);
				} else {
					$config_deskripsitag = trim(strip_tags(stripslashes(html_entity_decode($detail['keterangan_produk']))));
					$config_deskripsitag = str_replace("\n",". ",$config_deskripsitag);
				}
				
				if ($detail['metatag_keyword'] != '') {
					$config_keywordtag = stripslashes($detail['metatag_keyword']);
				} else {
					$config_keywordtag = stripslashes($detail['nama_produk']);
				}
				/* @end metatag seo untuk detail produk */
			  
				
				$script 	= '<script type="text/javascript" src="'.URL_THEMES.'assets/js/enscroll-0.6.2.min.js"></script>';
				$script 	.= '<script type="text/javascript" src="'.URL_THEMES.'assets/js/produk.js"></script>';
				$script 	.= '<script type="text/javascript" src="'.URL_THEMES.'assets/js/cart.js"></script>';
				
				$file = '/detailprod.php';
				
				if($detail['gbr_produk'] != '' || $detail['gbr_produk'] != null) {
					$gambarshare = $config_alamatsite.URL_IMAGE.'_zoom/zoom_'.$detail['gbr_produk'];
				}
				$breadcrumbs = '<a href="'.URL_PROGRAM.'">Home</a> | '.stripslashes($detail['nama_produk']);
			
			} else {
				$folder = 'error/';
				$file = 'error.php';
			}
		}
	  
	break;
	
}
include DIR_THEMES."header.php";
?>
<main>
	
	<?php include DIR_THEMES."/module/bannertop.php";?>
	<div class="container"><small class="breadcrumbs"><?php echo $breadcrumbs ?></small></div>
	<?php if($file!='') include DIR_THEMES.$folder.$file; ?>

</main>
 
<?php include DIR_THEMES."script.php";?>
<?php echo $script ?>
<?php include DIR_THEMES."footer.php";?>