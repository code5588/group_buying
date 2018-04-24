<?php
/**
 * Created by PhpStorm.
 * User: Xubin
 * Date: 2017/12/26
 * Time: 14:08
 */

namespace app\admin\controller;

use think\App;
use think\Db;
use think\response\Redirect;

class Statistics extends \app\admin\Auth
{
	public function showList(){


		$today = date('Y-m-d');
		$time = strtotime($today);
		$tmpTime = 90*24*3600;
		//90天购车统计
		$car= \think\Db::query('select sum(amount)+sum(extra_point) as amount, count(*) as count from `member` where type = 1');
		$todayCar = \think\Db::query('select sum(amount)+sum(extra_point) as amount, count(*) as count from `member` where type = 1 and from_unixtime(add_time,\'%Y-%m-%d\') = '."'".$today."'");
		$carFull = \think\Db::query("select sum(amount)+sum(extra_point) as amount, count(*) as count from member m , wait_time  w where m.id = w.mid and m.type = 1 and $time - (w.wait_time+$tmpTime) >= 0");
		$carToDayFull = \think\Db::query("select sum(amount)+sum(extra_point) as amount, count(*) as count from member m , wait_time  w where m.id = w.mid and m.type = 1 and $time - (w.wait_time+$tmpTime) = 0");

		//90天购房统计
		$house= \think\Db::table('member')->query('select sum(amount)+sum(extra_point) as amount, count(*) as count from `member` where type = 2');
		$todayHouse = \think\Db::table('member')->query('select sum(amount)+sum(extra_point) as amount, count(*) as count from `member` where type = 2 and from_unixtime(add_time,\'%Y-%m-%d\') = '."'".$today."'");
		$houseFull = \think\Db::query("select sum(amount)+sum(extra_point) as amount, count(*) as count from member m , wait_time  w where m.id = w.mid and m.type = 2 and $time - (w.wait_time+$tmpTime) >= 0");
		$houseToDayFull = \think\Db::query("select sum(amount)+sum(extra_point) as amount, count(*) as count from member m , wait_time  w where m.id = w.mid and m.type = 2 and $time - (w.wait_time+$tmpTime) = 0");

		//180天购车购房统计
		$all= \think\Db::table('member')->query('select sum(amount)+sum(extra_point) as amount, count(*) as count from `member` where type = 3');
		$todayAll = \think\Db::table('member')->query('select sum(amount)+sum(extra_point) as amount, count(*) as count from `member` where type = 3 and from_unixtime(add_time,\'%Y-%m-%d\') = '."'".$today."'");
		$allFull = \think\Db::query("select sum(amount)+sum(extra_point) as amount, count(*) as count from member m , wait_time  w where m.id = w.mid and m.type = 3 and $time - (w.wait_time+$tmpTime) >= 0");
		$allToDayFull = \think\Db::query("select sum(amount)+sum(extra_point) as amount, count(*) as count from member m , wait_time  w where m.id = w.mid and m.type = 3 and $time - (w.wait_time+$tmpTime) = 0");


		$this->assign('car',$car[0]);
		$this->assign('todayCar',$todayCar[0]);
		$this->assign('carFull',$carFull[0]);
		$this->assign('carToDayFull',$carToDayFull[0]);

		$this->assign('house',$house[0]);
		$this->assign('todayHouse',$todayHouse[0]);
		$this->assign('houseFull',$houseFull[0]);
		$this->assign('houseToDayFull',$houseToDayFull[0]);

		$this->assign('all',$all[0]);
		$this->assign('todayAll',$todayAll[0]);
		$this->assign('allFull',$allFull[0]);
		$this->assign('allToDayFull',$allToDayFull[0]);
	   return  $this->fetch('showList');
	}

}