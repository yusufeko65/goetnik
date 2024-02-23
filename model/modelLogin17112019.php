<?php
class model_Login {
	private $db;
	private $tabelnya;
	
	public function __construct(){
		$this->tabelnya = '_customer';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	
	public function checkDataLogin($data){
		$check = $this->db->query("select cust_email,cust_pass from ".$this->tabelnya." where cust_email='".$this->db->escape($data['emailuser'])."' AND cust_pass='".$this->db->escape($data['passuser'])."' AND cust_status='1'");
		$jml=$check->num_rows;
		if($jml) return true;
		else return false;
	}
	
	public function getLogin($data){
		$strsql=$this->db->query("select * from ".$this->tabelnya." where cust_email='".$this->db->escape($data['emailuser'])."' AND cust_pass='".$this->db->escape($data['passuser'])."' AND cust_status='1'");
		return $strsql->row;
	}
	
	public function __destruct() {
		$this->db->disconnect();
	}
}
?>