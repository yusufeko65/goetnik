<div class="container">
	<section class="page-section">
		<h2 class="section-title"><span>Check Out</span></h2>
	</section>
	<div class="col-md-12">
		<form method="POST" name="frmkasir" id="frmkasir" action="<?php echo URL_PROGRAM.$folder.'/checkout/'?>">
			<input type="hidden" id="url_wil" value="<?php echo URL_THEMES.'wilayah/index.php' ?>">
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
							<h6 class="list-title"><?php echo $cart['product'] ?></h6>
							<small class="text-muted">
								<?php echo $cart['warna_nama'] != '' ? 'Warna : '. $cart['warna_nama'] : '' ?>, <?php echo $cart['ukuran_nama'] != '' ? 'Ukuran : '. $cart['ukuran_nama'] : '' ?><br>
								Berat : <?php echo $cart['berat'] ?> gr<br>
								QTY : <?php echo $cart['qty'] ?>, Subtotal : Rp. <?php echo $dtFungsi->fuang($cart['total']) ?>
							</small>
						</li>
					<?php } ?>
						<li class="list-group-item">
							<h4 class="text-center listcart-subtotal">Subtotal : <?php echo 'Rp. '.$dtFungsi->fuang($zsubtotal) ?></h4>
						</li>
					</ul>
					<br>
					<input type="hidden" name="totberat" id="totberat" value="<?php echo $totberat ?>">
				</div>
				<div class="col-md-8 order-md-1">
					<div class="row">
						<div class="col-md-12 plat-alamat">
							<h4 <?php echo $grup_dropship == '0' ? "style=display:none" : '' ?>><i class="fa fa-map-marker" aria-hidden="true"></i> Alamat Pengirim</h4>
							<div class="plat-alamat-detail" <?php echo $grup_dropship == '0' ? "style=display:none" : '' ?>>
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
								<input type="hidden" name="propinsi_pengirim" id="propinsi_pengirim" value="<?php echo $propinsi_pengirim ?>">
								<input type="hidden" name="kabupaten_pengirim" id="kabupaten_pengirim" value="<?php echo $kabupaten_pengirim ?>">
								<input type="hidden" name="kecamatan_pengirim" id="kecamatan_pengirim" value="<?php echo $kecamatan_pengirim ?>">
								<input type="hidden" name="kelurahan_pengirim" id="kelurahan_pengirim" value="<?php echo $kelurahan_pengirim ?>">
								<input type="hidden" name="kodepos_pengirim" id="kodepos_pengirim" value="<?php echo $kodepos_pengirim ?>">
								<input type="hidden" name="telp_pengirim" id="telp_pengirim" value="<?php echo $telp_pengirim ?>">
							</div>
						</div>
						<div class="col-md-12 plat-alamat">
							<h4><i class="fa fa-map-marker" aria-hidden="true"></i> Alamat Penerima</h4>
							<div class="plat-alamat-detail">
								<div id="alamatpenerima">
									<!-- form alamat penerima -->
									<div class="form-row align-items-center">
										<div class="form-group col-sm-6">
											<label for="nama_penerima" class="col-form-label col-form-label-sm">Nama</label>
											<input type="text" id="nama_penerima" name="nama_penerima" class="form-control " value="<?php echo $nama_penerima ?>" placeholder="Nama">
											<div class="invalid-feedback">
												Masukkan Nama
											</div>
										</div>
										<div class="form-group col-sm-6">
											<label for="telp_penerima" class="col-form-label col-form-label-sm">Nomor Hp</label>
											<input type="text" id="telp_penerima" name="telp_penerima" class="form-control " value="<?php echo $telp_penerima ?>" placeholder="Nomor Hp">
											<div class="invalid-feedback">
												Masukkan Nomor Hp
											</div>
										</div>
										<div class="form-group col-sm-12">
											<label for="alamat_penerima" class="col-form-label col-form-label-sm">Alamat</label>
											<textarea id="alamat_penerima" name="alamat_penerima" class="form-control "><?php echo $alamat_penerima ?></textarea>
											<div class="invalid-feedback">
												Masukkan Alamat
											</div>
										</div>
										<div class="form-group col-sm-4">
											
											<label for="propinsi_penerima" class="col-form-label col-form-label-sm">Propinsi</label>
											<select id="propinsi_penerima" name="propinsi_penerima" class="form-control custom-select" onchange="findKabupaten(this.value,'kabupaten_penerima');$('#kecamatan_penerima').html('<option value=\'0\'>- Kecamatan -</option>');">
												<?php if($dataprop) { ?>
												<?php echo $dataprop ?>
												<?php } ?>
											<select>
											<div class="invalid-feedback">
												Masukkan Propinsi
											</div>
										</div>
										<div class="form-group col-sm-4">
											<label for="kabupaten_penerima" class="col-form-label col-form-label-sm">Kota/Kabupaten</label>
											<select id="kabupaten_penerima" name="kabupaten_penerima" class="form-control custom-select" onchange="findKecamatan(this.value,'kecamatan_penerima')">
												<?php echo $optkabupaten; ?>
											<select>
											<div class="invalid-feedback">
												Masukkan Kabupaten
											</div>
										</div>
										<div class="form-group col-sm-4">
											<label for="kecamatan_penerima" class="col-form-label col-form-label-sm">Kecamatan</label>
											<select id="kecamatan_penerima" name="kecamatan_penerima" class="form-control custom-select">
												<?php echo $optkecamatan ?>
											<select>
											<div class="invalid-feedback">
												Masukkan Kecamatan
											</div>
										</div>
										<div class="form-group col-sm-6">
											<label for="kelurahan_penerima" class="col-form-label col-form-label-sm">Kelurahan</label>
											<input type="text" id="kelurahan_penerima" name="kelurahan_penerima" class="form-control " value="<?php echo $kelurahan_penerima ?>" placeholder="Kelurahan">
										</div>
										<div class="form-group col-sm-6">
											<label for="kodepos_penerima" class="col-form-label col-form-label-sm">Kode Pos</label>
											<input type="text" id="kodepos_penerima" name="kodepos_penerima" class="form-control " value="<?php echo $kodepos_penerima ?>" placeholder="Kode Pos">
										</div>
									</div>
									<!-- end form alamat penerima -->
								</div>
							</div>
						</div>
						<div class="col-md-12 plat-alamat">
							<h4><i class="fa fa-truck" aria-hidden="true"></i> Kurir</h4>
							<div class="plat-alamat-detail">
								<div class="form-group">
									<cite>Harga yang tercantum adalah Harga Per Kilo</cite>
									<select class="custom-select form-control" name="serviskurir" id="serviskurir">
										<option value="0">- Pilih Kurir -</option>
										<?php if($servis) { ?>
										<?php foreach($servis as $ship) {?>
										<option value="<?php echo $ship['servis_id'] ?>"><?php echo $ship['shipping_kode'].' - '.$ship['servis_code'].' ('.$ship['servis_nama'].') - '.$ship['hrg_perkilo']?></option>
										<?php } ?>
										<?php } ?>
									<select>
								</div>
								<div class="form-group">
									<b>Total Berat </b><?php echo $totberat ?> Gr / <?php echo $totberat/1000 ?> Kg
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
								<?php if($grupcust['cg_deposito'] == '1' && $reseller['cd_deposito'] > 0) { ?>
								<li class="list-group-item">
									<div class="row">
										<div class="col-6">
											<h5>Potongan dari Deposito : </h5>
											<small>Anda memiliki Deposito sebesar <?php echo $dtFungsi->fuang($reseller['cd_deposito']) ?></small>
										</div>
										<div class="col-6">
											<h5 class="text-right" id="potdepositocaption">
											<?php 
											if($reseller['cd_deposito'] > $zsubtotal) {  
												$potdeposito = 'Rp. '.$dtFungsi->fuang($zsubtotal);
											} else { 	
												$potdeposito = 'Rp. '.$dtFungsi->fuang($reseller['cd_deposito']);
											} 
											?>
											</h5>
											<input type="hidden" name="potdeposito" id="potdeposito" value="<?php echo $potdeposito ?>">
										</div>
									</div>
								</li>
								<?php } ?>
								<?php if($reseller['cp_poin']) { ?>
								<li class="list-group-item">
									<div class="row">
										<div class="col-6">
											<h5>Poin Anda : <?php echo $reseller['cp_poin'] > 0 ? $reseller['cp_poin'] : 0 ?></h5>
										</div>
										<div class="col-6">
											<input type="number" name="poin" id="poin" placeholder="Masukkan Poin" class="form-control">
										</div>
									</div>
								</li>
								<?php } ?>
							</ul>
							
						</div>
						
						<div class="container" style="padding-top:10px">
							<div class="row justify-content-md-center">
								<div class="col-md-4 text-center text-danger">
									<button id="simpancart" type="submit" class="btn btn-block btn-info">Bayar</button>
								</div>
							</div>
						</div>
						
					</div>
				</div>
				
			</div>
		</form>
	</div>
</div>