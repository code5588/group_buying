<?php
/**
 * Created by PhpStorm.
 * User: Xubin
 * Date: 2018/4/20
 * Time: 15:28
 */
namespace app\admin\controller;

use think\App;
use think\Db;
use think\response\Redirect;

class Reward extends \app\admin\Auth{

    public function agenctShowList(){

        $searchTime = $this->request->param('searchTime');
        if(empty($searchTime)){
            $searchTime = date('Y-m',time());
        }
        if ($this->request->isAjax()) {

            $sql = "select if(count(*) >=15, count(*),0) as count,m.agency_id,a.agency_name  from `member` as m left join `agency` as a ON m.agency_id = a.agency_id where  from_unixtime(m.charge_date,'%Y-%m') = '".$searchTime."' group by m.agency_id";
            $agency =\think\Db::query($sql);
            foreach ($agency as $k => $v){
                if($v['count'] == 0){
                    unset($agency[$k]);
                }
            }
            $count = count($agency);
            return json(['code'=>0,'count'=>$count,'data'=>$agency]);
        }
        $this->assign('searchTime', $searchTime);
        return $this->fetch('showList');
    }

    public function show()
    {
        $searchTime = $this->request->param('searchTime');
        $id = $this->request->param('id');
        if ($this->request->isAjax()) {
            $page = $this->request->get('page');
            $limit = $this->request->get('limit');
            $pages = $page > 0 ? $page - 1 : 0;
            $pagesize = $page*$limit;
            //".$where
            $count  = \think\Db::query("select count(*) as count from `member` as m left join wait_time as w on m.id = w.mid left join bank_description as bd on m.bank_name = bd.bank_id left join agency as a on a.agency_id = m.agency_id  where m.agency_id = $id and from_unixtime(m.charge_date,'%Y-%m') = '$searchTime'");
            $member = \think\Db::query("select m.*,w.wait_time,w.str_code,bd.bank_name as bd_name,a.agency_name from `member` as m left join wait_time as w on m.id = w.mid left join bank_description as bd on m.bank_name = bd.bank_id left join agency as a on m.agency_id = a.agency_id where  m.agency_id = $id and from_unixtime(m.charge_date,'%Y-%m') = '$searchTime' limit $pages,$pagesize");
            foreach ($member as $k => $v) {
                $member[$k]['charge_date'] = date('Y-m-d', $v['charge_date']);
                $member[$k]['wait_time'] = date('Y-m-d', $v['wait_time']);
                if($member[$k]['type'] == 1){
                    $member[$k]['str_code'] = 'C90-'.$v['str_code'];
                }elseif ($member[$k]['type'] == 2){
                    $member[$k]['str_code'] = 'H90-'.$v['str_code'];
                }elseif ($member[$k]['type'] == 4){
                    $member[$k]['str_code'] = 'CH180-'.$v['str_code'];
                }
            }

            return json(['code'=>0,'count'=>$count[0]['count'],'data'=>$member]);
        }
        $this->assign('searchTime', $searchTime);
        $this->assign('id', $id);
        return $this->fetch('show');
    }
}