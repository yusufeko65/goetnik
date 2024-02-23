<?php
class model_Order {
	private $db;
	private $tabelnya;
	private $idlogin;
	
	function __construct(){
		$this->db 	= new Database();
		$this->db->connect();
		$this->idlogin = isset($_SESSION['idmember']) ? $_SESSION['idmember']:'';
	}
    
	function cekOrder($noorder,$login){
	    $str = $this->db->query("select count(*) as total from _order WHERE pesanan_no = '".$noorder."' AND pelanggan_id='".$login."'");
		
		if($str->row['total'] > 0) return true;
		else return false;
	}
	
	function getOrder($batas,$baris,$where){
		$sql = "SELECT * FROM _order 
				INNER JOIN _status_order ON _order.status_id = _status_order.status_id 
				left join _order_pengirim on _order.pesanan_no = _order_pengirim.pesanan_no
				left join _order_penerima on _order.pesanan_no = _order_penerima.pesanan_no
				WHERE pelanggan_id='".$this->idlogin."' ORDER BY pesanan_tgl desc limit $batas,$baris";
		
		$str = $this->db->query($sql);
		if($str) {
			$data = [];
			foreach($str->rows as $row){
				$data[] = $row;
			}
			return $data;
		} else {
			return false;
		}
		
	}
	
	function getOrderByID($noorder){
	
		$sql = "select o.pesanan_no,o.pelanggan_id,o.pesanan_jml,
				o.pesanan_subtotal,o.pesanan_kurir,o.pesanan_tgl,
				o.status_id,so.status_nama,o.kurir,sp.shipping_nama,
				o.servis_kurir,s.servis_code,o.grup_member,
				o.dari_poin,o.potongan_kupon,o.kode_kupon,o.tgl_kirim,
				o.no_awb,o.dari_deposito,o.kurir_konfirm,
				n.nama_penerima,n.hp_penerima,
				n.alamat_penerima,ngn.negara_nama as negaranm_penerima,n.negara_penerima,
				prn.provinsi_nama as propinsinm_penerima,n.propinsi_penerima,kbn.kabupaten_nama as kotanm_penerima,kcn.kecamatan_nama as kecamatannm_penerima,
				n.kelurahan_penerima, n.kodepos_penerima,
				p.nama_pengirim,p.hp_pengirim,
				p.alamat_pengirim,ngp.negara_nama as negaranm_pengirim,prp.provinsi_nama as propinsinm_pengirim,kbp.kabupaten_nama as kotanm_pengirim,kcp.kecamatan_nama as kecamatannm_pengirim,
				p.kelurahan_pengirim,p.kodepos_pengirim,
				n.kota_penerima,n.kecamatan_penerima,n.kodepos_penerima,
				cg.cg_dropship
				from _order o
				inner join _customer_grup cg on o.grup_member = cg.cg_id
				left join _status_order so on o.status_id = so.status_id
				left join _servis s on o.servis_kurir = s.servis_id
				left join _shipping sp on o.kurir = sp.shipping_kode
				inner join _order_penerima n on o.pesanan_no = n.pesanan_no
				INNER JOIN _negara ngn ON n.negara_penerima = ngn.negara_id
				INNER JOIN _provinsi prn ON n.propinsi_penerima = prn.provinsi_id
				INNER JOIN _kabupaten kbn ON n.kota_penerima = kbn.kabupaten_id
				INNER JOIN _kecamatan kcn ON n.kecamatan_penerima = kcn.kecamatan_id
				INNER JOIN _order_pengirim p ON o.pesanan_no = p.pesanan_no
				INNER JOIN _negara ngp ON p.negara_pengirim = ngp.negara_id
				INNER JOIN _provinsi prp ON p.propinsi_pengirim = prp.provinsi_id
				INNER JOIN _kabupaten kbp ON p.kota_pengirim = kbp.kabupaten_id
				INNER JOIN _kecamatan kcp ON p.kecamatan_pengirim = kcp.kecamatan_id
				WHERE o.pelanggan_id='".$this->idlogin."' AND o.pesanan_no='".$this->db->escape($noorder)."'";
	    $strsql=$this->db->query($sql);
		
        return isset($strsql->row) ? $strsql->row : false;
	}
	
	function getOrderAlamat($noorder){
	    $strsql=$this->db->query("SELECT 
							 n.pesanan_no, n.nama_penerima,n.hp_penerima,
							 n.alamat_penerima,ngn.negara_nama as negaranm_penerima,prn.provinsi_nama as propinsinm_penerima,kbn.kabupaten_nama as kotanm_penerima,kcn.kecamatan_nama as kecamatannm_penerima,
							 n.kelurahan_penerima, n.kodepos_penerima,
							 p.nama_pengirim,p.hp_pengirim,
							 p.alamat_pengirim,ngp.negara_nama as negaranm_pengirim,prp.provinsi_nama as propinsinm_pengirim,kbp.kabupaten_nama as kotanm_pengirim,kcp.kecamatan_nama as kecamatannm_pengirim,
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
							 WHERE n.pesanan_no='".$noorder."'");
        return isset($strsql->row) ? $strsql->row : false;
	}
	
	function getOrderDetail($noorder){
		
		$sql = "SELECT _order_detail.pesanan_no,
					   _order_detail.produk_id,
					   _produk_deskripsi.nama_produk,
					   _order_detail.jml,
					   _order_detail.harga,_order_detail.warnaid,
					   _order_detail.ukuranid,
					   _order_detail.iddetail,_order_detail.berat
			    FROM _order_detail 
				LEFT JOIN _produk_deskripsi ON _order_detail.produk_id = _produk_deskripsi.idproduk
				WHERE pesanan_no = '".$noorder."'";
		
		$strsql=$this->db->query($sql);
		if($strsql){
			$data = array();
			foreach ($strsql->rows as $rs) {
				$data[] = array(
				   'noorder' 		=> $rs['pesanan_no'],
				   'produkid'		=> $rs['produk_id'],
				   'nama_produk'	=> $rs['nama_produk'],
				   'jml'			=> $rs['jml'],
				   'harga'			=> $rs['harga'],
				   'warnaid'		=> $rs['warnaid'],
				   'ukuranid'		=> $rs['ukuranid'],
				   'iddetail'		=> $rs['iddetail'],
				   'berat'          => $rs['berat']
				);
				
			}
			return $data;
		} else {
			return false;
		}
       
	}
	
	function getOrderStatus($noorder){
	   $tabels = array();
		$strsql=$this->db->query("SELECT tanggal,status_nama,keterangan FROM _order_status INNER JOIN _status_order ON
		                     _order_status.status_id = _status_order.status_id
							WHERE nopesanan = '".$noorder."' ORDER BY tanggal desc ");
		
		foreach ($strsql->rows as $rs) {
            $tabels[] = array(
		       'tgl' 		=> $rs['tanggal'],
		       'status'		=> $rs['status_nama'],
			   'keterangan'	=> $rs['keterangan'],
	        );
	        
        }
        return $tabels;
	}
	
	public function totalOrder($where){
	    if($where!='') $where = " where ".$where;
		$query = '';
		$sql = "select count(pelanggan_id) as total from _order INNER JOIN _status_order ON _order.status_id = _status_order.status_id WHERE pelanggan_id='".$this->idlogin."'".$query.$where;
		
		$strsql=$this->db->query($sql);
		
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	
	function getOrderbyName($nama){
		$strsql=$this->db->query("select * from _shipping WHERE tampil=1 AND nama_shipping='$nama'");
        return $strsql->row;
	}
	
	function getLastOrder($idmember,$status_order,$limit){
		$sql = "select pesanan_no,pesanan_subtotal,
				pesanan_kurir,pesanan_tgl,dari_poin,potongan_kupon,dari_deposito
				from _order 
				where pelanggan_id='".$idmember."' and status_id='".$status_order."'
				order by pesanan_tgl desc limit $limit";
		
		$strsql = $this->db->query($sql);
		if($strsql){
			$data = [];
			foreach($strsql->rows as $row) {
				$data[] = $row;
			}
			return $data;
		} else {
			return false;
		}
	}
	
}
?>