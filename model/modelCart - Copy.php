<?php
class model_Cart {
	private $db;
	private $tabelnya;
	
	public function __construct(){
    	$this->db = new Database();
		$this->db->connect();
		/*$this->db->query("SET AUTOCOMMIT=0");
        $this->db->query("START TRANSACTION");
		*/
		
	}

	public function SimpanOrderDetail($data){
	  /*  $sql = $this->db->query("insert into _order_detail values ('".$data['iddetail']."',
		                   '".$data['nopesanan']."','".$data['pid']."','".$data['jml']."',
						   '".$data['harga']."','".$data['satuan']."','".$data['berat']."','".$data['hrgbeli']."')");
		
		if($sql) return true;
	    else return false;
		*/
		$sql =  "insert into _order_detail values ('".$data['iddetail']."',";
		$sql .= "'".$data['nopesanan']."','".$data['pid']."','".$data['jml']."',";
	    $sql .= "'".$data['harga']."','".$data['satuan']."','".$data['berat']."','0')";
		
		return $sql;
						   
	}
	
	public function SimpanOrderDetailOption($data){
	   /* $sql = $this->db->query("insert into _order_detail_option values ('','".$data['iddetail']."',
		                   '".$data['idwarna']."','".$data['idukuran']."')");
		
		if($sql) return true;
	    else return false;
		*/
		$sql = "insert into _order_detail_option values ('','".$data['iddetail']."','".$data['idwarna']."','".$data['idukuran']."')";
		return $sql;
						   
	}
	
	public function UpdateStokOption($data){
	   /* $sql = $this->db->query("update _produk_option set stok=stok - ".$data['jml']." 
		                    WHERE idproduk = '".$data['pid']."' AND ukuran='".$data['idukuran']."' AND
							warna = '".$data['idwarna']."'");
		
		if($sql) return true;
	    else return false;
		*/
		$sql = "update _produk_options set stok=stok - ".$data['jml']." WHERE idproduk = '".$data['pid']."' AND ukuran='".$data['idukuran']."' AND warna = '".$data['idwarna']."'";
		return $sql;
	}
	function updateStokOptionBertambah($nopesan,$jml,$warna,$ukuran,$produk){
		$sql = $this->db->query("UPDATE _produk_options set stok = stok+$jml 
							WHERE idproduk='".$produk."' AND ukuran='".$ukuran."' AND warna='".$warna."'");
		return $sql;
	}
	
	public function UpdateStok($data=array()){
	    /*
		$sql = $this->db->query("update _produk set jml_stok=jml_stok - ".(int)$data['jml']." 
		                    WHERE idproduk = '".$data['pid']."'");
		
		if($sql) return true;
	    else return false;
		*/
		$sql = "update _produk set jml_stok=jml_stok - ".(int)$data['jml']." WHERE idproduk = '".$data['pid']."'";
		return $sql;
	}
	function updateStokBertambah($jml,$produk){
		$sql = $this->db->query("UPDATE _produk set jml_stok = jml_stok+$jml 
							WHERE idproduk='".$produk."'");
		
       // return $sql;
	}
	public function updateOrderProduk($data){
	   $sql = "UPDATE _order_detail INNER JOIN _order_detail_option ON _order_detail.iddetail = _order_detail_option.iddetail 
			  SET warnaid = '".$data['idwarna']."',ukuranid = '".$data['idukuran']."',
			  jml='".$data['jml']."',harga='".$data['harga']."',satuan='".$data['satuan']."',
			  berat='".$data['berat']."',hrg_beli='".$data['hrgbeli']."' 
			  WHERE warnaid='".$data['warnalama']."' AND ukuranid='".$data['ukuranlama']."' AND pesanan_no='".$data['nopesan']."' 
			  AND produk_id='".$data['pid']."'";
		//echo $sql;
	   $sql = $this->db->query($sql);
	   /*
	   if($sql) return true;
	   else return false;
	   */
	   //return $sql;
	}
	function getProdukOrderOption($idproduk,$idwarna,$idukuran,$nopesan) {
	   $arkab = array();
	   $w='';
	   if($idukuran != '' && $idukuran != '0') {
	      $w .= " AND ukuranid='".$idukuran."'";
	   } 
	   if($idwarna != '' && $idwarna != '0') {
		    $w .= " AND warnaid='".$idwarna."'";
	   }
	   if($idproduk != ''){
	     $w .= " AND produk_id='".$idproduk."'";
	   }
	   $sql = "SELECT _order_detail.iddetail,pesanan_no,produk_id,jml,harga,satuan,berat,hrg_beli,warnaid,ukuranid
               FROM _order_detail INNER JOIN _order_detail_option 
			   ON _order_detail.iddetail = _order_detail_option.iddetail
			   WHERE pesanan_no='$nopesan' $w ORDER BY _order_detail.iddetail ASC";
		//echo $sql;
	   $strsql=$this->db->query($sql);
	    foreach($strsql->rows as $rsa){
			$arkab[] = array(
				'iddetail' => $rsa['iddetail'],
				'nopesanan' => $rsa['pesanan_no'],
				'idproduk' => $rsa['produk_id'],
				'jml' => $rsa['jml'],
				'warna' => $rsa['warnaid'],
				'ukuran' => $rsa['ukuranid']
			);
		}
		return $arkab;
	}
	public function simpanEditKurir($data){
	   $sql = "UPDATE _order set pesanan_kurir = '".$data['tarifkurir']."',kurir='".$data['kurir']."',servis_kurir='".$data['kurirservis']."',
	           kurir_perkilo='".$data['satuantarifkurir']."' WHERE pesanan_no='".$data['nopesanan']."'";
	   //echo $sql;
       $stre = $this->db->query($sql);
	  
	   return $stre;
	}
	public function simpanEditPenerimaOrder($data){
	   $sql = "UPDATE _order_penerima set nama_penerima = '".$data['nama']."',telp_penerima='".$data['notelp']."',
	           hp_penerima='".$data['nohp']."',alamat_penerima='".$data['alamat']."',
	           negara_penerima='".$data['negara']."',
			   propinsi_penerima = '".$data['propinsi']."',
			   kota_penerima = '".$data['kota']."',
			   kecamatan_penerima = '".$data['kecamatan']."',
			   kelurahan_penerima = '".$data['kelurahan']."',
			   kodepos_penerima = '".$data['kodepos']."' WHERE pesanan_no='".$data['nopesan']."'";
	   
       $stre = $this->db->query($sql);
	  
	   return $stre;
	}
	public function hapusProdukOrderOption($data){
	  $sql = "DELETE _order_detail,_order_detail_option
			  FROM _order_detail
              INNER JOIN _order_detail_option
              ON _order_detail.iddetail = _order_detail_option.iddetail
			  WHERE _order_detail.iddetail = '".$data['iddetail']."'";
      $sql = $this->db->query($sql);
	  /*
	  if($sql) return true;
	  else return false;
	  */
	  return $sql;
			 
	}
	public function UpdateOrder($data){
	  $sql = "UPDATE _order set pesanan_jml='".$data['totjumlah']."',
	                pesanan_subtotal = '".$data['subtotal']."',
					pesanan_kurir = '".$data['tarifkurir']."',
					kurir_perkilo = '".$data['satuantarifkurir']."'
			  WHERE pesanan_no = '".$data['nopesanan']."'";
	  $sql = $this->db->query($sql);
	  /*
	   if($sql) return true;
	   else return false;
	   */
	   return $sql;
					
					
	}
	public function HapusOrder($data) {
	   $sql = "DELETE from _order where pesanan_no='".$data['nopesanan']."'";
	   $sql = $this->db->query($sql);
	   return $sql;
	}
	public function UpdateDeposit($data){
	    $sql = $this->db->query("update _reseller_deposit set jumlah=jumlah - ".$data['deposit'].",user_login='0', tglupdate='".$data['tgltrans']."'
		                    WHERE reseller_id = '".$data['idmember']."'");
		/*
		if($sql) return true;
	    else return false;
		*/
	}
	public function UpdateGetPoin($data){
	    $sql = "update _customer_point set cp_poin=cp_poin + ".$data['totgetpoin'].",cp_tglupdate='".$data['tgltrans']."'
		                    WHERE cp_cust_id = '".$data['idmember']."'";
		return $sql;
	}
	
	public function SimpanGetPoin($data){
	    $sql = "INSERT INTO _customer_point set cp_cust_id = '".$data['idmember']."', cp_poin=".$data['totgetpoin'].",cp_tglupdate='".$data['tgltrans']."'";
		return $sql;
	}
	public function SimpanDetailGetPoin($data){
	    $sql = "INSERT INTO _customer_point_history set cph_cust_id = '".$data['idmember']."', cph_poin=".$data['totgetpoin'].",cph_tipe='IN',cph_tgl='".$data['tgltrans']."',cph_order='".$data['nopesanan']."'";
		return $sql;
	}
	public function InsertDepositDetail($data){
	   $sql = $this->db->query("insert into _reseller_deposit_history values ('','".$data['idmember']."','".$data['deposit']."','".$data['tgltrans']."','".$data['nopesanan']."','SPEND')");
	   /*
	   if($sql) return true;
	   else return false;
	   */
	   return $sql;
	}
	
	public function UpdatePoinCustomer($data){
	    $sql = "update _customer_point set cp_poin = cp_poin - ".$data['poin'].", cp_tglupdate='".$data['tgltrans']."' WHERE cp_cust_id = '".$data['idmember']."'";
		return $sql;
	}
	public function InsertPoinDetail($data){
	   $sql = "insert into _customer_point_history set cph_cust_id='".$data['idmember']."',
	          cph_poin='".$data['poin']."',
			  cph_tipe='OUT',
			  cph_tgl='".$data['tgltrans']."',
			  cph_order='".$data['nopesanan']."'";
	   return $sql;
	}
	public function UpdateDepositoCustomer($data){
	    $sql = "update _customer_deposito set cd_deposito = ".$data['sisadeposito'].", cd_tglupdate='".$data['tgltrans']."' WHERE cd_cust_id = '".$data['idmember']."'";
		return $sql;
	}
	public function InsertDepositoDetail($data){
	   $sql = "insert into _customer_deposito_history set cdh_cust_id='".$data['idmember']."',
	          cdh_deposito='".$data['potdeposito']."',
			  cdh_tipe='OUT',
			  cdh_tgl='".$data['tgltrans']."',
			  cdh_order='".$data['nopesanan']."',
			  cdh_keterangan='".$data['keterangandeposito']."'";
	   return $sql;
	}
	function simpanorder($data) {
	}
	public function SimpanOrder($data){
	    /*
	    $sql = $this->db->query("insert into _order values ('','".$data['nopesanan']."',
		                   '".$data['idmember']."','".$data['totjumlah']."',
						   '".$data['subtotal']."','".$data['tarifkurir']."',
						   '".$data['tgltrans']."',0,'".$data['orderstatus']."',
						   '".$data['kurir']."',
						   '".$data['kurirservis']."','-',
						   '".$data['satuantarifkurir']."',
						   '".$_SERVER['REMOTE_ADDR']."','".$data['infaq']."',0,0,'".$data['reseller_grup']."','0','".$data['deposit']."')");
	   
		
		if($sql) return true;
	    else return false;
		*/
		$sql = "insert into _order values ('','".$data['nopesanan']."',
		       '".$data['idmember']."','".$data['totjumlah']."',
			   '".$data['subtotal']."','".$data['tarifkurir']."',
			   '".$data['tgltrans']."',0,'".$data['orderstatus']."',
			   '".$data['kurir']."',
			   '".$data['kurirservis']."','-',
			   '".$data['satuantarifkurir']."',
			   '".$data['ipaddress']."',0,0,0,'".$data['cust_grup']."',
			   '0','".$data['poin']."','".$data['potongan_kupon']."','".$data['kode_kupon']."',
			   '0000-00-00','-','".$data['potdeposito']."')";
		return $sql;				   
	}
	
	public function SimpanOrderPenerima($data){
	    /*
	    $sql = $this->db->query("insert into _order_penerima values ('".$data['nopesanan']."',
		                   '".$data['nama']."','".$data['telp']."',
						   '".$data['hp']."','".$data['alamat']."',
						   '".$data['negara']."','".$data['propinsi']."',
						   '".$data['kabupaten']."','".$data['kecamatan']."',
						   '".$data['kelurahan']."','".$data['kodepos']."')");
		
		if($sql) return true;
	    else return false;
		*/
		$sql = "insert into _order_penerima values ('".$data['nopesanan']."',
		                   '".$data['nama']."','".$data['telp']."',
						   '-','".$data['alamat']."',
						   '".$data['negara']."','".$data['propinsi']."',
						   '".$data['kabupaten']."','".$data['kecamatan']."',
						   '".$data['kelurahan']."','".$data['kodepos']."')";
		return $sql;
						   
	}
	
	public function SimpanOrderPengirim($data){
	    /*
		$sql = $this->db->query("insert into _order_pengirim values ('".$data['nopesanan']."',
		                   '".$data['namapengirim']."','".$data['telppengirim']."',
						   '".$data['hppengirim']."','".$data['alamatpengirim']."',
						   '".$data['negarapengirim']."','".$data['propinsipengirim']."',
						   '".$data['kabupatenpengirim']."','".$data['kecamatanpengirim']."',
						   '".$data['kelurahanpengirim']."','".$data['kodepospengirim']."')");
	    */
		$sql = "insert into _order_pengirim values ('".$data['nopesanan']."',
		                   '".$data['namapengirim']."','".$data['telppengirim']."',
						   '-','".$data['alamatpengirim']."',
						   '".$data['negarapengirim']."','".$data['propinsipengirim']."',
						   '".$data['kabupatenpengirim']."','".$data['kecamatanpengirim']."',
						   '".$data['kelurahanpengirim']."','".$data['kodepospengirim']."')";
        return $sql;		
	}
	
	public function SimpanOrderStatus($data){
	    /*
	    $sql = $this->db->query("insert into _order_status values ('','".$data['nopesanan']."',
		                   '".$data['tgltrans']."','".$data['orderstatus']."','-')");
		
		if($sql) return true;
	    else return false;
		*/
		$sql = "insert into _order_status values ('','".$data['nopesanan']."',
		       '".$data['tgltrans']."','".$data['orderstatus']."','-')";
		return $sql;
						   
	}
	public function prosesTransaksi($proses) {
	    $jmlproses = count($proses);
		$error = array();
        $this->db->autocommit(false);
        for($i=0; $i < $jmlproses; $i++)
        {
         
		   if (!$this->db->query($proses[$i])){
		      
			  $error[] = $i;
			  
		    } 
        }
       
		if(count($error) > 0) {
		   $this->db->rollback();
		   return false;
		} else {
		
		   $this->db->commit();
		   $this->db->autocommit(true);
		   return true;
		}
		
        //$this->db->query("SET AUTOCOMMIT=1");
	}
	
    public function checkPoin($idmember) {
		$query = $this->db->query("SELECT cp_poin as poin FROM _customer_point WHERE cp_cust_id='".$idmember."'");
		return isset($query->row['poin']) ? $query->row['poin']:0;
		
	}
	
	public function __destruct() {
		$this->db->disconnect();
	}
}
?>