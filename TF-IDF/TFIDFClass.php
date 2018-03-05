<?php

class TFIDFClass
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

    public function lbb() // mengambil lbb_id yang ada di tb_lbb => setiap lbb
    {
        $tampil = $this->koneksi()->prepare("SELECT id FROM tb_lbb");
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $lbb[] = $data['id'];
            }
        }
        return $lbb;
    }

    public function term_lbb() //mengambil term setiap lbb
    {
        $lbb_id = $this->lbb();
        for ($i=0;$i<count($lbb_id);$i++){
            $ambil = $this->koneksi()->prepare("SELECT term FROM tb_term WHERE lbb_id=:lbb_id");
            $ambil->bindParam(":lbb_id", $lbb_id[$i]);
            $ambil->execute();
            $ambil->setFetchMode(PDO::FETCH_ASSOC);
            if (count($ambil)>0){
                while ($data=$ambil->fetch(PDO::FETCH_ORI_NEXT)){
                    $term_lbb [$lbb_id[$i]] =  explode(",", $data['term']);
                }
            }
        }
        return $term_lbb;
    }

    public function term_aktivitas() //contoh sementara > selesaikan
    {
        $term = "bimbel,sd,smp,sma,stan,pns,forum,guru,malang,ujian,nasional"; //contoh dan harus bersih -> dari database -> sementara
        $term = explode(",", $term);

        return $term;
    }

    public function tabel_term_lbb()
    {
        $term = $this->term_aktivitas();
        $lbb_id = $this->lbb();
    }

    public function coba_in() //langsung hitung tf dalam satu waktu
    {
        $term_lbb = $this->term_lbb();
        $term = ["bimbel","dua","tujuh","tiga"];
        $count = 0 ;

        for ($i=0;$i<count($term);$i++){ // i adalah term
            for ($j=0;$j<=count($term_lbb);$j++){ // j adalah total lbb dimulai dari id ada
                for ($k=0;$k<count($term_lbb[$j]);$k++){
                    if ($term[$i] == $term_lbb[$j][$k]) {
                        $count = $count + 1;
                    }
                }
            }
        }
        return $count ;
    }

    public function coba_nah() //hitung tf dengan fungsu tf terpisah
    {
        $lbb_id = $this->lbb();
        $term_aktivitas = ["bimbel","dua","tujuh","tiga"];

        for ($i=0;$i<count($term_aktivitas);$i++){
            for ($j=0;$j<count($lbb_id);$j++){
                $return [$term_aktivitas[$i]] [$lbb_id[$j]] = $this->hitung_tf($term_aktivitas[$i],$lbb_id[$j]);
            }
        }

        return $return;
    }

    public function hitung_tf($term_aktivitas, $lbb_id)
    {
        $ambil = $this->koneksi()->prepare("SELECT term FROM tb_term WHERE lbb_id=:lbb_id");
        $ambil->bindParam(":lbb_id", $lbb_id);
        $ambil->execute();
        $ambil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($ambil)>0){
            while ($data=$ambil->fetch(PDO::FETCH_ORI_NEXT)){
                $term_lbb =  explode(",", $data['term']);
            }
        }

        $total = 0;
        for ($i=0;$i<count($term_lbb);$i++){ // cek berapa jumlah term_aktivitas_t yang ada di term_Lbb
            if ($term_aktivitas == $term_lbb[$i]){
                $total = $total + 1;
            }
        }
        return $total;
    }


    public function truncate_tb_term()
    {
        $del = $this->koneksi()->prepare("TRUNCATE TABLE tb_term");
        return $del->execute();
    }

}