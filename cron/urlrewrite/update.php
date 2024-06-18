<?php
$urlrewrite = $_SERVER['DOCUMENT_ROOT'].'/urlrewrite.php';
$correct = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/cron/urlrewrite/urlrewrite.php');
$file = file_get_contents($urlrewrite);

if ($correct <> $file) {
	file_put_contents($urlrewrite, $correct);
}
?>

