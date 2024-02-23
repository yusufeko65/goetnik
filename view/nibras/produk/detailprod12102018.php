<div class="container">
	<section class="page-section">
		<h1 class="section-title"><span>PRODUK</span></h2>
	</section>
	<div class="col-md-12 plat-produk">
		<form id="frmpesan">
			<input type="hidden" id="url_cart" value="<?php echo URL_PROGRAM.'cart/add'?>">
			<input type="hidden" id="url_image" value="<?php echo URL_IMAGE ?>">
			<div class="row justify-content-md-center">
				<div class="col-md-5">
					<a href="<?php echo URL_IMAGE.'_zoom/zoom_gproduk'.$detail['gbr_produk'] ?>" title="<?php echo $detail['nama_produk']?>" class="zoom-produk" id="urlgbr"><img class="img-fluid cover-produk" src="<?php echo URL_IMAGE.'_detail/detail_gproduk'.$detail['gbr_produk'] ?>" id="idimage"></a>
					
					<?php if(count($gbrdetail) > 0) { ?>
					<div class="col-md-12">
						
							
						<?php foreach($gbrdetail as $gbrdet) { ?>
							<div class="detail-img-produk">
								<a href="<?php echo URL_IMAGE.'_zoom/zoom_gproduk'.$gbrdet['gbr'] ?>" class="thumbnail zoom-produk" title="<?php echo $detail['nama_produk']?>">
									<img class="img-fluid img-thumbnail" src="<?php echo URL_IMAGE.'_small/small_gproduk'.$gbrdet['gbr'] ?>" alt="<?php echo $detail['nama_produk']?>">
								</a>
							</div>
						<?php } ?>
								
						
					</div>
					<div class="clearfix"></div>
					<?php } ?>
				</div>
				<div class="col-md-7">
					<input type="hidden" id="url_produk" value="<?php echo $currentUrl ?>">
					<div class="well description">
						
						<div id="hasil"></div>
						<h3><span><?php echo $detail['nama_produk']?></span><?php echo $labelsale ?></h3>
						<div class="form-group row">
							<label class="col-md-3 col-form-label">Kode Produk</label>
							<div class="col-md-8">
								<input type="text" readonly class="form-control-plaintext" value="<?php echo strtoupper($detail['kode_produk']) ?>">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-3 col-form-label">Berat</label>
							<div class="col-md-8">
								<input type="text" readonly class="form-control-plaintext" value="<?php echo $detail['berat_produk'] ?> Gram">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-md-3 col-form-label">Harga Satuan</label>
							<div class="col-md-8">
								<?php echo $labelhargasatuan ?>
							</div>
						</div>
						<?php if($idmember != '') { ?>
						<div class="form-group row">
							<label class="col-md-3 col-form-label">Harga <?php echo $grup_nama ?></label>
							<div class="col-md-8 labelharga">
								<?php echo $labelhargacust ?><span> <?php echo $labelminbeli ?></span>
							</div>
						</div>
						<?php } ?>
						<?php if($dataukuran) { ?>
						<div class="form-group row">
							<label class="col-md-3 col-form-label">Ukuran</label>
							<div class="col-md-5"> 
								<?php if(count($dataukuran) > 1) { ?>
								<select id="ukuran" name="ukuran" class="form-control custom-select form-control-sm" onchange="pilihwarna(this.value,'<?php echo $pid; ?>','<?php echo $detail['head_produk']?>')">
									<option value="0">- Pilih Ukuran -</option>
								<?php foreach($dataukuran as $uk) { ?>
									<option value="<?php echo $uk['id'] ?>"><?php echo $uk['nm'] ?></option>
								<?php  } ?>
								</select>
								<?php } else { ?>
								<input type="hidden" id="ukuran" name="ukuran" value="<?php echo $uk ?>"><?php echo $ukuran ?>
								<?php } ?>
							</div>
						</div>
						<?php } ?>
						<?php if($detail['head_produk'] < 1) { ?>
						<div class="form-group row">
							<label class="col-md-3 col-form-label">Stok</label>
							<div class="col-md-3">
								<input type="text" id="ket_stok" readonly class="form-control-plaintext form-control-sm" value="<?php echo strtoupper($detail['jml_stok']) ?> Pcs">
							</div>
						</div>
						<?php } ?>
						<?php if($warna){ ?>
						<div class="form-group row">
							<label class="col-md-3 col-form-label">Warna</label>
							<div class="col-md-6"> 
								<input type="text" id="jmlwarna" readonly class="form-control-plaintext" value="<?php echo count($warna) ?> warna">
								<!--<input type="hidden" id="idwarna" name="idwarna">-->
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-8">
								<div class="warna-lain-produk">
									<div class="input-produk"><input type="radio" name="warna" value="0" checked></div>
									<?php foreach($warna as $w) { ?>
									<div class="input-produk">
										<input data-toggle="tooltip" data-placement="bottom" title="<?php echo $w['nm'] ?>" class="warna" type="radio" id="warna<?php echo $w['id'] ?>" name="warna" value="<?php echo $w['id'] ?>" rel="<?php echo $w['gbr'] ?>" onchange="viewImageStok('<?php echo $w['gbr'] ?>','<?php echo $w['id'] ?>','<?php echo $detail['head_produk'] ?>');$('#nama_warna').html($(this).prop('title'))"/>
										<label title="<?php echo $w['nm'] ?>" class="produk_warna" for="warna<?php echo $w['id'] ?>" style="background-image:url('<?php echo URL_IMAGE.'_small/small_gproduk'.$w['gbr'] ?>');"></label>
									</div>
									<?php } ?>
								</div>
								<div class="col-md-12" id="keteranganwarna">
									<div class="row">
										<b>Warna yang dipilih : </b> <span id="nama_warna"> - </span>
									</div>
								</div>
							</div>
						</div>	
						<?php } ?>
						<div class="form-group row">
							<label class="col-md-3 col-form-label">Jumlah</label>
							<div class="col-md-5">
								<input type="number" min="1" class="form-control" id="jumlah" name="jumlah" value="1">
								<input type="hidden" name="product_id" id="product_id" size="2" value="<?php echo $pid; ?>" />
								<input type="hidden" name="image_product" id="image_product" value="<?php echo $detail['gbr_produk'] ?>">
							</div>
						</div>
						<div class="form-group row">
							<div class="col-md-8">
								<button type="button" id="button-beli" class="btn btn-outline-success btn-lg btn-block" /><i class="fa fa-shopping-basket" aria-hidden="true"></i>Beli</button>
							</div>
						</div>	
							
					</div>
					
				</div>
				
			</div>
			<div class="col-md-12 keterangan-produk">
				<div class="container">
					<?php if($detail['keterangan_produk']) { ?>
					<h4>Deskripsi Produk</h4>
					<?php	echo $detail['keterangan_produk'] ?>
					<?php } ?>
				</div>
			</div>
		</form>
	</div>
</div>  
