<?php
$awal = microtime(true);
$data = array(
    "satu" => "SATU",
    "dua" => "DUA"
);
var_dump($data);
echo microtime(true)-$awal;