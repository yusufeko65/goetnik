<?php
class modelPacking {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_packing';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function getsPacking(){
		$sql = "SELECT ID,nominal,min_weight,max_weight 
				FROM _packing 
				WHERE 1=1";
		
		$strsql = $this->db->query($sql);
		foreach ($strsql->rows as $rsa) {
			$hasil[] = $rsa;
		}
		return $hasil;
	}

	function setPacking($id,$nominal){
		return $this->db->query("update ".$this->tabelnya." set nominal='".$nominal."' where ID='".$id."'");
	   
	}
	
}
?>