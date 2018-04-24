<?php
/**
 * Created by PhpStorm.
 * User: Xubin
 * Date: 2017/12/19
 * Time: 13:47
 */
namespace app\admin\controller;

use think\App;
use think\Db;
use think\response\Redirect;

class Share extends \app\admin\Auth{

	public function showList(){

		if($this->request->isAjax()){

			$page = $this->request->get('page');
			$limit = $this->request->get('limit');
			$search = $this->request->get('search');

			$where = [];
			if (!empty($search)) {
				$where = ['share_yun_id' => ['like', '%' . $search . '%']];
			}
			
			$page = $page > 0 ? $page - 1 : 0;

			$count = \think\Db::table("share")->where($where)->count();
			$data = \think\Db::table('share')->where($where)->limit($page*$limit,$limit)->order('id')->select();
			foreach ($data as $k => $v) {
				$shareCount = \think\Db::query("select count(*) as count from `member` where share_yun_id = '".$v['share_yun_id']."' group by 'share_yun_id' ");
				if($shareCount){
					$data[$k]['shareCount'] = $shareCount[0]['count'];
				}

			}
			return json(['code' => 0, 'count' => $count, 'data' => $data]);
		}


		return $this->fetch('showList');
	}

	public function show(){

		$id = $this->request->param('id');
        $searchTime = $this->request->param('searchTime');

		if($this->request->isAjax()){

			$page = $this->request->get('page');
			$limit = $this->request->get('limit');
			$search = $this->request->get('search');
            $searchTime = $this->request->param('searchTime');
			$page = $page > 0 ? $page - 1 : 0;

            $where = '';
			if($searchTime){
				$where = " and from_unixtime(charge_date,'%Y-%m') ='$searchTime'";
			}
			$shareYunId = \think\Db::table("share")->where('id = '.$id)->find();
			// $count = \think\Db::table("member")->where("share_yun_id = '".$shareYunId['share_yun_id']."'")
			// 			->count();

            $sql = "select count(*) as count from `member` as  m left join  `wait_time` as w  on m.id = w.mid where share_yun_id = '".$shareYunId['share_yun_id']."'".$where;

            $count = \think\Db::query($sql);

			// $data = \think\Db::field('m.name,m.mobile,m.yun_id,m.type,m.amount,m.charge_date,w.wait_time,w.str_code')
			// 	->table("member m,wait_time w")
			// 	->where("m.id = w.mid and share_yun_id = '".$shareYunId['share_yun_id']."'")
			// 	->order('m.id asc')
			// 	->limit($page * $limit, $limit)
			// 	->select();

			$pagesize = $page * $limit;
			$data = \think\Db::query("select m.name,m.mobile,m.yun_id,m.type,m.amount,m.charge_date,w.wait_time,w.str_code from `member` as  m left join  `wait_time` as w  on m.id = w.mid where share_yun_id = '".$shareYunId['share_yun_id']."'".$where." order by m.id asc limit ".$pagesize.",".$limit."");
            $conf = getConf();
			$tmpPoint = bcdiv($conf[0]['point'], 100, 2);
			foreach ($data as $k => $v) {
				//$data[$k]['charge_date'] = date('Y-m-d', $v['charge_date']);
				$data[$k]['wait_time'] = date('Y-m-d', $v['wait_time']);
				$data[$k]['point'] = $tmpPoint * $data[$k]['amount'];
				if($v['type'] == 1){
					$data[$k]['type'] = '90天购车';
					$data[$k]['str_code'] = 'C90-'.$v['str_code'];
					$pointTime = $data[$k]['charge_date']+ 90*24*3600;
					$data[$k]['point_time'] = date('Y-m-d',$pointTime);
				}elseif ($v['type'] == 2){
					$data[$k]['type'] = '90天购房';
					$data[$k]['str_code'] = 'H90-'.$v['str_code'];
					$pointTime = $data[$k]['charge_date']+ 90*24*3600;
					$data[$k]['point_time'] = date('Y-m-d',$pointTime);
				}elseif ($v['type'] == 3){
					$data[$k]['type'] = '180天购车购房';
					$data[$k]['str_code'] = 'CH180-'.$v['str_code'];
					$pointTime = $data[$k]['charge_date']+ 180*24*3600;
					$data[$k]['point_time'] = date('Y-m-d',$pointTime);
				}

			}
			return json(['code' => 0, 'count' => $count[0]['count'], 'data' => $data]);
		}

		return $this->fetch('show',['id'=>$id,'searchTime'=>$searchTime]);
	}
}