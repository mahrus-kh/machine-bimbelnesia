<?php
class ItemBasedClass
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

    public function user() //mengambil user_id yang ada di tb_rating
    {
        $tampil = $this->koneksi()->prepare("SELECT DISTINCT(user_id)FROM tb_rating");
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $user[] =  $data['user_id'];
            }
        }
        return $user;
    }
    public function lbb() // mengambil lbb_id yang ada di tb_rating
    {
        $tampil = $this->koneksi()->prepare("SELECT DISTINCT(lbb_id) FROM tb_rating");
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $lbb[] = $data['lbb_id'];
            }
        }
        return $lbb;
    }
    public function rata_rating_user($user_id)// menghitung rata2 rating user terhadap semua lbb yang ada di tb_rating
    {
        $tampil = $this->koneksi()->prepare("SELECT rating FROM tb_rating WHERE user_id=:user_id");
        $tampil->bindParam(':user_id',$user_id);
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
               $rata[] = $data['rating'];
            }
            $rata_rating_user = 0;
            for ($i=0;$i<count($rata);$i++){
                $rata_rating_user = $rata_rating_user + $rata[$i];
            }
            $rata_rating_user = $rata_rating_user / count($rata);
        }
        return $rata_rating_user;
    }

    public function coba_ambil_one() //mengambil nilai rating dari user = nilai rating lbb1 DAN user = nilai rating lbb2 / yang sama
    {
        $rating_user_lbb1 = [];
        $rating_user_lbb2 = [];
        $lbb1  = [];
        $lbb2  = [];
        $user_merating = [];
        $return = [];

        $user_id = $this->user();
        $lbb_id = $this->lbb();

        for($i=0;$i<count($user_id);$i++){
            for ($a=0;$a<1;$a++){
                $nilai_lbb1 = 0;
                $nilai_lbb2 = 0;

                $tampil = $this->koneksi()->prepare("SELECT rating FROM tb_rating WHERE user_id=:user_id AND lbb_id=1");
                $tampil->bindParam(':user_id',$user_id[$i]);
                $tampil->execute();
                $tampil->setFetchMode(PDO::FETCH_ASSOC);
                if (count($tampil)>0){
                    while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                        $nilai_lbb1 =  $data['rating'];

                    }
                }
                $tampil = $this->koneksi()->prepare("SELECT rating FROM tb_rating WHERE user_id=:user_id AND lbb_id=2");
                $tampil->bindParam(':user_id',$user_id[$i]);
                $tampil->execute();
                $tampil->setFetchMode(PDO::FETCH_ASSOC);
                if (count($tampil)>0){
                    while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                        $nilai_lbb2 =  $data['rating'];
                    }
                }
                if (!empty($nilai_lbb1) && !empty($nilai_lbb2)){
                    $lbb1 [] = $nilai_lbb1;
                    $lbb2 [] = $nilai_lbb2;
                    $user_merating [] = $user_id[$i];
                }
            }
        }
        array_push($return, $lbb1);
        array_push($return, $lbb2);
        array_push($return, $user_merating);

        return $return;
    }
    public function coba_ambil($lbb_id1, $lbb_id2) //mengambil nilai rating dari user = nilai rating lbb1 DAN user = nilai rating lbb2 / yang sama
    {
        $rating_user_lbb1 = [];
        $rating_user_lbb2 = [];
        $lbb1  = [];
        $lbb2  = [];
        $user_merating = [];
        $return = [];

        $user_id = $this->user();

        for($i=0;$i<count($user_id);$i++){
            for ($a=0;$a<1;$a++){
                $nilai_lbb1 = 0;
                $nilai_lbb2 = 0;

                $tampil = $this->koneksi()->prepare("SELECT rating FROM tb_rating WHERE user_id=:user_id AND lbb_id=:lbb_id1");
                $tampil->bindParam(':user_id',$user_id[$i]);
                $tampil->bindParam('lbb_id1',$lbb_id1);
                $tampil->execute();
                $tampil->setFetchMode(PDO::FETCH_ASSOC);
                if (count($tampil)>0){
                    while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                        $nilai_lbb1 =  $data['rating'];

                    }
                }
                $tampil = $this->koneksi()->prepare("SELECT rating FROM tb_rating WHERE user_id=:user_id AND lbb_id=:lbb_id2");
                $tampil->bindParam(':user_id',$user_id[$i]);
                $tampil->bindParam(':lbb_id2',$lbb_id2);
                $tampil->execute();
                $tampil->setFetchMode(PDO::FETCH_ASSOC);
                if (count($tampil)>0){
                    while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                        $nilai_lbb2 =  $data['rating'];
                    }
                }
                if (!empty($nilai_lbb1) && !empty($nilai_lbb2)){
                    $lbb1 [] = $nilai_lbb1;
                    $lbb2 [] = $nilai_lbb2;
                    $user_merating [] = $user_id[$i];
                }
            }
        }
        array_push($return, $lbb1);
        array_push($return, $lbb2);
        array_push($return, $user_merating);

        return $return;
    }
    public function sim_atas($lbb_id1, $lbb_id2) // menghitung nilai rumus atas similiarity
    {
        $data = $this->coba_ambil($lbb_id1, $lbb_id2);
        $rating_lbb1 = $data[0];
        $rating_lbb2 = $data[1];
        $user_merating = $data [2];

        $sim_atas = 0;
        for ($i=0;$i<count($user_merating);$i++){ //hitung nilai diulang sebanyak yang sama -> rating_lbb1 / rating_lbb1
            $sim_atas = $sim_atas + (($rating_lbb1[$i]-$this->rata_rating_user($user_merating[$i]))*($rating_lbb2[$i]-$this->rata_rating_user($user_merating[$i])));
        }
        return $sim_atas;
    }

    public function sim_bawah($lbb_id1, $lbb_id2)
    {
        $data = $this->coba_ambil($lbb_id1, $lbb_id2);
        $rating_lbb1 = $data[0];
        $rating_lbb2 = $data[1];
        $user_merating = $data[2];

        if (empty($user_merating)){
            $sim_bawah = null;
        } else {
            $bawah1 =0;
            $bawah2 = 0;
            for ($i=0;$i<count($user_merating);$i++){
                $bawah1 = $bawah1 + (pow(($rating_lbb1[$i]-$this->rata_rating_user($user_merating[$i])),2));
                $bawah2 = $bawah2 + (pow(($rating_lbb2[$i]-$this->rata_rating_user($user_merating[$i])),2));
            }
            $sim_bawah = sqrt($bawah1)*sqrt($bawah2);
        }
        return  $sim_bawah;
    }
    public function similiarity()
    {
        $similiarity = [];
        $lbb_id  = $this->lbb();
        for ($i=0;$i<count($lbb_id);$i++){
            for ($j=$i;$j<count($lbb_id);$j++){
                if ($i == $j){
                    continue;
                }

                $sim_bawah = $this->sim_bawah($lbb_id[$i],$lbb_id[$j]);
                if ($sim_bawah === null){
                    continue;
                }else {
                    $sim_atas = $this->sim_atas($lbb_id[$i],$lbb_id[$j]);
                    $similiarity [] = $sim_atas / $sim_bawah;
                }
            }
        }
        return $similiarity;
    }
    public function similiarity_save()
    {
        $lbb_id  = $this->lbb();
        for ($i=0;$i<count($lbb_id);$i++){
            for ($j=$i;$j<count($lbb_id);$j++){

                if ($i == $j){
                    continue;
                }

                $sim_bawah = $this->sim_bawah($lbb_id[$i],$lbb_id[$j]);
                if ($sim_bawah == null){
                    continue;
                }else {
                    $sim_atas = $this->sim_atas($lbb_id[$i],$lbb_id[$j]);
                    $similiarity = $sim_atas / $sim_bawah;

                    $save = $this->koneksi()->prepare("INSERT INTO tb_itembased_sim (lbb1_id,lbb2_id,similiarity) VALUES (:lbb1_id,:lbb2_id,:similiarity)");
                    $save->bindParam(':lbb1_id',$lbb_id[$i]);
                    $save->bindParam(':lbb2_id',$lbb_id[$j]);
                    $save->bindParam(':similiarity',$similiarity);
                    $save->execute();
                }
            }
        }
        return TRUE;
    }

    public function lbb_id_kosong($user_id)
    {
        $lbb_id_kosong = [];
        $lbb_id = $this->lbb();
        for ($i=0;$i<count($lbb_id);$i++){
            $kosong = 0;
            $tampil = $this->koneksi()->prepare("SELECT id FROM tb_rating WHERE user_id=:user_id AND lbb_id =:lbb_id");
            $tampil->bindParam(':user_id',$user_id);
            $tampil->bindParam(':lbb_id',$lbb_id[$i]);
            $tampil->execute();
            $tampil->setFetchMode(PDO::FETCH_ASSOC);
            if (count($tampil)>0){
                while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                    $kosong =  $lbb_id[$i];
                }
            }
            if ($kosong == 0){
                $lbb_id_kosong [] = $lbb_id[$i];
            }
        }
        return $lbb_id_kosong;
    }
    public function rating_user_lbb($user_id, $lbb_id){ //mengambil nulai rating user_i terhadap lbb j
        $tampil = $this->koneksi()->prepare("SELECT rating FROM tb_rating WHERE user_id=:user_id AND lbb_id=:lbb_id");
        $tampil->bindParam(':user_id',$user_id);
        $tampil->bindParam(':lbb_id',$lbb_id);
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $rating_user_lbb =  $data['rating'];
            }
        }
        return $rating_user_lbb;
    }
    public function similiarity_lbb($lbb_id_kosong , $lbb_idi) //mengambil similiarity antar lbb
    {
        $sql = "SELECT similiarity FROM tb_itembased_sim WHERE lbb1_id=:lbb_id_kosong AND lbb2_id=:lbb_idi OR lbb1_id=:lbb_idi AND lbb2_id=:lbb_id_kosong";
        $tampil = $this->koneksi()->prepare($sql);
        $tampil->bindParam(':lbb_id_kosong',$lbb_id_kosong);
        $tampil->bindParam(':lbb_idi',$lbb_idi);
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $similiarity_lbb =  $data['similiarity'];
            }
        }
        return $similiarity_lbb;
    }
    public function pre_atas($user_id, $lbb_id_kosong)
    {
        $lbb_id = [];
        $tampil = $this->koneksi()->prepare("SELECT lbb_id FROM tb_rating WHERE user_id=:user_id");
        $tampil->bindParam(':user_id',$user_id);
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $lbb_id [] =  $data['lbb_id'];
            }
        }

        $pre_atas = 0;
        for ($i=0;$i<count($lbb_id);$i++) {
            $pre_atas = $pre_atas + ($this->rating_user_lbb($user_id,$lbb_id[$i])*$this->similiarity_lbb($lbb_id_kosong,$lbb_id[$i]));
        }
        return $pre_atas;
    }
    public function pre_bawah($user_id,$lbb_id_kosong)
    {

        $lbb_id = [];
        $tampil = $this->koneksi()->prepare("SELECT lbb_id FROM tb_rating WHERE user_id=:user_id");
        $tampil->bindParam(':user_id',$user_id);
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $lbb_id [] =  $data['lbb_id'];
            }
        }

        $pre_bawah = 0;
        for ($i=0;$i<count($lbb_id);$i++){
            $pre_bawah = $pre_bawah + abs($this->similiarity_lbb($lbb_id_kosong,$lbb_id[$i]));
        }
        return $pre_bawah;
    }
    public function prediksi()
    {
       $prediksi = [];
       $user_id = $this->user();

       for ($i=0;$i<count($user_id);$i++){
           $lbb_id_kosong = 0;
           $lbb_id_kosong = $this->lbb_id_kosong($user_id[$i]);
           for ($j=0;$j<count($lbb_id_kosong);$j++){
               $prediksi [] = "User " . $user_id[$i] . " - LBB " . $lbb_id_kosong[$j] . " = " .  $this->pre_atas($user_id[$i],$lbb_id_kosong[$j]) / $this->pre_bawah($user_id[$i],$lbb_id_kosong[$j]);
           }
       }
       return $prediksi;

    }
    
    //Tambahan
    public function truncate_tb_itembased_sim()
    {
        $del = $this->koneksi()->prepare("TRUNCATE TABLE tb_itembased_sim");
        return $del->execute();
    }

    //versi full array => berikut dibawah

    public function tabel_data()
    {
        $tampil = $this->koneksi()->prepare("SELECT id FROM tb_users");
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $user_id_array [] =  $data['id'];
            }
        }

        $tampil = $this->koneksi()->prepare("SELECT id FROM tb_lbb");
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $lbb_id_array [] =  $data['id'];
            }
        }

        foreach ($user_id_array as $user_id){
            foreach ($lbb_id_array as $lbb_id){
                $rating = "0";
                $tampil = $this->koneksi()->prepare("SELECT rating FROM tb_rating WHERE user_id=:user_id AND lbb_id=:lbb_id");
                $tampil->bindParam(':user_id', $user_id);
                $tampil->bindParam(':lbb_id', $lbb_id);
                $tampil->execute();
                $tampil->setFetchMode(PDO::FETCH_ASSOC);
                if (count($tampil)>0){
                    while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                       $rating = $data['rating'];
                    }
                }
                $tabel_data [$user_id] [$lbb_id] = $rating;
            }
        }

        return $tabel_data;
    }

    public function tabel_data_balik() //versi transpose
    {
        $tampil = $this->koneksi()->prepare("SELECT id FROM tb_users");
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $user_id_array [] =  $data['id'];
            }
        }

        $tampil = $this->koneksi()->prepare("SELECT id FROM tb_lbb");
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $lbb_id_array [] =  $data['id'];
            }
        }

        foreach ($user_id_array as $user_id){
            foreach ($lbb_id_array as $lbb_id){
                $rating = "0";
                $tampil = $this->koneksi()->prepare("SELECT rating FROM tb_rating WHERE user_id=:user_id AND lbb_id=:lbb_id");
                $tampil->bindParam(':user_id', $user_id);
                $tampil->bindParam(':lbb_id', $lbb_id);
                $tampil->execute();
                $tampil->setFetchMode(PDO::FETCH_ASSOC);
                if (count($tampil)>0){
                    while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                        $rating = $data['rating'];
                    }
                }
                $tabel_data [$lbb_id] [$user_id] = $rating;
            }
        }
        return $tabel_data;
    }

    public function rata_user() //menghitung rata-rata rating user terhadap setiap lbb yang di rating
    {
        $tabel_data = $this->tabel_data();

        foreach ($tabel_data as $id_user => $lbb_array){
            $rata = [];
            foreach ($lbb_array as $rating) {
                if ($rating > 0){
                    $rata [] = $rating;
                }
            }
           if ($rata){
               $rata_user [$id_user] = array_sum($rata) / count($rata);
           }
        }

        return $rata_user;
    }

    public function hitung_similariy()
    {
        $tabel_data = $this->tabel_data_balik();
        $rata_user  = $this->rata_user();

//        for ($i=0;$i<count($tabel_data);$i++){
//            for ($j=$i;$j<count($tabel_data);$j++){
//                $halo [] = $tabel_data[$i][$j];
//            }
//        }

        foreach ($tabel_data as $lbb_id_i =>  $rating_array){
            foreach ($tabel_data as $lbb_id_j => $rating_array){

                if ($lbb_id_i == $lbb_id_j){
                    continue;
                }

                $sim_atas = 0;

                foreach ($rating_array as $user_id => $rating){
                    if ($rating == 0 || $tabel_data [$lbb_id_j] [$user_id] == 0){
                        continue;
                    }

                    $sim_atas = $sim_atas + (($rating-$rata_user[$user_id])*($tabel_data[$lbb_id_j][$user_id]-$rata_user[$user_id]));
                }

                return $sim_atas;

            }
        }

//        return $halo;
    }

}