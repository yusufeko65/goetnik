<div class="container">
	<section class="page-section">
		<h2 class="section-title"><span>Check Out</span></h2>
	</section>
	<div class="col-md-12">
		<form method="POST" name="frmkasir" id="frmkasir" action="<?php echo URL_PROGRAM.$folder.'/checkout/'?>">
			<div class="row">
				<div class="col-md-4 order-md-2">
					
					<h5 class="justify-content-between align-items-center">
						<span class="text-muted">Belanja</span> 
						<span class="badge badge-secondary badge-pill"><?php echo $jmlitem ?></span>
					</h5>
					
					<ul class="list-group">
					<?php $totberat = 0 ?>
					<?php foreach($hcart as $cart) { ?>
					<?php $totberat = $totberat + $cart['berat'] ?>
						<li class="list-group-item">
							<h6><?php echo $cart['product'] ?></h6>
							<small class="text-muted">
								<?php echo $cart['warna_nama'] != '' ? 'Warna : '. $cart['warna_nama'] : '' ?>, <?php echo $cart['ukuran_nama'] != '' ? 'Ukuran : '. $cart['ukuran_nama'] : '' ?><br>
								Berat : <?php echo $cart['berat'] ?> gr<br>
								QTY : <?php echo $cart['qty'] ?>, Subtotal : Rp. <?php echo $dtFungsi->fuang($cart['total']) ?>
							</small>
						</li>
					<?php } ?>
					
						
					</ul>
					<br>
					<input type="hidden" name="berat" id="berat" value="<?php echo $totberat ?>">
				</div>
				<div class="col-md-8 order-md-1">
					<div class="row">
						<div class="col-md-12 plat-alamat">
							<h4><i class="fa fa-map-marker" aria-hidden="true"></i> Alamat Pengirim</h4>
							<div class="plat-alamat-detail">
								<div class="text-right">
									<button type="button" id="btnalamatpengirim" class="btn btn-sm btn-outline-secondary">Ganti Alamat</button>
								</div>
								<div id="alamatpengirim">
								<b><?php echo $nama_pengirim?></b><br>
								<?php echo $alamat_pengirim.', <br>'.$propinsi_pengirim_nm.', '.$kabupaten_pengirim_nm.', '.$kecamatan_pengirim_nm ?>
								<?php echo $kelurahan_pengirim != '' ? ', '.$kelurahan_pengirim: '' ?>
								<?php echo $kodepos_pengirim != '' ? ', '.$kodepos_pengirim: '' ?><br>
								
								Hp. <?php echo $telp_pengirim ?>
								</div>
								<input type="hidden" name="nama_pengirim" id="nama_pengirim" value="<?php echo $nama_pengirim ?>">
								<input type="hidden" name="alamat_pengirim" id="alamat_pengirim" value="<?php echo $alamat_pengirim ?>">
								<input type="hidden" name="propinsi_pengirim" id="kabupaten_penerima" value="<?php echo $propinsi_pengirim ?>">
								<input type="hidden" name="kabupaten_pengirim" id="kabupaten_pengirim" value="<?php echo $kabupaten_pengirim ?>">
								<input type="hidden" name="kecamatan_pengirim" id="kecamatan_pengirim" value="<?php echo $kecamatan_pengirim ?>">
								<input type="hidden" name="kelurahan_pengirim" id="kelurahan_pengirim" value="<?php echo $kelurahan_pengirim ?>">
								<input type="hidden" name="kodepos_pengirim" id="kodepos_pengirim" value="<?php echo $kodepos_pengirim ?>">
								<input type="hidden" name="telp_pengirim" id="telp_pengirim" value="<?php echo $telp_pengirim ?>">
							</div>
						</div>
						<div class="col-md-12 plat-alamat">
							<h4><i class="fa fa-map-marker" aria-hidden="true"></i> Alamat Penenerima</h4>
							<div class="plat-alamat-detail">
								<div class="text-right">
									<button type="button" id="btnalamatpenerima" class="btn btn-sm btn-outline-secondary">Ganti Alamat</button>
								</div>
								<div id="alamatpenerima">
								<b><?php echo $nama_penerima?></b><br>
								<?php echo $alamat_penerima.', <br>'.$propinsi_penerima_nm.', '.$kabupaten_penerima_nm.', '.$kecamatan_penerima_nm ?>
								<?php echo $kelurahan_penerima != '' ? ', '.$kelurahan_penerima: '' ?>
								<?php echo $kodepos_penerima != '' ? ', '.$kodepos_penerima: '' ?><br>
								
								Hp. <?php echo $telp_penerima ?>
								</div>
								<input type="hidden" name="nama_penerima" id="nama_penerima" value="<?php echo $nama_penerima ?>">
								<input type="hidden" name="alamat_penerima" id="alamat_penerima" value="<?php echo $alamat_penerima ?>">
								<input type="hidden" name="propinsi_penerima" id="kabupaten_penerima" value="<?php echo $propinsi_penerima ?>">
								<input type="hidden" name="kabupaten_penerima" id="kabupaten_penerima" value="<?php echo $kabupaten_penerima ?>">
								<input type="hidden" name="kecamatan_penerima" id="kecamatan_penerima" value="<?php echo $kecamatan_penerima ?>">
								<input type="hidden" name="kelurahan_penerima" id="kelurahan_penerima" value="<?php echo $kelurahan_penerima ?>">
								<input type="hidden" name="kodepos_penerima" id="kodepos_penerima" value="<?php echo $kodepos_penerima ?>">
								<input type="hidden" name="telp_penerima" id="telp_penerima" value="<?php echo $telp_penerima ?>">
							</div>
						</div>
						<div class="col-md-12 plat-alamat">
							<h4><i class="fa fa-truck" aria-hidden="true"></i> Kurir</h4>
							<div class="plat-alamat-detail">
								<div class="form-group">
									<select class="custom-select form-control" name="serviskurir" id="serviskurir">
										<option value="0">- Pilih Kurir -</option>
										<?php if($servis) { ?>
										<?php foreach($servis as $ship) {?>
										<option value="<?php echo $ship['servis_id'] ?>"><?php echo $ship['shipping_kode'].' - '.$ship['servis_code'].' ('.$ship['servis_nama'].')'?></option>
										<?php } ?>
										<?php } ?>
									<select>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<ul class="list-group">
								<li class="list-group-item">
									<div class="row">
										<div class="col-6">
											<h6>Subtotal : </h6>
										</div>
										<div class="col-6">
											<h6 class="text-right"><?php echo 'Rp. '.$dtFungsi->fuang($zsubtotal) ?></h6>
											<input type="hidden" id="subtotal" name="subtotal" value="<?php echo $zsubtotal ?>">
										</div>
									</div>
							
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-6">
											<h6>Kurir : </h6>
										</div>
										<div class="col-6">
											<h6 class="text-right" id="tarif">-</h6>
											<input type="hidden" name="tarifkurir" id="tarifkurir">
										</div>
									</div>
									
								</li>
								<li class="list-group-item">
									<div class="row">
										<div class="col-6">
											<h5>Total Tagihan : </h5>
										</div>
										<div class="col-6">
											<h5 class="text-right" id="totaltagihan">-</h5>
										</div>
									</div>
									
								</li>
							</ul>
							
						</div>
						
						<div class="container" style="padding-top:10px">
							<div class="row justify-content-md-center">
								<div class="col-md-4 text-center text-danger">
									<button id="simpancart" type="submit" class="btn btn-block btn-danger">Bayar</button>
								</div>
							</div>
						</div>
						
					</div>
				</div>
				
			</div>
		</form>
	</div>
</div>