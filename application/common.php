<?php

function d($arr){
    echo '<pre>';
    var_dump($arr);
}

function translateMemberType($type){

    if($type == 1){
        $s_type = 'car';
    }elseif ($type == 2){
        $s_type = 'house';
    }elseif ($type == 3){
        $s_type = 'all';
    }

    return $s_type;
}

function getConf(){

    $conf = \think\Db::table('conf')->select();
    return $conf;

}
