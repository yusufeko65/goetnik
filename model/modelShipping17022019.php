<?php
class model_Shipping {
	private $db;
	private $tabelnya;
	private $userlogin;
	
	function __construct(){
		$this->db 		= new Database();
		$this->db->connect();
	}

	function getShipping(){
		
		$strsql=$this->db->query("select * from _shipping WHERE tampil=1");
		if($strsql){
			$shipping = array();
			foreach ($strsql->rows as $rs) {
			   $shipping[] = array(
				   'id' 		=> $rs['shipping_id'],
				   'nama' 		=> $rs['shipping_nama'],
				   'tabel' 		=> $rs['tabel_servis'],
				   'tabeltarif' => $rs['tabel_tarif'],
				   'logo' 		=> $rs['shipping_logo'],
				   'tabeldiskon'=> $rs['tabel_diskon'],
				   'detek_kdpos'=> $rs['detek_kdpos'],
				   'ket_shipping'=> $rs['shipping_keterangan']
				);
			}
			return $shipping;
		}
		return false;
		
        
	}
	
	function getShippingbyName($nama){
		$strsql=$this->db->query("select * from _shipping WHERE tampil=1 AND shipping_nama='$nama'");
        return  isset($strsql->row) ? $strsql->row : false;
	}
	function getAllServicesAndTarifByWilayah($propinsi,$kabupaten,$kecamatan){
		$sql = "select _servis.servis_id,servis_code,
				servis_nama,servis_shipping,
				shipping_kode,shipping_nama,
				shipping_keterangan,shipping_bataskoma,
				shipping_konfirmadmin,hrg_perkilo,_tarif.keterangan,shipping_cod
				from _tarif 
				left join _servis on _tarif.servis_id = _servis.servis_id
				left join _shipping on _servis.servis_shipping = _shipping.shipping_id
				where tampil='1' and shipping_publik='1' and shipping_konfirmadmin = '0' 
				and kecamatan_id='".$kecamatan."'
				and kabupaten_id='".$kabupaten."'
				and provinsi_id='".$propinsi."'
				union
				select _servis.servis_id,servis_code,
				servis_nama,servis_shipping,
				shipping_kode,shipping_nama,
				shipping_keterangan,shipping_bataskoma,
				shipping_konfirmadmin,'Konfirmasi Admin' as hrg_perkilo,'Konfirmasi Admin' as keterangan,shipping_cod
				from _servis
				left join _shipping on _servis.servis_shipping = _shipping.shipping_id
				where tampil='1' and shipping_publik='1' and shipping_konfirmadmin = '1'
				order by shipping_konfirmadmin asc, servis_shipping ASC, servis_code asc";
		
		$strsql = $this->db->query($sql);
		if($strsql) {
			$data = [];
			foreach($strsql->rows as $row) {
				$data[] = $row;
			}
			return $data;
		} else {
			return false;
		}
	}
	function getAllServices(){
		$sql = "select servis_id,servis_code,
				servis_nama,servis_shipping,
				shipping_kode,shipping_nama,
				shipping_keterangan,shipping_bataskoma
				from _servis left join _shipping on _servis.servis_shipping = _shipping.shipping_id
				where tampil='1' and shipping_publik='1'
				order by servis_shipping,servis_id asc";
		$strsql = $this->db->query($sql);
		if($strsql) {
			$data = [];
			foreach($strsql->rows as $row) {
				$data[] = $row;
			}
			return $data;
		} else {
			return false;
		}
	}
	function getServis($tabel){
	    
		$strsql = $this->db->query("select * from $tabel");
		if($strsql){
			$tabels = array();
			foreach ($strsql->rows as $rs) {
				$tabels[] = array(
				   'id' 		=> $rs['ids'],
				   'nama' 		=> $rs['servis_nama'],
				   'keterangan'	=> $rs['shipping_keterangan'],
				   'minkilo'	=> $rs['min_kilo']
				);
			}
			
			return $tabels;
		}
		return false;
	}
	function getServisbyId($tabel,$id){
	    $strsql=$this->db->query("select * from $tabel WHERE ids='".$id."'");
		
        return isset($strsql->row) ? $strsql->row : false;
	}
	function getShippingByIdServ($data){
		$sql = "select shipping_id,shipping_kode,
					   shipping_bataskoma,
					   servis_code,
					   shipping_konfirmadmin
				from _shipping left join _servis
				on _shipping.shipping_id = _servis.servis_shipping
				where _servis.servis_id='".$data['serviskurir']."'";
		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}
	function getServisByIdserv($data){
		$sql = "select _servis.servis_id,servis_code,servis_nama,servis_shipping,
				shipping_kode,shipping_nama,shipping_logo,tampil,
				detek_kdpos,shipping_keterangan,shipping_bataskoma,
				shipping_cod,shipping_konfirmadmin,hrg_perkilo,_tarif.keterangan
				from _servis 
				left join _shipping on _servis.servis_shipping = _shipping.shipping_id
				left join _tarif on _tarif.servis_id = _servis.servis_id
				where _servis.servis_id='".$data['serviskurir']."' 
				and tampil = '1' and shipping_publik='1'
				and kecamatan_id='".$data['kecamatan_penerima']."'
				and kabupaten_id='".$data['kabupaten_penerima']."'
				and provinsi_id='".$data['propinsi_penerima']."'";
		
		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}
	function tarifkurir($data){
		$totberat = (int)$data['totberat'] / 1000;
		if($totberat < 1) $totberat = 1;
		$jarakkoma = 0;
		if($totberat > 1) {
			$berat = floor($totberat);
			$jarakkoma = $totberat - $berat;
		}
		$idservis = $data['serviskurir'];
		
		$sql = "select _servis.servis_id,servis_code,
				servis_nama,servis_shipping,
				shipping_kode,shipping_nama,
				shipping_keterangan,shipping_bataskoma,
				shipping_konfirmadmin,hrg_perkilo,_tarif.keterangan
				from _tarif 
				left join _servis on _tarif.servis_id = _servis.servis_id
				left join _shipping on _servis.servis_shipping = _shipping.shipping_id
				where tampil='1' and shipping_publik='1' 
				and _tarif.servis_id='".$idservis."' 
				and kecamatan_id='".$data['kecamatan_penerima']."'
				and kabupaten_id='".$data['kabupaten_penerima']."'
				and provinsi_id='".$data['propinsi_penerima']."'";
				
				
		$strsql = $this->db->query($sql);
		$row = isset($strsql->row) ? $strsql->row : false;
		if($row) {
			if($row['shipping_konfirmadmin'] == '0') {
				$batas = $row['shipping_bataskoma'];
				$hargaperkilo = $row['hrg_perkilo'];
				if($jarakkoma > $batas) $totberat = ceil($totberat);
				else $totberat = floor($totberat);
				$tarif = $totberat * $hargaperkilo;
			} else {
				$tarif = 'Konfirmasi Admin';
			}
		} else {
			$tarif = 'Konfirmasi Admin';
		}
		return $tarif;
		
	}
	
	
}
?>