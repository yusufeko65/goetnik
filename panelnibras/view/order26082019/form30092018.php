<div class="col-lg-12 main-content">
	<h2 class="judulmodul"><?php echo $judul ?></h2>
	<div class="widget-box">
		<div class="widget-title"><span class="icon"><i class="icon-file"></i></span><h5>FORM <?php echo strtoupper($judul) ?></h5></div>
		<div class="widget-content nopadding">
			<form method="POST" name="frmdata" id="frmdata" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="form-horizontal">
				<input type="hidden" name="aksi" id="aksi" value="<?php echo $modul ?>">
				<input type="hidden" name="iddata" id="iddata" value="<?php echo $iddata ?>">
				<input type="hidden" name="stsnow" id="stsnow" value="<?php echo $order['status_id'] ?>">
				<input type="hidden" name="stsshipping" id="stsshipping" value="<?php echo $dataset['config_shippingstatus'] ?>">
				<input type="hidden" name="stskonfirm" id="stskonfirm" value="<?php echo $dataset['config_konfirmstatus'] ?>">
				<input type="hidden" name="stssudahbayar" id="stssudahbayar" value="<?php echo $dataset['config_sudahbayarstatus'] ?>">
				<input type="hidden" name="stsgetpoin" id="stsgetpoin" value="<?php echo $dataset['config_getpoincust'] ?>">
				<input type="hidden" name="grpreseller" id="grpreseller" value="<?php echo $order['grup_member'] ?>">
				<input type="hidden" name="idkurir" id="idkurir" value="<?php echo $order['kurir'] ?>">
				<input type="hidden" name="idserviskurir" id="idserviskurir" value="<?php echo $order['servis_kurir'] ?>">
				<input type="hidden" name="tglkirimshipping" id="tglkirimshipping" value="<?php echo $order['tgl_kirim'] ?>">
				<input type="hidden" name="awbshipping" id="awbshipping" value="<?php echo $order['no_awb'] ?>">
				<input type="hidden" name="pelangganid" id="pelangganid" value="<?php echo $order['pelanggan_id'] ?>">
				<input type="hidden" name="tglorder" id="tglorder" value="<?php echo $order['pesanan_tgl'] ?>">
				<input type="hidden" name="nmreseller" id="nmreseller" value="<?php echo $order['cust_nama'] ?>">
				<input type="hidden" name="nmgrpreseller" id="nmgrpreseller" value="<?php echo $order['grup_cust'] ?>">
				<input type="hidden" name="nmstatus" id="nmstatus" value="<?php echo $order['status_nama'] ?>">
				
				<div id="hasil" style="display: none;"></div>
				<div class="col-md-12 text-right">
					<a href="<?php echo URL_PROGRAM_ADMIN . folder .'?modul=cetak&pid='.$iddata ?>" target="_blank" id="btncetak" class="btn btn-sm btn-default"><i class="icon-print" aria-hidden="true"></i> Cetak Nota</a>
					<a href="<?php echo URL_PROGRAM_ADMIN . folder .'?modul=cetaklabel&pid='.$iddata ?>" target="_blank" id="btncetaklabel" class="btn btn-sm btn-default"><i class="icon-print" aria-hidden="true"></i> Cetak Label</a>
				</div>
				<div class="clearfix"></div>
				<div class="well plat-order">
					<div class="col-md-6">
						<table class="table">
							<tr>
								<td width="20%"><b>No Order</b></td>
								<td>: <?php echo sprintf('%08s', (int)$order['pesanan_no']); ?></td>
							</tr>
							<tr>
								<td width="20%"><b>Tgl Order</b></td>
								<td>: <?php echo $dtFungsi->ftanggalFull1($order['pesanan_tgl']) ?></td>
							</tr>
						</table>
					</div>
					<div class="col-md-6">
						<table class="table">
							<tr>
								<td width="20%"><b>Pelanggan</b></td>
								<td>: <span class="label label-default"><?php echo $order['cust_nama'].' ('.$order['grup_cust'].') ' ?></span></td>
							</tr>
							<tr>
								<td width="20%"><b>Status</b></td>
								<td>: <?php echo $order['status_nama']; ?> <a href="javascript:void(0)" id="btnEditStatus" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-pencil"></span> Edit</a></td>
							</tr>

						</table>
					</div>
					<div class="clearfix"></div>
				</div> <!-- @well -->
				<?php 
					$md = 'col-md-12';
					$style = ' style="display:none"';
					$labelorder = '';
					if($order['dropship'] == '1') { 
						$md = 'col-md-6';
						$style = '';
						$labelorder = '<div class="col-md-12 text-right"><div class="label label-warning">Dropship</div></div><div class="clearfix"></div>';
					} 
				?>
				<?php echo $labelorder ?>
				<div class="<?php echo $md ?> deskripsi" <?php echo $style ?>>
					<h4 class="judulmodul">Alamat Pengirim</h4>
					<b><?php echo stripslashes(ucwords(strtolower($order['nama_pengirim'])))?></b><br>
					<?php echo stripslashes($order['alamat_pengirim'])?>
					<?php if($order['kelurahan_pengirim']) { ?>
					, Kel. <?php echo $order['kelurahan_pengirim']?> <br>
					<?php } else { ?>
					<br>
					<?php } ?>
					Kec. <?php echo $order['kecamatannm_pengirim']?>, <?php echo stripslashes($order['kotanm_pengirim'])?> <br>
					<?php echo stripslashes($order['propinsinm_pengirim'])?>,
					<?php echo $order['negaranm_pengirim']?> <?php echo $order['kodepos_pengirim']?><br>
					<?php if($order['hp_pengirim'] !='') echo 'Hp. '.$order['hp_pengirim'] ?>
				</div>
				
				<div class="<?php echo $md ?> deskripsi  text-right">
					<h4 class="judulmodul">Alamat Tujuan</h4>
					<b><?php echo stripslashes(ucwords(strtolower($order['nama_penerima'])))?></b><br>
					<?php echo $order['alamat_penerima']?>
					<?php if($order['kelurahan_penerima']) { ?>
					, Kel. <?php echo $order['kelurahan_penerima']?> <br>
					<?php } ?>
					Kec. <?php echo $order['kecamatannm_penerima']?>,<?php echo $order['kotanm_penerima']?><br> 
					<?php echo $order['propinsinm_penerima']?>,  
					<?php echo $order['negaranm_penerima']?> <?php echo $order['kodepos_penerima']?><br>
					<?php if($order['hp_penerima'] !='') echo 'Hp. '.$order['hp_penerima'] ?>
				</div>
				<div class="clearfix"></div>
				<div class="col-md-12" style="display:none">
					<div class="row">
						<div class="text-right">
							<button id="btnaddprod" name="btnaddprod" class="btn btn-sm btn-primary">Tambah Produk</button>
						</div>
						<div id="platcari" class="alert alert-info" style="display:none">
							<input type="text" id="cariproduk" name="cariproduk" placeholder="Cari Nama produk yang ingin di tambah" class="form-control" autocomplete="off">
						</div>
					</div>
				</div>
				<table class="table table-bordered table-striped tabel-grid">
					<thead>
						<tr>
						  <!--<td class="text-center" width="10%">Aksi</td>-->
							<td class="text-left">Produk</td>
							<td class="text-right">Jumlah</td>
							<td class="text-right">Berat</td>
							<td class="text-right">Harga</td>
							<td class="text-right" width="200px">Total</td>
						</tr>
					</thead>
					<tbody>
					<?php 
					$totberat = 0;
					$totpoin = 0;
					
					foreach($datadetail as $dt) {
						$totberat = $totberat + $dt['berat'];
						$dt['poin'] = isset($dt['poin']) && $dt['poin'] != null && $dt['poin'] != '' ? $dt['poin']:'0';
						$totpoin = $totpoin + ((int)$dt['poin'] * $dt['jml']);
						
						$datapoin = $dtFungsi->fcaridata('_produk','poin','idproduk',$dt['produk_id']);
						$datapoin = $datapoin == '' && $datapoin == null ? '0' : $datapoin;
					?> 
						<tr>
					 
							<td class="text-left">
								<b><?php echo $dt['nama_produk'] ?></b><br>
								<?php if($dt['ukuran'] != '') { ?>
									<div class="label label-default"><?php echo 'Ukuran : '.$dt['ukuran'];?></div>
								<?php } ?>
								<?php if($dt['warna'] != '') { ?>
									<div class="label label-default"><?php echo 'Warna  : '.$dt['warna'];?></div><br><br>
								<?php } ?>
								<?php if(in_array($order['status_id'],$dataset['config_editorder'])) {?>
								<div>
									<a class="btn btn-sm btn-danger" onclick="delProduk('<?php echo $iddata ?>','<?php echo $dt['iddetail'] ?>','<?php echo $dt['produk_id'] ?>','<?php echo $dt['nama_produk'] ?>','<?php echo $dt['warnaid'] ?>','<?php echo $dt['ukuranid'] ?>','<?php echo $dt['jml'] ?>','<?php echo $order['grup_member'] ?>','<?php echo $order['pelanggan_id'] ?>','<?php echo $dt['warna'] ?>','<?php echo $dt['ukuran'] ?>','<?php echo $order['pesanan_subtotal']?>','<?php echo $datapoin ?>')"><span class="glyphicon glyphicon-trash"></span></a>
									<a class="btn btn-sm btn-primary" onclick="editProduk('<?php echo $iddata ?>','<?php echo $dt['iddetail'] ?>','<?php echo $dt['produk_id'] ?>','<?php echo $dt['nama_produk'] ?>','<?php echo $dt['warnaid'] ?>','<?php echo $dt['ukuranid'] ?>','<?php echo $dt['jml'] ?>','<?php echo $order['grup_member'] ?>','<?php echo $order['pelanggan_id'] ?>','<?php echo $datapoin ?>')"><span class="glyphicon glyphicon-pencil"></span></a>
								</div>
							<?php } ?>
							</td>
							<td class="text-right"><?php echo $dt['jml'] ?></td>
							<td class="text-right"><?php echo $dt['berat'] ?> Gram</td>
							<td class="text-right"><?php echo $dtFungsi->fFormatuang($dt['harga']) ?><input type="hidden" name="iddetail[]" value="<?php echo $dt['iddetail'] ?>"></td>
							<td class="text-right"><?php echo $dtFungsi->fFormatuang(((int)$dt['jml']) * (int)$dt['harga']) ?></td>
						</tr>
			  <?php } ?>
						<tr>
							<td colspan="5" class="text-center"><b>TOTAL BERAT </b><?php echo $totberat .' Gram / '.($totberat/1000).' Kg'?> </td>
						</tr>
						<tr>
							<td colspan="4" class="text-right"><b>Subtotal</b></td>
							<td class="text-right"><?php echo $dtFungsi->fFormatuang($order['pesanan_subtotal']) ?></td>
						</tr>
						<tr>
							<td colspan="4" class="text-right">
							<?php if(in_array($order['status_id'],$dataset['config_editorder'])) {?>
							<a onclick="editKurir('<?php echo $order['negara_penerima'] ?>','<?php echo $order['propinsi_penerima'] ?>','<?php echo $order['kota_penerima'] ?>','<?php echo $order['kecamatan_penerima'] ?>','<?php echo $order['kodepos_penerima'] ?>','<?php echo $totberat ?>','<?php echo $iddata ?>','<?php echo $order['kurir'] ?>','<?php echo $order['servis_kurir'] ?>','<?php echo $order['kurir_konfirm'] ?>','<?php echo $order['pesanan_subtotal'] ?>');" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-pencil"></span> Edit</a>
							<?php } ?>
							<b><?php echo $order['kurir'].' - '.$order['servis_code'] ?></b>

							</td>
							<td class="text-right"> <?php echo $order['kurir_konfirm'] == '1' ? 'Konfirmasi Admin' : $dtFungsi->fFormatuang($order['pesanan_kurir']) ?>
							<input type="hidden" name="zhrgkurir" id="zhrgkurir" value="<?php echo $order['pesanan_kurir'] ?>">
							<input type="hidden" name="totpoin" id="totpoin" value="<?php echo $totpoin ?>">
							</td>
						</tr>
						<tr>
							<td colspan="4" class="text-right"><b>POTONGAN POIN</b></td>
							<td class="text-right">(<?php echo $dtFungsi->fFormatuang($order['dari_poin']) ?> )
							<input type="hidden" name="potpoinlama" id="potpoinlama" value="<?php echo $order['dari_poin'] ?>">
							</td>
						</tr>
				  
						<tr>
							<td colspan="4" class="text-right"><b>POTONGAN DEPOSITO</b></td>
							<td class="text-right">(<?php echo $dtFungsi->fFormatuang($order['dari_deposito']) ?> )
							<input type="hidden" name="potdepositolama" id="potdepositolama" value="<?php echo $order['dari_deposito'] ?>">
							</td>
						</tr>
				  
						<tr>
						
							<td colspan="4" class="text-right"><b>TOTAL</b></td>
							<td class="text-right">
							<?php $grandtotal = ((int)$order['pesanan_kurir'] + (int)$order['pesanan_subtotal'])-(int)$order['dari_poin']-(int)$order['dari_deposito']; ?>
							<input type="hidden" name="grandtotal" id="grandtotal" value="<?php echo $grandtotal ?>">
							<b><?php echo $dtFungsi->fFormatuang($grandtotal) ?></b></td>
						</tr>
					</tbody>
				</table>
         
				<div class="col-md-12">
					<a href="<?php echo URL_PROGRAM_ADMIN.'order' ?>" class="btn btn-primary btn-block">Kembali</a>
				</div>
				<div class="clearfix"></div>
			</form>
		</div>
	</div>
</div>

<script>
var action =$('#frmdata').prop("action");
var wil = [];
var tarifwil = [];
var nilaitarifwil = [];
$(function(){ 
   var nopesan = $('#iddata').val();
   
   var stsnow = $('#stsnow').val();
   var namaservis = '<?php echo $order['servis_code'].'::'.$order['kurir'] ?>';
   var stsshipping = $('#stsshipping').val();
   var stssudahbayar = $('#stssudahbayar').val();
   var stskonfirm = $('#stskonfirm').val();
   var tglshipping = $('#tglkirimshipping').val();
   var awbshipping = $('#awbshipping').val();
   var stsgetpoin = $('#stsgetpoin').val();
   var pelangganid = $('#pelangganid').val();
   var totpoin = $('#totpoin').val();
   var grandtotal = $('#grandtotal').val();
   $('#btnEditStatus').click(function(){
       var url = action+'?modul=frmEditStatus';
       var zdata = nopesan+'::'+stsnow+'::'+stsshipping+'::'+stskonfirm+'::'+namaservis+'::'+tglshipping+'::'+awbshipping+'::'+stsgetpoin+'::'+pelangganid+'::'+totpoin+'::'+stssudahbayar+'::'+grandtotal;
	   
	   $("#loadingweb").fadeIn();
       tampilform(url,zdata);
	   return false;
   }); 
   $("#btnaddprod").click(function(){
      $("#platcari").toggle();
	  return false;
   });
   
   $('#cariproduk').autocomplete({
		delay: 0,
		source: function( request, response ) {
		  $.ajax({
			url: action,
			dataType: "json",
			data: {
			   modul: 'cariproduk',
			   grupreseller: $('#grpreseller').val(),
			   cariproduk: request.term
			},
			success: function( data ) {
			  //alert(data);
			  response( $.map( data, function( item ) {
				return {
				  label: item.kode + ' :: ' + item.name,
				  value: item.name,
				  kode: item.kode,
				  nama: item.name,
				  id: item.product_id,
				  ukuran: item.ukuran,
				  warna: item.warna,
				  berat: item.berat,
				  harga: item.harga,
				  hrgsatuan: item.satuan,
				  stok: item.stok,
				  gbr: item.gbr
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
		  
	      $( "#dialog-form-addproduk" ).dialog( "open" );
		  $('#cariproduk').val("");
		  $("input[name='aidproduk']").attr('value', ui.item.id);
		  $("input[name='akdproduk']").attr('value', ui.item.kode);
		  $("input[name='anmproduk']").attr('value', ui.item.nama);
		  html = '';
		  html += '<tr><td>Berat</td><td><input type="text" value="'+ ui.item.berat +'" class="inputbox2" style="width:50px" readonly> Gram </td></tr>';
		  if($('#reseller').val() == '') {
			 $('#addcart').hide();
			 $('#keterangan').html('Masukkan ID Reseller Untuk Add to Cart');
			 html += '<tr><td>Eceran </td><td><input type="text" value="Rp. '+ numeral(ui.item.hrgsatuan).format("0,0") +'" class="inputbox2" readonly></td></tr>';
		  } else {
			 $('#addcart').show();
			 $('#keterangan').html('');
				
			 var jmldt = ui.item.harga.length;
			 var hit = 0;
			 var labelketerangan = '';
			 var label = '';
				
			 if( $('#grpreseller').val() != $('#reseller_bayar').val() || jmldt < 1) {
			     html += '<tr><td> Beli 1</td><td>Rp. ' + numeral(ui.item.hrgsatuan).format("0,0") + '</td></tr>';
			 }
				
			for (j = 0; j < jmldt; j++) {
			  if(j < jmldt-1) {
			    hit = ui.item.harga[j+1]['minimal'] - 1;
				labelketerangan = ' (' + ui.item.harga[j]['minimal'] + ' - ' + hit + ' Pcs)';
			  } else {
				labelketerangan = ' (' + ui.item.harga[j]['minimal'] + ' Pcs atau lebih)';
			  }
					
			  if (ui.item.harga[j]['minimal'] < 2) {
				 label = 'Harga ';
			  } else {
				 label = 'Beli ' + ui.item.harga[j]['minimal'];
			  }
			  html += '<tr><td>' + label + '</td><td>Rp. ' + numeral(ui.item.harga[j]['harga']).format("0,0") + labelketerangan + '</td></tr>';
			}
		  }
	   
		  if(ui.item.ukuran != '' || ui.item.warna != ''){
	      
			if(ui.item.ukuran != ''){
				html += '<tr><td>Ukuran </td>';
				html += '<td><select id="aukuran" name="aukuran" style="width:200px" class="selectbox" onchange="pilihwarna(this.value,\'awarna\',\'aidproduk\')" >';
					
				for (j = 0; j < ui.item.ukuran.length; j++) {
					html += '<option value="' + ui.item.ukuran[j]['id'] + '">' + ui.item.ukuran[j]['nm'] + '</option>';
				}
			 
				html += '</select>';
				html += '</td></tr>';
			}
		  
			if(ui.item.warna != ''){
				html += '<tr><td>Warna </td>';
				html += '<td><select id="awarna" name="awarna" style="width:200px" class="selectbox" onchange="pilihstok(this.value)">';
				html += '<option value="0">- Pilih Warna -</option>';
					
				for (j = 0; j < ui.item.warna.length; j++) {
					html += '<option value="' + ui.item.warna[j]['id'] + '">' + ui.item.warna[j]['nm'] + '</option>';
				}
					
				html += '</select>';
				html += '</td></tr>';
			}
		  }
	   
		  html += '<tr><td>Stok</td><td><input type="text" id="stok" value="'+ ui.item.stok +' Pcs" class="inputbox2" style="width:40px"></td></tr>';
	   
		  $('#optionadd').html(html);
		  return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	/* @end pencarian produk */
});
function editProduk(nopesan,iddetail,produkid,produknm,warna,ukuran,qty,idgrup,idmember,poin) {
  var url = action + '?modul=frmEditProduk';
  
  var zdata = nopesan+'::'+iddetail+'::'+produkid+'::'+produknm+'::'+warna+'::'+ukuran+'::'+qty+'::'+idgrup+'::'+idmember+"::"+poin;
  $("#loadingweb").fadeIn();
  tampilform(url,zdata);
  return false;
}
function delProduk(nopesan,iddetail,produkid,produknm,warna,ukuran,qty,idgrup,idmember,nmwarna,nmukuran,subtotal,poin) {
   var url = action + '?modul=frmDelProduk';
   var zdata = nopesan+'::'+iddetail+'::'+produkid+'::'+produknm+'::'+warna+'::'+ukuran+'::'+qty+'::'+idgrup+'::'+idmember+'::'+nmwarna+'::'+nmukuran+'::'+subtotal+'::'+poin;
   $("#loadingweb").fadeIn();
   tampilform(url,zdata);
   return false;
}
function editKurir(negara,prop,kota,kec,kdpos,totberat,nopesan,idkurir,idservis,konfirmadmin,subtotal){
   var url = action + '?modul=frmEditKurir';
   var zdata = negara+"::"+prop+"::"+kota+"::"+kec+"::"+kdpos+"::"+totberat+"::"+nopesan+"::"+idkurir+"::"+idservis+'::'+konfirmadmin+'::'+subtotal;
   $("#loadingweb").fadeIn();
   tampilform(url,zdata);
   return false;
}
function tampilform(url,zdata){
  $.post(url,  { stsload: "load",data:zdata } ,function(data) {
    //alert(data);
    $("#loadingweb").fadeOut();
    $('<div class="modal fade" id="modalfrm" tabindex="-1" role="dialog" aria-labelledby="formedit" aria-hidden="true">' + data + '</div>').modal().on("hidden.bs.modal", function () {
	   $(this).remove();
	});
  });
}
function simpanstatus(){
	var orderstatus = $('#orderstatus').val();
	var keterangan  = $('#keterangan').val();
	
	var aksi        = $('#aksi').val();
	var nopesanan     = $('#nopesanan').val();
	var urlfolder   = $('#urlfolder').val();
	var stsnow      = $('#stsnow').val();
	var frm = $('#frmeditstatus').serialize();
	$('#btnsimpanstatus').button("loading");
	frm = frm+'&tglorder='+$("#tglorder").val()+"&nmreseller="+$("#nmreseller").val()+"&nmgrpreseller="+$("#nmgrpreseller").val()+"&nmstatus="+$("#nmstatus").val()+"&alamatreport="+$("#alamatreport").val();
	//alert(action + ' :: '+ frm);

	$.post(action,  frm ,function(xdata) {
     
		if(xdata['status'] == 'success'){
			location = urlfolder;
		} else {
			$('#hasileditstatus').removeClass();
			$('#hasileditstatus').addClass('alert alert-danger');
			if(xdata['result'].length > 0) {
			   $('#hasileditstatus').show();   
			   $('#hasileditstatus').html(xdata['result']);
			}
		}
  
	},'json');
  
}

function hapusprodukorder(){
   var datanya = "didproduk="+$('#didproduk').val()+'&didmember='+$('#didmember').val();
       datanya += '&dukuran='+$('#dukuran').val()+'&dwarna='+$('#dwarna').val()+'&didgrup='+$('#didgrup').val();
       datanya += "&dnopesan="+$('#dnopesan').val()+'&dqtylama='+$('#dqtylama').val();
	   datanya += "&duklama="+$('#duklama').val()+'&dwnlama='+$('#dwnlama').val();
	   datanya += "&diddetail="+$('#diddetail').val()+"&zhrgkurir="+$("#zhrgkurir").val()+"&potpoinlama="+$("#potpoinlama").val()+"&potdepositolama="+$("#potdepositolama").val();
	   datanya += "&dtotlama="+$('#dtotlama').val();
   var redirectview = $("#redirectview").val();
   var redirectedit = $("#redirectedit").val();
   var redirect = '';
   $('#loadingweb').show(500);
     
   $.ajax({
		type: "POST",
		url: action+"/?modul=hapusorderproduk",
		data: datanya,
		cache: false,
		success: function(msg){
			//alert(msg);
			$('#loadingweb').hide(500);
			hasilnya = msg.split("|");
			if(hasilnya[0]=="gagal") {
			   $('#hasildelprod').addClass("alert alert-danger");
			   $('#hasildelprod').html(hasilnya[1]);
			   $('#hasildelprod').show(0);
			
			} else {
		       if($.trim(hasilnya[1])=='ya') {
			      redirect = redirectedit;
			   } else {
			      redirect = redirectview;
			   }
			   location = redirect;
			   
			}
			return false;
		},  
			error: function(e){  
			alert('Error: ' + e);  
		}  
	  }); 
	
}
function simpaneditproduk() {
   $('#loadingweb').show(500);
   var datanya = "eidproduk="+$('#eidproduk').val()+'&eidmember='+$('#eidmember').val();
       datanya += '&eqty='+$('#eqty').val()+'&eukuran='+$('#eukuran').val()+'&ewarna='+$('#ewarna').val()+'&eidgrup='+$('#eidgrup').val();
       datanya += "&enopesan="+$('#enopesan').val()+'&eqtylama='+$('#eqtylama').val();
	   datanya += "&euklama="+$('#euklama').val()+'&ewnlama='+$('#ewnlama').val();
	   datanya += "&eiddetail="+$('#eiddetail').val()+"&zhrgkurir="+$("#zhrgkurir").val();
	   datanya += "&idkurir="+$('#idkurir').val()+"&idserviskurir="+$('#idserviskurir').val()+"&potpoinlama="+$("#potpoinlama").val()+"&potdepositolama="+$("#potdepositolama").val();
	   //alert(datanya);
   var redirect = $("#redirectedit").val();
   if($('#eukuran').val() == '0'){
       $('#hasilproduk').addClass("warning");
	   $('#hasilproduk').html('Pilih Ukuran');
	   $('#hasilproduk').show(0);
	   return false;
   }
   
   if($('#ewarna').val() == '0'){
       $('#hasilproduk').addClass("warning");
	   $('#hasilproduk').html('Pilih Warna');
	   $('#hasilproduk').show(0);
	   return false;
   }
   $('#btnsimpanprod').button('loading');
    $.ajax({
		type: "POST",
		url: action+"/?modul=editorderproduk",
		data: datanya,
		cache: false,
		success: function(msg){
		   //alert(msg);
			$('#btnsimpanprod').button('reset');
			$('#loadingweb').hide(500);
			hasilnya = msg.split("|");
			if($.trim(hasilnya[0])=="gagal") {
			   $('#hasiladdprod').addClass("alert alert-danger");
			   $('#hasiladdprod').html(hasilnya[1]);
			   $('#hasiladdprod').show(0);
			  
			} else {
			   $('#hasiladdprod').addClass("alert alert-success");
			   $('#hasiladdprod').html(hasilnya[1]);
			   $('#hasiladdprod').show(0);
			   location = redirect;
			}
			return false;
		},  
			error: function(e){  
			alert('Error: ' + e);  
		}  
	});  
}
function pilihwarna(ukuran,warna,idproduk){
   $('#'+warna).hide();
   $('#loadingweb').show(500);
   var dataload = '&ukuran='+ukuran+'&idproduk='+$('#'+idproduk).val()+'&idtxtwarna='+warna;
   
   $('#'+warna).load(action+'/?modul=cariwarna' + dataload,function(responseTxt,statusTxt,xhr)
	{
	    
		  if(statusTxt=="success") {
		   
		    $('#'+warna).show();
			$('.loading').remove();
			$('#loadingweb').hide(500);
			
		  } 
	});
     
}
function cetakInvoice(){
	var frm = $('#frmdata').serialize();
	$('#btncetak').button("loading");
  

	$.post(action+'?modul=cetak',  frm ,function(xdata) {
		data = xdata.split("|");
		if($.trim(data[0]) == 'sukses'){
			location = urlfolder;
		} else {
			$('#hasileditstatus').removeClass();
			$('#hasileditstatus').addClass('alert alert-danger');
			if(data[1].length > 0) {
			   $('#hasileditstatus').show();   
			   $('#hasileditstatus').html(data[1]);
			}

		}

	});
}

function simpaneditkurir() {
    $('#btnsimpankurir').button("loading");
	var tarif = $('#tarifkurir').val();
	var redirect = $('#redirectedit').val();
	var frm = $('#frmeditkurir').serialize();
	var url = $('#frmdata').prop("action")+'?modul=simpaneditkurir';
	
	$('#hasileditkurir').removeClass();
	$('#hasileditkurir').hide();
	
	
	if(tarif == ''){
		$('#hasileditkurir').addClass("alert alert-danger");
		$('#hasileditkurir').html('Masukkan Tarif Kurir');
		$('#hasileditkurir').show();
		$('#btnsimpankurir').button("reset");
		return false;
	}
	
	$.ajax({
		type: "POST",
		url: url,
		data:frm,
		dataType: 'json',
		success: function(json){
	
			if(json['status']=="error") {
				$('#hasileditkurir').addClass("alert alert-danger");
				$('#btnsimpankurir').button("reset");
			} else {
				$('#hasileditkurir').addClass("alert alert-success");
				location.href = redirect;
			}
			$('#hasileditkurir').html(json['result']);
			$('#hasileditkurir').show(0);
			return false;
		},  
			error: function(e){  
			alert('Error: ' + e);  
		}  
    }); 
	
}

function cektarifkurir(){
	var frm = $('#frmeditkurir').serialize();
	var url = $('#frmdata').prop("action")+'?modul=tarifkurir';
	var servis = $('#serviskurir').val();
	
	var tarif;
	var nilaitarif;
	var datawil = $('#kecamatan_penerima').val()+','+servis;
	var cekwil = wil.indexOf(datawil);
	var totaltarif;
	
	if(cekwil < 0) {
		
		wil.push(datawil);
		$.ajax({
			type: "POST",
			url: url,
			data: frm,
			dataType: 'json',
			success: function(json){
				
				if(json['status'] == 'error') {
					alert(json['result']);
				} else {
					
					tarifwil[datawil] = json['tarif'];
					nilaitarifwil[datawil] = json['nilaitarif'];
					
					tarif = json['tarif'];
					
					$('#tarifkurir').val(json['nilaitarif']);
					if(tarif == 'Konfirmasi Admin'){
						$('#tarifkurir').attr("readonly",false);
						$('#tarifkurir').focus();
					} else {
						$('#tarifkurir').attr("readonly",true);
					}
					
				}
			},  
			error: function(e){  
				alert('Error: ' + e);  
			} 
		});
		
	} else {
		
		tarif = tarifwil[datawil];
		nilaitarif = nilaitarifwil[datawil];
		
		/* $('#tarif').html(tarif); */
		$('#tarifkurir').val(nilaitarif);
		if(tarif == 'Konfirmasi Admin'){
			$('#tarifkurir').attr("readonly",false);
			$('#tarifkurir').focus();
		} else {
			$('#tarifkurir').attr("readonly",true);
		}
		
	}

}	
</script>