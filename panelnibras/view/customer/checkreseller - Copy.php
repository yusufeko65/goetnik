<?php
include "../../includes/config.php";

include "../../controller/controlReseller.php";
$dtReseller = new controllerReseller();

include DIR_INCLUDE."controller/controlSettingToko.php";
$dtSettingToko = new controllerSettingToko();

$datasetting= $dtSettingToko->getSettingToko();
$masa_approve = $datasetting['masa_approve'];

//var_dump(is_dir('/home/sloki/user/k2041548/sites/hijabsupplier.com/www/controlarea/view/reseller/'));

$dtReseller->ResellerEksekusi($masa_approve);

?>