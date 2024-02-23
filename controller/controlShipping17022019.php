<?php
class controller_Shipping {
	private $dataModel;
	private $Fungsi;
   
      
	public function __construct(){
		$this->dataModel= new model_Shipping();
		$this->Fungsi	= new FungsiUmum();
	}
   
  
	public function getShipping(){
		return $this->dataModel->getShipping();
	}
	public function getServis($tabel) {
		return $this->dataModel->getServis($tabel);
	}
	public function getAllServices(){
		return $this->dataModel->getAllServices();
	}
	public function getAllServicesAndTarifByWilayah($propinsi,$kabupaten,$kecamatan){
		return $this->dataModel->getAllServicesAndTarifByWilayah($propinsi,$kabupaten,$kecamatan);
	}
	public function getAllServicesAndTarifByWilayahJson(){
		foreach($_POST as $key => $value){
			${$key} = trim($value);
		}
		$services = $this->dataModel->getAllServicesAndTarifByWilayah($propinsi,$kabupaten,$kecamatan);
		if($services) {
			$status = 'success';
		} else {
			$status = 'error';
		}
		echo json_encode(array("status"=>$status,"result"=>$services));
	}
	public function getServisbyId($tabel,$id) {
		return $this->dataModel->getServisbyId($tabel,$id);
	}
	
	public function getShippingbyName($kurir) {
		return $this->dataModel->getShippingbyName($kurir);
	}
	
	public function tarifkurir(){
		$wil = '';
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$data = [];
			$wilayah = [];
			foreach ($_POST as $key => $value) {
				$data["$key"]	= isset($_POST["$key"]) ? $value : '';
				if($key == 'kecamatan_penerima'){
					$wilayah[] = $key;
				}
			}
			$cektarif = $this->dataModel->tarifkurir($data);
			if($cektarif == 'Konfirmasi Admin') {
				$tarif = 'Konfirmasi Admin';
				$nilaitarif = 0;
				$total = 'Konfirmasi Admin';
				$nilaitotal = 0;
			} else {
				$tarif = "Rp. ".$this->Fungsi->fuang($cektarif);
				$nilaitarif = $cektarif;
				$nilaitotal = (int)$data['subtotal'] + $cektarif;
				$total = "Rp. ".$this->Fungsi->fuang($nilaitotal);
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
