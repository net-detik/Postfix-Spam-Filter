<?php
define("PLATFORM_ROOT", __DIR__ ."/");
error_reporting('none');
require_once(PLATFORM_ROOT.'spam.algoritma.php');

//maksimal jumlah toleransi trafik email dalam waktu yang sama
$config['ToleransiMax']=15;

$SA=new spamAlgoritma();
$mailq=$SA::findSpam($config);

#echo '<pre>'.print_r($mailq,true).'</pre>';
