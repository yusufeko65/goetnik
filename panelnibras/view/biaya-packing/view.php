<div id="hasil"></div>
<div class="col-lg-12 main-content">
	<h2 class="judulmodul"><?php echo $judul ?></h2>
	<?php if ($status != ''): ?>
    	<?php if ($status == 'success'): ?>
			<div id="hasil" class="alert alert-success">Update Berhasil</div>
		<?php else: ?>
			<div id="hasil" class="alert alert-danger">Update Gagal</div>
		<?php endif;?>
	<?php endif;?>
	<div class="panel-body">
		<div class="row">
					<form role="form-inline" id="frmcari" name="frmcari" action="<?php echo URL_PROGRAM_ADMIN . 'biaya-packing?u_token=' . $u_token ?>" method='post'>
						<div class="col-md-6">
                        <div class="form-group">
                                    <label class="col-md-3 control-label">Biaya Packing</label>
                                    <div class="col-md-6">
                                        <input type='hidden' name='setting_key' value="<?= (isset($data["setting_key"]) ? $data["setting_key"] : '_biaya_packing') ?>" />
                                        <input type="text" id="biaya_packing" name="biaya_packing" placeholder="Biaya Shipping" class="form-control" value="<?= ($data["setting_value"] ?? 0) ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="submit"  class="btn btn-sm btn-primary" value='Simpan' />
                                    </div>
                                </div>
						</div>
					</form>
		</div>
	</div>
</div>
