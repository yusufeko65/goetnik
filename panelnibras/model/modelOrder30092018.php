<?php
class modelOrder {
	private $db;
	private $tabelnya;
	private $user;
	
	function __construct(){
		$this->db 		= new Database();
		$this->db->connect();
		$this->user = isset($_SESSION["idlogin"]) ? $_SESSION["idlogin"]:'';
		
	}
    
	function cekOrder($noorder){
	    $str = $this->db->query("select count(*) as total from _order WHERE pesanan_no = '".$this->db->escape($noorder)."'");
		$jml = isset($str->row['total']) ? $str->row['total'] : 0;
		
		if($jml > 0) return true;
		else return false;
	}
	
	
	function checkPoinHistory($data,$jenis) {
		$poin = -1;
		$str = $this->db->query("SELECT cph_poin FROM _customer_point_history WHERE cph_tipe='$jenis' AND cph_cust_id='".$data['pelangganid']."' AND cph_order='".$data['nopesanan']."'");
	   
		return isset($str->row['cph_poin']) ? $str->row['cph_poin'] : 0;
    }
	
	function insertGetPoin($data,$jenis) {
		$sql = "INSERT INTO _customer_point_history SET cph_cust_id='".$data['pelangganid']."',cph_poin = '".$data['totpoin']."',cph_tipe='$jenis',cph_tgl='".$data['tgl']."',cph_order='".$data['nopesanan']."'";
		return $sql;
	}
	function updateGetPoin($data,$jenis) {
		$sql = "UPDATE _customer_point_history SET cph_poin='".$data['totpoin']."' WHERE cph_cust_id='".$data['pelangganid']."' AND cph_tipe='$jenis' AND cph_order='".$data['nopesanan']."'";
		return $sql;
	}
	function deleteGetPoin($data,$jenis) {
		$sql = "DELETE from _customer_point_history WHERE cph_cust_id='".$data['pelangganid']."' AND cph_tipe='$jenis' AND cph_order='".$data['nopesanan']."'";
		return $sql;
	}
	function updatePoinPelanggan($data){
		$cekpoin = $this->db->query("SELECT count(cp_cust_id) FROM _customer_point WHERE cp_cust_id='".$data['pelangganid']."'");
		$rs = mysql_fetch_row($cekpoin);
		if($rs[0] > 0){
			$sql = "UPDATE _customer_point SET cp_poin=cp_poin+".$data['totpoin'].",cp_tglupdate='".$data['tgl']."' WHERE cp_cust_id='".$data['pelangganid']."'";
		} else {
			$sql = "INSERT into _customer_point SET cp_cust_id='".$data['pelangganid']."',cp_poin=".$data['totpoin'].",cp_tglupdate='".$data['tgl']."'";
		}
		return $sql;
	}
	function simpanStatusOrder($data){
	  
		$sql = "INSERT INTO _order_status set nopesanan='".$data['nopesanan']."',tanggal='".$data['tgl']."',status_id='".$data['orderstatus']."',
	                     keterangan='".$data['keterangan']."'";
		return $sql;
	}
    function updateOrderStatus($data){
	   $sql = "UPDATE _order set status_id='".$data['orderstatus']."',tgl_kirim  = '".$data['tglkirim']."',no_awb='".$data['noawb']."' WHERE pesanan_no='".$data['nopesanan']."'";
	   return $sql;
	}
	function editHrgOrderDetail($hrg,$iddetail,$jenis){
	   if($jenis == 'jual') {
			$sql = $this->db->query("update _order_detail set harga='".$hrg."' WHERE iddetail='".$iddetail."'");
	   } else {
	        $sql = $this->db->query("update _order_detail set hrg_beli='".$hrg."' WHERE iddetail='".$iddetail."'");
			 //echo "update _order_detail set hrg_beli='".$hrg."' WHERE iddetail='".$iddetail."'";
	   }
	   /*
		if($sql) return true;
		else return false;
		*/
		return $sql;
	}
	function editSubtotalOrder($hrg,$nopesan){
		$sql = $this->db->query("update _order set pesanan_subtotal='".$hrg."' WHERE pesanan_no='".$nopesan."'");
		/*
		if($sql) return true;
		else return false;
		*/
		return $sql;
	}
	function editPenambahanKekuranganOrder($jmltambah,$jmlkurang,$nopesan,$ststransaksi){
		$sql = $this->db->query("update _order set pesanan_penambahan='".$jmltambah."',
		                    pesanan_kekurangan='".$jmlkurang."',transaksi_close='".$ststransaksi."' WHERE pesanan_no='".$nopesan."'");
		/*
		if($sql) return true;
		else return false; */
		return $sql;
	}
	function getOrder($batas,$baris,$data){
		
		$where = '';
		$filter = array();
		
		if($data['caridata']!='') $filter[] = " (pesanan_no ='".trim(strip_tags($caridata))."' OR cust_nama like'".trim(strip_tags($data['caridata']))."%' OR cust_telp like '".trim(strip_tags($data['caridata']))."%')";
		if($data['status'] != '' && $data['status'] != '0') $filter[] = " _order.status_id = '".trim(strip_tags(urlencode($data['status'])))."'";
		if(!empty($filter))	$where = implode(" AND ",$filter);
		
		
		if($where!='') $where = " where ".$where;
		
		$sql = "select idpesanan,_order.pesanan_no,cust_nama,pesanan_jml as jml,pesanan_subtotal as subtotal,pesanan_tgl as tgl,status_nama as status,pesanan_kurir,dari_poin 
				from _order inner join _status_order on _order.status_id = _status_order.status_id 
				inner join _customer on _order.pelanggan_id = _customer.cust_id ".$where." order by pesanan_tgl desc limit $batas,$baris";
		
		$strsql = $this->db->query($sql);
		if($strsql){
			$hasil = [];
			foreach($strsql->rows as $rs)
			{
				$hasil[] = $rs;
			}
			return $hasil;
		}
		return false;
		
	}
	
	public function totalOrder($data){
		$where = '';
		$filter = array();
		
		if($data['caridata']!='') $filter[] = " (pesanan_no ='".trim(strip_tags($caridata))."' OR cust_nama like'".trim(strip_tags($data['caridata']))."%' OR cust_telp like '".trim(strip_tags($data['caridata']))."%')";
		if($data['status'] != '' && $data['status'] != '0') $filter[] = " _order.status_id = '".trim(strip_tags(urlencode($data['status'])))."'";
		if(!empty($filter))	$where = implode(" AND ",$filter);
	
	    if($where!='') $where = " where ".$where;
		$query = '';
		
		$strsql=$this->db->query("select count(*) as total from _order INNER JOIN _status_order ON _order.status_id = _status_order.status_id 
		                     INNER JOIN _customer ON _order.pelanggan_id = _customer.cust_id ".$query.$where);
		
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	
	function getOrderByID($noorder){
	    //$strsql=$this->db->query("select * from _order LEFT JOIN _status_order ON _order.status_id = _status_order.status_id WHERE pesanan_no='".$this->db->escape($noorder)."'");
		$sql = "select o.pesanan_no,o.pelanggan_id,c.cust_nama,cg.cg_nm as grup_cust,o.pesanan_jml,
				o.pesanan_subtotal,o.pesanan_kurir,o.pesanan_tgl,
				o.status_id,so.status_nama,o.kurir,sp.shipping_nama,
				o.servis_kurir,s.servis_code,o.grup_member,
				o.dari_poin,o.potongan_kupon,o.kode_kupon,o.tgl_kirim,
				o.no_awb,o.dari_deposito,o.dropship,o.kurir_konfirm,
				n.nama_penerima,n.hp_penerima,
				n.alamat_penerima,ngn.negara_nama as negaranm_penerima,n.negara_penerima,
				prn.provinsi_nama as propinsinm_penerima,n.propinsi_penerima,kbn.kabupaten_nama as kotanm_penerima,n.kota_penerima,kcn.kecamatan_nama as kecamatannm_penerima,n.kecamatan_penerima,
				n.kelurahan_penerima, n.kodepos_penerima,
				p.nama_pengirim,p.hp_pengirim,
				p.alamat_pengirim,ngp.negara_nama as negaranm_pengirim,prp.provinsi_nama as propinsinm_pengirim,kbp.kabupaten_nama as kotanm_pengirim,kcp.kecamatan_nama as kecamatannm_pengirim,
				p.kelurahan_pengirim,p.kodepos_pengirim,
				n.kota_penerima,n.kecamatan_penerima,n.kodepos_penerima
				from _order o
				left join _status_order so on o.status_id = so.status_id
				left join _customer c on o.pelanggan_id = c.cust_id
				inner join _customer_grup cg on o.grup_member = cg.cg_id
				left join _servis s on o.servis_kurir = s.servis_id
				left join _shipping sp on o.kurir = sp.shipping_kode
				inner join _order_penerima n ON o.pesanan_no = n.pesanan_no
				INNER JOIN _negara ngn ON n.negara_penerima = ngn.negara_id
				INNER JOIN _provinsi prn ON n.propinsi_penerima = prn.provinsi_id
				INNER JOIN _kabupaten kbn ON n.kota_penerima = kbn.kabupaten_id
				INNER JOIN _kecamatan kcn ON n.kecamatan_penerima = kcn.kecamatan_id
				INNER JOIN _order_pengirim p ON o.pesanan_no = p.pesanan_no
				INNER JOIN _negara ngp ON p.negara_pengirim = ngp.negara_id
				INNER JOIN _provinsi prp ON p.propinsi_pengirim = prp.provinsi_id
				INNER JOIN _kabupaten kbp ON p.kota_pengirim = kbp.kabupaten_id
				INNER JOIN _kecamatan kcp ON p.kecamatan_pengirim = kcp.kecamatan_id
				where o.pesanan_no = '".$this->db->escape($noorder)."'";
		$strsql = $this->db->query($sql);
        return isset($strsql->row) ? $strsql->row : false;
	}
	
	function getOrderAlamat($noorder){
	    $strsql=$this->db->query("SELECT 
							 n.pesanan_no, n.nama_penerima,n.telp_penerima,n.hp_penerima,
							 n.alamat_penerima,ngn.negara_nama,prn.provinsi_nama,kbn.kabupaten_nama,kcn.kecamatan_nama,
							 n.kelurahan_penerima, n.kodepos_penerima,
							 p.nama_pengirim,p.telp_pengirim,p.hp_pengirim,
							 p.alamat_pengirim,ngp.negara_nama,prp.provinsi_nama,kbp.kabupaten_nama,kcp.kecamatan_nama,
							 p.kelurahan_pengirim,p.kodepos_pengirim,
							 n.negara_penerima,n.propinsi_penerima,
							 n.kota_penerima,n.kecamatan_penerima,n.kodepos_penerima
							 FROM _order_penerima n
							 INNER JOIN _negara ngn ON n.negara_penerima = ngn.negara_id
							 INNER JOIN _provinsi prn ON n.propinsi_penerima = prn.provinsi_id
							 INNER JOIN _kabupaten kbn ON n.kota_penerima = kbn.kabupaten_id
							 INNER JOIN _kecamatan kcn ON n.kecamatan_penerima = kcn.kecamatan_id
							 INNER JOIN _order_pengirim p ON n.pesanan_no = p.pesanan_no
							 INNER JOIN _negara ngp ON p.negara_pengirim = ngp.negara_id
							 INNER JOIN _provinsi prp ON p.propinsi_pengirim = prp.provinsi_id
							 INNER JOIN _kabupaten kbp ON p.kota_pengirim = kbp.kabupaten_id
							 INNER JOIN _kecamatan kcp ON p.kecamatan_pengirim = kcp.kecamatan_id
							 WHERE n.pesanan_no='".$this->db->escape($noorder)."'");
        return isset($strsql->row) ? $strsql->row : false;
	}
	
	function getOrderDetail($noorder){
		$sql = "SELECT _order_detail.pesanan_no,
				_order_detail.produk_id,
				_produk_deskripsi.nama_produk,
				_order_detail.jml,
				_order_detail.harga,
				_order_detail.warnaid,w.warna,
				_order_detail.ukuranid,u.ukuran,
				_order_detail.berat,
				_order_detail.satuan,
				_order_detail.iddetail,poin 
				FROM _order_detail 
				left join _warna w on _order_detail.warnaid = w.idwarna
				left join _ukuran u on _order_detail.ukuranid = u.idukuran
				left join _produk_deskripsi ON _order_detail.produk_id = _produk_deskripsi.idproduk
				left join _produk ON _order_detail.produk_id = _produk.idproduk
				WHERE pesanan_no = '".$this->db->escape($noorder)."'";
		$strsql=$this->db->query($sql);
         
		if($strsql){
			$tabels = array();
			foreach($strsql->rows as $rs) {
				$tabels[] = $rs;
				
			}
			return $tabels;
		}
		return false;
        
	}
	
	function getOrderStatus($noorder){
		$sql = "SELECT tanggal,status_nama,keterangan,idostatus 
				FROM _order_status 
				INNER JOIN _status_order ON
				 _order_status.status_id = _status_order.status_id
				WHERE nopesanan = '".$this->db->escape($noorder)."' 
				ORDER by tanggal desc";
		$strsql=$this->db->query($sql);
		
		if($strsql){
			$tabels = array();
			foreach($strsql->rows as $rs) {
				$tabels[] = $rs;
			}
			return $tabels;
		}
		return false;
	}
	function getOrderPoin($noorder,$customer){
	   $data = 0;
	   $sql = "SELECT cph_poin FROM _customer_point_history WHERE cph_order='".$noorder."' AND cph_cust_id='".$customer."' AND cph_tipe='IN'";
	   $strsql = $this->db->query($sql);
	   return isset($strsql->row['cph_poin']) ? $strsql->row['cph_poin'] : 0;
	   
	}
	function getOrderKonfirmasi($noorder){
		$strsql=$this->db->query("SELECT id_konfirm,order_pesan,jml_bayar,
		                            bank_rek_tujuan,bank_dari,bank_rek_dari,
									bank_atasnama_dari,tgl_transfer,status_bayar,buktitransfer
								  FROM _order_konfirmasi_bayar WHERE order_pesan = '".$this->db->escape($noorder)."'");
		return isset($strsql->row) ? $strsql->row : false;
	}
	
	
	function getQytOrder($noorder){
	    
		$strsql=$this->db->query("SELECT pesanan_no,_order_detail.iddetail,jml,warnaid,ukuranid,produk_id FROM _order_detail 
							 INNER JOIN _order_detail_option ON _order_detail.iddetail = _order_detail_option.iddetail 
							 WHERE pesanan_no='".$this->db->escape($noorder)."'");
		if($strsql){
			$tabels = array();
			foreach($strsql->rows as $rs) {
				$tabels[] = array(
				   'pesanan_no' 		=> $rs[0],
				   'iddetail'			=> $rs[1],
				   'jml'				=> $rs[2],
				   'warna'				=> $rs[3],
				   'ukuran'				=> $rs[4],
				   'produk'				=> $rs[5]
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
	function getTarif($servis,$frmnegara,$frmpropinsi,$frmkabupaten,$frmkecamatan,$totberat,$minkilo){
	    //$tarif = array();
		$totberat = (int)$totberat/1000;
	    if($totberat < 1) $totberat = ceil($totberat);
		if($minkilo > $totberat) $totberat = $minkilo;
		if($totberat > 1) {
		    $berat = floor($totberat);
			$jarakkoma = $totberat - $berat;
			if($jarakkoma > 0.4) $totberat = ceil($totberat);
			else $totberat = floor($totberat);
		} else {
		   $jarakkoma = 0;
		}
	   $strsql = $this->db->query("select * from _tarif_jne WHERE servis_id='".$servis."' AND negara_id='".$frmnegara."' AND
	                          provinsi_id='".$frmpropinsi."' AND kabupaten_id='".$frmkabupaten."' AND kecamatan_id='".$frmkecamatan."'");
       
	   
	   $rs=mysql_fetch_row($strsql);
       $tarif = array($rs[0],((int)$totberat) * (int)$rs[6],$rs[7],(int)$totberat,(int)$rs[6],$jarakkoma);
	   
	        
        return $tarif;					  
	}
	
	function generateInvoice($data){
	   $sql = $this->db->query("update _order set no_invoice='".$data['invoice']."' WHERE pesanan_no='".$data['noorder']."'");
	   if($sql) return true;
	   else return false;
	}
	
	function hapusOrder($data){
		
		$sql = "DELETE _order_penerima,
		                      _order_pengirim,
							  _order, 
							  _order_detail,_order_status FROM 
							  _order 
							  LEFT JOIN _order_penerima ON _order.pesanan_no = _order_penerima.pesanan_no
							  LEFT JOIN _order_pengirim ON _order.pesanan_no = _order_pengirim.pesanan_no 
							  LEFT JOIN _order_detail ON _order.pesanan_no = _order_detail.pesanan_no 
							  LEFT JOIN _order_status ON _order.pesanan_no = _order_status.nopesanan
							  WHERE _order.pesanan_no = '".$data."'";
        return $sql;
	}
	
	function hapusOrderDetailOption($data)
	{
	   
	   $sql = "DELETE FROM _order_detail_option WHERE iddetail = '".$data."'";
	   return $sql;
	}
	function updateStokOption($nopesan,$jml,$warna,$ukuran,$produk){
		$sql = "UPDATE _produk_options set stok = stok+$jml WHERE idproduk='".$produk."' AND ukuran='".$ukuran."' AND warna='".$warna."'";
		return $sql;
	}
	
	public function UpdateStokOptionberKurang($data){
	    $sql = "update _produk_options set stok=stok - ".$data['jml']." WHERE idproduk = '".$data['pid']."' AND ukuran='".$data['idukuran']."' AND warna = '".$data['idwarna']."'";
		
		return $sql;
	}
	
	function updateStok($jml,$produk){
		$sql = "UPDATE _produk set jml_stok = jml_stok+$jml WHERE idproduk='".$produk."'";
        return $sql;
	}
	
	public function UpdateStokberKurang($data){
	    $sql = "update _produk set jml_stok=jml_stok - ".$data['jml']." WHERE idproduk = '".$data['pid']."'";
		/* if($sql) return true;
	    else return false; */
		return $sql;
	}
	
	
	function getOrderEksekusi($masa_belanja,$order_status,$tgl){
		$arkab = array();
	   
		$strsql=$this->db->query("SELECT idpesanan,pesanan_no,pesanan_tgl FROM _order WHERE status_id='".$order_status."' AND (pesanan_tgl + INTERVAL $masa_belanja DAY) < '$tgl'");
		foreach($strsql->rows as $rsa){
			$arkab[] = array(
				'idpesanan' => $rsa['idpesanan'],
				'pesanan_no' => $rsa['pesanan_no']
			);
		}
		return $arkab;
	}
	
	function getProdukOrderOptionByIDdetail($iddetail,$nopesan) {
	    $sql = "SELECT _order_detail.iddetail,pesanan_no,produk_id,jml,harga,satuan,berat,hrg_beli,warnaid,ukuranid
               FROM _order_detail LEFT JOIN _order_detail_option 
			   ON _order_detail.iddetail = _order_detail_option.iddetail
			   WHERE _order_detail.iddetail <> '".$iddetail."' AND pesanan_no='$nopesan' ORDER BY _order_detail.iddetail ASC";
		
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
               FROM _order_detail LEFT JOIN _order_detail_option 
			   ON _order_detail.iddetail = _order_detail_option.iddetail
			   WHERE pesanan_no='$nopesan' $w ORDER BY _order_detail.iddetail ASC";
		
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
	
	function checkTabunganReseller($id){
	   $sql = $this->db->query("SELECT count(jumlah) as total FROM _cust_deposit WHERE cust_id='".$id."'");
	   $total = isset($sql->row['total']) ? $sql->row['total'] : 0;
	   
	   if($total > 0) return true;
  	   else return false;
	}
	function insertTabunganReseller($id,$jml,$tgl){
	   $sql = $this->db->query("insert into _cust_deposit values ('','".$id."','".$jml."','".$tgl."','".$this->user."')");
	   
	   return $sql;
	}
	function updateTabunganReseller($id,$jml,$jmllama,$status,$tgl){
	    //if($status == 'tambah') {
		    $sql = $this->db->query("update _cust_deposito set 
								jumlah = (jumlah - ".$jmllama.") + ".$jml.",tglupdate='".$tgl."',user_login='".$this->user."' 
								WHERE cust_id='".$id."'");
			
		//} else {
		//	$sql = $this->db->query("update _cust_deposit set 
		//						jumlah = (jumlah - ".$jmllama.") - ".$jml.",tglupdate='".$tgl."',user_login='".$this->user."' 
		//						WHERE cust_id='".$id."'");
		//echo "update _cust_deposit set 
		//						jumlah = (jumlah - ".$jmllama.") - ".$jml.",tglupdate='".$tgl."',user_login='".$this->user."' 
		//						WHERE cust_id='".$id."'";
		
		//}
		/*
		if($sql) return true;
		else return false;
		*/
		return $sql;
	
	}
	public function UpdateDeposit($tabungan,$tambah,$reseller,$tgl){
	    $sql = $this->db->query("update _cust_deposit set jumlah=(jumlah + ".$tabungan.") - ".$tambah.",user_login='".$this->user."', tglupdate='".$tgl."'
		                    WHERE cust_id = '".$reseller."'");
		
		return $sql;
	}
	
	public function UpdateDepositBerkurang($data){
	    $sql = $this->db->query("update _cust_deposit set jumlah=jumlah - ".$data['deposit'].",user_login='0', tglupdate='".$data['tgltrans']."'
		                    WHERE cust_id = '".$data['idmember']."'");
		
		return $sql;
	}
	
	function insertTabunganDetail($id,$jml,$tgl,$nopesan,$sts){
	   $sql = $this->db->query("SELECT MAX(idhistory) FROM _cust_deposit_history WHERE cust_id='".$id."' and no_pesanan='".$nopesan."'");
	   $rs = mysql_fetch_row($s);
	   //echo "rs ".$rs[0];
	   if($rs[0] > 0){
	      $sql = $this->db->query("DELETE FROM _cust_deposit_history WHERE idhistory='".$rs[0]."'");
	   }
	   if($sql){
	     $sql = $this->db->query("insert into _cust_deposit_history values ('','".$id."','".$jml."','".$tgl."','".$nopesan."','".$sts."')");
	   }
	   /*
	   if($sql) return true;
	   else return false;
	   */
	   return $sql;
	}
	
	public function InsertDepositDetail($data){
	   $sql = $this->db->query("insert into _cust_deposit_history values ('','".$data['idmember']."','".$data['deposit']."','".$data['tgltrans']."','".$data['nopesanan']."','SPEND')");
	  
	   return $sql;
	}
	
	function DeleteDepositDetail($reseller,$data){
	  $sql = $this->db->query("SELECT MAX(idhistory) FROM _cust_deposit_history WHERE cust_id='".$reseller."' and no_pesanan='".$data."' and sts='GET'");
	  $rs = mysql_fetch_row($s);
	   //echo "rs ".$rs[0];
	  if($rs[0] > 0){
	      $sql = $this->db->query("DELETE FROM _cust_deposit_history WHERE idhistory='".$rs[0]."'");
	   }
	  if($sql) {
	     $sql = $this->db->query("DELETE from _cust_deposit_history WHERE no_pesanan='".$data."' and cust_id='".$reseller."' and sts='SPEND'");
	  }
	  return $sql;
	}
	public function SimpanOrderDetail($data){
	    $sql = $this->db->query("insert into _order_detail values ('".$data['iddetail']."',
		                   '".$data['nopesanan']."','".$data['pid']."','".$data['jml']."',
						   '".$data['harga']."','".$data['satuan']."','".$data['berat']."','".$data['hrgbeli']."')");
		/* if($sql) return true;
	    else return false;
		*/
		return $sql;
						   
	}
	
	public function SimpanOrderDetailOption($data){
	    $sql = $this->db->query("insert into _order_detail_option values ('','".$data['iddetail']."',
		                   '".$data['idwarna']."','".$data['idukuran']."')");
		/* if($sql) return true;
	    else return false;
		*/
		return $sql;
						   
	}
	
	public function updateOrderProdukOption($data){
	   $sql = "UPDATE _order_detail INNER JOIN _order_detail_option ON _order_detail.iddetail = _order_detail_option.iddetail 
			  SET warnaid = '".$data['idwarna']."',ukuranid = '".$data['idukuran']."',
			  jml='".$data['jml']."',harga='".$data['harga']."',satuan='".$data['satuan']."',
			  berat='".$data['berat']."',hrg_beli='".$data['hrgbeli']."' 
			  WHERE warnaid='".$data['warnalama']."' AND ukuranid='".$data['ukuranlama']."' AND pesanan_no='".$data['nopesanan']."' 
			  AND produk_id='".$data['pid']."'";
	 
	 
	   return $sql;
	}
	
	public function updateOrderProduk($data){
	    $sql = "UPDATE _order_detail SET jml='".$data['jml']."',harga='".$data['harga']."',satuan='".$data['satuan']."',
			  berat='".$data['berat']."' WHERE pesanan_no='".$data['nopesanan']."' AND produk_id='".$data['pid']."'";
	  
		return $sql;
	}
	
	public function UpdateOrder($data){
	  $sql = "UPDATE _order set pesanan_jml='".$data['totjumlah']."',
	                pesanan_subtotal = '".$data['subtotal']."',
					pesanan_kurir = '".$data['tarifkurir']."',
					kurir_perkilo = '".$data['satuantarifkurir']."',
					dari_poin = '".$data['potpoinbaru']."',
					dari_deposito = '".$data['potdepositobaru']."'
			  WHERE pesanan_no = '".$data['nopesanan']."'";
	
	   return $sql;
					
					
	}
	
	public function hapusProdukOrderOption($data){
	  $sql = "DELETE _order_detail,_order_detail_option
			  FROM _order_detail
              LEFT JOIN _order_detail_option
              ON _order_detail.iddetail = _order_detail_option.iddetail
			  WHERE _order_detail.iddetail = '".$data['iddetail']."'";
      $sql = $this->db->query($sql);
	 
	  return $sql;
			 
	}
	
	public function SimpanOrder($data){
	    $sql = $this->db->query("insert into _order values ('','".$data['nopesanan']."',
		                   '".$data['idmember']."','".$data['totjumlah']."',
						   '".$data['subtotal']."','".$data['tarifkurir']."',
						   '".$data['tgltrans']."',0,'".$data['orderstatus']."',
						   '".$data['kurir']."',
						   '".$data['kurirservis']."','-',
						   '".$data['satuantarifkurir']."',
						   '".$_SERVER['REMOTE_ADDR']."','".$data['infaq']."',0,0,'".$data['cust_grup']."','0','".$data['deposit']."')");
		/*
		if($sql) return true;
	    else return false; */
		return $sql;
						   
	}
	
	public function SimpanOrderPenerima($data){
	    $sql = $this->db->query("insert into _order_penerima values ('".$data['nopesanan']."',
		                   '".$data['nama']."','".$data['telp']."',
						   '".$data['hp']."','".$data['alamat']."',
						   '".$data['negara']."','".$data['propinsi']."',
						   '".$data['kabupaten']."','".$data['kecamatan']."',
						   '".$data['kelurahan']."','".$data['kodepos']."')");
		/*if($sql) return true;
	    else return false; */
		return $sql;
						   
	}
	
	public function SimpanOrderPengirim($data){
	    $sql = $this->db->query("insert into _order_pengirim values ('".$data['nopesanan']."',
		                   '".$data['namapengirims']."','".$data['telppengirims']."',
						   '".$data['hppengirims']."','".$data['alamatpengirims']."',
						   '".$data['negarapengirims']."','".$data['propinsipengirims']."',
						   '".$data['kabupatenpengirims']."','".$data['kecamatanpengirims']."',
						   '".$data['kelurahanpengirims']."','".$data['kodepospengirims']."')");
		/*
		if($sql) return true;
	    else return false; */
		return $sql;
						   
	}
	public function SimpanIntensif($data){
	   $sql = $this->db->query("INSERT INTO _order_intensif values ('".$data['nopesanan']."','".$data['userid']."','0000-00-00','0000-00-00 00:00:00','".$data['jmlbonus']."','".$data['btsbonus']."')");
	   /*if($sql) return true;
	   else return false; */
	   return $sql;
	}
	function cekIntensif($noorder){
	    $str = $this->db->query("select count(*) from _order_intensif WHERE pesanan_no = '".$this->db->escape($noorder)."'");
		$rs	 = mysql_fetch_row($str);
		if($rs[0] > 0) return true;
		else return false;
	}
	function UpdateIntensif($data){
	   $field = array();
	   //$sql = false;
	   /*
	   if($data['tglkomplet'] != '0000-00-00 00:00:00') {

	      $field[] = "tgl_komplet = '".$data['tglkomplet']."'";
	   }
	   if($data['tglkonfirm'] != '0000-00-00') {

	      $field[] = "tgl_konfirm = '".$data['tglkonfirm']."'";
	   }
	   if(count($field) > 0){
	       $fields = implode(',',$field);
	   } else {
	       $fields = '';
	   } 
	   if($fields != ''){
	      $sql = $this->db->query("UPDATE _order_intensif set $fields WHERE pesanan_no = '".$data['iddata']."'");
		  
	   }
	   */
	    $field[] = "tgl_komplet = '".$data['tglkomplet']."'";
		$field[] = "tgl_konfirm = '".$data['tglkonfirm']."'";
		$fields = implode(',',$field);
		$sql = $this->db->query("UPDATE _order_intensif set $fields WHERE pesanan_no = '".$data['iddata']."'");
	   
	   /*
	   if($sql) return true;
	   else return false;
	   */
	   return $sql;
	}
	public function SimpanOrderStatus($data){
	    $sql = $this->db->query("insert into _order_status values ('','".$data['nopesanan']."',
		                   '".$data['tgltrans']."','".$data['orderstatus']."','-')");
		/*
		if($sql) return true;
	    else return false; */
		return $sql;
						   
	}
	
	public function editKonfirmasiOrder($data) {
	   $sql = "UPDATE _order_konfirmasi_bayar set jml_bayar='".$data['jmlbayar']."',bank_rek_tujuan='".$data['bankto']."',
	           bank_dari = '".$data['bankfrom']."',bank_rek_dari='".$data['norekfrom']."',bank_atasnama_dari='".$data['atasnamafrom']."',
			   tgl_transfer = '".$data['tglbayar']."',status_bayar='".$data['orderstatus']."' WHERE order_pesan='".$data['nopesanan']."'";
	   //$str = $this->db->query($sql);
	   /*
	   if($str)
	      return true;
	   else
	      return false;
	   */
	   //return $str;
	   return $sql;
	}
	
	public function addKonfirmasiOrder($data) {
	   $sql = "INSERT INTO _order_konfirmasi_bayar values ('',
							'".$data['nopesanan']."',
							'".$data['jmlbayar']."',
							'".$data['bankto']."',
							'".$data['bankfrom']."',
							'".$data['norekfrom']."',
							'".$data['atasnamafrom']."',
							'".$data['tglbayar']."',
							'".$data['orderstatus']."',
							'".$data['tgl']."',
							'".$data['ipdata']."')";
      
	  // $str = $this->db->query($sql);
	   /*
	   if($str)
	      return true;
	   else
	      return false;
	    */
		//return $str;
		return $sql;
	}
	
	public function simpanEditKurir($data){
		$sql = "UPDATE _order set pesanan_kurir = '".$data['tarifkurir']."',kurir='".$data['kurir']."',servis_kurir='".$data['serviskurir']."',
	           kurir_perkilo='".$data['kurir_perkilo']."',
			   kurir_konfirm='".$data['konfirm_admin']."'
			   WHERE pesanan_no='".$data['nopesanan']."'";
	   
		$stre = $this->db->query($sql);
	   
		if($stre) return true;
        else return false;
			
	}
	
	public function getOrderNolKurir() {
	  $tabels = array();
	  $sql = "select pesanan_no,cust_nama,pesanan_jml,pesanan_subtotal,pesanan_kurir,pesanan_infaq,pesanan_tgl from _order INNER JOIN _status_order ON _order.status_id = _status_order.status_id INNER JOIN _reseller ON _order.pelanggan_id = _reseller.cust_id where pesanan_kurir = 0 AND kurir <> '' AND servis_kurir <> '' AND servis_kurir > 0 ";
	  $strsql = $this->db->query($sql);
	  foreach($strsql->rows as $rs) {
            $tabels[] = array(
		       'noorder' 		=> $rs[0],
		       'reseller'		=> $rs[1],
			   'jml'   	        => $rs[2],
			   'subtotal'		=> $rs[3],
			   'kurir'			=> $rs[4],
			   'infaq'   		=> $rs[5],
			   'tgl'   		    => $rs[6]
	        );
	        
        }
        return $tabels;
	}
	
	public function getOrderPending($status) {
	   $tabels = array();
	   $sql = "select pesanan_no,cust_nama,pesanan_jml,pesanan_subtotal,pesanan_kurir,
	           pesanan_infaq,pesanan_tgl from _order INNER JOIN _status_order ON _order.status_id = _status_order.status_id 
			   NNER JOIN _customer ON _order.pelanggan_id = _customer.cust_id where status_id='".$status."'";
	   $strsql = $this->db->query($sql);
	   foreach($strsql->rows as $rs) {
            $tabels[] = array(
		       'noorder' 		=> $rs[0],
		       'reseller'		=> $rs[1],
			   'jml'   	        => $rs[2],
			   'subtotal'		=> $rs[3],
			   'kurir'			=> $rs[4],
			   'infaq'   		=> $rs[5],
			   'tgl'   		    => $rs[6]
	        );
	        
        }
        return $tabels;
	}
	public function getTotalOrderPending($status){
	  $sql = "SELECT count(pesanan_no) FROM _order WHERE status='".$status."'";
	  $str = $this->db->query($sql);
	  $rs  = mysql_fetch_row($sql);
	  return $rs[0];
	  
	}
	
	public function simpaneditstatus($data){
		$error = array();
		$status = '';
		$this->db->autocommit(false);
		
		/* simpan ke table order status */
		if($data['simpanstatusorder']) {
			$sql = "insert into _order_status values
					(null,'".$data['nopesanan']."','".$data['tgl']."',
					'".$data['orderstatus']."','".$this->db->escape($data['keterangan'])."')";
			$strsql = $this->db->query($sql);
			if(!$strsql) $error[] = 'Error di table _order_status';
		}
		
		/* simpan ke table _order_konfirmasi_bayar */
		if($data['konfirmasiorder'] == 'add') {
			
			$sql = "insert into _order_konfirmasi_bayar values 
					(null,'".$data['nopesanan']."',
					'".$data['jmlbayar']."','".$data['bankto']."',
					'".$data['namabankdari']."','".$data['rekbankdari']."',
					'".$data['atasnamabankdari']."','".$data['tglbayar']."',
					'".$data['orderstatus']."','".$data['tgl']."','".$data['ipdata']."','')";
			
			$strsql = $this->db->query($sql);
			if(!$strsql) $error[] = 'Error di table _order_konfirmasi_bayar';
			
		} elseif($data['konfirmasiorder'] == 'edit') {
		
			$sql = "update _order_konfirmasi_bayar set
					jml_bayar='".$data['jmlbayar']."',
					bank_rek_tujuan='".$data['bankto']."',
					bank_dari='".$data['namabankdari']."',
					bank_rek_dari='".$data['rekbankdari']."',
					bank_atasnama_dari='".$data['atasnamabankdari']."',
					tgl_transfer='".$data['tglbayar']."',
					status_bayar='".$data['orderstatus']."'
					WHERE order_pesan='".$data['nopesanan']."'";
			$strsql = $this->db->query($sql);
			if(!$strsql) $error[] = 'Error di table _order_konfirmasi_bayar';
		}
		
		
		/* simpan ke tabel _order */
		$sql = "UPDATE _order set 
				status_id='".$data['orderstatus']."',
				tgl_kirim  = '".$data['tglkirim']."',
				no_awb='".$data['noawb']."' 
				WHERE pesanan_no='".$data['nopesanan']."'";
		
		$strsql = $this->db->query($sql);
		if(!$strsql) $error[] = 'Error di table _order';
		
		/* simpan ke table _customer_point_history */
		if($data['simpangetpoin'] == 'update'){
			
			$sql = "update _customer_point_history 
					SET cph_poin='".$data['totpoindapat']."' 
					WHERE cph_cust_id='".$data['pelangganid']."' 
					AND cph_tipe='IN' AND cph_order='".$data['nopesanan']."'";
					
		} elseif ($data['simpangetpoin'] == 'insert'){
			
			$sql = "INSERT INTO _customer_point_history 
					SET cph_cust_id='".$data['pelangganid']."',
					cph_poin = '".$data['totpoindapat']."',
					cph_tipe='IN',cph_tgl='".$data['tgl']."',
					cph_order='".$data['nopesanan']."'";
			
		}
		
		$strsql = $this->db->query($sql);
		if(!$strsql) $error[] = 'Error di table _customer_point_history';
		
		/* simpan _customer_point */
		if($data['customerpoin'] == 'update'){
			
			$sql = "UPDATE _customer_point 
					SET cp_poin=cp_poin+".$data['totpoindapat'].",
					cp_tglupdate='".$data['tgl']."' 
					WHERE cp_cust_id='".$data['pelangganid']."'";
					
		} elseif ($data['customerpoin'] == 'insert'){
			
			$sql = "INSERT into _customer_point values 
					(null,'".$data['pelangganid']."','".$data['totpoindapat']."',
					cp_tglupdate='".$data['tgl']."')";
			
		}
			
		$strsql = $this->db->query($sql);
		if(!$strsql) $error[] = 'Error di table _customer_point';
		
		
		if(count($error) > 0) {
			$this->db->rollback();
			$status = 'error';
		} else {
			$this->db->commit();
			$status = 'success';
		}
		return array("status"=>$status);
	}
	
	public function checkCustomerPoin($id){
		$cekpoin = $this->db->query("SELECT count(cp_cust_id) as total FROM _customer_point WHERE cp_cust_id='".$id."'");
		return isset($cekpoin->row['total']) ? $cekpoin->row['total'] : 0;
	}
	
	public function prosesTransaksi($proses) {
	    $jmlproses = count($proses);
        $this->db->query("SET AUTOCOMMIT=0");
        $this->db->query("START TRANSACTION");
		/*
        for($i=0; $i < $jmlproses; $i++)
        {
           try {
		      
                if (!$this->db->query($proses[$i])) throw new Exception(mysql_error());
           }
           catch (Exception $e) {
             $this->db->query("ROLLBACK");
			 echo($proses[$i]);
             break;
           }
        }
        */
		$error = array();
		
		for($i=0; $i < $jmlproses; $i++)
        { 
		 
		  if (!$this->db->query($proses[$i])){
		      	  
		  } 
		
		}
		
		if(count($error) > 0) {
		   $this->db->query("ROLLBACK");
		   return false;
		} else {
		   $this->db->query("COMMIT");
           $this->db->query("SET AUTOCOMMIT=1");
		   return true;
		}
		
	}
	
	function __destruct() {
		$this->db->disconnect();
	}
}
?>