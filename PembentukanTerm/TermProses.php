<?php
include "TermClass.php";
$awal = microtime(true);
$TermProses = New TermClass();

var_dump($TermProses->merge_term());
var_dump($TermProses->lagi());

var_dump($TermProses->cut_term(12));

//echo microtime(true) - $awal;