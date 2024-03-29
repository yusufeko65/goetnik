<div id="hasil"></div>
<div class="col-lg-12 main-content">
   <h2 class="judulmodul"><?php echo $judul ?></h2>
   <div class="row">
		<div class="col-md-4" style="background-color: #79d2ff;padding:5px 12px;border:1px solid #ccc;border-radius:5px;margin-left: 14px;margin-bottom: 10px;">
			<form role="form-inline" method="POST" id="frmimport" name="frmimport" action="<?php echo $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
				<div class="col-md-9">
					<input type="hidden" name="aksi" value="import">
					<input type="file" id="myFile" name="filename">
				</div>
				<div class="col-md-3">
					<input type="submit" value="import">
				</div>
			</form>
	   </div>
	   <div class="col-md-7">
		<div>&nbsp;</div>
		</div>
	  <div class="col-md-8 bagian-frm-cari ">
		<div class="row">
		   <form role="form-inline" id="frmcari" name="frmcari" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		   		<div class="col-md-2">
					<select id="statusP" name="statusP" class="form-control input-sm">
						<option value="0" <?php echo $statusP==0?'selected':'' ;?>>Semua</option>
						<option value="1" <?php echo $statusP==1?'selected':'' ;?>>Disabled</option>
						<option value="2" <?php echo $statusP==2?'selected':'' ;?>>Enabled</option>
					</select>
				</div>
				<div class="col-md-4">
					<input type="text" placeholder="Filter Kategori" id="knama" name="knama" class="form-control input-sm" autocomplete="off" value="<?php echo $katname ?>">
					<input type="hidden" id="k" name="k" value="<?php echo $kat ?>">
				</div>
				<div class="col-md-6">

					<div class="input-group">
					  <input type="text" class="form-control input-sm" id="datacari" name="datacari" value="<?php echo isset($_GET['datacari']) ? $_GET['datacari']:'' ?>" placeholder="Pencarian <?php echo $judul ?> ">
					  <span class="input-group-btn">
						 <button class="btn btn-default btn-sm" type="button" id="tblcari"><span class="glyphicon glyphicon-search"></span></button>
						 <button class="btn btn-default btn-sm" type="button" id="tblrefresh" onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>'">Refresh</button>
					  </span>
					</div>	 
				</div>
		   </form>
		 </div>
	   </div>
	   	<div class="col-md-4 bagian-tombol">
			<a href="<?php echo URL_PROGRAM_ADMIN.folder."/?op=add"?>" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit"></span> Tambah</a> 
			<a class="btn btn-warning btn-sm" onclick="hapusdata()"><span class="glyphicon glyphicon-trash"></span> Hapus</a>
			<a class="btn btn-sm btn-default" id="tblexport" target="_blank" href="<?php echo URL_PROGRAM_ADMIN."view/".folder?>/exportexcel.php">Export</a>
		</div>
    </div>
	
	<table class="table table-bordered table-striped table-hover tabel-grid">
	  <thead>
		 <tr>
		   <td style="min-width:3%" class="tengah"><input type="checkbox" id="checkall" onchange="cekall()" name="checkall" value="ON"></td>
		   <td>Kode</td>
		   <td>Nama Produk</td>
		   <td>Harga Eceran</td>
		   <td>Sisa Stok</td>
		   <td>Gambar</td>
		   <td>Status</td>
		   <td style="min-width:5%" class="tengah">Ubah</td>
		 </tr>
	  </thead>
	  <tbody id="viewdata">
		<?php foreach($ambildata as $datanya) {?>
		<tr>
		   <td class="tengah"><input type="checkbox" class="chk" value="<?php echo $datanya['idproduk']?>" /></td>
		   <td><?php echo $datanya["kode_produk"]?></td>
		   <td><?php echo $datanya["nama_produk"]?></td>
		   <td align="right"><?php echo $dtFungsi->fFormatuang($datanya["hrg_jual"])?></td>
		   <td align="right"><?php echo $datanya["jml_stok"]?></td>
		   <td><img src="<?php echo URL_PROGRAM.'assets/image/_small/small_gproduk'.$datanya["gbr_produk"]?>"></td>
		   <td align="center"><?php echo $datanya["status_produk"]==1?'Enabled':'Disabled';?></td>
		   <td class="tengah"><a href="<?php echo URL_PROGRAM_ADMIN.folder."/?op=edit&pid=".$datanya['idproduk']?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-pencil"></span></a></td>
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
var action = $('#frmcari').prop('action');
$(function(){
	$("#datacari").focus();
	$('#tblreset').click(function(){
		tampilkan('<?php echo URL_PROGRAM_ADMIN.folder.'/' ?>');
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
	/* autocomplete */
	$('#knama').autocomplete({
		delay: 0,
		source: function( request, response ) {
		  $.ajax({
			url: action,
			dataType: "json",
			data: {
			   loads: 'kategori',
			   cari: request.term
			},
			success: function( data ) {
			  response( $.map( data, function( item ) {
				return {
				  label: item.name,
				  value: item.name,
				  kode: item.category_id,
				 
				}
			   }));
			},
			error: function(e){  
			  alert('Error: ' + e);  
			}  
		   });
		},
		minLength: 1,
		select: function( event, ui ) {
		  $('#knama').val(ui.item.value);
		  $('#k').val(ui.item.kode);
		  
		  return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
   
	/* @end autocomplete */
});
function caridata(){
   var zdata 	= escape($('#datacari').val());
   var kat	= escape($('#k').val());
   var sts	= escape($('#statusP').val());
   location='<?php echo URL_PROGRAM_ADMIN.folder.'/?datacari=' ?>'+zdata+'&k='+kat+'&sts='+sts;

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
				success: function(msg){
					//alert(msg);
					hasilnya = msg.split("|");
					
					$('#hasil').show(0);
					$('#hasil').focus();
					if(hasilnya[0]=="gagal") {
						//$('#hasil').html(hasilnya[1]);; 
						alert(hasilnya[1]);
					} else {
						
						alert('Berhasil Menghapus Produk');
						location.reload();
					}
				
					
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