<div class="container">
	<section class="product-section">
		<h2 class="section-title"><span><?php echo $namakategori ?></span></h2>
	</section>
	<div class="col-sm-12">
		<?php if($ambildata) { ?>
		<?php if($deskripsikategori) { ?>
		<?php echo $deskripsikategori ?>
		<?php } ?>
		<div class="row">
			<div class="col-sm-3">
				<select name="sort" class="form-control form-control-sm" onchange="location = '<?php echo URL_PROGRAM.$linkpage?>?sort=' + this.value">
					<option value="new" <?php if($sort=='new') echo "selected" ?>>Produk Terbaru</option>
					<option value="old" <?php if($sort=='old') echo "selected" ?>>Produk Terlama</option>
					<option value="hrgasc" <?php if($sort=='hrgasc') echo "selected" ?>>Harga Terendah</option>
					<option value="hrgdesc" <?php if($sort=='hrgdesc') echo "selected" ?>>Harga Tertinggi</option>
					<option value="namaasc" <?php if($sort=='namaasc') echo "selected" ?>>A - Z</option>
					<option value="namadesc" <?php if($sort=='namadesc') echo "selected" ?>>Z - A</option>
				</select>
			</div>
			<?php if($total > 0) { ?>
			<div class="col-sm-9">
				<div class="float-right">
					<nav aria-label="Page navigation">
						<ul class="pagination pagination-sm">
							<?php echo $dtPaging->GetPaging2($total,$baris,$page,$jmlpage,$linkpage,$linkcari,$amenu) ?>
						</ul>
					</nav>
				</div>
				<div class="clearfix"></div>
			</div>
			<?php } ?>
		</div>
		<div class="row list-produk">
	<?php
		$asid=0;
		foreach($ambildata as $datanya) {
			$gbrprod    = $datanya['gbr_produk'];
			$produkwarna = $dtProduk->getProdukSemuaWarna($datanya['idproduk']);
	?>
		<div class="col-sm-3">
			<div class="thumb_produk wow bounceIn" data-wow-delay="0.2s" data-wow-iteration="1">
				<?php if($produkwarna) { ?>
					<div class="thumb_produk_item">
						<?php $i = 0; ?>
						<?php foreach($produkwarna as $pw) { ?>
							<?php if($i < 4) { ?>
									<?php if(file_exists(DIR_IMAGE.'_small/small_gproduk'.$pw['gbr'])) { ?>
									<img src="<?php echo URL_IMAGE.'_small/small_gproduk'.$pw['gbr']; ?>" class="rounded img-thumbnail">
									<?php } ?>
							<?php } ?>
								<?php $i++ ?>
						<?php } ?>
					</div>
				<?php } ?>
				<a class="imgproduk" href="<?php echo URL_PROGRAM.$datanya['alias_url'] ?>" title="<?php echo $datanya['nama_produk']; ?>" width="<?php echo $config_produkthumbnail_p ?>px" height="<?php echo $config_produkthumbnail_l ?>px">
					<img src="<?php echo URL_IMAGE.'_thumb/thumbs_gproduk'.$gbrprod; ?>" alt="<?php echo $datanya['nama_produk']; ?>" title="<?php echo $datanya['nama_produk']; ?>" />
				</a>
				<?php 
				$harga	= $datanya['hrg_jual'];
				
				if($idmember != '') {
					if($grup_min_beli == 1) {
						$harga = $datanya['hrg_jual'] - (($datanya['hrg_jual']*$grup_diskon)/100);
					} 
				}
				if($datanya['sale'] == '1') {
							
					if($idmember != '') {
						
						if($grup_min_beli == 1) {
							$harga = $datanya['hrg_diskon'];
							$hargadiskon = $datanya['hrg_diskon'] - (($datanya['hrg_diskon']*$grup_diskon/100));
						} else {
							$hargadiskon = $datanya['hrg_diskon'];
						}
					} else {
						$hargadiskon = $datanya['hrg_diskon'];
					}
					$labelsale 	 = '<span class="onsale">Sale!</span>';
					$labelharga  = '<div class="oldprice">Rp. '.$dtFungsi->fuang($harga).'</div>';
					$labelharga .= '<div class="newprice">Rp. '.$dtFungsi->fuang($hargadiskon).'</div>';
				} else {
					$labelsale 	 = '';
					$labelharga  = '<div class="price">Rp. '.$dtFungsi->fuang($harga).'</div>';
				}
				?>
				<div class="nama_produk"><?php echo $datanya['nama_produk'] ?></div>
				<?php echo $labelharga ?>
				<?php echo $labelsale ?>
			</div>
		</div>
			
  <?php } ?>
		</div>
		<?php if($total > 0) { ?>
		<div class="float-right">
			<nav aria-label="Page navigation">
				<ul class="pagination pagination-sm">
					<?php echo $dtPaging->GetPaging2($total,$baris,$page,$jmlpage,$linkpage,$linkcari,$amenu) ?>
				</ul>
			</nav>
		</div>
		<div class="clearfix"></div>
		<?php } ?>
		<?php } else { ?>
		<p class="text-center">Tidak ada produk</p>
		<?php } ?>
	</div>
</div>