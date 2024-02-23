<div id="hasil"></div>
<div class="col-lg-12 main-content">
   <h2 class="judulmodul"><?php echo $judul ?></h2>
   <div class="row">
	  <div class="col-md-8 bagian-frm-cari ">
		<div class="row">
		  <form role="form-inline" id="frmcari" name="frmcari">
		     <div class="col-md-4">
			    <?php echo $dtFungsi->cetakcombobox2('- Status -','',$status,'fstatus','_status_order','status_id','status_nama','input-sm form-control') ?>
			 </div>
		   	 <div class="col-md-6">
				<div class="input-group">
			      <input type="text" class="form-control input-sm" id="datacari" name="datacari" value="<?php echo isset($_GET['datacari']) ? $_GET['datacari']:'' ?>" placeholder="Pencarian <?php echo $judul ?> ">
				  <span class="input-group-btn">
			 		 <button class="btn btn-hijau btn-sm" type="button" id="tblcari"><span class="glyphicon glyphicon-search"></span></button>
				  </span>
				</div>	 
			  </div>
		   </form>
		 </div>
	   </div>
	   <div class="col-md-4 bagian-tombol"><a href="<?php echo URL_PROGRAM_ADMIN.folder."/?op=add"?>" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit"></span> Tambah</a> <a class="btn btn-warning btn-sm" onclick="hapusdata()"><span class="glyphicon glyphicon-trash"></span> Cancel Order</a></div>
    </div>
	
	<table class="table table-bordered table-striped table-hover tabel-grid">
	  <thead>
		 <tr>
		   <td style="min-width:3%" class="tengah"><input type="checkbox" id="checkall" onchange="cekall()" name="checkall" value="ON"></td>
		   <td>Order ID</td>
		   <td>Customer</td>
		   <td>Penerima</td>
		   <td class="text-right">Jumlah</td>
		   <td class="text-right">Total</td>
		   <td class="text-center">Tgl</td>
		   <td>Status</td>
		   <td style="min-width:5%" class="tengah">Ubah</td>
		 </tr>
	  </thead>
	  <tbody id="viewdata">
		
		<?php foreach($ambildata as $datanya) {?>
		<?php
				$kelasgrid = '';
				if($datanya['status_id'] == $config_orderstatus) {
					$kelasgrid = 'class="kuning"';
					
				} elseif ($datanya['status_id'] == $config_konfirmstatus) {
					$kelasgrid = 'class="pink"';
					
				} elseif ($datanya['status_id'] == $config_sudahbayarstatus) {
					$kelasgrid = 'class="hijau"';
				} elseif ($datanya['status_id'] == $config_ordercancel) {
					$kelasgrid = 'class="merah"';
					
				} elseif ($datanya['status_id'] == $config_shippingstatus) {
					$kelasgrid = 'class="ungu"';
					
				}
		?>
		<?php if($datanya["pesanan_kurir"] < 0) $datanya["pesanan_kurir"] = 0 ?>
		<tr <?php echo $kelasgrid ?>>
		   <td class="tengah"><input type="checkbox" class="chk" value="<?php echo $datanya['pesanan_no']?>" /></td>
		   <td><?php echo sprintf('%08s', (int)$datanya["pesanan_no"]);?></td>
		   <td><?php echo $datanya["cust_nama"]?></td>
		   <td><?php echo $datanya["nama_penerima"]?></td>
		   <td class="text-right"><?php echo $datanya["jml"]?></td>
		   <td class="text-right"><?php echo $dtFungsi->fFormatuang($datanya["pesanan_kurir"] + $datanya["subtotal"] - $datanya['dari_poin'])?></td>
		   <td class="text-center"><?php echo $dtFungsi->ftanggalFull1($datanya["tgl"])?></td>
		   <td><?php echo $datanya["status"]?></td>
		   <td class="tengah"><a href="<?php echo URL_PROGRAM_ADMIN.folder."/?op=info&pid=".$datanya['pesanan_no']?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></a></td>
		</tr>
		<?php } ?>
	   </tbody>
	 </table>
	 <?php if($total>0) { ?>
	  <!-- Pagging -->
	    <div class="col-md-6">
		  <div class="row">Showing <?php echo $page ?> of <?php echo $jmlpage ?> Page, <?php echo $total ?> data</div>
		</div>
        <div class="col-md-6 text-right">
		   <ul class="pagination pagination-sm"><?php echo $dtPaging->GetPaging($total,$baris,$page,$jmlpage,$linkpage) ?></ul>
		</div>
			  
		<!-- End Pagging -->
		<?php } ?>
  </div>

<script>
$(function(){
   $("#datacari").focus();
   
   $('#fstatus').change(function(){
        caridata();
		return false;
   });
   $('#tblcari').click(function(){
        caridata();
		return false;
   });
   $("#datacari").keypress(function(event) {
        if(event.which == 13) {
 		   caridata();
	      return false;
		} else {
		   return true;
		}
   });
   
  
	
});
function caridata(){
   var zdata 	= escape($('#datacari').val());
   var status 	= escape($('#fstatus').val());
   location = '<?php echo URL_PROGRAM_ADMIN.folder.'/?datacari=' ?>'+zdata+'&status='+status;

}
function hapusdata(){
	var ids = [];
	var dataId;
	var datahapus;
	$('.chk').each(function () {
		if (this.checked) {
			if($(this).val()!="ON"){
				ids.push($(this).val());
			}
		}
	});
	dataId = ids.join(':');
	datahapus = 'aksi=hapus&id=' + dataId;
	if(dataId==""){
	   alert('Tidak Ada Pilihan');
	   return false;
	} else {
	   var a = confirm('Apakah ingin menghapus data yang terpilih?');
	   if (a == true) {
			$.ajax({
				type: "POST",
				url: "<?php echo $_SERVER['PHP_SELF'] ?>",
				data: datahapus,
				dataType: 'json',
				success: function(msg){
					
					if(msg['status']=="error") alert('Error \n' + msg['result']); 
					location = '<?php echo URL_PROGRAM_ADMIN.folder.'/' ?>';
					return false;
				},  
					error: function(e){  
					alert('Error: ' + e);  
				}  
			});  
		}
	}
}
</script>