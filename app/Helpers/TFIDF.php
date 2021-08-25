<?php
namespace App\Helpers;

use Kamus;

class TFIDF {
    public static function tanda_baca($kalimat) {
        return preg_replace('/[^A-Za-z\  ]/','', $kalimat);
    }

    public static function proses_awal($kalimat) {
        $data 		= [];
        $document 	= TFIDF::tanda_baca(strtolower($kalimat));
        $tokenKata 	= explode(" ",$document);
        for ($i=0; $i < count($tokenKata) ; $i++) {
            $kata   = $tokenKata[$i];
            $data[] = $kata;
        }
        return $data;
    }

    
    public static function get_document($pengetahuan) {
        $index_kata 		= [];
        $index_document 	= [];
        $index_term 		= [];
        $datas 			    = [];
        foreach($pengetahuan AS $key => $value) {
            $index_term[$value['id']]   = TFIDF::proses_awal($value['pertanyaan']);
            $datas 			            = TFIDF::proses_awal($value['pertanyaan']);
            $index_document[]           = $value['id'];

        }

        $index_kata             =  TFIDF::insert_index_kata($index_kata, $datas);
        $data['index_term']     = $index_term;
        $data['index_kata']     = $index_kata;
        $data['index_document'] = $index_document;
        return $data;
    }

    public static function get_kalimat($kalimat) {
        $index_kata 		= [];
        $index_document 	= [];
        $index_term 		= [];
        $datas 			    = [];
        $index_term["Q"]    = TFIDF::proses_awal($kalimat);
        $datas 			    = TFIDF::proses_awal($kalimat);


        $index_kata             =  TFIDF::insert_index_kata($index_kata, $datas);
        $data['index_term']     = $index_term;
        $data['index_kata']     = $index_kata;
        $data['index_document'] = "Q";
        return $data;
    }

    public static function stemming($kata){
        $kataAsal = $kata;
        $cekKata  = TFIDF::cek_kamus($kata);
        if($cekKata == true){ // Cek Kamus
            return $kata; // Jika Ada maka kata tersebut adalah kata dasar
        } else { //jika tidak ada dalam kamus maka dilakukan stemming
            $kata = TFIDF::del_inflection_suffixes($kata);
            if(TFIDF::cek_kamus($kata)){
                return $kata;
            }

            $kata = TFIDF::del_derivation_suffixes($kata);
            if(TFIDF::cek_kamus($kata)){
                return $kata;
            }

            $kata = TFIDF::del_derivation_prefix($kata);
            if(TFIDF::cek_kamus($kata)){
                return $kata;
            }
        }
    }

    public static function cek_kamus($kata){
        $words = Kamus::words();
        if (in_array($kata, $words)) {
            return true;
        } else {
            return false;
        }
    }


    public static function del_inflection_suffixes($kata){
        $kataAsal = $kata;
        if(preg_match('/([km]u|nya|[kl]ah|pun)\z/i',$kata)){ // Cek Inflection Suffixes
            $__kata = preg_replace('/([km]u|nya|[kl]ah|pun)\z/i','',$kata);

            return $__kata;
        }
        return $kataAsal;
    }

    public static function del_derivation_suffixes($kata){
        $kataAsal = $kata;
        if(preg_match('/(i|an)\z/i',$kata)){
            $__kata = preg_replace('/(i|an)\z/i','',$kata);
            if(TFIDF::cek_kamus($__kata)){ 
                return $__kata;
            }else if(preg_match('/(kan)\z/i',$kata)){
                $__kata = preg_replace('/(kan)\z/i','',$kata);
                if(TFIDF::cek_kamus($__kata)){
                    return $__kata;
                }
            }
        }
        return $kataAsal;
    }

    public static function del_derivation_prefix($kata){
        $kataAsal = $kata;

        /* —— Tentukan Tipe Awalan ————*/
        if(preg_match('/^(di|[ks]e)/',$kata)){ // Jika di-,ke-,se-
            $__kata = preg_replace('/^(di|[ks]e)/','',$kata);

            if(TFIDF::cek_kamus($__kata)){
                return $__kata;
            }

            $__kata__ = TFIDF::del_derivation_suffixes($__kata);

            if(TFIDF::cek_kamus($__kata__)){
                return $__kata__;
            }

            if(preg_match('/^(diper)/',$kata)){ //diper-
                $__kata = preg_replace('/^(diper)/','',$kata);
                $__kata__ = TFIDF::del_derivation_suffixes($__kata);

                if(TFIDF::cek_kamus($__kata__)){
                    return $__kata__;
                }

            }

            if(preg_match('/^(ke[bt]er)/',$kata)){  //keber- dan keter-
                $__kata = preg_replace('/^(ke[bt]er)/','',$kata);
                $__kata__ = TFIDF::del_derivation_suffixes($__kata);

                if(TFIDF::cek_kamus($__kata__)){
                    return $__kata__;
                }
            }

        }

        if(preg_match('/^([bt]e)/',$kata)){ //Jika awalannya adalah "te-","ter-", "be-","ber-"

            $__kata = preg_replace('/^([bt]e)/','',$kata);
            if(TFIDF::cek_kamus($__kata)){
                return $__kata; // Jika ada balik
            }

            $__kata = preg_replace('/^([bt]e[lr])/','',$kata);
            if(TFIDF::cek_kamus($__kata)){
                return $__kata; // Jika ada balik
            }

            $__kata__ = TFIDF::del_derivation_suffixes($__kata);
            if(TFIDF::cek_kamus($__kata__)){
                return $__kata__;
            }
        }

        if(preg_match('/^([mp]e)/',$kata)){
            $__kata = preg_replace('/^([mp]e)/','',$kata);
            if(TFIDF::cek_kamus($__kata)){
                return $__kata; // Jika ada balik
            }
            $__kata__ = TFIDF::del_derivation_suffixes($__kata);
            if(TFIDF::cek_kamus($__kata__)){
                return $__kata__;
            }

            if(preg_match('/^(memper)/',$kata)){
                $__kata = preg_replace('/^(memper)/','',$kata);
                if(TFIDF::cek_kamus($kata)){
                    return $__kata;
                }
                $__kata__ = TFIDF::del_derivation_suffixes($__kata);
                if(TFIDF::cek_kamus($__kata__)){
                    return $__kata__;
                }
            }

            if(preg_match('/^([mp]eng)/',$kata)){
                $__kata = preg_replace('/^([mp]eng)/','',$kata);
                if(TFIDF::cek_kamus($__kata)){
                    return $__kata; // Jika ada balik
                }
                $__kata__ = TFIDF::del_derivation_suffixes($__kata);
                if(TFIDF::cek_kamus($__kata__)){
                    return $__kata__;
                }

                $__kata = preg_replace('/^([mp]eng)/','k',$kata);
                if(TFIDF::cek_kamus($__kata)){
                    return $__kata; // Jika ada balik
                }
                $__kata__ = TFIDF::del_derivation_suffixes($__kata);
                if(TFIDF::cek_kamus($__kata__)){
                    return $__kata__;
                }
            }

            if(preg_match('/^([mp]eny)/',$kata)){
                $__kata = preg_replace('/^([mp]eny)/','s',$kata);
                if(TFIDF::cek_kamus($__kata)){
                    return $__kata; // Jika ada balik
                }
                $__kata__ = TFIDF::del_derivation_suffixes($__kata);
                if(TFIDF::cek_kamus($__kata__)){
                    return $__kata__;
                }
            }

            if(preg_match('/^([mp]e[lr])/',$kata)){
                $__kata = preg_replace('/^([mp]e[lr])/','',$kata);
                if(TFIDF::cek_kamus($__kata)){
                    return $__kata; // Jika ada balik
                }
                $__kata__ = TFIDF::del_derivation_suffixes($__kata);
                if(TFIDF::cek_kamus($__kata__)){
                    return $__kata__;
                }
            }

            if(preg_match('/^([mp]en)/',$kata)){
                $__kata = preg_replace('/^([mp]en)/','t',$kata);
                if(TFIDF::cek_kamus($__kata)){
                    return $__kata; // Jika ada balik
                }
                $__kata__ = TFIDF::del_derivation_suffixes($__kata);
                if(TFIDF::cek_kamus($__kata__)){
                    return $__kata__;
                }

                $__kata = preg_replace('/^([mp]en)/','',$kata);
                if(TFIDF::cek_kamus($__kata)){
                    return $__kata; // Jika ada balik
                }
                $__kata__ = TFIDF::del_derivation_suffixes($__kata);
                if(TFIDF::cek_kamus($__kata__)){
                    return $__kata__;
                }
            }

            if(preg_match('/^([mp]em)/',$kata)){
                $__kata = preg_replace('/^([mp]em)/','',$kata);
                if(TFIDF::cek_kamus($__kata)){
                    return $__kata; // Jika ada balik
                }
                $__kata__ = TFIDF::del_derivation_suffixes($__kata);
                if(TFIDF::cek_kamus($__kata__)){
                    return $__kata__;
                }

                $__kata = preg_replace('/^([mp]em)/','p',$kata);
                if(TFIDF::cek_kamus($__kata)){
                    return $__kata; // Jika ada balik
                }

                $__kata__ = TFIDF::del_derivation_suffixes($__kata);
                if(TFIDF::cek_kamus($__kata__)){
                    return $__kata__;
                }
            }
        }
        return $kataAsal;
    }



    /*=============Tambahkan Ke Matriks GVSM &OR VSM================*/
    public static function insert_index_term($index_term,$arr){
        foreach ($arr as $key => $value) {
            if($key != ""){
                $index_term[$key] = array_count_values($value);
            }
        }
        return $index_term;
    }


    /*=============Tambahkan Index Kata================*/
    public static function insert_index_kata($index_kata, $data){
        for ($i=0; $i < count($data) ; $i++) {
            if(!in_array($data[$i], $index_kata)){
                $index_kata[] = $data[$i];
            }
        }
        $data = array_filter($index_kata,function($var){return !is_null($var);});
        return $data;
    }


    /*=============Tambahkan Index Dokumen================*/
    public static function insert_index_dokumen($index_document, $dokumen){
        if(!is_array($dokumen)){
            if(!in_array($dokumen, $index_document)){
                $index_document[] = $dokumen;
            }
        }else{
            for ($i=0; $i < count($dokumen) ; $i++) {
                if(!in_array($dokumen[$i], $index_document)){
                    $index_document[] = $dokumen[$i];
                }
            }
            $index_document = array_filter($index_document,function($var){return !is_null($var);});
        }
        return $index_document;
    }
    //==========================TFIDF=======================

    public static function insert_dokumen_frekuensi($term_frekunesi,$index_kata,$index_document){
        $dokumen_frekuensi = array();
        for ($i=0; $i < count($index_kata); $i++) { 
            if($index_kata[$i] != ""){
                if(!array_key_exists($index_kata[$i], $dokumen_frekuensi)){
                    $dokumen_frekuensi[$index_kata[$i]] = 0;
                }

                for ($j=0; $j < count($index_document) ; $j++) { 
                    if(array_key_exists($index_kata[$i], $term_frekunesi[$index_document[$j]])){
                        $dokumen_frekuensi[$index_kata[$i]] = $dokumen_frekuensi[$index_kata[$i]] + 1;
                    }
                }
            }
        }
        $data = array_filter($dokumen_frekuensi,function($var){return !is_null($var);});
        return $data;
    }

    public static function insert_IDF($jum_dokumen, $dokumen_frekuensi,$index_kata){
        $IDF = array();
        for ($i=0; $i < count($index_kata) ; $i++) { 
            if($index_kata[$i] != ""){
                $IDF[$index_kata[$i]] = round(log10($jum_dokumen/$dokumen_frekuensi[$index_kata[$i]]),4);
            }
        }

        return $IDF;
    }

    public static function insert_TFID($index_kata,$index_document,$term_frekunesi,$IDF){
        $TFIDF = array();
        for ($i=0; $i < count($index_kata); $i++) { 
            if($index_kata[$i] != ""){
                for ($j=0; $j < count($index_document) ; $j++) { 
                    $TF = !empty($term_frekunesi[$index_document[$j]][$index_kata[$i]]) ? $term_frekunesi[$index_document[$j]][$index_kata[$i]] : 0;
                    $TFIDF[$index_document[$j]][$index_kata[$i]] =  round(($TF * $IDF[$index_kata[$i]]),4);
                }
            }
        }

        return $TFIDF;
    }

    public static function insert_DQVSM($index_kata,$index_document,$TF_IDF,$id_kueri){
        $DQ = array();
        for ($i=0; $i < count($index_kata); $i++) { 
            if($index_kata[$i] !=""){
                for ($j=0; $j < count($index_document) ; $j++) { 
                    if($index_document[$j] != $id_kueri){
                        $DQ[$index_document[$j]][$index_kata[$i]] = round($TF_IDF[$index_document[$j]][$index_kata[$i]]* $TF_IDF[$id_kueri][$index_kata[$i]],4);
                    }
                }
            }
        }
        return $DQ;
    }


    public static function insert_panjang_vector($index_document,$index_kata,$TFIDF){
        $panjang_vector = array();
        for ($i=0; $i < count($index_kata); $i++) { 
            if($index_kata[$i] != ""){
                for ($j=0; $j < count($index_document) ; $j++) { 
                    $panjang_vector[$index_document[$j]][$index_kata[$i]] = round(pow($TFIDF[$index_document[$j]][$index_kata[$i]], 2),4);
                }
            }
        }
        return $panjang_vector;
    }


    public static function similarity_VSM($index_document,$DQ,$panjang_vector,$id_kueri){
        $list_hasil = array();
        for ($i=0; $i < count($index_document) ; $i++) { 
            if($index_document[$i] != $id_kueri){
                $sum_index_document = array_sum($DQ[$index_document[$i]]);
                $sum_panjang_vector = sqrt(array_sum($panjang_vector[$index_document[$i]])) * sqrt(array_sum($panjang_vector[$id_kueri]));
                $nilai_cosine = $sum_index_document != 0 || $sum_panjang_vector != 0 ? $sum_index_document / $sum_panjang_vector : 0;
                $list_hasil[$index_document[$i]]= $nilai_cosine != 0 ? round($nilai_cosine, 2): $nilai_cosine;
            }
        }

        return $list_hasil;
    }



    public static function hasil_akhir($kalimat, $pengetahuan){

        $data           = TFIDF::get_document($pengetahuan);
        $index_term     = [];
        $index_kata     = [];
        $index_document = [];
        $index_term     = TFIDF::insert_index_term($index_term, $data['index_term']);
        $index_kata     = TFIDF::insert_index_kata($index_kata, $data['index_kata']);
        $index_document = TFIDF::insert_index_dokumen($index_document, $data['index_document']);

        $s              = TFIDF::get_kalimat($kalimat);
        $id_kueri       = $s['index_document'];
        $index_term     = TFIDF::insert_index_term($index_term, $s['index_term']);
        $index_kata     = TFIDF::insert_index_kata($index_kata, $s['index_kata']);
        $index_document = TFIDF::insert_index_dokumen($index_document,$s['index_document']);

        $dokumen_frekuensi  = TFIDF::insert_dokumen_frekuensi($index_term,$index_kata,$index_document);
        $IDF                = TFIDF::insert_IDF(count($index_document),$dokumen_frekuensi,$index_kata);
        $TF_IDF             = TFIDF::insert_TFID($index_kata,$index_document,$index_term,$IDF);
        $panjang_vector     = TFIDF::insert_panjang_vector($index_document,$index_kata,$TF_IDF);
        $DQ                 = TFIDF::insert_DQVSM($index_kata,$index_document,$TF_IDF,$id_kueri); 
        $list_hasil         = TFIDF::similarity_VSM($index_document,$DQ,$panjang_vector,$id_kueri);
        

        $datas['index_kata']        = $index_kata;
        $datas['index_document']    = $index_document;
        $datas['index_term']        = $index_term;
        $datas['dokumen_frekuensi'] = $dokumen_frekuensi;
        $datas['IDF']               = $IDF;
        $datas['TF_IDF']            = $TF_IDF;
        $datas['panjang_vector']    = $panjang_vector;
        $datas['DQ']                = $DQ;
        $datas['list_hasil']        = $list_hasil;

        return $datas;

    }
}
?>