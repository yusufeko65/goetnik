<div class="container">
	<section class="page-section">
		<h1 class="section-title"><span>Detail Belanja</span></h2>
	</section>
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
				<div class="col-md-6">
					<h6>Alamat Pengirim</h6>
					<?php 
						$alamatpengirim  = '<b>'.stripslashes($dataorder['nama_pengirim']).'</b><br> ';
						$alamatpengirim .= stripslashes($dataorder['alamat_pengirim']).' <br>';
					
						if($dataorder['kelurahan_pengirim'] != '') {
							
							$alamatpengirim .= $dataorder['kelurahan_pengirim'].', ';
						}
						$alamatpengirim .= $dataorder['kecamatannm_pengirim'].', '.$dataorder['kotanm_pengirim'].', '.$dataorder['propinsinm_pengirim'];
						$alamatpengirim .= '<br> '.$dataorder['negaranm_pengirim'];
						if($dataorder['kodepos_pengirim'] != '') {
							
							$alamatpengirim .= ' '.$dataorder['kodepos_pengirim'];
						}
						$alamatpengirim .= '<br> Hp. '.$dataorder['hp_pengirim'];
						echo $alamatpengirim;
					?>
				</div>
				<?php } ?>
				<div class="col-md-6">
					<h6>Alamat Penerima</h6>
					<?php 
						$alamatpenerima  = '<b>'.stripslashes($dataorder['nama_penerima']).'</b><br> ';
						$alamatpenerima .= stripslashes($dataorder['alamat_penerima']).' <br>';
						
						if($dataorder['kelurahan_penerima'] != '') {
							
							$alamatpenerima .= $dataorder['kelurahan_penerima'].', ';
						}
						$alamatpenerima .= $dataorder['kecamatannm_penerima'].', '.$dataorder['kotanm_penerima'].', '.$dataorder['propinsinm_penerima'].'<br>';
						$alamatpenerima .= $dataorder['negaranm_penerima'];
						if($alamatpenerima['kodepos_penerima'] != '') {
							
							$alamatpenerima .= ' '.$dataorder['kodepos_penerima'];
						}
						$alamatpenerima .= '<br> Hp. '.$dataorder['hp_penerima'];
						echo $alamatpenerima;
					?>
				</div>
			</div>
		</div>
		<div class="table-responsive">
			
			<table class="table table-bordered">
				<thead>
					 <tr>
						<th class="text-left">Produk</th>
						<th class="text-right">Jumlah</th>
						<th class="text-right">Harga Normal</th>
						<th class="text-center">Diskon</th>
						<th class="text-right">Harga</th>
						<th class="text-right">Total</th>
					 </tr>
				</thead>
				<tbody>
					
					<?php foreach($datadetail as $dt) {
							$harga_satuan = $dt['harga_satuan'];
							$harga_tambahan = $dt['harga_tambahan'];
							$persen_diskon_satuan = $dt['diskon_satuan'];
							$harga_normal_total = $harga_satuan + $harga_tambahan;
							$diskon_rupiah = $harga_normal_total - $dt['harga'];
							$persen_all_diskon = $diskon_rupiah/$harga_normal_total * 100;
							$diskon_cust_persen = $persen_all_diskon - $persen_diskon_satuan;
							
							$harga_normal = $dtFungsi->fFormatuang($dt['harga_satuan'] + $dt['harga_tambahan']);
							/*
							if($dt['harga_tambahan'] > 0) {
								$harga_normal .= '<br><small> + '.$dtFungsi->fuang($dt['harga_tambahan']) . '<br>(Harga tambahan)</small>';
							}
							*/
							//$persen = $diskoncust + $dt['diskon_satuan'];
					?>
					<tr>
						<td class="text-left"><b><?php echo $dt['nama_produk'] ?></b>
							<?php if($dt['warnaid'] || $dt['ukuranid']) {?>
							<br>
							<?php echo 'Warna  :'.$dtFungsi->fcaridata('_warna','warna','idwarna',$dt['warnaid']);?><br>
							<?php echo 'Ukuran :'.$dtFungsi->fcaridata('_ukuran','ukuran','idukuran',$dt['ukuranid']);?>
							<?php } ?>
						</td>
						<td class="text-right"><?php echo $dt['jml'] ?></td>
						<td class="text-right"><?php echo $harga_normal ?></td>
						<td class="text-right"><?php echo $persen_all_diskon ?> %</td>
						<td class="text-right"><?php echo $dtFungsi->fFormatuang($dt['harga']) ?></td>
						<td class="text-right"><?php echo $dtFungsi->fFormatuang(((int)$dt['jml']) * (int)$dt['harga']) ?></td>
					</tr>
			    <?php } ?>
					<tr>
						<td colspan="5" class="text-right"><b>Subtotal</b></td>
						<td class="text-right"><?php echo $dtFungsi->fFormatuang($dataorder['pesanan_subtotal']) ?></td>
					</tr>
					<tr>
						<td colspan="5" class="text-right"><b>Tarif Kurir (<?php echo $dataorder['kurir'] ?> - <?php echo $dataorder['servis_code'] ?>)</b></td>
						<td class="text-right"><?php echo $dataorder['kurir_konfirm'] == '1' && $dataorder['pesanan_kurir'] < 0 ? 'Konfirmasi Admin':$dtFungsi->fFormatuang($dataorder['pesanan_kurir']) ?></td>
					</tr>
					 <?php if($dataorder['dari_poin'] > 0) {?>
					<tr>
						<td colspan="5" class="text-right"><b>POTONGAN DARI POIN</b></td>
						<td class="text-right"><?php echo $dtFungsi->fFormatuang($dataorder['dari_poin']) ?></td>
					</tr>
					<?php } ?>
					
					<tr>
						<td colspan="5" class="text-right"><b>TOTAL</b></td>
						<td class="text-right"><b><?php echo $dataorder['kurir_konfirm'] == '1' && $dataorder['pesanan_kurir'] < 0 ? 'Konfirmasi Admin':$dtFungsi->fFormatuang(((int)$dataorder['pesanan_subtotal'] + (int)$dataorder['pesanan_kurir'])-(int)$dataorder['dari_poin']) ?></b></td>
					</tr>
				</tbody>
		    </table>
		</div>
	</div>
</div>