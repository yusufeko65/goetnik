<?php
class model_SettingToko {
	private $db;
	private $tabelnya;
	private $userlogin;
	
	public function __construct(){
		$this->tabelnya = '_setting';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	public function getSettingToko(){
	    $data = array();
		$strsql=$this->db->query("select * from ".$this->tabelnya);
		foreach ($strsql->rows as $rsa) {
		  $data[] = $rsa;
		}
		
		return $data;
	}
	
	public function getSettingTokoByKey($key){
		$sql = "select setting_key,setting_value 
				from _setting where setting_key='".$key."'";
		$strsql = $this->db->query($sql);
		return isset($strsql->row['setting_value']) ? $strsql->row['setting_value'] : false;
	}
	
	public function __destruct() {
		$this->db->disconnect();
	}
}
?>