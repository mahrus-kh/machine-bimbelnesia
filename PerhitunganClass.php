<?php
class PerhitunganClass
{
    private function koneksi()
    {
        try {
            $koneksi = New PDO ("mysql:host=localhost;dbname=machine_bimbelnesia","root","root");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return $koneksi;
    }
    public function rating()
    {
        $tampil = $this->koneksi()->prepare("SELECT id_rating FROM tb_rating");
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $rating[] =  $data['id_rating'];
            }
        }
        return $rating;
    }
    public function hitung_user_rating() //menghitung rata2 rating pada lbb (hitungan biasa)
    {
        $id_rating = $this->rating();
        for($i=0;$i<count($id_rating);$i++){
            $detail_rating = [];
            $rata_rating = 0;
            $jml = 0;
            $tampil = $this->koneksi()->prepare("SELECT detail_rating FROM tb_detail_rating WHERE id_rating=:id_rating");
            $tampil->bindParam(':id_rating',$id_rating[$i]);
            $tampil->execute();
            $tampil->setFetchMode(PDO::FETCH_ASSOC);
            if (count($tampil)>0){
                while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                    $detail_rating[] =  $data['detail_rating'];
                }
                for ($a=0;$a<count($detail_rating);$a++){
                    $jml = $jml + $detail_rating[$a];
                }
                $rata_rating = $jml / count($detail_rating);
            }
            $sql = "UPDATE tb_rating SET rata_rating=:rata_rating,jml_user_rating=:jml_user_rating WHERE id_rating=:id_rating";
            $simpan = $this->koneksi()->prepare($sql);
            $simpan->bindParam(':rata_rating',$rata_rating);
            $simpan->bindParam(':jml_user_rating',count($detail_rating));
            $simpan->bindParam(':id_rating',$id_rating[$i]);
            $simpan->execute();
        }
        return TRUE;
    }
    public function cepat()
    {
     $awal = microtime(true);
        for ($i=0;$i<1000000;$i++){
           // $halo[] = $i;
            echo $i . " ";
        }
        //var_dump($i);
        return microtime(true)-$awal;
    }
}