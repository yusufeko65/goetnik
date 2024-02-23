<?php

class controller_Order {
	private $page;
	private $rows;
	private $offset;
	private $dataModel;
	private $Fungsi;
	private $data=array();
	private $idlogin;
   
	public function __construct(){
		$this->dataModel= new model_Order();
		$this->Fungsi= new FungsiUmum();
		$this->idlogin = isset($_SESSION['idmember']) ? $_SESSION['idmember']:'';
	}
   
	public function cekOrder($noorder) {
		return $this->dataModel->cekOrder($noorder,$this->idlogin);
	}
	
	public function getLastOrder($idmember,$status_order,$limit){
		return $this->dataModel->getLastOrder($idmember,$status_order,$limit);
	}
	
	public function tampildata(){
		$this->page 	    = isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->rows			=  10;
	
		$result 			= array();
		$filter				= array();
		$where 				= '';
		$caridata			= isset($_GET['q']) ? $_GET['q']:'';
		$sortir				= isset($_GET['sort']) ? $_GET['sort']:'';
	
		$result["total"] = 0;
		$result["rows"] = '';
		$this->offset = ($this->page-1)*$this->rows;

		$result["total"]   = $this->dataModel->totalOrder($where);
		$result["rows"]    = $this->dataModel->getOrder($this->offset,$this->rows,$where);
		$result["page"]    = $this->page; 
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
		
		return $result;
	}
     
	public function getOrder(){
		return $this->dataModel->getOrder();
	}
  
	public function getOrderOption($id,$tipe){
		return $this->dataModel->getOrderOption($id,$tipe);

	}
  
	public function getOrderWarna($idproduk){
		return $this->dataModel->getOrderWarna($idproduk);
	}

	public function getOrderImages($idproduk){
		return $this->dataModel->getOrderImages($idproduk);
	}

	public function getOrderImagesbyWarna($idproduk,$warna){
		return $this->dataModel->getOrderImagesbyWarna($idproduk,$warna);

	}
  
	public function getOrderDiskon($id,$grup){
		return $this->dataModel->getOrderDiskon($id,$grup);
	}

	public function checkDataOrderByID($pid){
		return $this->dataModel->checkDataOrderByID($pid);
	}
  
	public function checkDataKategori($pid,$j){
		return $this->dataModel->checkDataKategori($pid,$j);
	}

	public function dataOrderByID($noorder){
		return $this->dataModel->getOrderByID($noorder);
	}
  
	public function dataOrderDetail($noorder){
		return $this->dataModel->getOrderDetail($noorder);
	}

	public function dataOrderStatus($noorder){
		return $this->dataModel->getOrderStatus($noorder);
	}

	public function dataOrderAlamat($noorder){
		return $this->dataModel->getOrderAlamat($noorder);
	}

	public function getResellerGrup($tipe){
		return $this->dataModel->getResellerGrup($tipe);
	}
	public function getGambarOrder($id){
		return $this->dataModel->getGambarOrder($id);
	}
	public function getKategoriOrder($id){
		return $this->dataModel->getKategoriOrder($id);
	}
	public function getOptionOrder($id){
		return $this->dataModel->getOptionOrder($id);
	}
	public function getOption($id,$warna,$ukuran){
		return $this->dataModel->getOption($id,$warna,$ukuran);
	}
	public function getHarga($pid,$tipe){
		return $this->dataModel->getHarga($pid,$tipe);
	}
}
?>
