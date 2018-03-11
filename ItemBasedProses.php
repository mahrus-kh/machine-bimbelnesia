<?php
include "ItemBasedClass.php";

$ItemBasedProses = New ItemBasedClass();

//$ItemBasedProses->truncate_tb_itembased_sim();

//$ItemBasedProses->similiarity_save();

//var_dump($ItemBasedProses->prediksi());

$awal = microtime(true);
var_dump($ItemBasedProses->similiarity());
$cara1 = microtime(true) - $awal;

$awal = microtime(true);
var_dump($ItemBasedProses->hitung_similariy());
$cara2 = microtime(true) - $awal;

echo "Cara 1 : " . $cara1  . " | " . "Cara 2 : " . $cara2;

//var_dump($ItemBasedProses->sim_atas(1,7));

//var_dump($ItemBasedProses->sim_bawah(1,7));
//
//var_dump($ItemBasedProses->similiarity_lbb(1,7));