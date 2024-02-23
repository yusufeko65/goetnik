<?php
//require_once DIR_MODEL."modelProduk.php";
class controller_Produk {
   private $page;
   private $rowsproduk;
   private $offset;
   private $dataModel;
   private $Fungsi;
   private $data = array();

   
	public function __construct(){
		$this->dataModel= new model_Produk();
		$this->Fungsi= new FungsiUmum();
		
	}
	public function produkAutocomplete(){
		$json 	= array();
	 
		$grupmember = isset($_GET['grupreseller']) ? $_GET['grupreseller']:'';
		$cari		 = isset($_GET['cariproduk']) ? $_GET['cariproduk']:'';
		$produk = $this->dataModel->getProduksBy($cari);
		foreach ($produk as $prod) {
			$ukurans = $this->dataModel->getProdukOption($prod['idproduk'],'ukuran');
			$uk = isset($ukurans[0]['id']) ? $ukurans[0]['id']:0;
			$warna = $this->dataModel->getProdukWarnaByUkuran($prod['idproduk'],$uk);
			
			$hargadiskon = $this->dataModel->getProdukDiskons($prod['idproduk'],$grupmember);
		
			$json[] = array(
				'product_id' => $prod['idproduk'],
				'kode'		 => $prod['kode_produk'],
				'name'       => strip_tags(html_entity_decode($prod['nama_produk'], ENT_QUOTES, 'UTF-8')),
				'keterangan' => $prod['keterangan_produk'],
				'ukuran'     => $ukurans,
				'warna'		 => $warna,
				'harga'      => $hargadiskon,
				'stok'		 => $prod['jml_stok'],
				'berat'		 => $prod['berat_produk'],
				'satuan'	 => $prod['hrg_jual'],
				'gbr'		 => $prod['gbr_produk']
				
			);
		}
		return $json;
	 
	}
	public function tampildata($jenis,$id,$rows){
		$this->page 	    = isset($_GET['page']) ? intval($_GET['page']) : 1;
	
		$this->rowsproduk	= !isset($this->rowsproduk) || $this->rowsproduk == '' ? $rows : 12;
		$result 			= array();
		$filter				= array();
		$where 				= '';
		$caridata			= isset($_GET['q']) ? $_GET['q']:'';
		$sortir				= isset($_GET['sort']) ? $_GET['sort']:'new';
	
		if($jenis == 'kategori' && $id != '') {
			/*
			$tableprod = "_produk LEFT JOIN _produk_kategori ON _produk.idproduk = _produk_kategori.idproduk";
			$wprod = ' WHERE _produk_kategori.idkategori = '.$id;
			$jmlprod = $this->Fungsi->fjumlahdata($tableprod,$wprod);
			if($jmlprod > 0) {
				$filter[] = "  _produk_kategori.idkategori = '".$id."'";
			} else {
				$idinduk = $this->Fungsi->fcaridata("_category","parent_id","category_id",$id);
		  
			if($idinduk == 0) {
				$idchilds = $this->Fungsi->fcaridata3("_category","category_id","parent_id='".$id."'");
			 
				if(count($idchilds) > 0) {
					$dataidchilds = implode(",",$idchilds);
				} else {
					$dataidchilds = '0'; 
				}
					$filter[] = "  _produk_kategori.idkategori in (".$dataidchilds.")";
				}
			}
			*/
			$filter[] = "  _produk_kategori.idkategori = '".$id."'";
		}
		
		if($jenis == 'warna' && $id != '') $filter[] = "  _produk_options.warna = '".$id."' AND stok > 0";
		if($jenis == 'ukuran' && $id != '') $filter[] = "  _produk_options.ukuran = '".$id."' AND stok > 0 GROUP BY _produk_options.idproduk";
	
		if(trim($caridata) != '') $filter[] = " _produk_deskripsi.nama_produk like '%".trim(strip_tags($caridata))."%'";
	
	
		switch($sortir){
			 default:
			 case "upd":
				$sort = "_produk.date_updated desc";
			 break;
			 case "new";
				$sort = "_produk.idproduk desc";
			 break;
			 case "old";
				$sort = "_produk.idproduk asc";
			 break;
			 case "hrgdesc":
				$sort = "_produk.hrg_jual desc";
			 break;
			 case "hrgasc":
				$sort = "_produk.hrg_jual asc";
			 break;
			 case "namaasc":
				$sort = "_produk_deskripsi.nama_produk asc";
			 break;
			 case "namadesc":
				$sort = "_produk_deskripsi.nama_produk desc";
			 break;
			 
		}
	
		if(!empty($filter))	$where = implode(" and ",$filter);
	
	
		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rowsproduk;
		
		$result["total"]   		= $this->dataModel->totalProduk($where,$jenis);
		$result["rows"]    		= $this->dataModel->getProdukLimit($this->offset,$this->rowsproduk,$jenis,$where,$sort);
		$result["page"]    		= $this->page; 
		$result["baris"]   		= $this->rowsproduk;
		$result["jmlpage"] 		= ceil(intval($result["total"])/intval($result["baris"]));
		
		return $result;
	}
     
	public function getProduk(){
		return $this->dataModel->getProduk();
	}
  
	public function getProdukOption($id,$tipe){
		return $this->dataModel->getProdukOption($id,$tipe);
	}
  
	public function getHargaProdukGrupCustomerByID($pid,$tipemember,$config_memberdefault) {
		return $this->dataModel->getHargaProdukGrupCustomerByID($pid,$tipemember,$config_memberdefault);
	}
  
	public function getDiskonProdukGrupCustomerByID($pid) {
		return $this->dataModel->getDiskonProdukGrupCustomerByID($pid);
	}
  
	public function getProdukWarna($idproduk){
		return $this->dataModel->getProdukWarna($idproduk);
	}
	public function getProdukSemuaWarna($idproduk){
		return $this->dataModel->getProdukSemuaWarna($idproduk);
	}
	public function getProdukWarnaByUkuran($idproduk,$ukuran){
		return $this->dataModel->getProdukWarnaByUkuran($idproduk,$ukuran);
	}
  
	public function getPoinProdukByPIDgCust($pid,$tipemember){
		return $this->dataModel->getPoinProdukByPIDgCust($pid,$tipemember);
	}
  
	public function getProdukImages($idproduk){
		return $this->dataModel->getProdukImages($idproduk);
	}
	public function getProdukImagesDetail($idproduk){
		return $this->dataModel->getProdukImagesDetail($idproduk);
	}
	public function getProdukImagesbyWarna($idproduk,$warna,$ukuran){
		return $this->dataModel->getProdukImagesbyWarna($idproduk,$warna,$ukuran);
	}
	/*
	public function getProdukStokOption($idproduk,$warna,$ukuran){
		return $this->dataModel->getProdukStokOption($idproduk,$warna,$ukuran);
	}
  */
	public function getProdukDiskon($id,$grup){
		return $this->dataModel->getProdukDiskon($id,$grup);
	}
	public function getProdukDiskons($id,$grup){
		return $this->dataModel->getProdukDiskons($id,$grup);
	}
	public function checkDataProdukByID($pid){
		return $this->dataModel->checkDataProdukByID($pid);
	}
  
	public function checkDataKategori($pid,$j){
		return $this->dataModel->checkDataKategori($pid,$j);
	}
  
	public function dataProdukByID($iddata){
		return $this->dataModel->getProdukByID($iddata);
	}
	public function getResellerGrup($tipe){
		return $this->dataModel->getResellerGrup($tipe);
	}
	public function getGambarProduk($id){
		return $this->dataModel->getGambarProduk($id);
	}
	public function getKategoriProduk($id){
		return $this->dataModel->getKategoriProduk($id);
	}
	public function getOptionProduk($id){
		return $this->dataModel->getOptionProduk($id);
	}
	public function getOption($id,$warna,$ukuran){
		return $this->dataModel->getOption($id,$warna,$ukuran);
	}
	public function getHarga($pid,$tipe){
		return $this->dataModel->getHarga($pid,$tipe);
	}
	public function getPotonganByGrupCustomer($tipe){
		return $this->dataModel->getHarga($pid,$tipe);
	}
	public function getCover($pid,$gbrawal){
		$gbr = $this->dataModel->getCover($pid);
		if($gbr['produk_gbr'] != '' || !empty($gbr['produk_gbr'])) {
			return $gbr['produk_gbr'];
		} else {
			return $gbrawal;
		}
	}
	public function getProdukOrder($nopesan,$produkid,$ukuranid,$warnaid,$tipe){
		return $this->dataModel->getProdukOrder($nopesan,$produkid,$ukuranid,$warnaid,$tipe);
	}
	public function getProdukOrderWarnaByUkuran($idproduk,$ukuran,$warna,$nopesan) {
		return $this->dataModel->getProdukOrderWarnaByUkuran($idproduk,$ukuran,$warna,$nopesan);
	}
	public function getCoverByWarna($pid,$warna){
		return $this->dataModel->getCoverByWarna($pid,$warna);
	}
	public function getPoinProdukGrupCustomerByID($pid) { 
		return $this->dataModel->getPoinProdukGrupCustomerByID($pid);
	}
	public function getProdukRelateByKategori($idkat){
		return $this->dataModel->getProdukRelateByKategori($idkat);
	}
	public function getModuleProdukSale($jmlproduk){
		return $this->dataModel->getModuleProdukSale($jmlproduk);
	}
	public function dataHeadProdukByID($id) {
		return $this->dataModel->getHeadProdukByID($id);
	}
	public function warnaProduk(){
		$idukuran= isset($_GET['ukuran']) ? $_GET['ukuran']:'';
		$idproduk = isset($_GET['produk_id']) ? $_GET['produk_id']:'0';
		$datawarnaproduk = $this->dataModel->getProdukWarnaUkuran($idproduk,$idukuran);
		$stok = $this->dataModel->jumlahStokByUkuran($idproduk,$idukuran);
		echo json_encode(array("warna"=>$datawarnaproduk,"jmlwarna"=>count($datawarnaproduk),"stok"=>$stok));
	}
	public function getProdukByHeadproduk($headproduk){
		return $this->dataModel->getProdukByHeadproduk($headproduk);
	}
	public function getWarnaHeadProduk($id) {
		return $this->dataModel->getWarnaHeadProduk($id);
	}
	public function stokProdukWarnaUkuran(){
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			foreach ($_POST as $key => $value) {
				$data["$key"]	= isset($_POST["$key"]) ? $value : '';
			}
			$stok = $this->dataModel->stokProdukWarnaUkuran($data);
			echo json_encode(array("stok"=>$stok.' pcs'));
		}
	}
}
?>
