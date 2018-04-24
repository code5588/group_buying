<?php
namespace app\admin\controller;
use think\App;
use think\Db;
use think\response\Redirect;

class Agency extends \app\admin\Auth
{
	public function showList(){

		if($this->request->isAjax()){
			$page = $this->request->get('page');
			$limit = $this->request->get('limit');
			$search = $this->request->get('search');
			$where = [];
			if(!empty($search)){
				$where = ['agency_id|agency_name'=>['like','%'.$search.'%']];
			}

			$page = $page>0?$page-1:0;

			$count = \think\Db::table("agency")->where($where)->count();
			$data = \think\Db::table("agency")->where($where)->limit($page*$limit,$limit)->select();

			return json(['code'=>0,'count'=>$count,'data'=>$data]);
		}

		return $this->fetch('showList');
	}

	public function create(){

		if($this->request->isPost()){
			$data = [
				'agency_name' => $this->request->post('agency_name'),
				'date_added' => time(),
				'date_modified' => time(),
			];
			$return = \think\Db::table('agency')->insertGetId($data);
			if($return){
				$this->success('新增成功', 'admin/agency/showList');
			}else{
				$this->error('新增失败');
			}
		}

		return $this->fetch('create');
	}

	public function update($id){
		$agency = \think\Db::table('agency')->where(['agency_id'=>$id])->find();

		if($this->request->isPost()){
			$data = [
				'agency_name' => $this->request->post('agency_name'),
				'date_modified' => time(),
			];
			$return = \think\Db::table('agency')->where(['agency_id'=>$id])->update($data);
			if($return){
				$this->success('编辑成功', 'admin/agency/showList');
			}else{
				$this->error('编辑失败');
			}
		}

		return $this->fetch('update',['agency'=>$agency]);
	}

	public function showRebate(){

		$id = $this->request->param('id');
		$searchTime = $this->request->post('searchTime');
		if($searchTime){
			$conf = getConf();
			$agencyPoint1 = $conf[0]['agencypoint1']/100;
			$agencyPoint2 = $conf[0]['agencypoint2']/100;

			$member = \think\Db::query("select sum(amount)+sum(extra_point) as amount from `member` where from_unixtime(charge_date,'%Y-%m') = '$searchTime' and agency_id = ".$id);
			$agencyTotalPoint1 = bcmul($agencyPoint1,$member[0]['amount']);
			$agencyTotalPoint2 = bcmul($agencyPoint2,$member[0]['amount']);

			$time1 = date('Y-m-5',strtotime("$searchTime +1 month"));
			$time2 = date('Y-m-5',strtotime("$searchTime +4 month"));
			$this->assign('agencyTotalPoint1',$agencyTotalPoint1);
			$this->assign('agencyTotalPoint2',$agencyTotalPoint2);
			$this->assign('time1',$time1);
			$this->assign('time2',$time2);
		}else{
			$this->assign('agencyTotalPoint1',0);
			$this->assign('agencyTotalPoint2',0);
			$this->assign('time1','');
			$this->assign('time2','');
		}
			$this->assign('searchTime',$searchTime);
		return $this->fetch('showRebate',['id'=>$id]);
	}

	public function show()
	{
		$id = $this->request->param('id');
		$searchTime = $this->request->param('searchTime');
		if ($this->request->isAjax()) {

			$page = $this->request->get('page');
			$limit = $this->request->get('limit');
			$pages = $page > 0 ? $page - 1 : 0;
			$count  = \think\Db::query("select count(*) as count from `member` where agency_id = $id and from_unixtime(charge_date,'%Y-%m') = '$searchTime'");
			$pagesize = $page*$limit;
			$member = \think\Db::query("select m.charge_date,m.amount,m.name,m.mobile,m.yun_id,m.type,w.str_code,w.wait_time from `wait_time` as w, `member` as m where m.id = w.mid and m.agency_id = $id and from_unixtime(charge_date,'%Y-%m') = '$searchTime' limit $pages,$pagesize");
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
		$this->assign('id', $id);
		$this->assign('searchTime', $searchTime);
		return $this->fetch('show');
	}
}
