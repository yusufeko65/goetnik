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
	public function getAllServicesAndTarifByWilayah($propinsi,$kabupaten,$kecamatan,$konfirm_admin=0){
		return $this->dataModel->getAllServicesAndTarifByWilayah($propinsi,$kabupaten,$kecamatan,$konfirm_admin);
	}
	public function getAllServisKonfirmAdmin(){
		return $this->dataModel->getAllServisKonfirmAdmin();
	}
	public function getAllServicesAndTarifByWilayahRajaOngkir($origin,$kecamatan_penerima,$berat,$apiurl,$apikey){
		$shipping = $this->dataModel->getShippingRajaOngkir();
		$kurir = [];
		$cekKurir = [];
		
		foreach($shipping as $ship){
			//$kurir[] = $ship['shipping_kode'];
			if(!in_array($ship['shipping_kdrajaongkir'],$cekKurir)){
				$kurir["shipping_kdrajaongkir"][] = trim($ship['shipping_kdrajaongkir']);
				
				$cekKurir[] = $ship['shipping_kdrajaongkir'];
			}
			
			$kurir["shipping_id"][] = $ship['shipping_id'];
			$kurir["{$ship['shipping_kode']}"]["{$ship['servis_code']}"] = array("servis"=>$ship['servis_id'],"shipping_code"=>$ship['shipping_kdrajaongkir']);
			$kurir["servis_nama"][] = $ship['servis_nama'];
			
							 
		}
		
		$kurir["shipping_kdrajaongkir"] = isset($kurir["shipping_kdrajaongkir"]) ? $kurir["shipping_kdrajaongkir"] : array();
		
		$shipping_code = implode(":",$kurir["shipping_kdrajaongkir"]);
		
		$data['curl'] 		= curl_init();
		$data['urlcurl'] 	= $apiurl.'cost';
		$data['postfield'] 	= "origin=$origin&originType=city&destination=$kecamatan_penerima&destinationType=subdistrict&weight=$berat&courier=".strtolower($shipping_code);
		
		$data['httpheader'] =	array(
									"content-type: application/x-www-form-urlencoded",
									"key: ".$apikey
								);
		
		$grab = $this->Fungsi->grabData($data);
		
		$datagrab = json_decode($grab, true);
		
		//$jmldata 		= count($datagrab['rajaongkir']['results'][0]['costs']);
		$jmldata 		= isset($datagrab['rajaongkir']['results']) ? count($datagrab['rajaongkir']['results']) : 0;
		/*
		echo '<pre>';
		
		print_r($datagrab);
		exit;
		*/
		$dataship = [];
		
		if($jmldata > 0 ) {
			for ($i=0; $i < $jmldata; $i++) {
			
				$jmlservis = count($datagrab['rajaongkir']['results'][$i]['costs']);
				for($x=0;$x < $jmlservis; $x++){
					$kode_ship = strtoupper($datagrab['rajaongkir']['results'][$i]['code']);

					$shipping_code = '';
					if(isset($kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["shipping_code"])){
						$shipping_code = $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["shipping_code"];
					}
					
					$servis_id = '';
					if(isset($kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["servis"])){
						$servis_id = $kurir["{$kode_ship}"]["{$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service']}"]["servis"];
					}

					$dataship[] = array("shipping_code_rajaongkir"=>$kode_ship,
										"shipping_code"=>$shipping_code,
										"servis_id"=>$servis_id,
										"servis_code"=>$datagrab['rajaongkir']['results'][$i]['costs'][$x]['service'],
										"tarif"=>$datagrab['rajaongkir']['results'][$i]['costs'][$x]['cost'][0]['value'],
										"etd"=>$datagrab['rajaongkir']['results'][$i]['costs'][$x]['cost'][0]['etd'],
										"shipping_konfirmadmin"=>0,
										"shipping_rajaongkir"=>1);
				}
				
			}
		}
	
		return $dataship;
		
	}
	public function getAllServicesAndTarifByWilayahJson(){
		foreach($_POST as $key => $value){
			${$key} = trim($value);
		}
		
		$modelsetting = new model_SettingToko();
		$setting = $modelsetting->getSettingByKeys(array('config_lokasiorigin','config_apiurlongkir','config_apikeyongkir'));
		foreach($setting as $st){
			$key 	= $st['setting_key'];
			$value 	= $st['setting_value'];
			$$key = $value;
		}
		
		$servis_rajaongkir = $this->getAllServicesAndTarifByWilayahRajaOngkir($config_lokasiorigin,$kecamatan,$totberat,$config_apiurlongkir,$config_apikeyongkir);
		$servis_ondb = $this->dataModel->getAllServisKonfirmAdmin();
		
		//$services = $this->dataModel->getAllServicesAndTarifByWilayah($propinsi,$kabupaten,$kecamatan);
		$services = [];
		foreach($servis_rajaongkir as $ship) {
			$services[] = $ship;
		}
		foreach($servis_ondb as $servdb) {
			$services[] = $servdb;
		}
		if(count($services) > 0) {
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
			$serviskurir = isset($data['serviskurir']) ? explode("::",$data['serviskurir']) : array();

			// Get data packing
			$modelpacking = new model_Packing();
			$biaya = $modelpacking->getsPacking();
			$packing = isset($biaya[0]) ? $biaya[0]['nominal'] :0;

			// Check MP
			$resi=0;
			if($serviskurir[2]=="MP"){
				$resi=1;
			}

			// Get kode unik
			$url = URL_API_UNIQUE_CODE . $data['subtotal']; // path to your JSON file
			$response = file_get_contents($url); // put the contents of the file into a variable
			$request = json_decode($response,true);

			$kodeunik = 0;
			if($request['status']){
				$kodeunik = $request['data'];
			}
			
			$cektarif	= $serviskurir[1];
			//$cektarif = $this->dataModel->tarifkurir($data);
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
			$kodeunik = 0;
			$total = '';
			$packing = 0;
		}
		
		echo json_encode(array("status"=>$status,"tarif"=>$tarif,"nilaitarif"=>$nilaitarif,"total"=>$total,"kodeunik"=>$kodeunik,"nilaitotal"=>$nilaitotal,"wil"=>$wil,"packing"=>$packing));
	}

  
}
