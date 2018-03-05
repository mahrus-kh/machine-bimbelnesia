<?php
include "ItemBasedClass.php";
$awal = microtime(true);

$ItemBasedProses = New ItemBasedClass();

$ItemBasedProses->truncate_tb_itembased_sim();

$ItemBasedProses->similiarity_save();

var_dump($ItemBasedProses->prediksi());


//var_dump($ItemBasedProses->sim_atas(1,7));

//var_dump($ItemBasedProses->sim_bawah(1,7));
//
//var_dump($ItemBasedProses->similiarity_lbb(1,7));
