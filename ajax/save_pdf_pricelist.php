<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");

?>
<?
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

$http_host_temp = explode(":",$_SERVER['HTTP_HOST']);
$_SERVER['HTTP_HOST'] = $http_host_temp[0];

$type = $_GET['typelist'];
$my_city = $_GET['city'];

$pr_list_pdf = _get_pricelist_pdf($type,$my_city);
//$pr_list_pdf = "<h1>HELLO WORLD!</h1>";

set_include_path( get_include_path() . PATH_SEPARATOR . $_SERVER["DOCUMENT_ROOT"]."/include/dompdf");

require_once ($_SERVER["DOCUMENT_ROOT"] . "/include/dompdf/autoload.inc.php");

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->setIsRemoteEnabled(true);
$dompdf = new Dompdf($options);
$dompdf->setBasePath($_SERVER["DOCUMENT_ROOT"].'/bitrix/templates/print/');
$dompdf->loadHtml($pr_list_pdf);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$output = $dompdf->output();


$number_r = __random_number_order();

$dfileName = $_SERVER["DOCUMENT_ROOT"] . '/upload/' . $number_r . '-pricelist.pdf';
$downloadName = '../upload/' . $number_r . '-pricelist.pdf';

file_put_contents($downloadName, $output);

print $downloadName;
//print $my_city;


?>
