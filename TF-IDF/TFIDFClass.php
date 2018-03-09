s<?php

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

    public function lbb() // mengambil id lbb yang ada di tb_lbb => setiap lbb
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

    public function coba_nah() //hitung tf dengan fungsu tf terpisah //percobaan logika
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

    public function hitung_tf($term_aktivitas, $lbb_id) // hitung tf dari term_aktivitas terhadapt term di lbb_id
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

        $hitung_tf= 0;
        for ($i=0;$i<count($term_lbb);$i++){ // cek berapa jumlah term_aktivitas_t yang ada di term_Lbb
            if ($term_aktivitas == $term_lbb[$i]){
                $hitung_tf = $hitung_tf + 1;
            }
        }
        return $hitung_tf;
    }

    public function tabel_tf() //membuat tabel tf setiap term_aktivitas terhadap term setiap lbb
    {
        $lbb_id = $this->lbb();
        $term_aktivitas = ["bimbel","malang","ujian","lulus","mahrus"];

        for ($i=0;$i<count($term_aktivitas);$i++){
            for ($j=0;$j<count($lbb_id);$j++){
                $tabel_tf [$term_aktivitas[$i]] [$lbb_id[$j]] = $this->hitung_tf($term_aktivitas[$i],$lbb_id[$j]);
            }
        }
        return $tabel_tf;
    }

    public function tabel_df() //menghitung df setiap term_aktivitas
    {
        $tabel_tf = $this->tabel_tf();
//
        foreach ($tabel_tf as $term => $row ){ //$term = term_aktivitas, $row = array nilai tf
            $hitung_df = 0;
            foreach ($row as $tf) {
                if ($tf > 0){ // tf > 0 berarti ada pada lbb tersebut
                    $hitung_df = $hitung_df + 1;
                }
            }
            $tabel_df [$term] = $hitung_df;
        }

    return $tabel_df;
    }

    public function tabel_idf()
    {
        $tabel_df = $this->tabel_df();
        foreach ($tabel_df as $term => $df) { //$term = term_aktivitas, $df = nilai df setiap term_aktivitas
            if ($df > 0) { // cek apakah df lebih dari 0, atau term_aktifitas (df) ada pada setidaknya satu lbb
                $tabel_idf [$term] = 1 / $df;
            }
        }

        return $tabel_idf;
    }

    public function tabel_tf_idf() //catatan : tabel tf_idf harus sudah bersih dari term_aktivitas yang kosong sama sekali
    {
        $tabel_tf = $this->tabel_tf();
        $tabel_idf = $this->tabel_idf();

        foreach ($tabel_tf as $key => $row){ //$key = term_aktvitas, $row = array setiap term_aktvitas (tf setiap term_aktivitas terhadap lbb)
            foreach ($row as $id_lbb => $tf) { //$id_lbb = id_lbb yang ada tf nya, $tf = nilai tf setiap $lbb
                if (isset($tabel_idf[$key])){ //cek jika term_aktifitas ada / tidak bernilai 0 maka akan dibentuk tabel tf idf nya
                    $tabel_tf_idf [$key] [$id_lbb] = $tf * $tabel_idf[$key];
                }
            }
        }

        return $tabel_tf_idf;
    }

    public function tabel_tf_idf_balik() //catatan : tabel tf_idf harus sudah bersih dari term_aktivitas yang kosong sama sekali
    {
        $tabel_tf = $this->tabel_tf();
        $tabel_idf = $this->tabel_idf();

        foreach ($tabel_tf as $key => $row){ //$key = term_aktvitas, $row = array setiap term_aktvitas (tf setiap term_aktivitas terhadap lbb)
            foreach ($row as $id_lbb => $tf) { //$id_lbb = id_lbb yang ada tf nya, $tf = nilai tf setiap $lbb
                if (isset($tabel_idf[$key])){
                    $tabel_tf_idf [$id_lbb] [$key] = $tf * $tabel_idf[$key];
                }
            }
        }

        return $tabel_tf_idf;
    } //versi transpose

    public function rata_term() //menghitung nilai rata2 term dimana nilai term > 0
    {
        $tabel_tf_idf = $this->tabel_tf_idf(); //tf_idf sudah bersih

        foreach ($tabel_tf_idf as $term => $row){ //$term = term_aktivitas, $row = array nilai tf.idf setiap lbb
            $rata = [];
            foreach ($row as $tf){
               if ($tf > 0){
                   $rata [] = $tf;
               }
            }
            $rata_term [$term] = array_sum($rata) / count($rata);
        }

        return $rata_term;
    }
    
    //baca ini : similariy hanya pada lbb yang sedang dilihat

    public function similarity($id_lbb_aktif = 3) // $id_lbb_aktif adalah lbb yang sedang dilihat dan dicari nilai kemiripan terhapat lbb lain
    {
       $tabel_tf_idf_balik = $this->tabel_tf_idf_balik();
       $rata_term = $this->rata_term();
       $similarity = [];


       foreach ($tabel_tf_idf_balik as $id_lbb => $row){
           $sim_atas = 0;
           $bawah1 = 0;
           $bawah2 = 0;
           $sim_bawah = 0;

           if ($id_lbb == $id_lbb_aktif){ // $id_lbb_aktif adalah LBB yang dicari kemiripannya dengan LBB lain
               continue;
           }

           foreach ($row as $term_aktivitas => $tf_idf){
//
               if ($tf_idf == 0 || $tabel_tf_idf_balik[$id_lbb_aktif][$term_aktivitas] == 0){
                   continue;
               }

               $sim_atas = $sim_atas + (($tf_idf-$rata_term[$term_aktivitas])*($tabel_tf_idf_balik[$id_lbb_aktif][$term_aktivitas]-$rata_term[$term_aktivitas]));
               $bawah1 = $bawah1 + (pow($tf_idf-$rata_term[$term_aktivitas],2));
               $bawah2 = $bawah2 + (pow($tabel_tf_idf_balik[$id_lbb_aktif][$term_aktivitas]-$rata_term[$term_aktivitas], 2));
               $sim_bawah = sqrt($bawah1)*sqrt($bawah2);
           }
////
           if ($sim_bawah == 0){ //similarity bawah bernilai 0 terjadi karena tidak ada bisa dicari nilai kemiripan lbb aktif dengan lbb n lainnya
               continue;
           }

           $similarity [$id_lbb] = $sim_atas / $sim_bawah;
       }
//       arsort($similarity);
       return $similarity;
    }
    
    //tambahan hapus tabel
    public function truncate_tb_term()
    {
        $del = $this->koneksi()->prepare("TRUNCATE TABLE tb_term");
        return $del->execute();
    }

}