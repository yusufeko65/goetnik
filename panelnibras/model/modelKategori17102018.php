<?php
class modelKategori {
	private $db;
	private $tabelnya;
	
	function __construct(){
		$this->tabelnya = '_category';
		$this->db 		= new Database();
		$this->db->connect();
	}
	
	function checkDataKategori($kategori_nama){
		$check = $this->db->query("select name from _category_description where name='".$this->db->escape($kategori_nama)."'");
		
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function checkDataKategoriByID($kategori_id){
		$check = $this->db->query("select category_id from ".$this->tabelnya." where category_id='$kategori_id'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	
	function simpanKategori($data=array()){
		$error = array();
		$idkategori = '';
		$insert = '';
		$this->db->autocommit(false);
		
		/* table _Category */
		$sql = "INSERT INTO `_category` 
				SET `parent_id` = '" . (int)$data['kategori_induk'] . "',
				`sort_order` = '" . (int)$data['kategori_urutan'] . "',
                `image`='".$data['kategori_logo']."',
				`date_modified` = '".$data['tgl']."', 
				`date_added` = '".$data['tgl']."',
				`alias_url` = '".$data['kategori_alias']."',
				`spesial`='".$data['spesial']."'";
		
		$sql = $this->db->query($sql);
		if(!$sql) {
			$error[] = "Error di table _Category";
		} else {
			/* mengambil idkategori yang terakhir diinput */
			$idkategori = $this->db->lastid();
		}
		
		/* table _category_description */
		$sql = $this->db->query("INSERT INTO `_category_description` SET `category_id` = '" . (int)$idkategori . "', `name` = '" . $data['kategori_nama'] . "',`description`='".$data['keterangan']."',`language_id`=0");
		if(!$sql) $error[] = "Error di table _category_description";
		
		/* table _url_alias */
		$inisial = 'kategori='.$idkategori;
	   
		$sql = $this->db->query("insert into _url_alias values ('".$inisial."','".$data['kategori_alias']."','produk')");
		if(!$sql) $error[] = "Error di table _url_alias";
		
		/* category _path */
		$level = 0;
		if($data['pathcategory']) {
			
			$datavalue = [];
			foreach($data['pathcategory'] as $cat)
			{
				$datavalue[] = "('".$idkategori."','".$cat['path_id']."','".$level."')";
				$level++;
			}
			
		}
		$datavalue[] = "('".$idkategori."','".$idkategori."','".$level."')";
		if(count($datavalue) > 0) {
			$insert = implode(",",$datavalue);
		}
		if($insert != '') {
			$sql = $this->db->query("INSERT INTO _category_path values ".$insert);
			if(!$sql) $error[] = "Error di table produk kategori path";
			
		}
		if(count($error) > 0) {
			$this->db->rollback();
			$status = "error";
			
		} else {
			$this->db->commit();
			$status = "success";
		}
		return array("status"=>$status);
	
	}
	function simpanDeskripsiKategori($data=array()){
	   //$sql=$this->db->query("insert into _kategori_deskripsi values ('','".$data['kategori_id']."','".$data['kategori_nama']."')");
	   return $this->db->query("INSERT INTO `_category_description` SET `category_id` = '" . (int)$data['kategori_id'] . "', `name` = '" . $data['kategori_nama'] . "',`description`='".$data['keterangan']."',`language_id`=0");
	   
	}
	function simpanPathKategori($data) {
	     $level = 0;

		$query = $this->db->query("SELECT * FROM _category_path WHERE category_id = '" . (int)$data['kategori_induk'] . "' ORDER BY level ASC");
//        $result = mysql_fetch_array($query);
		foreach($query->rows as $result) {
		
			$this->db->query("INSERT INTO `_category_path` SET `category_id` = '" . (int)$data['kategori_id'] . "', `path_id` = '" . (int)$result['path_id'] . "', `level` = '" . (int)$level . "'");

			$level++;
		}

		$s = $this->db->query("INSERT INTO `_category_path` SET `category_id` = '" . (int)$data['kategori_id'] . "', `path_id` = '" . (int)$data['kategori_id'] . "', `level` = '" . (int)$level . "'");
		if($s) return true;
		else return false;
	}
	function editKategori($data=array()){
		$error = array();
		$this->db->autocommit(false);
		$idkategori = $data['iddata'];
		/* update category */
		$sql = "UPDATE _category SET 
				alias_url = '" . $data['kategori_alias'] . "', 
				date_modified = '".$data['tgl']."', 
				image='".$data['kategori_logo']."', 
				sort_order='".$data['kategori_urutan']."',
				spesial = '".$data['spesial']."' WHERE category_id = '" . (int)$idkategori . "'";
		$sql = $this->db->query($sql);
		if(!$sql) $error[] = "Error di table _category";
		
		/* update category description */
		$sql = "UPDATE _category_description set name='".$this->db->escape($data['kategori_nama'])."',description='".$data['keterangan']."' WHERE category_id='".$idkategori."'";
		$sql = $this->db->query($sql);
		if(!$sql) $error[] = "Error di table _category";
		
		/* update url alias */
		$inisial = 'kategori='.$idkategori;
		$del = $this->db->query("delete from _url_alias WHERE inisial='".$inisial."'");
		$sql = $this->db->query("insert into _url_alias values ('".$inisial."','".$data['kategori_alias']."','produk')");
		if(!$sql) $error[] = "Error di table _url_alias";
		
		/* category _path */
		$level = 0;
		if($data['pathcategory']) {
			
			$datavalue = [];
			foreach($data['pathcategory'] as $cat)
			{
				$datavalue[] = "('".$idkategori."','".$cat['path_id']."','".$level."')";
				$level++;
			}
			
		}
		$datavalue[] = "('".$idkategori."','".$idkategori."','".$level."')";
		if(count($datavalue) > 0) {
			$insert = implode(",",$datavalue);
		}
		if($insert != '') {
			$sql = $this->db->query("delete from _category_path where category_id='".$idkategori."'");
			$sql = $this->db->query("INSERT INTO _category_path values ".$insert);
			if(!$sql) $error[] = "Error di table produk kategori path";
		}
		if(count($error) > 0) {
			$this->db->rollback();
			$status = "error";
			
		} else {
			$this->db->commit();
			$status = "success";
		}
		return array("status"=>$status);
	}
	
	function editDeskripsiKategori($data=array()){
	
	   $sql = "UPDATE _category_description set name='".$this->db->escape($data['kategori_nama'])."',description='".$data['keterangan']."' WHERE category_id='".$data['kategori_id']."'";
	   $sql = $this->db->query($sql);
	   if($sql) return true;
	   else return false;
	}
	function simpanAliasKategori($data){
	   $inisial = 'kategori='.$data['kategori_id'];
	   
	   $del = $this->db->query("delete from _url_alias WHERE inisial='".$inisial."'");
	   $sql = $this->db->query("insert into _url_alias values ('".$inisial."','".$data['kategori_alias']."','produk')");
	   if($sql) return true;
	   else return false;
	}
	function getKategori(){
		//$strsql=$this->db->query("select * from ".$this->tabelnya." INNER JOIN _kategori_deskripsi ON _kategori.kategori_id = _kategori_deskripsi.idkategori order by kategori_nama asc ");
		$strsql = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM _category_path cp LEFT JOIN _category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id GROUP BY cp.category_id) AS path FROM _category c LEFT JOIN _category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");
		$rsa=mysql_fetch_array($strsql);
		return $rsa;
	}
	function getPath($category_id) {
		$query = $this->db->query("SELECT kategori_nama, kategori_induk FROM ".$this->tabelnya." INNER JOIN _kategori_deskripsi ON _kategori.kategori_id = _kategori_deskripsi.idkategori WHERE kategori_id = '" . (int)$category_id . "' ORDER BY kategori_nama ASC");
		//$row = mysql_fetch_array($query);
		if($query->row['kategori_induk']!=0){
			return $this->getPath($query->row['kategori_induk']) . " >> " . $query->row['kategori_nama'];
		} else {
			return $query->row['kategori_nama'];
		}
    }
	function getPathInduk($category_id)
	{
		$query = $this->db->query("SELECT * FROM _category_path WHERE category_id = '" . (int)$category_id . "' ORDER BY level ASC");
		return (!$query) ? false  : $query->rows;
		
	}
	function getKategoriLimit($data,$parent_id = 0){
	   
		$category_data = array();
		$limit = '';
		$where = '';
		$filter = array();
		
		if($data['datacari'] != '') {
			$filter[] = " cd2.name like '%".trim($this->db->escape($data['datacari']))."%'";
		}
		if($data['spesial'] != '') $filter[] = " c1.spesial = '".trim(strip_tags($data['spesial']))."'";
		
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where) $where = " WHERE ".$where;
		
		$strsql  = "SELECT cp.category_id AS category_id,
		        GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, 
				c1.parent_id, c1.sort_order FROM _category_path cp 
				LEFT JOIN _category c1 ON (cp.category_id = c1.category_id) 
				LEFT JOIN _category c2 ON (cp.path_id = c2.category_id) 
				LEFT JOIN _category_description cd1 ON (cp.path_id = cd1.category_id) 
				LEFT JOIN _category_description cd2 ON (cp.category_id = cd2.category_id) ".$where;
		$strsql .= " GROUP BY cp.category_id ORDER BY name ASC";
		//echo $strsql;
		$sql = $this->db->query($strsql);
		foreach($sql->rows as $rs) {
		
            $category_data[] = array(
		       'kategori_id' => $rs['category_id'],
		       'kategori_nama' => $rs['name'],
			   'kategori_urutan' => $rs['sort_order']
	        );
        }
        return $category_data;
		//return $hasil;
		
	}
	
	function totalKategori($where,$parent_id = 0){
	   
		
		if($data['datacari'] != '') $filter[] = " cd2.name like '%".trim($this->db->escape($data['datacari']))."%'";
		if($data['spesial'] != '') $filter[] = " c1.spesial = '".trim(strip_tags($data['spesial']))."'";
		
		if(!empty($filter))	$where = implode(" and ",$filter);
		
		if($where) $where = " WHERE ".$where;
		$sql = "SELECT count(*) as total FROM  _category_description ".$where;
		
		$this->db->query($sql);
		return isset($strsql->row['total']) ? $strsql->row['total'] : 0;
	}
	
	function getKategoriByIDs($iddata){
		$sql = "SELECT cp.category_id AS category_id,
		        GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, 
				c1.parent_id, c1.sort_order,c1.image,cd2.description,c1.spesial FROM _category_path cp 
				LEFT JOIN _category c1 ON (cp.category_id = c1.category_id) 
				LEFT JOIN _category c2 ON (cp.path_id = c2.category_id) 
				LEFT JOIN _category_description cd1 ON (cp.path_id = cd1.category_id) 
				LEFT JOIN _category_description cd2 ON (cp.category_id = cd2.category_id)";
		
		$sql .= " WHERE cd2.category_id = '" . $iddata . "'";
		$sql .= " GROUP BY cp.category_id";
		
		$strsql = $this->db->query($sql);
		return isset($strsql->row) ? $strsql->row : false;
	}
	function getKategoriByID($iddata){
		$strsql = "SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM _category_path cp LEFT JOIN _category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id GROUP BY cp.category_id) AS path FROM _category c LEFT JOIN _category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$iddata . "'";
		$strsql = $this->db->query($strsql);
		return isset($strsql->row) ? $strsql->row : false;
	}
	
	function checkRelasi($data){
		$check = $this->db->query("select kategori_id from _produk_kategori where idkategori='$data'");
		$jml=$check->num_rows;
		if($jml>0) return true;
		else return false;
	}
	function hapusKategori($data){
		$check = $this->db->query("delete from ".$this->tabelnya." where category_id='$data'");
		if($check) {
		  $inisial = 'kategori='.$data;
		  $checks = $this->db->query("delete from _category_description where category_id = '$data'");
		  $checks = $this->db->query("delete from _category_path where category_id = '$data'");
		  $checks = $this->db->query("delete from _url_alias where inisial = '".$inisial."'");
		  if($checks) return true;
		  else return false;
		} else {
		  return false;
		}
	}
	function getWarnaKategoriByKategori($id){
		
		$sql = "SELECT cw.cat_id,cw.cat_warna,w.warna,cw.cat_foto 
			    FROM _category_warna cw 
				INNER JOIN _warna w ON cw.cat_warna = w.idwarna 
				WHERE cw.cat_id='".$id."'";
		
		$query = $this->db->query($sql);
		if($query){
			$data = array();
			foreach($query->rows as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
}
?>