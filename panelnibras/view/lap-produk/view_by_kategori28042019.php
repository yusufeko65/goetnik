<div id="hasil"></div>
<div class="col-lg-12 main-content">
    <h2 class="judulmodul"><?php echo $judul ?></h2>
	<!--
    <div class="col-md-12 bagian-frm-cari ">
        <div class="row">
            <form role="form-inline" id="frmcari" name="frmcari">
                <div class="col-md-4">
                    <div class="row">
                        <select id="kategori" name="kategori" class="form-control">
                            <option value="0">Pilih Kategori</option>
                            <?php 
                            //print_r($kategories);
							//$data['kategories'] = [];
							
                            foreach ($kategories as $kat) {
                                if ($kat['kategori_spesial'] == '0') {

                                    if ($kat['children']) {

                                        foreach ($kat['children'] as $child) {
                                            $childs = $dtKategori->getListKategori($child['id']);

                                            if ($childs) {
                                                foreach ($childs as $ch) {
                                                    $options .= '<option value="' . $ch['kategori_id'] . '">' . $ch['kategori_nama'] . '</option>';
													$data['kategories'][] = array('id' => $ch['kategori_id'], 'nama' => $ch['kategori_nama']);
                                                }
                                            } else {
                                                $options .= '<option value="' . $child['id'] . '">' . $child['nama'] . '</option>';
												$data['kategories'][] = array('id' => $child['id'], 'nama' => $child['nama']);
                                            }
                                        }
                                    } else {

                                        $options .= '<option value="' . $kat['id'] . '">' . $kat['nama'] . '></option>';
										$data['kategories'][] = array('id' => $kat['id'], 'nama' => $kat['nama']);
                                    }
                                }
                            }
							
                            //echo $options;
                            ?>
                        </select>
                        <button type="button" id="btnsearc" class="btn btn-default">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
	-->
	<?php $kategori_old = []; ?>
	
	<?php foreach($data['kategories'] as $kat) { ?>
		<?php if(isset($dataview["{$kat['id']}"])) { ?>
		<?php $dataproduk = $dataview["{$kat['id']}"]; ?>
		<?php if(count($dataproduk)) { ?>
				<table class="table_multi_kolom">
		<?php if(!in_array($kat['id'],$kategori_old)) { ?>
		<?php array_push($kategori_old,$kat['id']); ?>
	
		<?php $dataukuran = $ukuranperkat["{$kat['id']}"]; ?>
					<thead>
						<tr>
							<th colspan="<?php echo count($dataukuran) + 2 ?>" class="text-center kolom-row-multi-group"><?php echo $kat['nama'] ?></th>
						</tr>
						<tr>
							<th rowspan="2" class="text-center" valign="middle" width="40%">Nama Produk</th>
							<th rowspan="2" class="text-center" valign="middle" width="30%">Warna</th>
							<th colspan="<?php echo count($dataukuran) ?>" class="text-center" valign="middle">Size</th>
						</tr>
				
						<tr>
							<?php foreach($dataukuran as $uk) { ?>
							<th class="text-center"><?php echo $uk['ukuran'] ?></th>
							<?php } ?>
						</tr>
					
					</thead>
					<tbody>
					<?php foreach($dataproduk as $prod) { ?>
						<tr>
							<td class="row"><?php echo $prod["nama_produk"] ?></td>
							<td class="row"><?php echo $prod["warna"] ?></td>
							<?php //echo "<pre>" ?>
							<?php //print_r($datastoks) ?>
							<?php foreach($dataukuran as $uk) { ?>
							<?php $ids = $prod['idproduk'] .':' . $prod['idwarna'] . ':' . $uk['idukuran'] ?>
							<td class="row text-center">
							<?php echo isset($datastoks["{$ids}"]) ? $datastoks["{$ids}"] : 0 ?>
							</td>
							<?php } ?>
							
						</tr>
					<?php } ?>
					</tbody>
		<?php } ?>
				</table>
		<?php } ?>
		<?php } ?>
	<?php } ?>
			
</div> 