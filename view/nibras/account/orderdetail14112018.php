<div class="container">
	<section class="page-section">
		<h1 class="section-title"><span>Detail Belanja</span></h1>
	</section>
	<form id="frmorder" autocomplete="off" action="<?php echo URL_PROGRAM.'orderdetail'?>">
		<input type="hidden" id="pesanan_no" name="pesanan_no" value="<?php echo $dataorder['pesanan_no'] ?>">
		<input type="hidden" id="redirect" value="<?php echo URL_PROGRAM ?>orderdetail/?order=<?php echo $dataorder['pesanan_no'] ?>">
		<input type="hidden" name="jmlproduk" value="<?php echo count($datadetail) ?>">
		<input type="hidden" id="urlwilayah" value="<?php echo URL_THEMES.'wilayah/index.php' ?>">
		<div class="col-sm-12">
			<div class="plat-order">
				<div class="row">
					
					<div class="col-md-6">
						<b>No Order </b> : #<?php echo sprintf('%08s', (int)$dataorder['pesanan_no']); ?><br>
						<b>Tgl Order</b> : <?php echo $dtFungsi->ftanggalFull1($dataorder['pesanan_tgl']) ?>
					</div>
					<div class="col-md-6">
						<b>Status</b> : <?php echo $dataorder['status_nama']; ?><br>
						<b>No. Resi</b> : <?php echo $dataorder['no_awb']; ?>
					</div>
					
				</div>
			</div>
			<hr>
			
			<div class="col-md-12">
				<div class="row">
					<?php if($dataorder['cg_dropship'] == '1') { ?>
					<div class="col-md-6 kol-alamat">
						<button id="btnalamatpengirim" class="btn btn-sm btn-info" type="button" onclick="formAlamat('alamatpengirim')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Ganti Alamat Pengirim</button><br>
						<h5>Alamat Pengirim</h5>
						<?php 
							$alamat  = '<b>'.stripslashes($dataorder['nama_pengirim']).'</b><br> ';
							$alamat .= stripslashes($dataorder['alamat_pengirim']).' <br>';
							
							if($dataorder['kelurahan_pengirim'] != '') {
								
								$alamat .= $dataorder['kelurahan_pengirim'].', ';
							}
							$alamat .= $dataorder['kecamatannm_pengirim'].', '.$dataorder['kotanm_pengirim'].', '.$dataorder['propinsinm_pengirim'];
							$alamat .= ', '.$dataorder['negaranm_pengirim'];
							if($dataorder['kodepos_pengirim'] != '') {
								
								$alamat .= ' '.$dataorder['kodepos_pengirim'];
							}
							$alamat .= '<br> Hp. '.$dataorder['hp_pengirim'];
							echo $alamat;
						?>
					</div>
					<?php } ?>
					<div class="col-md-6 kol-alamat">
						<button id="btnalamatpenerima" onclick="formAlamat('alamatpenerima')" class="btn btn-sm btn-info" type="button"> <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Ganti Alamat Penerima</button><br>
						<h5>Alamat Penerima</h5>
						<?php 
							$alamat  = '<b>'.stripslashes($dataorder['nama_penerima']).'</b><br> ';
							$alamat .= stripslashes($dataorder['alamat_penerima']).' <br>';
							
							if($dataorder['kelurahan_penerima'] != '') {
								
								$alamat .= $dataorder['kelurahan_penerima'].', ';
							}
							$alamat .= $dataorder['kecamatannm_penerima'].', '.$dataorder['kotanm_penerima'].', '.$dataorder['propinsinm_penerima'];
							$alamat .= ', '.$dataorder['negaranm_penerima'];
							if($dataorder['kodepos_penerima'] != '') {
								
								$alamat .= ' '.$dataorder['kodepos_penerima'];
							}
							$alamat .= '<br> Hp. '.$dataorder['hp_penerima'];
							echo $alamat;
						?>
					</div>
				</div>
			</div>
			
			
			<div class="row">
				<div class="col-md-12">
					<div class="well-blue">
						<div class="form-group">
							<input type="text" class="form-control form-lg" name="search_produk" id="search_produk" placeholder="Cari nama produk yang ingin ditambahkan">
							<small clas="text-danger">Untuk menambah Produk, silahkan lakukan pencarian produk di box atas</small>
						</div>
					</div>
				</div>
			</div>
			
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						 <tr>
							<th width="10%"></th>
							<th class="text-left">Produk</th>
							<th class="text-right">Jumlah</th>
							<th class="text-center">Berat</th>
							<th class="text-right">Harga Normal</th>
							<th class="text-center">Diskon</th>
							<th class="text-right">Harga</th>
							<th class="text-right">Total</th>
						 </tr>
					</thead>
					<tbody>
						<?php 
							$totberat = 0;
							$i = 0;
							
							foreach($datadetail as $dt) {
								$harga_satuan = $dt['harga_satuan'];
								$harga_tambahan = $dt['harga_tambahan'];
								$persen_diskon_satuan = $dt['diskon_satuan'];
								$harga_normal_total = $harga_satuan + $harga_tambahan;
								$diskon_rupiah = $harga_normal_total - $dt['harga'];
								$persen_all_diskon = $diskon_rupiah/$harga_normal_total * 100;
								$diskon_cust_persen = $persen_all_diskon - $persen_diskon_satuan;
								
								$harga_normal = $dtFungsi->fFormatuang($dt['harga_satuan']+$dt['harga_tambahan']);
								/*
								if($dt['harga_tambahan'] > 0) {
									$harga_normal .= '<br><small> + '.$dtFungsi->fuang($dt['harga_tambahan']) . '<br>(Harga tambahan)</small>';
								}
								*/
								$options = array($dt['ukuranid'],$dt['warnaid'],$dt['ukuran'],$dt['warna']);
								$pid =  $dt['produkid'].'::'.base64_encode(serialize($options)).'::'.$dt['jml'];
								
							?>
						<tr>
							
							<td class="text-center"><a id="btnedit<?php echo $i?>" href="javascript:void(0)" class="btn btn-sm btn-outline-primary" onclick="editProduk('<?php echo $pid ?>')"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> <a id="btnhapus<?php echo $i?>" href="javascript:void(0)" class="btn btn-sm btn-outline-danger" onclick="hapusProduk('<?php echo $pid ?>')"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
							<td class="text-left"><b><?php echo $dt['nama_produk'] ?></b>
								<?php if($dt['warnaid'] || $dt['ukuranid']) {?>
								<br>
								<?php echo 'Warna  :'. $dt['warna']?><br>
								<?php echo 'Ukuran :'.$dt['ukuran'];?>
								<?php } ?>
							</td>
							<td class="text-right"><?php echo $dt['jml'] ?></td>
							<td class="text-center"><?php echo $dt['berat'] ?>Gr</td>
							<td class="text-right"><?php echo $harga_normal ?></td>
							<td class="text-right"><?php echo $persen_all_diskon ?> %</td>
							<td class="text-right"><?php echo $dtFungsi->fFormatuang($dt['harga']) ?></td>
							<td class="text-right"><?php echo $dtFungsi->fFormatuang(((int)$dt['jml']) * (int)$dt['harga']) ?></td>
						</tr>
						<?php $totberat = $totberat + $dt['berat'] ?>
						<?php $i++ ?>
					<?php } ?>
					</tbody>
				</table>
			</div>
				<table class="table table-bordered">
					
						<tr>
							<td class="text-right" colspan="7"><b>Total Berat</b></td>
							<td class="text-right"><?php echo $totberat ?> Gr
								<input type="hidden" name="totberat" value="<?php echo $totberat ?>">
							</td>
							
						</tr>
						<tr>
							<td colspan="7" class="text-right"><b>Subtotal</b></td>
							<td class="text-right"><?php echo $dtFungsi->fFormatuang($dataorder['pesanan_subtotal']) ?></td>
						</tr>
						<tr>
							<td colspan="7" class="text-right"><a href="javascript:void(0)" class="btn btn-sm btn-info" id="btneditorderkurir"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Ubah</a> <b>Tarif Kurir (<?php echo $dataorder['kurir'] ?> - <?php echo $dataorder['servis_code'] ?>)</b></td>
							<td class="text-right"><?php echo $dataorder['kurir_konfirm'] == '1' ? 'Konfirmasi Admin':$dtFungsi->fFormatuang($dataorder['pesanan_kurir']) ?></td>
						</tr>
						 <?php if($dataorder['dari_poin'] > 0) {?>
						<tr>
							<td colspan="7" class="text-right"><b>POTONGAN DARI POIN</b></td>
							<td class="text-right"><?php echo $dtFungsi->fFormatuang($dataorder['dari_poin']) ?></td>
						</tr>
						<?php } ?>
						
						<tr>
							<td colspan="7" class="text-right"><b>TOTAL</b></td>
							<td class="text-right"><b><?php echo $dataorder['kurir_konfirm'] == '1' ? 'Konfirmasi Admin':$dtFungsi->fFormatuang(((int)$dataorder['pesanan_subtotal'] + (int)$dataorder['pesanan_kurir'])-(int)$dataorder['dari_poin']) ?></b></td>
						</tr>
					
				</table>
			
		</div>
	</form>
</div>