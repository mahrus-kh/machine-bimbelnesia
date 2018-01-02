<?php
class StemmerClass
{
    private $text = ".Nama.& *Saya! Mahrus. Khomaini !!Mantap HAYO%";

    public function tokenizing()
    {
        $this->text = preg_replace("/[^a-z\ ]/"," ",strtolower($this->text));
        $this->text = array_values(array_filter(explode(" ",$this->text)));
        return $this->text;
    }
    
}