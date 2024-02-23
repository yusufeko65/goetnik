<div class="col-lg-12 main-content">
     <h2 class="judulmodul"><?php echo $judul ?></h2>
     <div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
		  <div class="widget-content nopadding">
	        <form class="form-horizontal" method="POST" name="frmdata" id="frmdata" onKeyPress="return disableEnterKey(event)" action="<?php echo $_SERVER['PHP_SELF'] ?>">
		      <input type="hidden" id="aksi" name="aksi" value="<?php echo $modul ?>">
		      <input type="hidden" id="iddata" name="iddata" value="<?php echo $iddata ?>">
		      <div id="hasil" style="display: none;"></div>
			  <div class="well well-sm">
			     <div class="col-md-6">
				   <table class="table">
				      <tr>
					     <td><b>Kode</b></td>
						 <td> : <?php echo sprintf('%04s', $reseller["cust_kode"]);?></td>
					  </tr>
					  
				      <tr>
					     <td><b>Nama</b></td>
						 <td> : <?php echo $reseller['cust_nama'] ?></td>
					  </tr>
					  <tr>
					    <td colspan="2"></td>
					  </tr>
				   </table>
				 </div>
				 <div class="col-md-6"> 
				    <table class="table">
				      <tr>
					     <td><b>Email</b></td>
						 <td> : <?php echo $reseller["cust_email"] ?></td>
					  </tr>
					  
				      <tr>
					     <td><b>No. Telp</b></td>
						 <td> : <?php echo $reseller['cust_telp'] ?></td>
					  </tr>
					  <tr>
					    <td colspan="2"></td>
					  </tr>
				   </table>
				 </div>
				 <div class="clearfix"></div>
			  </div>
			  <div class="well">
			     <div class="form-group">
			        <label class="col-sm-2 control-label">Jumlah Deposito</label>
				      <div class="col-sm-4">
					    <div class="row">
				         <input type="text" id="indeposito" name="indeposito" class="form-control" placeholder="Jumlah Deposito">
						</div>
				      </div>
		         </div>
			  </div>
			  
		      <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
		          <a onclick="simpandata()" id="btnsimpan" class="btn btn-sm btn-primary">Simpan</a>
		          <a onclick="location='<?php echo URL_PROGRAM_ADMIN.folder ?>'" class="btn btn-sm btn-warning">Kembali</a>
		        </div>
			  </div>
			  <div class="clearfix"></div>
			  <div class="well well-sm">
			   <label>
			   IN : Transfer Deposito <br>
			   OUT : Menggunakan Deposito Saat Pelanggan Membeli Produk
			   </label>
			  </div>
			  <table class="table table-bordered table-striped table-hover tabel-grid">
			    <thead>
				  <tr>
				    <td style="min-width:3%" class="tengah"></td>
					<td>Deposito</td>
					<td>Tipe</td>
					<td>Tgl</td>
					<td>Keterangan</td>
				  </tr>
				</thead>
				<tbody>
				   <?php foreach($datadeposit as $dp) { ?>
				   <tr>
				      <td></td>
					  <td><?php echo $dtFungsi->fFormatuang($dp['cdh_deposito']) ?></td>
					  <td><?php echo $dp['cdh_tipe'] ?></td>
					  <td><?php echo $dp['cdh_tgl'] ?></td>
					  <td><?php echo $dp['cdh_keterangan'] ?></td>
				   </tr>
				   <?php } ?>
				</tbody>
			  </table>
	        </form>
		  </div>
	    </div> 
	 </div>
  </div>

<script>
$(function(){
   $('#remail').focus();
	
   $('#rnegara').change(function() {
      //alert(this.value);
	  $('#rpropinsi').load('<?php echo URL_PROGRAM_ADMIN.folder;?>/?load=propinsi&negara=' + this.value);
	  return false;
   });
   $('#rpropinsi').change(function() {
	  $('#rkabupaten').load('<?php echo URL_PROGRAM_ADMIN.folder;?>/?load=kabupaten&propinsi=' + this.value);
	  $('#rkecamatan').html('<option value="0">- Kecamatan -</option>');
	  return false;
	});
	$('#rkabupaten').change(function() {
    	$('#rkecamatan').load('<?php echo URL_PROGRAM_ADMIN.folder;?>/?load=kecamatan&kabupaten=' + this.value);
		return false;
	});
	$("#depositoku").hide();
	if($("#aksi").val()!="ubah") {
  	  $('#rtipecust').change(function() {
	     var deposit =$(this).find('option:selected').attr('rel');
		 if(deposit == '1') {
		    $("#depositoku").show();
		 } else {
		    $("#depositoku").hide();
		 }
	  });
	}
});

var action = $('#frmdata').attr('action');

function kosongform(){
  $('#remail').focus();
  $('.form-control').each(function () {
    $(this).val("");
  });
}
function disableEnterKey(e){ //Disable Tekan Enter
    var key;
     if(window.event)
          key = window.event.keyCode;     //IE
     else
          key = e.which;     //firefox

     if(key == 13){ // Jika ditekan tombol enter
		  simpandata(); // Panggil fungsi simpandata()
          return false;
     } else {
          return true;
	 }
}

function simpandata(){ //Proses Simpan
	$('#loadingweb').show(500);
	$('#btnsimpan').button('loading');
	$.ajax({
 		type: "POST",
   		url: action,
    	data: $('#frmdata').serialize(),
 		cache: false,
    	success: function(msg){
		  
		    $('#btnsimpan').button('reset');
		    $('#loadingweb').fadeOut(500);
			hasilnya = msg.split("|");
			$('#hasil').show(0);
			if(hasilnya[0]=="sukses") {
			   if(hasilnya[1]=="input") kosongform();
			   hasilnya[2] = '<div class="alert alert-success">'+hasilnya[2]+'</div>';
			} else {
			   hasilnya[2] = '<div class="alert alert-danger">'+hasilnya[2]+'</div>';
			}
			$('#hasil').html(hasilnya[2]);
			return false;
      	}  
  	});  
	$('html, body').animate({ scrollTop: 0 }, 'slow');
}
</script>

