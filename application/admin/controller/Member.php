<?php

namespace app\admin\controller;

use think\App;
use think\Db;
use think\response\Redirect;

class Member extends \app\admin\Auth
{

	/**
	 * 排单
	 *
	 * 查出已排的最大日期
	 * 最大日期已排单多少个,不足累加,超过则排单时间+1,继续排
	 *
	 **/
	private function discharge($id,$type){


		$s_type = translateMemberType($type);
		$conf = \think\Db::table('conf')->field($s_type)->find();
		$time = strtotime(date('Y-m-d',time()));
		$tmp = \think\Db::query('select max(id) as id from `wait_time` where type = '.$type." and wait_time >= ".$time);
        $strCode = $this->creteStrCode($type);
		if($tmp[0]['id'] > 0){

			$maxDate = \think\Db::query("select max(wait_time) as wait_time from `wait_time` where type = $type");
			$maxDate = strtotime(date('Y-m-d',$maxDate[0]['wait_time']));
			$num = \think\Db::table('wait_time')->where('wait_time >= '.$maxDate.' and type = '.$type)->count();
            if($num<$conf[$s_type]){
				$insert = \think\Db::table('wait_time')->insertGetId(
					[
						'mid'=>$id,
						'wait_time'=>$maxDate,
						'type'=>$type,
						'str_code'=>$strCode
					]
				);
			}else{
				$maxDate = $maxDate+24*3600;
				$insert = \think\Db::table('wait_time')->insertGetId(
					[
						'mid'=>$id,
						'wait_time'=>$maxDate,
						'type'=>$type,
						'str_code'=>$strCode
					]
				);
			}

		}else{

            /*$maxDate = \think\Db::query("select max(wait_time) as wait_time from `wait_time` where type = $type");
            $maxDate = strtotime(date('Y-m-d',$maxDate[0]['wait_time']));
            $num = \think\Db::table('wait_time')->where('wait_time >= '.$maxDate.' and type = '.$type)->count();
            if($num<$conf[$s_type]){
                $insert = \think\Db::table('wait_time')->insertGetId(
                    [
                        'mid'=>$id,
                        'wait_time'=>$maxDate,
                        'type'=>$type,
                        'str_code'=>$strCode
                    ]
                );
            }else{
                $maxDate = $maxDate+24*3600;
                $insert = \think\Db::table('wait_time')->insertGetId(
                    [
                        'mid'=>$id,
                        'wait_time'=>$maxDate,
                        'type'=>$type,
                        'str_code'=>$strCode
                    ]
                );
            }*/
			$insert = \think\Db::table('wait_time')->insertGetId(
				[
					'mid'=>$id,
					'wait_time'=>time(),
					'type'=>$type,
					'str_code'=>$strCode
				]
			);
		}

		if($insert){
			return true;
		}else{
			return false;
		}
	}

	/*
	 * 更换购买类型后
	 * 返回排单时间
	 */
	private function findWaitTime($id,$type){

		$s_type = translateMemberType($type);
		$conf = \think\Db::table('conf')->field($s_type)->find();
		$tmp = \think\Db::query('select max(id) as id from `wait_time` where type = '.$type);

		if($tmp[0]['id'] > 0){

			$maxDate = \think\Db::query("select wait_time from `wait_time` where wait_time = (select max(wait_time) from `wait_time` where type = $type) and type = ".$type);
			$num = \think\Db::table('wait_time')->where('wait_time = '.$maxDate[0]['wait_time'].' and type = '.$type)->count();

			if($num<$conf[$s_type]){

				$wait_time=$maxDate[0]['wait_time'];

			}else{
				$maxDate = $maxDate[0]['wait_time']+24*3600;
			}

		}else{

			$wait_time=time();

		}
		return $wait_time;
	}

	//生成排单号
	private function creteStrCode($type){

		//查询该类型下最新排单号
		$oldStrCode = \think\Db::query("select max(str_code) as str_code from `wait_time` where type = $type");
		$newStrCode = $oldStrCode[0]['str_code'] + 1;

		return $newStrCode;
	}

	//验证分享人是否已经添加
	private  function shareUserIsAdd($name){

		$isAdd = \think\Db::table('member')->where("name = '$name'")->find();
		if($isAdd){
			return true;
		}else{
			return false;
		}
	}

	private function  verifyData($data){

		if(strlen($data['mobile'])!=11 && !is_numeric($data['mobile'])){
			return $mes = '手机号码错误';
		}

//        if(strlen($data['bind_mobile'])!=11 && !is_numeric($data['bind_mobile'])){
//            return $mes = '云联惠绑定手机号码错误';
//        }
		if(!is_numeric($data['amount'])){
			return $mes = '金额错误';
		}
		/*if(empty($data['share_yun_id'])){
			return $mes = '分享人云联惠ID必填!!';
		}
		if(strlen($data['idcard'])!=18){
			return $mes = '身份证错误';
		}
		if(!is_numeric($data['bank_account_number'])){
			return $mes = '银行账号错误';
		}
		if(strlen($data['share_usermobile'])!=11 && !is_numeric($data['share_usermobile'])){
			return $mes = '分享人绑定手机号码错误';
		}*/
		/*if(!empty($data['share_yun_id'])) {
			$share_yun_id = \think\Db::table('member')->field('share_yun_id')->where("yun_id = '" . $data['share_yun_id'] . "'")->find();
			if ($share_yun_id['share_yun_id']) {
				if ($share_yun_id['share_yun_id'] == $data['yun_id']) {
					return $mes = '不能互为分享人';
				}
			}
		}*/

	}

	private function updateFullStatus($type){
		//第几个订单已满资格
		if($type == 1){
			$filed = 'carnumber';
		}elseif($type == 2){
			$filed = 'housenumber';
		}elseif ($type == 3){
			$filed = 'allnumber';
		}
		$conf = \think\Db::table('conf')->field($filed)->find();
		$count = \think\Db::table('member')->where('type = '.$type)->count();
		$num = intval(bcdiv($count,$conf[$filed]));
		$fullCount = \think\Db::table('member')->where('type = ' .$type. " and is_full = 1")->count();
		$updateCount = $num - $fullCount;
		if($updateCount > 0){
			$updateNotFullMember = \think\Db::field('m.id')
				->table('member m, wait_time w')
				->where('m.id = w.mid')
				->where('m.is_full = 0 and m.type = '.$type)
				->order('w.wait_time asc')
				->limit($updateCount)
				->select();
			foreach ($updateNotFullMember as $k => $v){
				$id[] = $v['id'];
			}

			$idStr = implode(',',$id);
			$update = \think\Db::table('member')->where('id in ('.$idStr.")")->update(
				[
					'is_full'=>1
				]
			);
		}
		return true;
	}
	public function showList()
	{
        $searchTime  = $this->request->param('searchTime');
        $search = addslashes($this->request->get('search'));
        $type  = $this->request->param('type');
		if ($this->request->isAjax()) {
			$page = $this->request->get('page');
			$limit = $this->request->get('limit');
			$search = addslashes($this->request->get('search'));
            $searchTime  = $this->request->get('searchTime');
            $type  = $this->request->get('type');
			$where = 'where 1';

			$page = $page > 0 ? $page - 1 : 0;
            $pagesize = $page * $limit;

            if (!empty($search)) {
                $where .= " and ( m.name like '%".$search."%' or m.mobile like '%".$search."%' or m.yun_id like '%".$search."%' or m.share_username like '%".$search."%' ) ";
            }
            if(!empty($searchTime)){
                $where .= " and from_unixtime(m.charge_date,'%Y-%m') = '".$searchTime."'";
            }
            if(!empty($type)){
                $where .= " and m.type = ".$type;
            }
            $sql = "select count(*) as count from `member` as m left join wait_time as w on m.id = w.mid left join bank_description as bd on m.bank_name = bd.bank_id left join agency as a on a.agency_id = m.agency_id ".$where;
            //d($sql);die;
            $count = \think\Db::query($sql);

            $sql = "select m.*,w.wait_time,w.str_code,bd.bank_name as bd_name,a.agency_name from `member` as m left join wait_time as w on m.id = w.mid left join bank_description as bd on m.bank_name = bd.bank_id left join agency as a on m.agency_id = a.agency_id ".$where." order by m.id desc limit ".$pagesize.",".$limit."";
            $data = \think\Db::query($sql);

			foreach ($data as $k => $v) {
				$data[$k]['charge_date'] = date('Y-m-d', $v['charge_date']);
				$data[$k]['wait_time'] = date('Y-m-d', $v['wait_time']);
				$data[$k]['amount'] = $data[$k]['amount'] + $data[$k]['extra_point'];
				if($v['type'] == 1){
					$data[$k]['type'] = '90天购车';
					$data[$k]['str_code'] = 'C90-'.$v['str_code'];
				}elseif ($v['type'] == 2){
					$data[$k]['type'] = '90天购房';
					$data[$k]['str_code'] = 'H90-'.$v['str_code'];
				}elseif ($v['type'] == 3){
					$data[$k]['type'] = '180天购车购房';
					$data[$k]['str_code'] = 'CH180-'.$v['str_code'];
				}

			}

			return json(['code' => 0, 'count' => $count[0]['count'], 'data' => $data]);
		}

        $pass = \think\Db::table('admin')->where(['id'=>session('id')])->value('permission');
        $auth = [];
        if(session('isSuper') != 1){
            foreach(self::power as $k=>$v){
                if(($pass&$k) != 0){
                    $auth[] = $v['src'];
                }
            }
        }else{
            foreach(self::power as $k=>$v){
                $auth[] = $v['src'];
            }
        }

        $this->assign('auth',$auth);
        $this->assign('searchTime', $searchTime);
        $this->assign('search', $search);
        $this->assign('type', $type);
		return $this->fetch('showList');
	}

    public function search()
    {
        $search = addslashes($this->request->get('search'));
        $searchTime  = $this->request->param('searchTime');
        $type  = $this->request->param('type');
        if ($this->request->isAjax()) {
            $page = $this->request->get('page');
            $limit = $this->request->get('limit');
            $search = addslashes($this->request->get('search'));
            $searchTime  = $this->request->get('searchTime');
            $type  = $this->request->get('type');
            $page = $page > 0 ? $page - 1 : 0;
            $pagesize = $page * $limit;

            $where = ' where 1 ';

            if (!empty($search)) {
                $where .= " and ( m.name like '%".$search."%' or m.mobile like '%".$search."%' or m.yun_id like '%".$search."%' or m.share_username like '%".$search."%' ) ";
            }else{
                return json(['code' => 0, 'count' => 0, 'data' => []]);
            }
            if(!empty($searchTime)){
                $where .= " and from_unixtime(m.charge_date,'%Y-%m') = '".$searchTime."'";
            }
            if(!empty($type)){
                $where .= " and m.type = ".$type;
            }
            $sql = "select count(*) as count from `member` as m left join wait_time as w on m.id = w.mid left join bank_description as bd on m.bank_name = bd.bank_id left join agency as a on a.agency_id = m.agency_id ".$where;
            $count = \think\Db::query($sql);

            $sql = "select m.*,w.wait_time,w.str_code,bd.bank_name as bd_name,a.agency_name from `member` as m left join wait_time as w on m.id = w.mid left join bank_description as bd on m.bank_name = bd.bank_id left join agency as a on m.agency_id = a.agency_id ".$where." order by m.id desc limit ".$pagesize.",".$limit."";
            $data = \think\Db::query($sql);

            foreach ($data as $k => $v) {
                $data[$k]['charge_date'] = date('Y-m-d', $v['charge_date']);
                $data[$k]['wait_time'] = date('Y-m-d', $v['wait_time']);
                $data[$k]['amount'] = $data[$k]['amount'] + $data[$k]['extra_point'];
                if($v['type'] == 1){
                    $data[$k]['type'] = '90天购车';
                    $data[$k]['str_code'] = 'C90-'.$v['str_code'];
                }elseif ($v['type'] == 2){
                    $data[$k]['type'] = '90天购房';
                    $data[$k]['str_code'] = 'H90-'.$v['str_code'];
                }elseif ($v['type'] == 3){
                    $data[$k]['type'] = '180天购车购房';
                    $data[$k]['str_code'] = 'CH180-'.$v['str_code'];
                }

            }

            return json(['code' => 0, 'count' => $count[0]['count'], 'data' => $data]);
        }

        $pass = \think\Db::table('admin')->where(['id'=>session('id')])->value('permission');
        $auth = [];
        if(session('isSuper') != 1){
            foreach(self::power as $k=>$v){
                if(($pass&$k) != 0){
                    $auth[] = $v['src'];
                }
            }
        }else{
            foreach(self::power as $k=>$v){
                $auth[] = $v['src'];
            }
        }

        $this->assign('auth',$auth);
        $this->assign('searchTime', $searchTime);
        $this->assign('search', $search);
        $this->assign('type', $type);
        return $this->fetch('search');
    }


	private function  showMember($id){
		$member = \think\Db::table('member')->find($id);
		return $member;
	}

	public function ajaxUserInfo(){

	    $name = trim($this->request->post('name'));
        $mobile = trim($this->request->post('mobile'));
        if(empty($name) || empty($mobile)){
            $code = -1;
            $data = '';
            return json(['code' => $code, 'data' => $data]);
        }
        $sql = "select yun_id,bind_mobile from `member` where name = '$name' and mobile = '$mobile' and yun_id <> '' limit 1";
        $userInfo = \think\Db::query($sql);
        if($userInfo){
            $code = 0;
            $data = $userInfo;
        }else{
            $code = -2;
            $data = '';
        }
        return json(['code' => $code, 'data' => $data]);
    }

	public function create()
	{

		$bank = \think\Db::table('bank_description')->order('is_top DESC,sort_order ASC')->select();
		$agency = \think\Db::table('agency')->select();
		if ($this->request->isPost()) {
			$yun_id = trim($this->request->post('yun_id'));
			$share_yun_id = trim($this->request->post('share_yun_id'));
			$idCard = $this->request->post('idcard');
			$name = trim($this->request->post('name'));
			$type = $this->request->post('type');
			$amount = trim($this->request->post('amount'));
			$extra_point = $this->request->post('extra_point');
			$bank_branch = $this->request->post('bank_branch');
			if($extra_point == 1){
				$extra_point = 5000;
			}else{
				$extra_point = 0;
			}
			$data = [
				'name' => $name,
				'mobile' => trim($this->request->post('mobile')),
				'yun_id' => $yun_id,
				'bind_mobile' => trim($this->request->post('bind_mobile')),
				'amount' => trim($this->request->post('amount')),
				'charge_date' => strtotime($this->request->post('charge_date')),
				'payment' => trim($this->request->post('payment')),
				'idcard' => $idCard,
				'bank_username' => trim($this->request->post('bank_username')),
				'bank_name' => $this->request->post('bank_name'),
				'bank_branch' => $this->request->post('bank_branch'),
				'bank_account_number' => str_replace(' ','',$this->request->post('bank_account_number')),
				'agency_id' => $this->request->post('agency_id'),
				'share_username' => trim($this->request->post('share_username')),
				'share_yun_id' => trim($this->request->post('share_yun_id')),
				'share_usermobile' => trim($this->request->post('share_usermobile')),
				'type'=> $type,
				'status' => 1,
				'remarks' => $this->request->post('remarks'),
				'extra_point'=>$extra_point,
				'add_time' => time(),
				'update_time' => time()
			];

			$verify = $this->verifyData($data);
			if($verify){
				$this->error($verify);
			}
			//如果该用户已报过单,且有分享人id,以后报单将分享人更改为之前的分享人,先注释这一步,老数据录完后开启
			/*$selectShareYunId = \think\Db::table('member')->field('share_yun_id')->where("yun_id = '".$data['yun_id']."'")->find();
			if($selectShareYunId['share_yun_id']){
				$data['share_yun_id'] = $selectShareYunId['share_yun_id'];
			}*/
			//分单
			if($type == 1){
				$unitPrice = 35000;
			}elseif($type == 2){
				$unitPrice = 30000;
			}elseif($type == 3){
				$unitPrice = 20000;
			}
			if($data['amount']%$unitPrice == 0){
				$orderNums = $amount/$unitPrice;
			}else{
				$this->error('请输入正确的金额');
			}
			$paramWaitTime = strtotime($this->request->post('wait_time'));

			\think\Db::startTrans();
			try {
				$data['amount'] = $unitPrice;
				for($i = 1;$i<=$orderNums;$i++){
					$member_id = \think\Db::table('member')->insertGetId($data);
					if($paramWaitTime){
						$strCode = $this->creteStrCode($type);
						$return = \think\Db::table('wait_time')->insertGetId(
							[
								'mid'=>$member_id,
								'wait_time'=>$paramWaitTime,
								'type'=>$type,
								'str_code'=>$strCode
							]
						);
					}else{
						$return = $this->discharge($member_id, $type);
					}

				}

				$updateFull = $this->updateFullStatus($type);
				//存储分享云联惠id
				if(!empty($data['share_yun_id'])){
					$shareYunIdIsExist = \think\Db::table('share')->where("share_yun_id = '" .$data['share_yun_id']. "'")->find();
					$conf = getConf();
					$tmpPoint = bcdiv($conf[0]['point'], 100, 2);
					$point = bcmul($data['amount'], $tmpPoint)*$orderNums;
					if (empty($shareYunIdIsExist)) {
						$insertShareYunId = \think\Db::table('share')->insertGetId(
							[
								'share_yun_id' => $data['share_yun_id'],
								'point' => $point
							]
						);
					} else {
						$updateShareYunIdPoint = \think\Db::query("update `share` set point = point+" .$point. " where share_yun_id = '" .$data['share_yun_id']. "'");
					}
				}


				if ($type == 3) {
					$isExistUser = \think\Db::table('user')->where("yun_id = '".$yun_id."'")->find();
					if (empty($isExistUser)) {
						$insertUser = \think\Db::table('user')->insertGetId(
							[
								'yun_id' => $yun_id,
							]
						);
					}
				}

				\think\Db::commit();

			} catch (\Exception $e) {

				\think\Db::rollback();
				$this->error('系统繁忙，稍后再试');

			}

			$this->success('新增成功', 'admin/member/create');

		}

		return $this->fetch('create', ['bank' => $bank,'agency'=>$agency]);
	}

	public function show($id){

		$id = $_GET['id'];
		$bank = \think\Db::table('bank_description')->order('is_top DESC,sort_order ASC')->select();

		$member = $this->showMember($id);
		$agency = \think\Db::table('agency')->select();
		$member['charge_date'] = date('Y-m-d',$member['charge_date']);

		$this->assign('bank',$bank);
		$this->assign('member',$member);
		$this->assign('agency',$agency);
		return $this->fetch('show');
	}

	public function update($id)
	{

		$member = $this->showMember($id);

		$member['charge_date'] = date('Y-m-d',$member['charge_date']);

		$bank = \think\Db::table('bank_description')->order('is_top DESC,sort_order ASC')->select();
		$agency = \think\Db::table('agency')->select();
		if ($this->request->isPost()) {

			$yun_id = trim($this->request->post('yun_id'));
			$share_yun_id = trim($this->request->post('share_yun_id'));
			$idCard = trim($this->request->post('idcard'));
			$name = trim($this->request->post('name'));

			$data = [
				'name' => $name,
				'mobile' => trim($this->request->post('mobile')),
				'yun_id' => $yun_id,
				'bind_mobile' => trim($this->request->post('bind_mobile')),
				'amount' => trim($this->request->post('amount')),
				//'charge_date' => strtotime($this->request->post('charge_date')),
				'payment' => trim($this->request->post('payment')),
				'idcard' => $idCard,
				'bank_username' => trim($this->request->post('bank_username')),
				'bank_name' => $this->request->post('bank_name'),
				'bank_branch' => $this->request->post('bank_branch'),
				'bank_account_number' => trim($this->request->post('bank_account_number')),
				'agency_id' => $this->request->post('agency_id'),
				'share_username' => trim($this->request->post('share_username')),
				'share_yun_id' => trim($this->request->post('share_yun_id')),
				'share_usermobile' => trim($this->request->post('share_usermobile')),
				'update_time' => time(),
				'remarks' => $this->request->post('remarks')
			];

			$verify = $this->verifyData($data);
			if($verify){
				$this->error($verify);
			}
			if(!empty($data['share_yun_id'])){
				if($data['share_yun_id'] !=  $member['share_yun_id']){

					$shareYunIdIsExist = \think\Db::table('share')->where("share_yun_id = '" .$data['share_yun_id']. "'")->find();
					$conf = getConf();
					$tmpPoint = bcdiv($conf[0]['point'], 100, 2);
					$point = bcmul($data['amount'], $tmpPoint);
					if (empty($shareYunIdIsExist)) {
						$insertShareYunId = \think\Db::table('share')->insertGetId(
							[
								'share_yun_id' => $data['share_yun_id'],
								'point' => $point
							]
						);
					} else {
						$updateShareYunIdPoint = \think\Db::query("update `share` set point = point+" .$point. " where share_yun_id = '" .$data['share_yun_id']. "'");
					}

					//减去原分享云联惠id的积分
					$updatePoint = intval(bcmul($member['amount'],$tmpPoint));
					$shareOldPoint = \think\Db::table('share')->where("share_yun_id = '" .$member['share_yun_id']. "'")->find();

					if($shareOldPoint['point'] > $updatePoint){
						$updateShareOldYunIdPoint = \think\Db::query("update `share` set point = point-" .$updatePoint. " where share_yun_id = '" .$member['share_yun_id']. "'");
					}else{
						$deleteShare = \think\Db::table('share')->where("share_yun_id = '".$member['share_yun_id']."'")->delete();
					}

				}
			}

			$return = \think\Db::table('member')->where(['id' => $id])->update($data);


			if ($return) {
				$this->success('编辑成功', 'admin/member/showList');
			} else {
				$this->error('系统繁忙，稍后再试');
			}

		}

		return $this->fetch('update', ['bank' => $bank, 'member' => $member,'agency'=>$agency]);
	}



	public function delete(){

		$id = $this->request->param('id');

		$isExist = \think\Db::table('member m, wait_time w')
			->where('m.id = w.mid')
			->where('m.id = '.$id)
			->field('w.*,m.amount,m.share_yun_id,m.add_time,m.yun_id')
			->find();

		if(count($isExist) > 0){

			/*$maxId = \think\Db::query('select max(id) as id from `wait_time`');
			$waitId = \think\Db::table('wait_time')->where('mid = '.$id)->field('id')->find();*/

			//减去原分享云联惠id的积分
			$conf = getConf();
			$tmpPoint = bcdiv($conf[0]['point'], 100, 2);
			$point = bcmul($isExist['amount'], $tmpPoint);
			$shareOldPoint = \think\Db::table('share')->where("share_yun_id = '" .$isExist['share_yun_id']. "'")->find();
			\think\Db::startTrans();
			try {
				if ($shareOldPoint['point'] > $point) {
					$updateShareOldYunIdPoint = \think\Db::query("update `share` set point = point-" . $point . " where share_yun_id = '" . $isExist['share_yun_id'] . "'");
				} else {
					$deleteShare = \think\Db::table('share')->where("share_yun_id = '" . $isExist['share_yun_id'] . "'")->delete();
				}

				\think\Db::table('member')->where(['id' => $id])->delete();

				\think\Db::table('wait_time')->where(['mid' => $id])->delete();

				if ($isExist['type'] == 1) {
					$filed = 'carnumber';
				} elseif ($isExist['type'] == 2) {
					$filed = 'housenumber';
				} elseif ($isExist['type'] == 3) {
					$filed = 'allnumber';
				}
				$conf = \think\Db::table('conf')->field($filed)->find();
				$count = \think\Db::table('member')->where('type = ' . $isExist['type'])->count();
				$num = intval(bcdiv($count,$conf[$filed]));
				$fullCount = \think\Db::table('member')->where('type = ' . $isExist['type'] . " and is_full = 1")->count();
				if ($num < $fullCount) {
					$subtractNum = $fullCount - $num;
					$updateNotFullMember = \think\Db::field('m.id')
						->table('member m, wait_time w')
						->where('m.id = w.mid')
						->where('m.is_full = 1 and m.type = ' . $isExist['type'])
						->order('w.wait_time desc')
						->limit($subtractNum)
						->select();

					foreach ($updateNotFullMember as $k => $v) {
						$update = \think\Db::table('member')->where('id = '.$v['id'])->update(
							[
								'is_full' => 0
							]
						);
					}
				}

				if($isExist['type'] == 3){
					//add_time 大于十五天,减user表的积分
					$tmpTime = 15*24*3600;
					if(time() - $isExist['add_time'] >= $tmpTime){
						$update = \think\Db::query("update `user` set point = point - ".$isExist['amount']." where yun_id = '".$isExist['yun_id']."'");
					}
					//如果180天member表没有该用户,则user表也删除该用户
					$select_member = \think\Db::table('member')->where("yun_id = '".$isExist['yun_id']."'") -> find();
					if(empty($select_member)){
						\think\Db::table('user')->where("yun_id = '".$isExist['yun_id']."'")->delete();
					}
				}
				\think\Db::commit();

			} catch (\Exception $e) {

				\think\Db::rollback();

				return json(['code'=>-1,'msg'=>'系统繁忙，稍后再试']);
			}

		}else{

			return json(['code'=>-1,'msg'=>'系统繁忙，稍后再试']);
		}

		return json(['code'=>0,'msg'=>'删除成功']);

	}

	public function export(){

        $search = addslashes($this->request->get('search'));
        $searchTime  = $this->request->get('searchTime');
        $type  = $this->request->get('type');
        $where = 'where 1';
        if (!empty($search)) {
            $where .= " and ( m.name like '%".$search."%' or m.mobile like '%".$search."%' or m.yun_id like '%".$search."%' or m.share_username like '%".$search."%' )";
        }
        if(!empty($searchTime)){
            $where .= " and from_unixtime(m.charge_date,'%Y-%m') = '".$searchTime."'";
        }
        if(!empty($type)){
            $where .= " and m.type = ".$type;
        }
        $data = [];
        $sql = "select m.*,w.wait_time,w.str_code,bd.bank_name as bd_name,a.agency_name from `member` as m left join wait_time as w on m.id = w.mid left join bank_description as bd on m.bank_name = bd.bank_id left join agency as a on m.agency_id = a.agency_id ".$where." order by m.id desc";
        $data = \think\Db::query($sql);

        foreach ($data as $k => $v) {
            $data[$k]['charge_date'] = date('Y-m-d', $v['charge_date']);
            $data[$k]['wait_time'] = date('Y-m-d', $v['wait_time']);
            $data[$k]['amount'] = $data[$k]['amount'] + $data[$k]['extra_point'];
            if($v['type'] == 1){
                $data[$k]['type'] = '90天购车';
                $data[$k]['str_code'] = 'C90-'.$v['str_code'];
            }elseif ($v['type'] == 2){
                $data[$k]['type'] = '90天购房';
                $data[$k]['str_code'] = 'H90-'.$v['str_code'];
            }elseif ($v['type'] == 3){
                $data[$k]['type'] = '180天购车购房';
                $data[$k]['str_code'] = 'CH180-'.$v['str_code'];
            }
        }

        $cellArray = [
            ['cellName' => '汇款时间','dataKeyName' => 'charge_date','width' =>'50'],
            ['cellName' => '排单日期','dataKeyName' => 'wait_time','width' =>'50'],
            ['cellName' => '排单号','dataKeyName' => 'str_code','width' =>'50'],
            ['cellName' => '姓名','dataKeyName' => 'name','width' =>'50'],
            ['cellName' => '金额','dataKeyName' => 'amount','width' =>'50'],
            ['cellName' => '云联惠ID','dataKeyName' => 'yun_id','width' =>'50'],
            ['cellName' => '手机号','dataKeyName' => 'mobile','width' =>'50'],
            ['cellName' => '分享人','dataKeyName' => 'share_username','width' =>'50'],
            ['cellName' => '分享人ID','dataKeyName' => 'share_yun_id','width' =>'50'],
            ['cellName' => '分享人手机号','dataKeyName' => 'share_usermobile','width' =>'50'],
            ['cellName' => '购买类型','dataKeyName' => 'type','width' =>'50'],
            ['cellName' => '办事处','dataKeyName' => 'agency_name','width' =>'50'],
            ['cellName' => '备注','dataKeyName' => 'remarks','width' =>'50']
        ];
        \think\Loader::import('PHPExcel.ExcelHandle');
        $excelHandle = new \ExcelHandle();
        $excelHandle->exportExcel('报单-'.$searchTime,$cellArray,$data);
    }

    public function todayList(){
        $search = addslashes($this->request->get('search'));
        $type  = $this->request->param('type');
        if ($this->request->isAjax()) {
            $page = $this->request->get('page');
            $limit = $this->request->get('limit');
            $search = addslashes($this->request->get('search'));
            $type  = $this->request->get('type');
            $where = 'where 1';
            $tmp_time = date('Y-m-d',time());
            $searchTime = date('Y-m-d',strtotime($tmp_time." -90 day"));
            $where .= " and from_unixtime(w.wait_time,'%Y-%m-%d') = '".$searchTime."'";
            $page = $page > 0 ? $page - 1 : 0;
            $pagesize = $page * $limit;

            if (!empty($search)) {
                $where .= " and ( m.name like '%".$search."%' or m.mobile like '%".$search."%' or m.yun_id like '%".$search."%' or m.share_username like '%".$search."%' ) ";
            }
            if(!empty($type)){
                $where .= " and m.type = ".$type;
            }
            $sql = "select count(*) as count from `member` as m left join wait_time as w on m.id = w.mid left join bank_description as bd on m.bank_name = bd.bank_id left join agency as a on a.agency_id = m.agency_id ".$where;
            $count = \think\Db::query($sql);

            $sql = "select m.*,w.wait_time,w.str_code,bd.bank_name as bd_name,a.agency_name from `member` as m left join wait_time as w on m.id = w.mid left join bank_description as bd on m.bank_name = bd.bank_id left join agency as a on m.agency_id = a.agency_id ".$where." order by m.id desc limit ".$pagesize.",".$limit."";
            $data = \think\Db::query($sql);

            foreach ($data as $k => $v) {
                $data[$k]['charge_date'] = date('Y-m-d', $v['charge_date']);
                $data[$k]['wait_time'] = date('Y-m-d', $v['wait_time']);
                $data[$k]['amount'] = $data[$k]['amount'] + $data[$k]['extra_point'];
                if($v['type'] == 1){
                    $data[$k]['type'] = '90天购车';
                    $data[$k]['str_code'] = 'C90-'.$v['str_code'];
                }elseif ($v['type'] == 2){
                    $data[$k]['type'] = '90天购房';
                    $data[$k]['str_code'] = 'H90-'.$v['str_code'];
                }elseif ($v['type'] == 3){
                    $data[$k]['type'] = '180天购车购房';
                    $data[$k]['str_code'] = 'CH180-'.$v['str_code'];
                }
                if($v['is_full'] == 1){
                    $data[$k]['isfull'] = '已满' ;
                }else{
                    $data[$k]['isfull'] = '未满' ;
                }

            }

            return json(['code' => 0, 'count' => $count[0]['count'], 'data' => $data]);
        }

        $pass = \think\Db::table('admin')->where(['id'=>session('id')])->value('permission');
        $auth = [];
        if(session('isSuper') != 1){
            foreach(self::power as $k=>$v){
                if(($pass&$k) != 0){
                    $auth[] = $v['src'];
                }
            }
        }else{
            foreach(self::power as $k=>$v){
                $auth[] = $v['src'];
            }
        }

        $this->assign('auth',$auth);
        $this->assign('search', $search);
        $this->assign('type', $type);
        return $this->fetch('todayList');
    }

}
