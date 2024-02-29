<div id="hasil"></div>
<div class="col-lg-12 main-content">
	<h2 class="judulmodul"><?php echo $judul ?></h2>
	<div><?php echo (isset($result["cek"]) ? $result["cek"] : '') ?></div>
	<?php if (!is_null($result)): ?>
		<?php if ($result["status"] == 'success'): ?>
			<div id="hasil" class="alert alert-success"><?php echo $result["message"] ?></div>
		<?php else: ?>
			<div id="hasil" class="alert alert-danger"><?php echo $result["message"] ?></div>
		<?php endif;?>
	<?php endif;?>
	<div class="row">
		<div class="col-md-8 bagian-frm-cari ">
			<div class="row">
				<form role="form-inline" method="post" action="<?php echo URL_PROGRAM_ADMIN . folder . '?u_token=' . $u_token ?>" id="frminput">
					<div class="col-md-4">
						<div class="input-group">
							<input type="text" class="form-control input-sm" name="order_id" value="<?= $id_order ?>" placeholder="ID Order">
							<span class="input-group-btn">
								<button class="btn btn-hijau btn-sm" name='submit' value="1" type="submit">input</button>
							</span>
						</div>
					</div>
					<div class="col-md-2">
						<span> </span>
					</div>
					<div class="col-md-6">
						<div id="msg-alert" class="" style="text-align:center;margin-bottom:0px;padding:5px 15px;"></div>
					</div>
				</form>
			</div>
		</div>
		<div class="col-md-2">
			<div class="input-group" style="float:right">
				<label id="ready-scan"></label>
			</div>
		</div>
		<div class="col-md-2">
			<input style="opacity:0;" id="id-scan" type="text" class="form-control input-sm" name="scan_id" value="" placeholder="ID Scan">
		</div>
	</div>

	<table class="table table-bordered table-striped table-hover tabel-grid">
		<thead>
			<tr>
				<td>No</td>
				<td>Produk</td>
				<td>Kode Produk</td>
				<td>Warna</td>
				<td>Ukuran</td>
				<td>Barcode</td>
				<td>Berat</td>
				<td>Jumlah Order</td>
				<td>Jumlah Packing</td>
				<td>Status</td>
			</tr>
		</thead>
		<tbody id="viewdata">
			<?php foreach ($ambildata as $datanya) {

				if($datanya["status_packing"]){
					$color = '#3c763d';
					$icon = 'glyphicon glyphicon-ok';
				}else{
					$color = '#a94442';
					$icon = 'glyphicon glyphicon-remove';
				}

				$status = '<span class="'.$icon.'" style="color:'.$color.'"></span>';

				// Get Barcode
				$option = $mdlProduk->getOption($datanya['produk_id'],$datanya['warnaid'],$datanya['ukuranid']);
				$barcode = isset($option['barcode']) ? $option['barcode'] : '-';
			?>
			
				<tr>
					<td><?php echo $b++ ?></td>
					<td><?php echo $datanya["nama_produk"] ?></td>
					<td><?php echo $datanya["kode_produk"] ?></td>
					<td><?php echo $datanya["warna"] ?></td>
					<td><?php echo $datanya["ukuran"] ?></td>
					<td><?php echo $barcode ?></td>
					<td><?php echo $datanya["berat"] ?> gr</td>
					<td><?php echo $datanya["jml"] ?></td>
					<td><?php echo $datanya["jml_packing"] ?></td>
					<td style="text-align:center;"><?php echo $status ?></td>
				</tr>
			<?php }?>
		</tbody>
	</table>
</div>

<script>
	var action = $('#frminput').prop("action");

	jQuery(document).ready(function() {
		$("#id-scan").on('focus',function(){
			$('#ready-scan').html('Ready Scan');
		});

		$("#id-scan").on('blur',function(){
			$('#ready-scan').html('Click to Scan');
		});

		$("#ready-scan").on('click',function(){
			$('#id-scan').focus();
		});

		$("#id-scan").focus();
		$("#id-scan").on('change', function() {
			scan(this);
			$("#id-scan").val("");
		});
	});

	function scan(val){
		var idscan = $(val).val();
		$.ajax({

			url: action,

			dataType: "json",

			data: {

				modul: 'scanproduk',

				pesanan : $('input[name=order_id]').val(),

				scan: idscan,

				stsload: 'load'

			},

			success: function(data) {
				if(data.status=='success'){
					$('#viewdata').html(data.data);

					$('#msg-alert').html(data.msg);
					$('#msg-alert').attr('class','alert alert-success');
				}else{
					$('#msg-alert').html(data.msg);
					$('#msg-alert').attr('class','alert alert-danger');
				}
			},

			error: function(e) {

				alert('Error: ' + e);

			}

		});
	}
</script>