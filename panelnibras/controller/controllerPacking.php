<?php
class controllerPacking {
	private $page;
	private $rows;
	private $offset;
	private $db;
	private $model;
	private $Fungsi;
	private $data=array();
	private $error=array();   
	public function __construct(){
		$this->model= new modelPacking();
		$this->Fungsi	= new FungsiUmum();
	}

	public function getsPacking(){
		return $this->model->getsPacking();
	}
    public function setPacking($id,$nominal){
		return $this->model->setPacking($id,$nominal);
	}
}
?>
