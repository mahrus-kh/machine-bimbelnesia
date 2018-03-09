<?php

include "TFIDFClass.php";

$TFIDFProses = New TFIDFClass();
$awal = microtime(true);

//var_dump($TFIDFProses->tabel_term_lbb());

//var_dump($TFIDFProses->term_lbb());

//var_dump($TFIDFProses->bermain());

//var_dump($TFIDFProses->tabel_df());
//var_dump($TFIDFProses->tabel_idf());
var_dump($TFIDFProses->tabel_tf_idf());
var_dump($TFIDFProses->rata_term());

var_dump($TFIDFProses->similarity());



//echo microtime(true) - $awal;
//
//foreach ($table_tf as $row => $r){
//    echo $r . " : ";
//}