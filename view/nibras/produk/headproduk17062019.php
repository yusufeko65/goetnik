<div class="container">
	<section class="page-section">
		<h1 class="section-title"><span><?php echo $headproduk['dataproduk']['nama_produk']?></span></h2>
	</section>
	<div class="col-sm-12">
		
		<form id="frmpesan">
			<input type="hidden" id="url_image" value="<?php echo URL_IMAGE ?>">
			<div class="container">
				<div class="row justify-content-md-center">
					<div class="col-sm-5">
						
						<a href="<?php echo URL_IMAGE.'_zoom/zoom_gproduk'.$headproduk['dataproduk']['gbr_produk'] ?>" title="<?php echo $headproduk['dataproduk']['nama_produk']?>" class="zoom-produk" id="urlgbr"><img src="<?php echo URL_IMAGE.'_detail/detail_gproduk'.$headproduk['dataproduk']['gbr_produk'] ?>" class="img-fluid cover-produk" id="idimage"></a>
					
					</div>
					<div class="col-sm-7">
						<input type="hidden" id="url_produk" value="<?php echo $currentUrl ?>">
						<div class="well description">
							<div class="form-group row">
								
								<div class="col-md-12">
									<input type="text" readonly class="form-control-plaintext" value="<?php echo strtoupper($headproduk['dataproduk']['kode_produk']) ?>">
								</div>
							</div>
							
							<?php if($warna){ ?>
							<div class="form-group row">
								<label class="col-sm-4 col-form-label">Warna yang tersedia</label>
								<div class="col-sm-8"> 
									<input type="text" id="jmlwarna" readonly class="form-control-plaintext" value="<?php echo count($warna) ?> warna">
									<input type="hidden" id="idwarna" name="idwarna">
								</div>
							</div>
							<div class="form-group row">
								<div class="col-sm-8">
									<div class="warna-lain-produk">
										<div class="input-produk"><input type="radio" name="warna" value="0" checked></div>
										<?php foreach($warna as $w) { ?>
										<div class="input-produk">
											<input data-toggle="tooltip" data-placement="bottom" title="<?php echo $w['warna'] ?>" class="warna" type="radio" id="warna<?php echo $w['idwarna'] ?>" name="warna" value="<?php echo $w['idwarna'] ?>" rel="<?php echo $w['image_head'] ?>" onchange="viewProduk('<?php echo $w['image_head'] ?>','<?php echo $w['idwarna'] ?>','<?php echo $headproduk['dataproduk']['head_idproduk'] ?>');$('#keteranganwarna').html($(this).prop('title'))"/>
											<label title="<?php echo $w['warna'] ?>" class="produk_warna" for="warna<?php echo $w['idwarna'] ?>" style="background-image:url('<?php echo URL_IMAGE.'_thumb/thumbs_gproduk'.$w['image_head'] ?>');"></label>
										</div>
										<?php } ?>
									</div>
								
								</div>
							</div>	
							<?php } ?>
							<div class="form-group row">
								<div class="col-md-12">
								<?php 
								if($headproduk['dataproduk']['deskripsi_head']) { 
									echo $headproduk['dataproduk']['deskripsi_head'];
								} 
								?>
								</div>
							</div>
						</div>
					</div>
					<?php if(count($dataproduk) > 0) { ?>
					
					<div class="col-md-12">
						<?php foreach($dataproduk as $produk) { ?>
							<div class="detail-img-produk">
								<a href="<?php echo URL_PROGRAM.$produk['alias_url'] ?>" title="<?php echo $produk['nama_produk']?>">
									<img class="img-fluid img-thumbnail" src="<?php echo URL_IMAGE.'_small/small_gproduk'.$produk['gbr_produk'] ?>" alt="<?php echo $produk['nama_produk']?>">
								</a>
							</div>
						<?php } ?>
			
					</div>
					<div class="clearfix"></div>
					<?php } ?>
				</div>
			</div>
		</form>
	</div>
</div>  