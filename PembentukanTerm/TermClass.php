<?php
class TermClass
{
    private $term1 = ["malang","sma","smk","smp","ujian","nasional","neutron","bimbel","lbb"];
    private $term2 = ["bimbel","malang","lulus","universitas","gold","generation","sma","smp","sd","guru","favorit"];

    public function merge_term()
    {
        $term_atas = [];
        $term_bawah = [];

        for ($i=0;$i<count($this->term2);$i++){
            if (in_array($this->term2[$i], $this->term1)){
                $term_atas [] = $this->term2[$i];
            } else {
                $term_bawah [] = $this->term2[$i];
            }
        }

        for ($i=0;$i<count($this->term1);$i++){
            if (!in_array($this->term1[$i], $this->term2)){
                $term_bawah [] = $this->term1[$i];
            }
        }

        $merge_term = array_merge($term_atas,$term_bawah);

        return $merge_term;
    }

    public function lagi()
    {
        $term_lama = $this->merge_term();
        $term_baru  = ["k"];

        $term_atas = [];
        $term_bawah = [];

        for ($i=0;$i<count($term_baru);$i++){
            if (in_array($term_baru[$i], $term_lama)){
                $term_atas [] = $term_baru[$i];
            } else {
                $term_bawah [] = $term_baru[$i];
            }
        }

        for ($i=0;$i<count($term_lama);$i++){
            if (!in_array($term_lama[$i], $term_baru)){
                $term_bawah [] =$term_lama[$i];
            }
        }

        $merge_term = array_merge($term_atas,$term_bawah);

        return $merge_term;

    }

    public function cut_term($cut)
    {
        $term = $this->lagi();

        while (count($term)>$cut){
            array_pop($term);
        }

        return $term;
    }

}