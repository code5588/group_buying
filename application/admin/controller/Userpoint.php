<?php
/**
 * Created by PhpStorm.
 * User: Xubin
 * Date: 2017/12/22
 * Time: 13:47
 */
namespace app\admin\controller;

use think\App;
use think\Db;
use think\response\Redirect;

class Userpoint extends \app\admin\Auth{

    public function showList(){

        if($this->request->isAjax()){

            $page = $this->request->get('page');
            $limit = $this->request->get('limit');
            $search = $this->request->get('search');
            $where = [];

            if (!empty($search)) {
                $where = ['name' => ['like', '%' . $search . '%']];
            }

            $page = $page > 0 ? $page - 1 : 0;

            $count = \think\Db::table("user")->where($where)->count();

            $data = \think\Db::table('user')->where($where)->limit($page*$limit,$limit)->order('id')->select();

            return json(['code' => 0, 'count' => $count, 'data' => $data]);
        }


        return $this->fetch('showList');
    }

}