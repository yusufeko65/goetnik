<form method="POST" name="frmeditkurir" id="frmeditkurir" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="hidden" id="nopesanan" name="nopesanan" value="<?php echo $nopesan?>">
	<input type="hidden" id="propinsi_penerima" name="propinsi_penerima" value="<?php echo $prop ?>">
	<input type="hidden" id="kabupaten_penerima" name="kabupaten_penerima" value="<?php echo $kota ?>">
	<input type="hidden" id="kecamatan_penerima" name="kecamatan_penerima" value="<?php echo $kec ?>">
	<input type="hidden" id="kodepos_penerima" name="kodepos_penerima" value="<?php echo $kdpos ?>">
	<input type="hidden" id="totberat" name="totberat" value="<?php echo $totberat ?>">
	<input type="hidden" id="subtotal" name="subtotal" value="<?php echo $subtotal ?>">
	<input type="hidden" id="redirectview" value="<?php echo URL_PROGRAM_ADMIN.'order' ?>">
	<input type="hidden" id="redirectedit" value="<?php echo URL_PROGRAM_ADMIN.'order/?op=info&pid='.$nopesan ?>">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">&times;</a>
				<h4 class="modal-title">Edit Kurir</h4>
			</div>
			<div class="modal-body">
				<div id="hasileditkurir" style="display:none"></div>
				
				<div class="form-group">
					<label for="serviskurir" class="control-label">Servis : <cite>Harga yang ditampikan adalah harga per kilo</cite></label>
					<select class="form-control form-control-sm" name="serviskurir" id="serviskurir" onchange="cektarifkurir()">
						<option value="0">- Pilih Kurir -</option>
						<?php if($servis) { ?>
						<?php foreach($servis as $ship) {?>
						<option value="<?php echo $ship['servis_id'] ?>"><?php echo $ship['shipping_kode'].' - '.$ship['servis_code'].' ('.$ship['servis_nama'].') - '.$ship['hrg_perkilo']?></option>
						<?php } ?>
						<?php } ?>
					<select>
				</div>
				<div class="form-group">
					<label>Berat</label>
					<input type="text" disabled class="form-control form-control-sm" value="<?php echo $totberat .'Gr ('. $totberat/1000 .' Kg)'?>">
				</div>
				<div class="form-group" id="plattarif"<?php //echo $konfirmadmin == '0' ? ' style="display:none"':'' ?>>
					<label for="tarifkurir" class="control-label">Tarif : <cite>Harga tarif yang sudah berdasarkan hitungan total berat</cite></label>
					<input type="text" name="tarifkurir" id="tarifkurir" value="" placeholder="Masukkan Tarif" class="form-control form-control-sm" aria-describedby="helpBlock"<?php echo $konfirmadmin == '0' ? ' readonly': '' ?>>
					<span id="helpBlock" class="help-block"><cite>Khusus Servis yang masih "Konfirmasi Admin", silakan masukkan tarif sesuai servis masing-masing dari kurir tersebut.</cite></span>
					
				</div>
				
			</div>
			<div class="modal-footer">
			   <a onclick="simpaneditkurir()" data-loading-text="Tunggu..." id="btnsimpankurir" class="btn btn-sm btn-primary">Simpan</a>
			   <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="btnclose">Tutup</button>
			</div>
		</div>
	</div>
  
</form>