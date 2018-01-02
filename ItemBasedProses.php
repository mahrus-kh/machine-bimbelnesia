<?php
include "ItemBasedClass.php";
$awal = microtime(true);

$ItemBasedProses = New ItemBasedClass();

//for ($i=0;$i<count($ItemBasedProses->user());$i++){
//
//    $id_user = $ItemBasedProses->user();
//    echo $id_user[$i] . " : " . $ItemBasedProses->rata_rating_user($id_user[$i]) . " | ";
//
//}

//var_dump($ItemBasedProses->sim_atas());
//echo "  |  ";
//var_dump($ItemBasedProses->sim_bawah());
//echo "  |  ";
$ItemBasedProses->truncate_tb_itembase_sim();
$ItemBasedProses->similiarity_save();
var_dump($ItemBasedProses->prediksi());

echo " ";
echo "    " . microtime(true)-$awal;

