<div class="kotakplat" >
	<?php echo $dtFungsi->judulModul($judul.' Penjualan',"form") ?> 
	<div class="body">
	  <form method=POST name=frmdata id=frmdata action="<?php echo $_SERVER['PHP_SELF'] ?>">
		<!--<input type="hidden" name="aksi" id="aksi" value="<?php echo $modul ?>">-->
	          <input type="hidden" name="reseller_bayar" id="reseller_bayar" value="<?php echo $reseller_bayar ?>">
			  <input type="hidden" name="user_idlogin" id="user_idlogin" value="<?php echo $_SESSION["idlogin"] ?>">
			  <table class="form">
			     <tr>
				     <td class="bariskanan">
					   <b> Diinput oleh </b><input type="text" class="inputbox2" value="<?php echo $_SESSION["userlogin"] ?>" readonly>
					 </td>
				 </tr>
			  </table>
			  <div id="tabs_container">
					<ul id="tabs">
						<li class="active" id="lireseller"></li>
						
						<li id="liproduk"></li>
						<li id="lipengiriman"></li>
						<li id="likurir"></li>
						<li id="litotal"></li>
					</ul>
			  </div>
			  <div id="tabs_content_container">
			  <div id="areacust" class="tab_content" style="display:block">
			    <table class="forms">
				   <tr>
				       <td width="35%">
					          
					          <fieldset>
								<legend>Data Reseller</legend>
								<p style="text-align:center">
								<input type="radio" name="jinputan" value="cari" checked>Pencarian 
							    <input type="radio" name="jinputan" value="input">Pelanggan Baru </p>
								<table class="form" id="trform" style="display:none;">
								  <tr>
									  <td>Grup</td>
									  <td><b><?php echo $nmgrupresellers ?></b><input class="inputbox2 dr" type="hidden" id="injenis" name="injenis" value="<?php echo $idgrupresellers ?>"></td>
								  </tr>
								  <tr>
								     <td width="30%">Nama</td>
									 <td width="70%"><input type="text" class="inputbox2" id="innamareseller" name="innamareseller"> <span class="required">*</span><td>
								  </tr>
								  <tr>
										<td>No. Telp</td>
										<td><input type="text" class="inputbox2 dr" id="innotelp" name="innotelp">
											
										</td>
									</tr>
									<tr>
										<td>Handphone</td>
										<td><input type="text" class="inputbox2 dr" id="innohp" name="innohp"> <span class="required">*</span></td>
									</tr>
									<tr>
									   <td>Negara</td>
									   <td><?php echo $dtFungsi->cetakcombobox('- Negara -','230','','innegara','_negara','negara_id','negara_nama') ?> <span class="required">*</span></td>
									</tr>
				
								    <tr>
									   <td>Propinsi</td>
									   <td><select id="inpropinsi" name="inpropinsi" class="selectbox" style="width:230px"><option value="">- Propinsi -</option></select> <span class="required">*</span></td>
									</tr>
				
									<tr>
								       <td>Kota/Kabupaten</td>
									   <td><select id="inkabupaten" name="inkabupaten" class="selectbox" style="width:230px"><option value="">- Kota/Kabupaten -</option></select> <span class="required">*</span></td>
									</tr>
				
									<tr>
									   <td>Kecamatan</td>
									   <td><select id="inkecamatan" name="inkecamatan" class="selectbox" style="width:230px"><option value="">- Kecamatan -</option></select> <span class="required">*</span></td>
									</tr>
									<tr>
										<td>Kelurahan/Desa</td>
										<td><input type="text" class="inputbox2 dr" id="inkelurahan" name="inkelurahan"></td>
									</tr>
									<tr>
										<td>Kodepos</td>
										<td><input type="text" class="inputbox2 dr" id="inkodepos" name="inkodepos"></td>
									</tr>
									<tr>
										<td>Alamat</td>
										<td><textarea id="inalamat" name="inalamat" class="textareabox2 dr" style="width:230px"></textarea> <span class="required">*</span></td>
									</tr>
									<tr>
									    <td></td>
										<td><a id="tblsimpancust" name="tblsimpancust" class="tombols">Simpan Pelanggan</a></td>
									</tr>
								</table>
								<table class="form" id="trcari">
								
									<tr>
										<td width="30%">Reseller</td>
										<td width="70%"><input type="text" id="cidreseller" name="cidreseller" class="inputbox3"></td>
									</tr>
									<tr>
										<td>Kode</td>
										<td><input type="text" class="inputbox2 dr" readonly id="kdreseller" name="kdreseller"></td>
									</tr>
									<tr>
										<td >Nama</td>
										<td >
											<input type="hidden" id="idreseller" name="idreseller" class="inputbox2 dr">
											<input type="text" class="inputbox2 dr"  readonly id="namareseller" name="namareseller">
										</td>
									</tr>
									<tr>
										<td>Grup</td>
										<td><input type="text" class="inputbox2 dr" readonly id="jreseller"><input class="inputbox2 dr" type="hidden" id="jenis" name="jenis"></td>
									</tr>
									<tr>
										<td>No. Telp</td>
										<td><input type="text" class="inputbox2 dr" id="notelp" name="notelp" readonly>
											<input type="hidden" id="email" name="email" class="inputbox2 dr">
										</td>
									</tr>
									<tr>
										<td>Handphone</td>
										<td><input type="text" class="inputbox2 dr" id="nohp" name="nohp" readonly></td>
									</tr>
									<tr class="detail">
										<td>Negara</td>
										<td><input type="text" class="inputbox2 dr" id="negara" name="negara" readonly>
											<input type="hidden" id="idnegara" name="idnegara" class="inputbox2 dr">
										</td>
									</tr>
									<tr class="detail">
										<td>Propinsi</td>
										<td><input type="text" class="inputbox2 dr" id="propinsi" name="propinsi" readonly>
											<input type="hidden" id="idpropinsi" name="idpropinsi" class="inputbox2 dr">
										</td>
									</tr>
									<tr class="detail">
										<td>Kota/Kabupaten</td>
										<td><input type="text" class="inputbox2 dr" id="kabupaten" name="kabupaten" readonly>
											<input type="hidden" id="idkabupaten" name="idkabupaten" class="inputbox2 dr">
										</td>
									</tr>
									<tr class="detail">
										<td>Kecamatan</td>
										<td><input type="text" class="inputbox2 dr" id="kecamatan" name="kecamatan" readonly>
											<input type="hidden" id="idkecamatan" name="idkecamatan" class="inputbox2 dr">
										</td>
									</tr>
									<tr class="detail">
										<td>Kelurahan/Desa</td>
										<td><input type="text" class="inputbox2 dr" id="kelurahan" name="kelurahan" readonly></td>
									</tr>
									<tr class="detail">
										<td>Kodepos</td>
										<td><input type="text" class="inputbox2 dr" id="kodepos" name="kodepos" readonly></td>
									</tr>
									<tr class="detail">
										<td>Alamat</td>
										<td><textarea id="alamat" name="alamat" class="textareabox2 dr" style="width:150px" readonly></textarea></td>
									</tr>
									<tr>
										<td colspan="2" align="right"><a id="detailreseller">Detail</a></td>
									</tr>
									
								</table>
							</fieldset>
					   </td>
					   <td width="65%">
					          <fieldset>
								<legend>Produk</legend>
								<table class="form">
									<tr>
										<td width="20%">Cari produk</td>
										<td width="80%"><input type="text" id="cariproduk" name="cariproduk" class="inputbox3" value="" style="width:350px"></td>
									</tr>
								</table>
				   
								<div id="dialog-form-produk" title="Data Produk">
								<div id="hasil" style="display: none;"></div>
								<table class="form">
									<tr>
										<td width="20%">Kode Produk</td>
										<td  width="80%"><input type="text" id="kdproduk" name="kdproduk" class="inputbox2" value="" readonly>
											<input type="hidden" id="idproduk" name="idproduk" value="">
											
										</td>
									</tr>
									<tr>
										<td>Nama Produk</td>
										<td><input type="text" id="nmproduk" name="nmproduk" class="inputbox2" value="" readonly></td>
									</tr>
									<tbody id="option"></tbody>
									<tr>
										<td></td>
										<td><input type="text" class="inputbox" id="qyt" name="qyt" value="1" style="width:30px"><span id="keterangan"></span><a id="addcart" class="tombols">Add to Cart</a></td>
									</tr>
						
								</table>
				 				</div>
								 <table class="list">
									<thead>
										<tr>
											<td width="3%">X</td>
											<td>Produk</td>
											<td>Jml</td>
											<td>Berat</td>
											<td>Harga</td>
											<td>Sub Total</td>
										</tr>
									</thead>
									<tbody id="listcart">
										<tr>
											<td colspan="6" class="ltengah">Tidak ada data</td>
										</tr>
									</tbody>
						      
								 </table>
							</fieldset>
							
					   </td>
				   </tr>
				   <tr>
				       <td colspan="2">
						 <table class="form">
							<tr>
								<td align="left"><!--<a id="tblprevproduk" href="areacust" class="tombolkuning tbls" alt="lireseller">Kembali</a>--></td>
								<td align="right"><a id="tblnextreseller" href="areapengiriman" class="tombolkuning tbls" alt="liproduk">Lanjut</a></td>
							</tr>
						  </table>
					   </td>
				   </tr>
				</table>
			  </div>
			  
			 
			  <div id="areapengiriman" class="tab_content" style="display:none">
			     <fieldset>
					<legend>Alamat Penerima</legend>
						<table class="form">
						<tr>
							<td width="15%">Nama</td>
							<td width="85%"><input type="text" class="inputbox pengiriman" id="pnama" name="pnama"> *</td>
						</tr>
						<tr>
							<td>No. Telp</td>
							<td><input type="text" class="inputbox pengiriman" id="pnotelp" name="pnotelp">
								
							</td>
							
						</tr>
						<tr>
							<td>Handphone</td>
							<td><input type="text" class="inputbox pengiriman" id="pnohp" name="pnohp"> *</td>
						</tr>
						<tr>
							<td>Alamat</td>
							<td colspan="3">
							<textarea id="palamat" name="palamat" class="textareabox pengiriman" style="width:300px"> *</textarea>
							</td>
						</tr>
						<tr>
							<td>Negara</td>
							<td><select id="pnegara" name="pnegara" style="width:200px" class="selectbox pengiriman"><option value="0">- Negara -</option> </select> *
							</td>
							
						</tr>
						<tr>
							<td>Propinsi</td>
							<td><select id="ppropinsi" name="ppropinsi" style="width:200px" class="selectbox pengiriman"><option value="0">- Propinsi -</option> </select> *
							</td>
						</tr>
						<tr>
							<td>Kota/Kabupaten</td>
							<td><select id="pkabupaten" name="pkabupaten" style="width:200px" class="selectbox pengiriman"><option value="0">- Kota/Kabupaten -</option> </select> *
							</td>
							
						</tr>
						<tr>
							<td>Kecamatan</td>
							<td><select id="pkecamatan" name="pkecamatan" style="width:200px" class="selectbox pengiriman"><option value="0">- Kecamatan -</option> </select> *
							</td>
						</tr>
						<tr>
							<td>Kelurahan/Desa</td>
							<td><input type="text" class="inputbox pengiriman" id="pkelurahan" name="pkelurahan"></td>
							
						</tr>
						<tr>
							<td>Kodepos</td>
							<td><input type="text" class="inputbox pengiriman" id="pkodepos" name="pkodepos"></td>
						</tr>
						<tr>
							<td></td>
							<td><a id="tombolreset" class="tombols">Reset</a></td>
						</tr>
						<tr>
							<table class="form">
							<tr>
								<td align="left"><a id="tblprevpengiriman" href="areacust" class="tombolkuning tbls" alt="lireseller">Kembali</a></td>
								<td align="right"><a id="tblnextpengiriman" href="areametode" class="tombolkuning tbls" alt="lishipping">Lanjut</a></td>
							</tr>
						  </table>
						</tr>
						
					</table>
				</fieldset>
			  </div>
			  
			  <div id="areametode" class="tab_content" style="display:none">
			     <fieldset>
				   <legend>Metode Pengiriman</legend><br>
			       <div id="shipping"></div>
				   
				</fieldset>
				<table class="form">
					<tr>
						<td align="left"><a id="tblprevshipping" href="areapengiriman" class="tombolkuning tbls" alt="lireseller">Kembali</a></td>
						<td align="right"><a id="tblnextshipping" href="areatotal" class="tombolkuning tbls" alt="liproduk">Lanjut</a></td>
					</tr>
				</table>
			  </div>
			  
		      <div id="areatotal" class="tab_content" style="display:none">
			      <fieldset>
					<legend>TOTAL BELANJA</legend>
					<br>
					<table class="list">
						<thead>
							<tr>
								<td width="3%"></td>
								<td>Produk</td>
								<td>Jml</td>
								<td>Berat</td>
								<td>Harga</td>
								<td>Sub Total</td>
							</tr>
						</thead>
						<tbody id="listtotal">
							<tr>
								<td colspan="6" class="ltengah">Tidak ada data</td>
							</tr>
						</tbody>
						      
					</table>
					
					<table class="list">
					  <thead id="ttotal">
						
					  </thead>
					  
					</table>
					<table class="form">
						<tr>
							<td align="left"><a id="tblprevtotal" href="areametode" class="tombolkuning tbls" alt="">Kembali</a></td>
							<td align="right"><a id="tbltotal" href="areahasil" class="tombolkuning tbls" alt="">Simpan</a></td>
						</tr>
					</table>
					
				  </fieldset>
			  </div>			  
			  
			  <div id="areahasil" class="tab_content" style="display:none">
			    <fieldset>
					<legend>TOTAL BELANJA</legend>
					<br>
			       <table class="form" >
				     <tbody id="kethasil" >
					 </tbody>
				   </table>
				</fieldset>
				   <table class="form">
						<tr>
							<td align="left"></td>
							<td align="right">
							<!--<a onclick="tampilkan('<?php URL_PROGRAM_ADMIN.folder."/?op=add" ?>')" class="tombolkuning" alt="">Tambah Pesanan Baru</a>
							-->
							<a href='<?php echo URL_PROGRAM_ADMIN.folder."/?op=add" ?>' class="tombolkuning">Tambah Pesanan Baru</a>
							<a href='<?php echo URL_PROGRAM_ADMIN.folder ?>' class="tombolkuning" alt="">Lihat Data Order</a>
							</td>
						</tr>
					</table>
			  </div>
			  
		 </div>
			  
	</form>
   </div>
</div>
<script>
var action = $('#frmdata').attr('action');
var jgrup = "<?php echo $idgrupresellers ?>";
$(function(){
     $('#tblsimpancust').click(function(){
	      simpanpelanggan();
		  return false;
	 });
	 $('input[name="jinputan"]').change(function(){
	    
	    if($(this).val() == 'cari') {
		   $('#trcari').show();
		   $('#trform').hide();
		   $('#cidreseller').focus();
		} else {
		   $('#trcari').hide();
		   $('#trform').show();
		   $('#innamareseller').focus();
		   $('#injenis').val(jgrup);
		   //alert($('#injenis').val());
		}
	 });

     // Pendeklarasian awal,  fokus di pencarian reseller
	 $('#cidreseller').focus();
	 
	 $('#cariproduk').css("color","#999");
	 $('#cariproduk').val("Pencarian Produk");
	 
     $('#cariproduk').focusout(function() {
		  $(this).css("color","#999");
		  $(this).val("Pencarian Produk");
	 });
	 $('#cariproduk').focusin(function() {
	      $(this).css("color","#333");
		  $(this).val("");
	 });
	 $('#cidreseller').focusout(function() {
	      $(this).css("color","#999");
		  $(this).val("Pencarian Reseller");
	 });
	 $('#cidreseller').focusin(function() {
	      $(this).css("color","#333");
		  $(this).val("");
	 });
     $("#dialog-form-produk").dialog({
			autoOpen: false,
			height: 430,
			width: 600,
			modal: true,
			close: function() {
				$('#hasil').removeClass();
				$('#hasil').hide();
			}
	 });
   // End Pendeklrasian awan
	 
   // Even tombol
    
   /* Tombol detail reseller */	
   $('#detailreseller').click(function() {
		 $('.detail').toggle('slow');
	});
   /*- End tombol detail reseller */
	
   /* even tombol add cart */	
   $('#addcart').click(function(){
	   var datanya = "idproduk="+$('#idproduk').val()+'&idmember='+$('#idreseller').val()+'&qyt='+$('#qyt').val()+'&ukuran='+$('#ukuran').val()+'&warna='+$('#warna').val()+'&jenis='+$('#jenis').val();
       //alert(datanya);
	   $.ajax({
		type: "POST",
		url: "<?php echo URL_PROGRAM_ADMIN.'view/'.folder.'/?modul=addcart'?>",
		data: datanya,
		cache: false,
		success: function(msg){
			//alert(msg);
			hasilnya = msg.split("|");
			if(hasilnya[0]=="gagal") {
			   $('#hasil').addClass("warning");
			   $('#hasil').html(hasilnya[1]);
			   $('#hasil').show(0);
			   $('html, body').animate({ scrollTop: 0 }, 'slow');
			} else {
			   $('#hasil').addClass("success");
			   $('#hasil').html(hasilnya[1]);
			   $('#hasil').show(0);
			   $('#cart-total').html(hasilnya[2]);
			   //alert($('#jenis').val());
			   $('#listcart').load('<?php echo URL_PROGRAM_ADMIN."view/".folder."/"?>'+'?modul=listcart&jenis=' + $('#jenis').val() + '&idmember='+$('#idreseller').val()+'&h=hitung');
			   
			}
			return false;
		},  
			error: function(e){  
			alert('Error: ' + e);  
		}  
	  });  
   });
   
   /* end even tombol add cart */
   
   /* event tombol lanjut */
   $(".tbls").click(function() {
		var idtombol = $(this).attr("id");
		var selected_tab = $(this).attr("href");
		var alt = $(this).attr("alt");
		$('#loadingweb').show();
		$('#loadingweb').fadeOut(2000);
		if(idtombol == 'tblnextreseller'){
		   $('#loadingweb').fadeOut(2000);
			var jmls = 0;
			$('.jumlahqyt').each(function () {
				jmls += 1;
			});
			if(jmls < 1){
			    alert('Masukkan pesanan produk');
				return false;
			} else {
				$('#pnama').val($('#namareseller').val());
				$('#pnotelp').val($('#notelp').val());
				$('#pnohp').val($('#nohp').val());
				$('#palamat').val($('#alamat').val());
				createcombonpkk($('#idnegara').val(),'0','negara','pnegara');
				createcombonpkk($('#idpropinsi').val(),$('#idnegara').val(),'propinsi','ppropinsi');
				createcombonpkk($('#idkabupaten').val(),$('#idpropinsi').val(),'kabupaten','pkabupaten');
				createcombonpkk($('#idkecamatan').val(),$('#idkabupaten').val(),'kecamatan','pkecamatan');
				$('#pkelurahan').val($('#kelurahan').val());
				$('#pkodepos').val($('#kodepos').val());
								
			}
			//alert($('#jmlcart').val());
		} else if(idtombol == 'tblnextpengiriman'){
		  
		   /*
		    var datanya = '&negara='+$('#pnegara').val()+'&propinsi='+$('#ppropinsi').val()+
			              '&kabupaten='+$('#pkabupaten').val()+'&kecamatan='+$('#pkecamatan').val()+
						  '&totberat='+$('#totberat').val()+'&idmember='+$('#idreseller').val();
			*/
		    var datanya = '&negara='+$('#pnegara').val()+'&propinsi='+$('#ppropinsi').val();
			    datanya +='&kabupaten='+$('#pkabupaten').val()+'&kecamatan='+$('#pkecamatan').val();
				datanya +='&totberat='+$('#totberat').val()+'&idmember='+$('#idreseller').val();
				datanya +='&namapenerima='+escape($('#pnama').val())+'&alamatpenerima='+escape($('#palamat').val());
				datanya +='&alamatmember='+escape($('#alamat').val())+'&negaramember='+$('#idnegara').val();
				datanya +='&propmember='+$('#idpropinsi').val()+'&kabmember='+$('#idkabupaten').val();
				datanya +='&kecmember='+$('#idkecamatan').val();
		
			$('#loadingweb').fadeOut(2000);
			if($('#pnama').val() == ''){
				alert('Masukkan nama penerima');
				return false;
			}
						
			if($('#pnohp').val() == ''){
				alert('Masukkan no. hp penerima');
				return false
			}
			
			if($('#palamat').val() == ''){
				alert('Masukkan alamat penerima');
				return false;
			}
			
			if($('#pnegara').val() == '0'){
				alert('Masukkan negara penerima');
				return false;
			}
			
			if($('#ppropinsi').val() == '0'){
				alert('Masukkan propinsi penerima');
				return false;
			}
			
			if($('#pkabupaten').val() == '0'){
				alert('Masukkan kabupaten penerima');
				return false;
			}
			
			if($('#pkecamatan').val() == '0'){
				alert('Masukkan kecamatan penerima');
				return false;
			}
			
			
			
			$('#shipping').load('<?php echo URL_PROGRAM_ADMIN."view/".folder."/"?>'+'?modul=shipping'+datanya);
			
		
		} else if(idtombol == 'tblnextshipping'){
		   $('#loadingweb').fadeOut(2000);
		   //if ($("#serviskurir:checked").length == 0){
		   if($('input[name=serviskurir]:checked').length == 0){
	          alert('Pilih Kurir Pengiriman');
	          return false;
           } 
		   var error = '';
		   var idtxt = $('input[name=serviskurir]:checked').attr('rel');
		   var x;
		   var hrgkurir = 0;
		    $('.valueclass').each(function () {
			       x = $(this).attr("rel");
				   if(idtxt == x){
				       if($(this).val() == ''|| isNaN($(this).val())) {
						  error = 'Masukkan Harga Kurir dengan Angka';
					   } else {
					      hrgkurir = $(this).val();
					   }
					   return false;
				   }
		    });
		
		   if(error != ''){
		      alert(error);
			  return false;
		   }
           if($('#infaq').val() == ''){
              infaq = 0;
		   } else {
			  infaq = parseInt($('#infaq').val());
		   }
		   
		   if(infaq < 0 || (infaq % 1 != 0)){
			  alert('Masukkan Infaq dengan angka');
			  return false;
		   }

		  //dkurir = $('input[name=serviskurir]:checked').val();
		  dkurir = $('input[name=serviskurir]:checked').val();
		  kurir	= dkurir.split(":");
		   
		  if ($('#deposit').is(":checked")){
			deposit	= parseInt($('#deposit').val());
		  } else {
			deposit = 0;
		  }
		   
		  subtotal = parseInt($('#subtotal').val());
		  $('#listtotal').load('<?php echo URL_PROGRAM_ADMIN."view/".folder."/"?>'+'?modul=listcart&jenis=' + $('#jenis').val() + '&idmember='+$('#idreseller').val());
		
		  //total = (subtotal + parseInt(kurir[2]) + infaq) - deposit;
		  total = (subtotal + parseInt(hrgkurir) + infaq) - deposit;
		  dp = '';
		  dp += '<tr ><td style="width: 80%" align="right">' + kurir[3] +'</td>';
	      //dp += '<td style="width: 20%" align="right"> Rp. '+ numeral(kurir[2]).format("0,0") +'</td>';
		  dp += '<td style="width: 20%" align="right"><input type="hidden" name="zhrgkurir" id="zhrgkurir" value="'+hrgkurir+'"> Rp. '+ numeral(hrgkurir).format("0,0") +'</td>';
		  dp += '</tr>';

		  if (infaq > 0){
			 dp += '<tr>';
			 dp += '<td align="right">Infaq</td>';
			 dp += '<td align="right">' + infaq + '</td>';
			 dp += '</tr>';
	      }

		  if (deposit > 0){
	 
			 dp += '<tr>';
			 dp += '<td align="right">Potongan dari deposit</td>';
			 dp += '<td align="right">(' + deposit + ')</td>';
			 dp += '</tr>';
				
		   }
		   
		   dp += '<tr>';
		   dp += '<td align="right"><b>TOTAL</b></td>';
		   dp += '<td align="right"> Rp. ' + numeral(total).format("0,0") + '</td>';
		   dp += '</tr>';
		   $('#ttotal').html(dp);
		   
		} else if(idtombol == 'tbltotal'){
		   $('#loadingweb').fadeOut(2000);
		   simpanorder();
		}
		//alert(selected_tab);
		$("#tabs li").removeClass('active');
        
		//$('#'+alt).addClass("active");
		$(".tab_content").hide();
		$('#'+selected_tab).fadeIn();
		return false;
	});
	/* end event tombol lanjut */
	
	/* event combo box negara pengiriman tujuan */
	$('#pnegara').change(function() {
	    createcombonpkk('0',this.value,'propinsi','ppropinsi');
		createcombonpkk('0','0','kabupaten','pkabupaten');
		createcombonpkk('0','0','kecamatan','pkecamatan');
	});
	
    $('#innegara').change(function() {
	    createcombonpkk('0',this.value,'propinsi','inpropinsi');
		createcombonpkk('0','0','kabupaten','inkabupaten');
		createcombonpkk('0','0','kecamatan','inkecamatan');
	});	
	/* end event combo box negara pengiriman tujuan */
	
	/* event combo box propinsi pengiriman tujuan */
	$('#ppropinsi').change(function() {
	    createcombonpkk('0',this.value,'kabupaten','pkabupaten');
		createcombonpkk('0','0','kecamatan','pkecamatan');
	});
	$('#inpropinsi').change(function() {
	    createcombonpkk('0',this.value,'kabupaten','inkabupaten');
		createcombonpkk('0','0','kecamatan','inkecamatan');
	});
	/* end event combo box propinsi pengiriman tujuan */
	
	/* event combo box kabupaten pengiriman tujuan */
	$('#pkabupaten').change(function() {
	    createcombonpkk('0',this.value,'kecamatan','pkecamatan');
	});
	$('#inkabupaten').change(function() {
	    createcombonpkk('0',this.value,'kecamatan','inkecamatan');
	});
	/* end event combo box kabupaten pengiriman tujuan */
	
	/* pencarian reseller */
	$('#cidreseller').autocomplete({
	   delay: 0,
	   source: function( request, response ) {
	      $('#loadingweb').show();
	      $.ajax({
			url: action,
			dataType: "json",
			data: {
			    modul: 'reseller',
				idreseller: request.term
			},
			success: function( data ) {
			  kosongform();
			  $('#loadingweb').fadeOut(2000);
			  $('#listcart').load('<?php echo URL_PROGRAM_ADMIN."view/".folder."/"?>'+'?modul=listcart&jenis=&idmember=');
			  response( $.map( data, function( item ) {
			    //alert(item);
			    return {
				   label: item.rs_grupcode + item.reseller_kode + ' :: ' + item.reseller_nama,
				   value: item.reseller_nama,
				   id: item.reseller_id,
				   kode: item.rs_grupcode + item.reseller_kode,
				   nama: item.reseller_nama,
				   email: item.reseller_email,
				   telp: item.reseller_telp,
				   hp: item.reseller_hp,
				   alamat: item.reseller_alamat,
				   kelurahan: item.reseller_kelurahan,
				   kdpos: item.reseller_kodepos,
				   negara: item.reseller_negara,
				   propinsi: item.reseller_propinsi,
				   kabupaten: item.reseller_kabupaten,
				   kecamatan: item.reseller_kecamatan,
				   grup: item.reseller_grup
				 
				   
				}
			  }));
			}
	      });
	   },
	   minLength: 4,
	   select: function( event, ui ) {
	       $('#idreseller').val(ui.item.id);
		   $('#kdreseller').val(ui.item.kode);
		   $('#namareseller').val(ui.item.nama);
		   $('#jenis').val(ui.item.grup);
		   $('#listcart').load('<?php echo URL_PROGRAM_ADMIN."view/".folder."/"?>'+'?modul=listcart&jenis=' + ui.item.grup + '&idmember=' + ui.item.id+'&h=hitung');
		   carigrupreseller(ui.item.grup); 
		   $('#email').val(ui.item.email);
		   $('#notelp').val(ui.item.telp);
		   $('#nohp').val(ui.item.hp);
		   $('#alamat').val(ui.item.alamat);
		   $('#kelurahan').val(ui.item.kelurahan);
		   $('#kodepos').val(ui.item.kdpos);
		   $('#idnegara').val(ui.item.negara);
		   $('#idpropinsi').val(ui.item.propinsi);
		   $('#idkabupaten').val(ui.item.kabupaten);
		   $('#idkecamatan').val(ui.item.kecamatan);
		 
		   carinpkk(ui.item.negara,'negara');
		   carinpkk(ui.item.propinsi,'propinsi');
		   carinpkk(ui.item.kabupaten,'kabupaten');
		   carinpkk(ui.item.kecamatan,'kecamatan');
		   	$('.pengiriman').each(function () {
				$(this).attr("readonly",false);
			});
			$('#pnegara').attr("readonly",false);
			
	   },
	   focus: function(event, ui) {
			return false;
		}
	});	
	/* end pencarian reseller */
	
	/* pencarian produk*/
	$('#cariproduk').autocomplete({
		delay: 0,
		source: function( request, response ) {
		    $('#loadingweb').show();
			$.ajax({
				//url: "<?php echo URL_PROGRAM_ADMIN.'view/'.folder.'/?modul=cariproduk'?>",
				url: action,
				dataType: "json",
				data: {
				    modul: 'cariproduk',
					grupreseller: $('#jenis').val(),
					cariproduk: request.term
				},
				success: function( data ) {
				    $('#loadingweb').fadeOut(2000);
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
						/* dtharga: item.dataharga0,
						dtharga2: item.dataharga1*/
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
			//var minbeli = ui.item.harga['min_beli'];
	        $( "#dialog-form-produk" ).dialog( "open" );
	        //alert(ui.item.nama);
			$('#cariproduk').val("");
			$("input[name='idproduk']").attr('value', ui.item.id);
			$("input[name='kdproduk']").attr('value', ui.item.kode);
			$("input[name='nmproduk']").attr('value', ui.item.nama);
			html = '';
			html += '<tr><td>Berat</td><td><input type="text" value="'+ ui.item.berat +'" class="inputbox2" style="width:50px" readonly> Gram </td></tr>';
			if($('#jenis').val() == '') {
				$('#addcart').hide();
				$('#keterangan').html('Masukkan ID Reseller Untuk Add to Cart');
				//label = ' (beli '+ minbeli + ' atau lebih)';
				html += '<tr><td>Eceran </td><td><input type="text" value="Rp. '+ numeral(ui.item.hrgsatuan).format("0,0") +'" class="inputbox2" readonly></td></tr>';
				//html += '<tr><td>Grosir</td><td><input type="text" value="Rp. ' + numeral(ui.item.dtharga2).format("0,0") + ' (beli ' + ui.item.dtharga +' atau lebih ) " class="inputbox2" readonly></td></tr>';
			} else {
				$('#addcart').show();
				$('#keterangan').html('');
				/*
				if(minbeli > 1) {
					label = ' (beli '+ minbeli + ' atau lebih)';
					hargaeceran = ui.item.hrgsatuan;
					grosir = 'Grosir  ';						  
				} else {
					label = '';
					hargaeceran = 0;
					grosir = 'Harga';
				}
				
				if(hargaeceran > 0 && label != '') {
					html += '<tr><td>Eceran </td><td><input type="text" value="Rp. '+ numeral(hargaeceran).format("0,0") +'" class="inputbox2" readonly></td></tr>';
				} 
				html += '<tr><td>' + grosir + '</td><td><input type="text" value="Rp. ' + numeral(ui.item.harga['harga']).format("0,0") + label + '" class="inputbox2" readonly></td></tr>';
				*/
				var jmldt = ui.item.harga.length;
				var hit = 0;
				var labelketerangan = '';
				var label = '';
				
				if( $('#jenis').val() != $('#reseller_bayar').val() || jmldt < 1) {
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
					html += '<td><select id="ukuran" name="ukuran" style="width:200px" class="selectbox" onchange="pilihwarna(this.value)" >';
					
					for (j = 0; j < ui.item.ukuran.length; j++) {
						html += '<option value="' + ui.item.ukuran[j]['id'] + '">' + ui.item.ukuran[j]['nm'] + '</option>';
					}
			 
					html += '</select>';
					html += '</td></tr>';
				}
		  
				if(ui.item.warna != ''){
					html += '<tr><td>Warna </td>';
					html += '<td><select id="warna" name="warna" style="width:200px" class="selectbox" onchange="pilihstok(this.value)">';
					html += '<option value="0">- Pilih Warna -</option>';
					
					for (j = 0; j < ui.item.warna.length; j++) {
						html += '<option value="' + ui.item.warna[j]['id'] + '">' + ui.item.warna[j]['nm'] + '</option>';
					}
					
					html += '</select>';
					html += '</td></tr>';
				}
			}
	   
			html += '<tr><td>Stok</td><td><input type="text" id="stok" value="'+ ui.item.stok +' Pcs" class="inputbox2" style="width:40px"></td></tr>';
	   
			$('#option').html(html);
				return false;
		},
		focus: function(event, ui) {
			return false;
		}
	});
	
	$('#tombolreset').click(function(){
	    $('.pengiriman').each(function () {
			$(this).val("");

		});
	});
   // End EVEN Tombol
    
	$('.pengiriman').each(function () {
		$(this).attr("readonly",true);
	});
	
	
});


function simpanpelanggan(){
  var grup      = $('#injenis').val();
  var nama      = escape($('#innamareseller').val());
  var telp      = $('#innotelp').val();
  var hape      = $('#innohp').val();
  var negara    = $('#innegara').val();
  var propinsi  = $('#inpropinsi').val();
  var kabupaten = $('#inkabupaten').val();
  var kecamatan = $('#inkecamatan').val();
  var kelurahan = escape($('#inkelurahan').val());
  var kodepos   = $('#inkodepos').val();
  var alamat    = escape($('#inalamat').val());
  
  if(nama == '') {
     alert('Masukkan Nama Pelanggan Baru');
	 return false;
  }
  if(hape == '') {
     alert('Masukkan No. Handphone Pelanggan Baru');
	 return false;
  }
  if(negara == '' || negara == '0' ) {
     alert('Masukkan Negara Pelanggan Baru');
	 return false;
  }
  if(propinsi == '' || propinsi == '0' ) {
     alert('Masukkan Propinsi Pelanggan Baru');
	 return false;
  }
  if(kabupaten == '' || kabupaten == '0' ) {
     alert('Masukkan Kabupaten Pelanggan Baru');
	 return false;
  }
  if(kecamatan == '' || kecamatan == '0' ) {
     alert('Masukkan Kecamatan Pelanggan Baru');
	 return false;
  }
  //if(kelurahan == '' ) {
  //   alert('Masukkan Kelurahan Pelanggan Baru');
//	 return false;
  //}
  if(alamat == '' ) {
     alert('Masukkan Alamat Pelanggan Baru');
	 return false;
  }
  var datanya = "rnama="+nama+"&rtipereseller="+grup+"&rtelp="+telp+"&rhandphone="+hape+"&rnegara="+negara+"&rpropinsi="+propinsi+"&rkabupaten="+kabupaten;
  datanya    += "&rkecamatan="+kecamatan+"&rkelurahan="+kelurahan+"&rkdpos="+kodepos+"&ralamat="+alamat;
  
  $.ajax({
	type: "POST",
	url: "<?php echo URL_PROGRAM_ADMIN.'view/'.folder.'/?modul=simpanpelanggan'?>",
	data: datanya,
	cache: false,
	success: function(msg){
	
	  hasilnya = msg.split("|");
	  if(hasilnya[0]=="gagal") {
		  alert(hasilnya[2]);
	      $('html, body').animate({ scrollTop: 0 }, 'slow');
	  } else {
	      //alert(hasilnya[2]);
	      dataid = hasilnya[2].split(" ");
		  kodeid   = dataid[4];
		  idreseller = hasilnya[3];
		  $('#idreseller').val(idreseller);
		  $('#kdreseller').val(kodeid);
		  $('#namareseller').val($('#innamareseller').val());
		  $('#jenis').val($('#injenis').val());
		  $('#listcart').load('<?php echo URL_PROGRAM_ADMIN."view/".folder."/"?>'+'?modul=listcart&jenis=' + $('#jenis').val() + '&idmember=' + idreseller+'&h=hitung');
		   carigrupreseller($('#jenis').val()); 
		   
		   $('#notelp').val($('#innotelp').val());
		   $('#nohp').val($('#innohp').val());
		   $('#alamat').val($('#inalamat').val());
		   $('#kelurahan').val($('#inkelurahan').val());
		   $('#kodepos').val($('#inkodepos').val());
		   $('#idnegara').val($('#innegara').val());
		   $('#idpropinsi').val($('#inpropinsi').val());
		   $('#idkabupaten').val($('#inkabupaten').val());
		   $('#idkecamatan').val($('#inkecamatan').val());
		 
		   carinpkk($('#idnegara').val(),'negara');
		   carinpkk($('#idpropinsi').val(),'propinsi');
		   carinpkk($('#idkabupaten').val(),'kabupaten');
		   carinpkk($('#idkecamatan').val(),'kecamatan');
		   	$('.pengiriman').each(function () {
				$(this).attr("readonly",false);
			});
			$('#pnegara').attr("readonly",false);
			$('#trform').hide();
			$('#trcari').show();
	  }
	    return false;
    },  
		error: function(e){  
		alert('Error: ' + e);  
	}  
  });  
		  
}

function updatecart(){
      $.ajax({
		type: "POST",
		url: "<?php echo URL_PROGRAM_ADMIN.'view/'.folder.'/?modul=updatecart'?>",
		data: $('#frmdata').serialize(),
		cache: false,
		success: function(msg){
			hasilnya = msg.split("|");
			if(hasilnya[0]=="gagal") {
			  alert(hasilnya[1]);
			   $('html, body').animate({ scrollTop: 0 }, 'slow');
			} else {
			   alert('Update Qyt berhasil');
			   $('#listcart').load('<?php echo URL_PROGRAM_ADMIN."view/".folder."/"?>'+'?modul=listcart&jenis=' + $('#jenis').val() + '&idmember='+$('#idreseller').val()+'&h=hitung');
			}
			return false;
		},  
			error: function(e){  
			alert('Error: ' + e);  
		}  
	  });  
}

function hapusCart(zdata){
   var del = 'data=' + zdata +'&idmember='+$('#idreseller').val();
   //$('#hasil').removeClass();
   $.ajax({
		type: "POST",
		url: "<?php echo URL_PROGRAM_ADMIN.'view/'.folder.'/?modul=delcart'?>",
		data: del,
		cache: false,
		success: function(msg){
		    //alert(msg);
			hasilnya = msg.split("|");
			if(hasilnya[0]=="gagal") {
			   alert(hasilnya[1]);
			   $('html, body').animate({ scrollTop: 0 }, 'slow');
			} else {
			   $('#listcart').load('<?php echo URL_PROGRAM_ADMIN."view/".folder."/"?>'+'?modul=listcart&jenis=' + $('#jenis').val() + '&idmember='+$('#idreseller').val()+'&h=hitung');
			}
			return false;
		},  
			error: function(e){  
			alert('Error: ' + e);  
		}  
   });  
}

function carireseller(){
   var id = $('#cidreseller').val();
   var datanya = 'idreseller='+id+'&modul=reseller';
   if(id == ''){
      alert('Masukkan Id Reseller');
	  $('#idreseller').focus();
	  return false;
   }
   $('#loadingweb').show();
   $.ajax({
 		type: "GET",
   		url: action,
    	data: datanya,
		dataType: 'json',
    	success: function(msg){
		  $('#loadingweb').fadeOut(2000);
		   if(msg == '') {
		      alert('Maaf, tidak ditemukan data reseller');
			  //return false;
		   }
		   $('#idreseller').val(msg.reseller_id);
		   $('#nama').val(msg.reseller_nama);
		   $('#pnama').val(msg.reseller_nama);
		   $('#email').val(msg.reseller_email);
		   $('#notelp').val(msg.reseller_telp);
		   $('#pnotelp').val(msg.reseller_telp);
		   $('#nohp').val(msg.reseller_hp);
		   $('#pnohp').val(msg.reseller_hp);
		   $('#alamat').val(msg.reseller_alamat);
		   $('#palamat').val(msg.reseller_alamat);
		   $('#kelurahan').val(msg.reseller_kelurahan);
		   $('#pkelurahan').val(msg.reseller_kelurahan);
		   $('#kodepos').val(msg.reseller_kodepos);
		   $('#pkodepos').val(msg.reseller_kodepos);
		   $('#idnegara').val(msg.reseller_negara);
		   $('#jenis').val(msg.reseller_grup);
		   carigrupreseller(msg.reseller_grup);
		   carinpkk(msg.reseller_negara,'negara');
		   carinpkk(msg.reseller_propinsi,'propinsi');
		   carinpkk(msg.reseller_kabupaten,'kabupaten');
		   carinpkk(msg.reseller_kecamatan,'kecamatan');
		   createcombonpkk(msg.reseller_negara,'0','negara','pnegara');
		   createcombonpkk(msg.reseller_propinsi,msg.reseller_negara,'propinsi','ppropinsi');
		   createcombonpkk(msg.reseller_kabupaten,msg.reseller_propinsi,'kabupaten','pkabupaten');
		   createcombonpkk(msg.reseller_kecamatan,msg.reseller_kabupaten,'kecamatan','pkecamatan');
		   	$('.pengiriman').each(function () {
				$(this).attr("readonly",false);
			});
			$('#pnegara').attr("readonly",false);
			$('#listcart').load('<?php echo URL_PROGRAM_ADMIN."view/".folder."/"?>'+'?modul=listcart&jenis=' + $('#jenis').val() + '&idmember='+$('#idreseller').val()+'&h=hitung');
		   return false;
		},  
			error: function(e){  
      		alert('Error: ' + e);  
      	}  
  	});  
}

function createcombonpkk(id,id2,jenis,idobjek){
   var datanya = 'id='+id+'&id2='+id2+'&jenis='+jenis+'&modul=createcombonpkk';
   $.ajax({
 		type: "GET",
   		url: action,
    	data: datanya,
		dataType: 'html',
    	success: function(msg){
			//alert(msg);
		   $('#'+idobjek).html(msg);
		   return false;
		},
		   error: function(e){  
      		alert('Error: ' + e);  
      	}  
  	});  
}

function carinpkk(id,jenis){
   var datanya = 'id='+id+'&jenis='+jenis+'&modul=carinpkk';
   var x;
   $.ajax({
 		type: "GET",
   		url: action,
    	data: datanya,
		dataType: 'json',
    	success: function(msg){
		   $('#'+jenis).val(msg);
		   return false;
		},
		   error: function(e){  
      		alert('Error: ' + e);  
      	}  
  	});  
}
function carigrupreseller(idgrup){
   
   var datanya = 'id='+idgrup+'&modul=grupreseller';
   var x;
   $.ajax({
 		type: "GET",
   		url: action,
    	data: datanya,
		dataType: 'json',
    	success: function(msg){
		   $('#jreseller').val(msg);
		   return false;
		},
		   error: function(e){  
      		alert('Error: ' + e);  
      	}  
  	});  
}
function pilihwarna(ukuran){
   
   var dataload = '&ukuran='+ukuran+'&idproduk='+$('#idproduk').val();
   $('#warna').load('<?php echo URL_PROGRAM_ADMIN.'view/'.folder ?>/?modul=cariwarna' + dataload,function(responseTxt,statusTxt,xhr)
	{
		  if(statusTxt=="success")
			$('.loading').remove();
		
	});
     
}
function pilihstok(warna){
   ukuran = $('#ukuran').val();
   var dataload = 'warna='+warna+'&ukuran='+ukuran+'&idproduk='+$('#idproduk').val();
   //alert(datalog);
   $.ajax({
	 type: "GET",
	 url: '<?php echo URL_PROGRAM_ADMIN.'view/'.folder.'/?modul=caristok'?>',
	 data: dataload,
	 cache: false,
	 dataType: 'html',
	 success: function(msg){
	   //alert(msg);
	   //hasilnya = msg.split("|");
	   //$('#idimage').attr('src', '<?php echo URL_IMAGE."_detail/detail_"?>' + hasilnya[0]);
	   //$('#urlgbr').attr('href', hasilnya[1]);
	   $('#stok').val(msg);
	   return false;
	 },  
	   error: function(e){  
	   alert('Error: ' + e);  
	 }  
   });  
}

function kosongform(){
//alert('tes');
 $('.dr').each(function () {
	$(this).val("");

 });
}

function tampilshipping(){
  if ($("#serviskurir:checked").length == 0){
	 alert('Pilih Kurir Pengiriman');
	 return false;
  }
  if($('#infaq').val() == ''){
       infaq = 0;
  } else {
       infaq = parseInt($('#infaq').val());
  }
		   
  if(infaq < 0 || (infaq % 1 != 0)){
	alert('Masukkan Infaq dengan angka');
	return false;
   }

  dkurir = $('input[name=serviskurir]:checked').val();
  kurir	= dkurir.split(":");
		   
  if ($('#deposit').is(":checked")){
		deposit	= parseInt($('#deposit').val());
  } else {
		deposit = 0;
  }
		   
  subtotal = parseInt($('#subtotal').val());
  $('#listtotal').load('<?php echo URL_PROGRAM_ADMIN."view/".folder."/"?>'+'?modul=listcart&jenis=' + $('#jenis').val() + '&idmember='+$('#idreseller').val());
		
  total = (subtotal + parseInt(kurir[2]) + infaq) - deposit;
  dp = '';
  dp += '<tr ><td style="width: 80%" align="right">' + kurir[3] +'</td>';
  dp += '<td style="width: 20%" align="right"> Rp. '+ numeral(kurir[2]).format("0,0") +'</td>';
  dp += '</tr>';

  if (infaq > 0){

    dp += '<tr>';
	dp += '<td align="right">Infaq</td>';
	dp += '<td align="right">' + infaq + '</td>';
	dp += '</tr>';
				
  }

  if (deposit > 0){
	 
	 dp += '<tr>';
	 dp += '<td align="right">Potongan dari deposit</td>';
	 dp += '<td align="right">(' + deposit + ')</td>';
	 dp += '</tr>';
				
   }
		   
   dp += '<tr>';
   dp += '<td align="right"><b>TOTAL</b></td>';
   dp += '<td align="right"> Rp. ' + numeral(total).format("0,0") + '</td>';
   dp += '</tr>';
   $('#ttotal').html(dp);
   $('#loadingweb').fadeOut(2000);
}

function simpanorder(){
	//alert($('#frmdata').serialize());
   $.ajax({
		type: "POST",
		url: "<?php echo URL_PROGRAM_ADMIN.'view/'.folder.'/?modul=simpanorder'?>",
		data: $('#frmdata').serialize(),
		success: function(msg){
		  $('#loadingweb').fadeOut(2000);
		  //alert(msg)
		  hasilnya = msg.split("|");
		  nama = hasilnya[1];
		  totalbelanja = hasilnya[2];
		  noorder = hasilnya[3];
		  kodereseller = hasilnya[4];
		  html = '<tr><td>';
	      html += 'Data telah tersimpan, total belanja pelanggan <b>('+ kodereseller + ')' + nama + '</b> sebesar Rp. ' + numeral(totalbelanja).format("0,0");
		  html += '</td></tr><tr><td>';
		  html += '<br>No. Pesanan ' + noorder;
		  html += '</tr>';
		  $('#kethasil').html(html);
		  return false;
		},  
			error: function(e){  
			alert('Error: ' + e);  
		}  
   });  
}

</script>

