<?php
class LagiClass
{
    private function koneksi()
    {
        try {
            $koneksi = New PDO ("mysql:host=localhost;dbname=bimbelnesia","root","root");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        return $koneksi;
    }

    public function isi_slug()
    {
        $tampil = $this->koneksi()->prepare("SELECT id_lbb,lbb FROM tb_lbb");
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
            $id_lbb [] = $data['id_lbb'];
            $lbb [] = $data['lbb'];
        }

        for ($i=0;$i<count($id_lbb);$i++){
            $update = $this->koneksi()->prepare("UPDATE tb_lbb SET slug=:slug WHERE id_lbb=:id_lbb");
            $update->bindParam(':slug',$this->slug($lbb[$i]));
            $update->bindParam(':id_lbb',$id_lbb[$i]);
            $update->execute();
        }

    }


    public function slug($judul){
        $slug = preg_replace("/[^a-z0-9]/"," ",strtolower($judul));
        $slug = array_values(array_filter(explode(" ",$slug)));
        $slug = implode("-",$slug);
        return $slug;
    }

}