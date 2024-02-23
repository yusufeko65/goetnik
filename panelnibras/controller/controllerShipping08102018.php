<?php
class controllerShipping {
  
	private $page;
	private $rows;
	private $offset;
	private $db;
	private $model;
	private $dataFungsi;
	private $data=array();
      
	function __construct(){
		$this->model= new modelShipping();
		$this->dataFungsi= new FungsiUmum();
	}
	
	public function tampildata()
	{
		$this->page 	= isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->rows		= 10;
		$result 			= array();
		$filter				= array();
		
		
		$data['caridata']	= isset($_GET['datacari']) ? $_GET['datacari']:'';
		
		$result["total"] = 0;
		$result["rows"] = '';
		
		$this->offset = ($this->page-1)*$this->rows;

		$result["total"]   = $this->model->totalShipping($data);
		$result["rows"]    = $this->model->getShippingLimit($this->offset,$this->rows,$data);
		$result["page"]    = $this->page; 
		$result["baris"]   = $this->rows;
		$result["jmlpage"] = ceil(intval($result["total"])/intval($result["baris"]));
		
		return $result;
	}
	public function getAllServicesAndTarifByWilayah($propinsi,$kabupaten,$kecamatan){
		return $this->model->getAllServicesAndTarifByWilayah($propinsi,$kabupaten,$kecamatan);
	}
	public function tarifkurir(){
		$wil = '';
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data = [];
			$wilayah = [];
			foreach ($_POST as $key => $value) {
				$data["$key"]	= isset($_POST["$key"]) ? $value : '';
				if($key == 'kecamatan_penerima'){
					$wilayah[] = $value;
				}
			}
			$cektarif = $this->model->tarifkurir($data);
			if($cektarif == 'Konfirmasi Admin') {
				$tarif = 'Konfirmasi Admin';
				$nilaitarif = 0;
				$total = 'Konfirmasi Admin';
				$nilaitotal = 0;
			} else {
				$tarif = "Rp. ".$this->dataFungsi->fuang($cektarif);
				$nilaitarif = $cektarif;
				$nilaitotal = (int)$data['subtotal'] + $cektarif;
				$total = "Rp. ".$this->dataFungsi->fuang($nilaitotal);
			}
			if(count($wilayah) > 0){
				$wil = implode(",",$wilayah).','.$data['serviskurir'];
			}
			$result = 'valid';
			$status = 'success';
		} else {
			$status = 'error';
			$result = 'Tidak valid';
			$tarif = '';
			$nilaitarif = 0;
			$nilaitotal = 0;
			$total = '';
		}
		
		echo json_encode(array("status"=>$status,"tarif"=>$tarif,"nilaitarif"=>$nilaitarif,"total"=>$total,"nilaitotal"=>$nilaitotal,"wil"=>$wil));
	}
}
?>
