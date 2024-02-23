<?php
//session_start();
define("path_toincludes", "../../_includes/");
define("folder", "home");
include "../../../includes/config.php";
include "../../autoloader.php";
$dtFungsi = new FungsiUmum();
$u_token = isset($_SESSION['u_token']) ? $_SESSION['u_token'] : '';
$cekToken = $dtFungsi->cekTokenValid2();

if (!$cekToken) {
	session_destroy();
	echo "<script>window.location='" . URL_PROGRAM_ADMIN . "'</script>";
	exit;
}

$stsload = isset($_REQUEST['stsload']) ? $_REQUEST['stsload'] : '';
if ($stsload != "load") {
	require_once(DIR_INCLUDE . "header.php");
	require_once(DIR_INCLUDE . "menu.php");
}

//$dtFungsi = new FungsiUmum();
$tabelcust = "_customer";
$fieldcust = "count(cust_id) as total";
$wherecust = "cust_status='1'";
$datacust = $dtFungsi->fcaridata2($tabelcust, $fieldcust, $wherecust);

$tabelset = "_setting";
$fieldset = "setting_value";
$whereset1 = "setting_key";
$whereset2 = "config_orderstatus";
$dataset = $dtFungsi->fcaridata($tabelset, $fieldset, $whereset1, $whereset2);

$tabelorder = "_order";
$fieldorder = "count(pesanan_no) as total";
$whereorder = "status_id='$dataset'";
$dataorder  = $dtFungsi->fcaridata2($tabelorder, $fieldorder, $whereorder);

$tabellastorder = "_order INNER JOIN _status_order ON _order.status_id = _status_order.status_id 
			       INNER JOIN _customer ON _order.pelanggan_id = _customer.cust_id";
$fieldlastorder = "pesanan_no,cust_nama,pesanan_jml,pesanan_subtotal,pesanan_kurir,
	               pesanan_tgl";
$wherelastorder = "_order.status_id='" . $dataset . "' ORDER BY pesanan_tgl DESC LIMIT 5";
$datalastorder  = $dtFungsi->fcaridata3($tabellastorder, $fieldlastorder, $wherelastorder);

$tabelorderlunaspending = "_order INNER JOIN _status_order ON _order.status_id = _status_order.status_id 
			               INNER JOIN _customer ON _order.pelanggan_id = _customer.cust_id";
$fieldorderlunaspending = "pesanan_no,cust_nama,pesanan_jml,pesanan_subtotal,pesanan_kurir,pesanan_tgl";
$whereorderlunaspending = "_order.status_id='" . $dataset . "' AND ((pesanan_subtotal + pesanan_kurir) - dari_poin - dari_deposito) = 0 ORDER BY pesanan_tgl DESC";
$dataorderlunaspending  = $dtFungsi->fcaridata3($tabelorderlunaspending, $fieldorderlunaspending, $whereorderlunaspending);
print_r($datacust);
?>
<div class="col-lg-12 main-content">
	<h2 class="judulmodul">Selamat Datang, <?php echo $_SESSION['userlogin'] ?></h2>

	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-12">
			<!-- plat pelanggan -->
			<div class="tile">
				<div class="tile-heading"><i class="glyphicon glyphicon-user"></i> Pelanggan</div>
				<div class="tile-body"><i class="glyphicon glyphicon-user"></i>
					<h2 class="pull-right"><?php echo $datacust['total'] ?></h2>
				</div>
				<div class="tile-footer"><a href="<?php echo URL_PROGRAM_ADMIN . 'customer?u_token=' . $u_token ?>" class="link">Selengkapnya</a></div>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12">
			<!-- plat order -->
			<div class="tile">
				<div class="tile-heading"><i class="glyphicon glyphicon-shopping-cart"></i> Jumlah Pesanan Terbaru</div>
				<div class="tile-body"><i class="glyphicon glyphicon-shopping-cart"></i>
					<h2 class="pull-right"><?php echo $dataorder['total'] ?></h2>
				</div>
				<div class="tile-footer"><a href="<?php echo URL_PROGRAM_ADMIN . 'order?u_token=' . $u_token ?>" class="link">Selengkapnya</a></div>
			</div>
		</div>
		<?php if ($dataorderlunaspending) { ?>
			<div class="col-md-12">
				<div class="tile">
					<div class="tile-heading"><i class="glyphicon glyphicon-user"></i> Pesanan Yang Harus Diproses</div>
					<div class="tile-body">
						<table class="table table-bordered">
							<thead>
								<tr>
									<td>No. Pesanan</td>
									<td>Pelanggan</td>
									<td class="text-right">QTY</td>
									<td class="text-right">Kurir</td>
									<td class="text-right">Subtotal</td>
									<td class="text-center">Tanggal</td>
									<td></td>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($dataorderlunaspending as $ords) { ?>
									<tr>
										<td><?php echo sprintf('%05s', $ords['pesanan_no']) ?></td>
										<td><?php echo $ords['cust_nama'] ?></td>
										<td class="text-right"><?php echo $ords['pesanan_jml'] ?></td>
										<td class="text-right"><?php echo $dtFungsi->fFormatuang($ords['pesanan_kurir']) ?></td>
										<td class="text-right"><?php echo $dtFungsi->fFormatuang($ords['pesanan_subtotal']) ?></td>
										<td class="text-center"><?php echo $ords['pesanan_tgl'] ?></td>
										<td class="text-center"><a href="<?php echo URL_PROGRAM_ADMIN . 'order?op=info&pid=' . $ords['pesanan_no'] . '&u_token=' . $u_token ?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
									</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<div class="tile-footer"><a href="<?php echo URL_PROGRAM_ADMIN . 'order?u_token=' . $u_token ?>" class="link">Selengkapnya</a></div>
				</div>
			</div>
		<?php } ?>
		<div class="col-md-12">
			<div class="tile">
				<div class="tile-heading"><i class="glyphicon glyphicon-user"></i> Pesanan Terbaru</div>
				<div class="tile-body">
					<table class="table table-bordered">
						<thead>
							<tr>
								<td>No. Pesanan</td>
								<td>Pelanggan</td>
								<td class="text-right">QTY</td>
								<td class="text-right">Kurir</td>
								<td class="text-right">Subtotal</td>
								<td class="text-center">Tanggal</td>
								<td></td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($datalastorder as $ord) { ?>
								<tr>
									<td><?php echo sprintf('%05s', $ord['pesanan_no']) ?></td>
									<td><?php echo $ord['cust_nama'] ?></td>
									<td class="text-right"><?php echo $ord['pesanan_jml'] ?></td>
									<td class="text-right"><?php echo $dtFungsi->fFormatuang($ord['pesanan_kurir']) ?></td>
									<td class="text-right"><?php echo $dtFungsi->fFormatuang($ord['pesanan_subtotal']) ?></td>
									<td class="text-center"><?php echo $ord['pesanan_tgl'] ?></td>
									<td class="text-center"><a href="<?php echo URL_PROGRAM_ADMIN . 'order?op=info&pid=' . $ord['pesanan_no'] . '&u_token=' . $u_token ?>" class="btn btn-sm btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="tile-footer"><a href="<?php echo URL_PROGRAM_ADMIN . 'order?u_token=' . $u_token ?>" class="link">Selengkapnya</a></div>
			</div>
		</div>

	</div>
</div>

<?php
if ($stsload != "load") require_once(DIR_INCLUDE . "footer.php");
?>