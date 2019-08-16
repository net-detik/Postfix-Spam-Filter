<?php
define("PLATFORM_ROOT", __DIR__ ."/");
error_reporting('none');
require_once(PLATFORM_ROOT.'spam.algoritma.php');

$SA=new spamAlgoritma();
$mailq=$SA::findSpam();

#echo '<pre>'.print_r($mailq,true).'</pre>';
