<form method="POST" name="frmeditkurir" id="frmeditkurir" action="<?php echo $_SERVER['PHP_SELF'] ?>">
	<input type="hidden" id="nopesanan" name="nopesanan" value="<?php echo $formkurir['order']['pesanan_no']?>">
	<input type="hidden" id="propinsi_penerima" name="propinsi_penerima" value="<?php echo $formkurir['order']['propinsi_penerima'] ?>">
	<input type="hidden" id="kabupaten_penerima" name="kabupaten_penerima" value="<?php echo $formkurir['order']['kota_penerima'] ?>">
	<input type="hidden" id="kecamatan_penerima" name="kecamatan_penerima" value="<?php echo $formkurir['order']['kecamatan_penerima'] ?>">
	<input type="hidden" id="kodepos_penerima" name="kodepos_penerima" value="<?php echo $formkurir['order']['kodepos_penerima'] ?>">
	<input type="hidden" id="totberat" name="totberat" value="<?php echo $formkurir['totberat'] ?>">
	<input type="hidden" id="subtotal" name="subtotal" value="<?php echo $formkurir['order']['pesanan_subtotal'] ?>">
	<input type="hidden" id="redirectview" value="<?php echo URL_PROGRAM.'orderdetail' ?>">
	<input type="hidden" id="redirectedit" value="<?php echo URL_PROGRAM.'orderdetail/?order='.$formkurir['order']['pesanan_no'] ?>">
	<div class="modal-dialog modal-dialog-centered modal-dialog-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title text-center">Edit Kurir</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div id="hasileditkurir" style="display:none"></div>
				
				<div class="form-group">
					<label for="serviskurir" class="control-label">Servis : <cite>Harga yang ditampikan adalah harga per kilo</cite></label>
					
					<select class="form-control form-control-sm" name="serviskurir" id="serviskurir" onchange="cektarifkurir()">
						<option value="0">- Pilih Kurir -</option>
						<?php if($formkurir['servis']) { ?>
						<?php foreach($formkurir['servis'] as $ship) {?>
						<option value="<?php echo $ship['servis_id'] ?>"><?php echo $ship['shipping_kode'].' - '.$ship['servis_code'].' ('.$ship['servis_nama'].') - '.$ship['hrg_perkilo']?></option>
						<?php } ?>
						<?php } ?>
					<select>
				</div>
				<div class="form-group">
					<label>Berat</label>
					<input type="text" disabled class="form-control form-control-sm" value="<?php echo $formkurir['totberat'] .'Gr ('. $formkurir['totberat']/1000 .' Kg)'?>">
				</div>
				<div class="form-group">
					<label>Tarif</label>
					<input type="text" disabled class="form-control form-control-sm" id="tarifkurir">
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="simpaneditkurir()" data-loading-text="Tunggu..." id="btnsimpankurir" class="btn btn-sm btn-primary">Simpan</button>
				<button type="button" class="btn btn-default btn-sm" data-dismiss="modal" id="btnclose">Tutup</button>
			</div>
		</div>
	</div>
</form>