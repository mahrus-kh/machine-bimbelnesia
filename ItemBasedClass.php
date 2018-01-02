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
    public function user() //mengambil id_user yang ada di tb_detail_rating
    {
        $tampil = $this->koneksi()->prepare("SELECT DISTINCT(id_user)FROM tb_detail_rating");
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $user[] =  $data['id_user'];
            }
        }
        return $user;
    }
    public function lbb() // mengambil id_lbb yang ada di tb_detail_rating
    {
        $tampil = $this->koneksi()->prepare("SELECT DISTINCT(id_lbb) FROM tb_detail_rating");
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $lbb[] = $data['id_lbb'];
            }
        }
        return $lbb;
    }
    public function rata_rating_user($id_user)// menghitung rata2 rating user terhadap semua lbb yang ada di tb_detail_rating
    {
        $tampil = $this->koneksi()->prepare("SELECT detail_rating FROM tb_detail_rating WHERE id_user=:id_user");
        $tampil->bindParam(':id_user',$id_user);
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
               $rata[] = $data['detail_rating'];
            }
            $rata_rating_user = 0;
            for ($i=0;$i<count($rata);$i++){
                $rata_rating_user = $rata_rating_user + $rata[$i];
            }
            $rata_rating_user = $rata_rating_user / count($rata);
        }
        return $rata_rating_user;
    }
    public function ambil_sama() //mengambil nilai rating dari user = nilai rating lbb1 DAN user = nilai rating lbb2 / yang sama
    {
        $rating_user_lbb1 = [];
        $rating_user_lbb2 = [];
        $user = [];
        $return = [];

       $id_user = $this->user();
       $id_lbb = $this->lbb();

        for($i=0;$i<count($id_user);$i++){

        }

        $lbb1 = 0;
        $lbb2 = 0;
        $tampil = $this->koneksi()->prepare("SELECT detail_rating FROM tb_detail_rating WHERE id_user=1 AND id_lbb=1");
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $lbb1 =  $data['detail_rating'];
            }
        }
        $tampil = $this->koneksi()->prepare("SELECT detail_rating FROM tb_detail_rating WHERE id_user=1 AND id_lbb=2");
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $lbb2 =  $data['detail_rating'];
            }
        }
        if (!empty($lbb1) && !empty($lbb2)){ //cek apakah kedua lbb sudah dirating user
            $rating_user_lbb1 [] = $lbb1; // memasukkan nilai rating user ke lbb 1 kedalam array
            $rating_user_lbb2 [] = $lbb2;// memasukkan nilai rating user ke lbb 2 kedalam array
            array_push($return,$rating_user_lbb1);
            array_push($return,$rating_user_lbb2);
        }
        return $return;
    }
    public function coba_ambil_one() //mengambil nilai rating dari user = nilai rating lbb1 DAN user = nilai rating lbb2 / yang sama
    {
        $rating_user_lbb1 = [];
        $rating_user_lbb2 = [];
        $lbb1  = [];
        $lbb2  = [];
        $user_merating = [];
        $return = [];

        $id_user = $this->user();
        $id_lbb = $this->lbb();

        for($i=0;$i<count($id_user);$i++){
            for ($a=0;$a<1;$a++){
                $nilai_lbb1 = 0;
                $nilai_lbb2 = 0;

                $tampil = $this->koneksi()->prepare("SELECT detail_rating FROM tb_detail_rating WHERE id_user=:id_user AND id_lbb=1");
                $tampil->bindParam(':id_user',$id_user[$i]);
                $tampil->execute();
                $tampil->setFetchMode(PDO::FETCH_ASSOC);
                if (count($tampil)>0){
                    while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                        $nilai_lbb1 =  $data['detail_rating'];

                    }
                }
                $tampil = $this->koneksi()->prepare("SELECT detail_rating FROM tb_detail_rating WHERE id_user=:id_user AND id_lbb=2");
                $tampil->bindParam(':id_user',$id_user[$i]);
                $tampil->execute();
                $tampil->setFetchMode(PDO::FETCH_ASSOC);
                if (count($tampil)>0){
                    while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                        $nilai_lbb2 =  $data['detail_rating'];
                    }
                }
                if (!empty($nilai_lbb1) && !empty($nilai_lbb2)){
                    $lbb1 [] = $nilai_lbb1;
                    $lbb2 [] = $nilai_lbb2;
                    $user_merating [] = $id_user[$i];
                }
            }
        }
        array_push($return, $lbb1);
        array_push($return, $lbb2);
        array_push($return, $user_merating);

        return $return;
    }
    public function coba_ambil($id_lbb1, $id_lbb2) //mengambil nilai rating dari user = nilai rating lbb1 DAN user = nilai rating lbb2 / yang sama
    {
        $rating_user_lbb1 = [];
        $rating_user_lbb2 = [];
        $lbb1  = [];
        $lbb2  = [];
        $user_merating = [];
        $return = [];

        $id_user = $this->user();

        for($i=0;$i<count($id_user);$i++){
            for ($a=0;$a<1;$a++){
                $nilai_lbb1 = 0;
                $nilai_lbb2 = 0;

                $tampil = $this->koneksi()->prepare("SELECT detail_rating FROM tb_detail_rating WHERE id_user=:id_user AND id_lbb=:id_lbb1");
                $tampil->bindParam(':id_user',$id_user[$i]);
                $tampil->bindParam('id_lbb1',$id_lbb1);
                $tampil->execute();
                $tampil->setFetchMode(PDO::FETCH_ASSOC);
                if (count($tampil)>0){
                    while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                        $nilai_lbb1 =  $data['detail_rating'];

                    }
                }
                $tampil = $this->koneksi()->prepare("SELECT detail_rating FROM tb_detail_rating WHERE id_user=:id_user AND id_lbb=:id_lbb2");
                $tampil->bindParam(':id_user',$id_user[$i]);
                $tampil->bindParam(':id_lbb2',$id_lbb2);
                $tampil->execute();
                $tampil->setFetchMode(PDO::FETCH_ASSOC);
                if (count($tampil)>0){
                    while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                        $nilai_lbb2 =  $data['detail_rating'];
                    }
                }
                if (!empty($nilai_lbb1) && !empty($nilai_lbb2)){
                    $lbb1 [] = $nilai_lbb1;
                    $lbb2 [] = $nilai_lbb2;
                    $user_merating [] = $id_user[$i];
                }
            }
        }
        array_push($return, $lbb1);
        array_push($return, $lbb2);
        array_push($return, $user_merating);

        return $return;
    }
    public function sim_atas($id_lbb1, $id_lbb2) // menghitung nilai rumus atas similiarity
    {
        $data = $this->coba_ambil($id_lbb1, $id_lbb2);
        $rating_lbb1 = $data[0];
        $rating_lbb2 = $data[1];
        $user_merating = $data [2];

        $sim_atas = 0;
        for ($i=0;$i<count($user_merating);$i++){ //hitung nilai diulang sebanyak yang sama -> rating_lbb1 / rating_lbb1
            $sim_atas = $sim_atas + (($rating_lbb1[$i]-$this->rata_rating_user($user_merating[$i]))*($rating_lbb2[$i]-$this->rata_rating_user($user_merating[$i])));
        }

        return $sim_atas;
    }

    public function sim_bawah($id_lbb1, $id_lbb2)
    {
        $data = $this->coba_ambil($id_lbb1, $id_lbb2);
        $rating_lbb1 = $data[0];
        $rating_lbb2 = $data[1];
        $user_merating = $data[2];

        $sim_bawah = 0;
        $bawah1 =0;
        $bawah2 = 0;
        for ($i=0;$i<count($user_merating);$i++){
            $bawah1 = $bawah1 + (pow(($rating_lbb1[$i]-$this->rata_rating_user($user_merating[$i])),2));
            $bawah2 = $bawah2 + (pow(($rating_lbb2[$i]-$this->rata_rating_user($user_merating[$i])),2));
        }
        $bawah1 = sqrt($bawah1);
        $bawah2 = sqrt($bawah2);
        $sim_bawah = $bawah1*$bawah2;

        return $sim_bawah;
    }
    public function similiarity()
    {
        $similiarity = [];
        $id_lbb  = $this->lbb();
        for ($i=0;$i<count($id_lbb);$i++){
            for ($j=$i;$j<count($id_lbb);$j++){
                if ($i == $j){
                    continue;
                }
                $similiarity [] = $this->sim_atas($id_lbb[$i],$id_lbb[$j]) / $this->sim_bawah($id_lbb[$i],$id_lbb[$j]);
            }
        }
        return $similiarity;
    }
    public function similiarity_save()
    {
        $id_lbb  = $this->lbb();
        for ($i=0;$i<count($id_lbb);$i++){
            for ($j=$i;$j<count($id_lbb);$j++){
                $similiarity = 0;
                if ($i == $j){
                    continue;
                }
                $similiarity = $this->sim_atas($id_lbb[$i],$id_lbb[$j]) / $this->sim_bawah($id_lbb[$i],$id_lbb[$j]);
                $save = $this->koneksi()->prepare("INSERT INTO tb_itembased_sim (similiarity,id_lbb1,id_lbb2) VALUES (:similiarity,:id_lbb1,:id_lbb2)");
                $save->bindParam(':similiarity',$similiarity);
                $save->bindParam(':id_lbb1',$id_lbb[$i]);
                $save->bindParam(':id_lbb2',$id_lbb[$j]);
                $save->execute();
            }
        }
        return TRUE;
    }
    public function coba()
    {
        $id_user = $this->user(); 
        $id_lbb = $this->lbb();
//
        for($i=0;$i<count($id_user);$i++){
            for($a=0;$a<count($id_lbb);$a++){

                echo $id_user[$i] . " " . $id_lbb[$a] . "|";

            }
        }
       return TRUE;
    }
    public function lagi()
    {
     $id_lbb = $this->lbb();
     $id_lbb2 = $this->lbb();
     for ($i=0;$i<count($id_lbb);$i++){
         for ($j=0;$j<count($id_lbb);$j++){
             echo $id_lbb[$i] . " : " . $id_lbb[$j+1] . " | ";

         }
     }
    }

    public function cepat()
    {
        $a = microtime(true);
        for ($i=0;$i<1000000;$i++){
            echo $i . " ";
        }
        return microtime(true)-$a;
    }

    public function id_lbb_kosong($id_user)
    {
        $id_lbb_kosong = [];
        $id_lbb = $this->lbb();
        for ($i=0;$i<count($id_lbb);$i++){
            $kosong = 0;
            $tampil = $this->koneksi()->prepare("SELECT id_detail_rating FROM tb_detail_rating WHERE id_user=:id_user AND id_lbb =:id_lbb");
            $tampil->bindParam(':id_user',$id_user);
            $tampil->bindParam(':id_lbb',$id_lbb[$i]);
            $tampil->execute();
            $tampil->setFetchMode(PDO::FETCH_ASSOC);
            if (count($tampil)>0){
                while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                    $kosong =  $id_lbb[$i];
                }
            }
            if ($kosong == 0){
                $id_lbb_kosong [] = $id_lbb[$i];
            }
        }
        return $id_lbb_kosong;
    }
    public function detail_rating_user_lbb($id_user, $id_lbb){
        $tampil = $this->koneksi()->prepare("SELECT detail_rating FROM tb_detail_rating WHERE id_user=:id_user AND id_lbb=:id_lbb");
        $tampil->bindParam(':id_user',$id_user);
        $tampil->bindParam(':id_lbb',$id_lbb);
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $detail_rating_user_lbb =  $data['detail_rating'];
            }
        }
        return $detail_rating_user_lbb;
    }
    public function similiarity_lbb($id_lbb_kosong , $id_lbbi) //mengambil similiarity antar lbb
    {
        $sql = "SELECT similiarity FROM tb_itembased_sim WHERE id_lbb1=:id_lbb_kosong AND id_lbb2=:id_lbbi OR id_lbb1=:id_lbbi AND id_lbb2=:id_lbb_kosong";
        $tampil = $this->koneksi()->prepare($sql);
        $tampil->bindParam(':id_lbb_kosong',$id_lbb_kosong);
        $tampil->bindParam(':id_lbbi',$id_lbbi);
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $similiarity_lbb =  $data['similiarity'];
            }
        }
        return $similiarity_lbb;
    }
    public function pre_atas($id_user, $id_lbb_kosong)
    {
        $id_lbb = [];
        $tampil = $this->koneksi()->prepare("SELECT id_lbb FROM tb_detail_rating WHERE id_user=:id_user");
        $tampil->bindParam(':id_user',$id_user);
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $id_lbb [] =  $data['id_lbb'];
            }
        }

        $pre_atas = 0;
        for ($i=0;$i<count($id_lbb);$i++) {
            $pre_atas = $pre_atas + ($this->detail_rating_user_lbb($id_user,$id_lbb[$i])*$this->similiarity_lbb($id_lbb_kosong,$id_lbb[$i]));
        }
        return $pre_atas;
    }
    public function pre_bawah($id_user,$id_lbb_kosong)
    {

        $id_lbb = [];
        $tampil = $this->koneksi()->prepare("SELECT id_lbb FROM tb_detail_rating WHERE id_user=:id_user");
        $tampil->bindParam(':id_user',$id_user);
        $tampil->execute();
        $tampil->setFetchMode(PDO::FETCH_ASSOC);
        if (count($tampil)>0){
            while ($data=$tampil->fetch(PDO::FETCH_ORI_NEXT)){
                $id_lbb [] =  $data['id_lbb'];
            }
        }

        $pre_bawah = 0;
        for ($i=0;$i<count($id_lbb);$i++){
            $pre_bawah = $pre_bawah + abs($this->similiarity_lbb($id_lbb_kosong,$id_lbb[$i]));
        }
        return $pre_bawah;
    }
    public function prediksi()
    {
       $prediksi = [];
       $id_user = $this->user();

       for ($i=0;$i<count($id_user);$i++){
           $id_lbb_kosong = 0;
           $id_lbb_kosong = $this->id_lbb_kosong($id_user[$i]);
           for ($j=0;$j<count($id_lbb_kosong);$j++){
               $prediksi [] = $this->pre_atas($id_user[$i],$id_lbb_kosong[$j]) / $this->pre_bawah($id_user[$i],$id_lbb_kosong[$j]);
           }
       }
       return $prediksi;

    }
    
    //Tambahan
    public function truncate_tb_itembase_sim()
    {
        $del = $this->koneksi()->prepare("TRUNCATE TABLE tb_itembased_sim");
        return $del->execute();
    }
}