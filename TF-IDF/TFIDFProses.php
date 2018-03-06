<?php

include "TFIDFClass.php";

$TFIDFProses = New TFIDFClass();
$awal = microtime(true);

//var_dump($TFIDFProses->tabel_term_lbb());

//var_dump($TFIDFProses->term_lbb());

var_dump($TFIDFProses->tabel_tf_idf());

//$table_tf = $TFIDFProses->tabel_tf();
//
//foreach ($table_tf as $row => $r){
//    echo $r . " : ";
//}