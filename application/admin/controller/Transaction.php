<?php
namespace app\admin\controller;
use think\App;
use think\Db;
use think\response\Redirect;

class Transaction extends \app\admin\Auth
{

	//根据排单号,已获得购买资格的排单号
	private function righrStrCode($strCode,$type){

		if($type == 1){
			$filed = 'carnumber';
		}elseif($type == 2){
			$filed = 'housenumber';
		}elseif ($type == 3){
			$filed = 'allnumber';
		}
		$conf = \think\Db::table('conf')->field($filed)->find();
		$righrStrCode = bcdiv($strCode,$conf[$filed]);

		return $righrStrCode;
	}

	public function car(){

		if($this->request->isAjax()){
			$page = $this->request->get('page');
			$limit = $this->request->get('limit');
			$search = $this->request->get('search');

            $where = ' where m.type = 1';
			if (!empty($search)) {
                $where .= " and m.name like '%".$search."%' or m.mobile like '%".$search."%' or m.yun_id like '%".$search."%' or m.share_username like '%".$search."%'";
			}
			$page = $page > 0 ? $page - 1 : 0;

            $pagesize = $page * $limit;
            $sql = "select count(*) as count from `member` as m left join wait_time as w on m.id = w.mid left join bank_description as bd on m.bank_name = bd.bank_id left join agency as a on a.agency_id = m.agency_id ".$where;
            $count = \think\Db::query($sql);

            $sql = "select m.*,w.wait_time,w.str_code,bd.bank_name as bd_name,a.agency_name from `member` as m left join wait_time as w on m.id = w.mid left join bank_description as bd on m.bank_name = bd.bank_id left join agency as a on m.agency_id = a.agency_id ".$where." order by m.id desc limit ".$pagesize.",".$limit."";
            $data = \think\Db::query($sql);

			foreach ($data as $k => $v) {
				$data[$k]['charge_date'] = date('Y-m-d', $v['charge_date']);
				$data[$k]['wait_time'] = date('Y-m-d', $v['wait_time']);
				$data[$k]['str_code'] = 'C90-'.$v['str_code'];
				$data[$k]['amount'] = $data[$k]['amount'] + $data[$k]['extra_point'];
				$data[$k]['bank_name'] = $data[$k]['bd_name'].$data[$k]['bank_branch'];
				if($v['is_full'] == 1){
					$data[$k]['isfull'] = '已满' ;
				}else{
					$data[$k]['isfull'] = '未满' ;
				}
				$expiredTime = strtotime(date('Y-m-d',$v['wait_time']))+90*24*3600;
				$nowTime = strtotime(date('Y-m-d',time()));
				if($expiredTime <= $nowTime){
					$data[$k]['expired'] = '已到期' ;
				}else{
					$day = intval(($expiredTime - $nowTime)/(24*3600));
					$data[$k]['expired'] = '还有'.$day."天";
				}
			}

			return json(['code' => 0, 'count' => $count[0]['count'], 'data' => $data]);
		}

		return $this->fetch('nine_car');
	}

	public function house(){

		if($this->request->isAjax()){
			$page = $this->request->get('page');
			$limit = $this->request->get('limit');
			$search = $this->request->get('search');

            $where = ' where m.type = 2';
            if (!empty($search)) {
                $where .= " and m.name like '%".$search."%' or m.mobile like '%".$search."%' or m.yun_id like '%".$search."%' or m.share_username like '%".$search."%'";
            }

            $page = $page > 0 ? $page - 1 : 0;

            $pagesize = $page * $limit;
            $sql = "select count(*) as count from `member` as m left join wait_time as w on m.id = w.mid left join bank_description as bd on m.bank_name = bd.bank_id left join agency as a on a.agency_id = m.agency_id ".$where;
            $count = \think\Db::query($sql);

            $sql = "select m.*,w.wait_time,w.str_code,bd.bank_name as bd_name,a.agency_name from `member` as m left join wait_time as w on m.id = w.mid left join bank_description as bd on m.bank_name = bd.bank_id left join agency as a on a.agency_id = m.agency_id ".$where." order by w.str_code desc limit ".$pagesize.",".$limit."";
            $data = \think\Db::query($sql);

			foreach ($data as $k => $v) {
				$data[$k]['charge_date'] = date('Y-m-d', $v['charge_date']);
				$data[$k]['wait_time'] = date('Y-m-d', $v['wait_time']);
				$data[$k]['str_code'] = 'H90-'.$v['str_code'];
				$data[$k]['amount'] = $data[$k]['amount'] + $data[$k]['extra_point'];
				$data[$k]['bank_name'] = $data[$k]['bd_name'].$data[$k]['bank_branch'];
				if($v['is_full'] == 1){
					$data[$k]['isfull'] = '已满' ;
				}else{
					$data[$k]['isfull'] = '未满' ;
				}
				$expiredTime = strtotime(date('Y-m-d',$v['wait_time']))+90*24*3600;
				$nowTime = strtotime(date('Y-m-d',time()));
				if($expiredTime <= $nowTime){
					$data[$k]['expired'] = '已到期' ;
				}else{
					$day = intval(($expiredTime - $nowTime)/(24*3600));
					$data[$k]['expired'] = '还有'.$day."天";
				}
			}

			return json(['code' => 0, 'count' => $count[0]['count'], 'data' => $data]);
		}

		return $this->fetch('nine_house');
	}

	public function all(){

		if($this->request->isAjax()){
			$page = $this->request->get('page');
			$limit = $this->request->get('limit');
			$search = $this->request->get('search');

            $where = ' where m.type = 3';
            if (!empty($search)) {
                $where .= " and m.name like '%".$search."%' or m.mobile like '%".$search."%' or m.yun_id like '%".$search."%' or m.share_username like '%".$search."%'";
            }

            $page = $page > 0 ? $page - 1 : 0;

            $pagesize = $page * $limit;
            $sql = "select count(*) as count from `member` as m left join wait_time as w on m.id = w.mid left join bank_description as bd on m.bank_name = bd.bank_id left join agency as a on a.agency_id = m.agency_id ".$where;
            $count = \think\Db::query($sql);

            $sql = "select m.*,w.wait_time,w.str_code,bd.bank_name as bd_name,a.agency_name from `member` as m left join wait_time as w on m.id = w.mid left join bank_description as bd on m.bank_name = bd.bank_id left join agency as a on m.agency_id = a.agency_id  ".$where." order by m.id desc limit ".$pagesize.",".$limit."";
            $data = \think\Db::query($sql);

			foreach ($data as $k => $v) {
				$data[$k]['charge_date'] = date('Y-m-d', $v['charge_date']);
				$data[$k]['wait_time'] = date('Y-m-d', $v['wait_time']);
				$data[$k]['str_code'] = 'CH180-'.$v['str_code'];
				$data[$k]['amount'] = $data[$k]['amount'] + $data[$k]['extra_point'];
				$data[$k]['bank_name'] = $data[$k]['bd_name'].$data[$k]['bank_branch'];
				if($v['is_full'] == 1){
					$data[$k]['isfull'] = '已满' ;
				}else{
					$data[$k]['isfull'] = '未满' ;
				}
				$expiredTime = strtotime(date('Y-m-d',$v['wait_time']))+180*24*3600;
				$nowTime = strtotime(date('Y-m-d',time()));
				if($expiredTime <= $nowTime){
					$data[$k]['expired'] = '已到期' ;
				}else{
					$day = intval(($expiredTime - $nowTime)/(24*3600));
					$data[$k]['expired'] = '还有'.$day."天";
				}
			}

			return json(['code' => 0, 'count' => $count[0]['count'], 'data' => $data]);
		}

		return $this->fetch('eight');
	}

	public function conf(){

		$conf = \think\Db::table('conf')->where('id=1')->find();

		if($this->request->isPost()){

			$data=[
				'car'=>$this->request->post('car'),
				'house'=>$this->request->post('house'),
				'all'=>$this->request->post('all'),
				'carnumber'=>$this->request->post('carnumber'),
				'housenumber'=>$this->request->post('housenumber'),
				'allnumber'=>$this->request->post('allnumber'),
				'point'=>$this->request->post('point'),
				'agencypoint1'=>$this->request->post('agencypoint1'),
				'agencypoint2'=>$this->request->post('agencypoint2'),
			];

			$return = \think\Db::table('conf')->where('id=1')->update($data);

			if ($return) {
				$this->success('编辑成功', 'admin/transaction/conf');
			} else {
				$this->error('编辑失败');
			}

		}

		return $this->fetch('conf',['conf'=>$conf]);
	}
}
