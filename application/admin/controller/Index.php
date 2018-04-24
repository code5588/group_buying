<?php
namespace app\admin\controller;
use think\App;

class Index extends \app\admin\Auth
{
    const power = [
        1   =>  [
            'title' => '添加报单',
            'src'   => 'member/create'
        ],
        2   =>  [
            'title' => '编辑报单',
            'src'   => 'member/update'
        ],
        4   => [
            'title' => '报单列表',
            'src'   => 'member/showlist'
        ],
        8   => [
            'title' => '删除报单',
            'src'   => 'member/delete'
        ],
        /*16  => [
            'title' => '报单插队',
            'src'   => 'member/jump'
        ],*/
        32  => [
            'title' => '90天购车列表',
            'src'   => 'transaction/car'
        ],
        64  => [
            'title' => '90天购房列表',
            'src'   => 'transaction/house'
        ],
        128 => [
            'title' => '180天车房列表',
            'src'   => 'transaction/all'
        ],
        256 => [
            'title' => '分享统计',
            'src'   => 'share/showlist'
        ],
        512 => [
            'title' => '办事处列表',
            'src'   => 'agency/showlist'
        ],
        1024 => [
            'title' => '编辑办事处',
            'src'   => 'agency/update'
        ],
        2048 => [
            'title' => '添加办事处',
            'src'   => 'agency/create'
        ],
        4096 =>[
            'title' => '180天用户积分',
            'src'   => 'userpoint/showlist'
        ],
        8192 => [
            'title' => '参数配置',
            'src'   => 'transaction/conf'
        ],
        16384 => [
            'title' => '管理员',
            'src'   => 'admin/showlist'
        ],
        32768 =>[
            'title' => '统计',
            'src'   => 'statistics/showlist'
        ],
        65536 =>[
            'title' => '办事处奖励列表',
            'src'   => 'reward/agenctshowlist'
        ],
        131072 =>[
            'title' => '搜索',
            'src'   => 'member/search'
        ],
        262144 =>[
            'title' => '办事处奖励列表',
            'src'   => 'reward/agenctshowlist'
        ],
        524288 =>[
            'title' => '今日出单',
            'src'   => 'member/todaylist'
        ],
    ];
    public function index()
    {
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
        return view();
    }

    private function updateUserPoint(){
        //180天订单15天后返等额积分
        $tmpTime = 15*24*3600;
        $time = time();

        $user = \think\Db::table('user')->select();
        foreach ($user as $k => $v){
            $memberOrder = \think\Db::query("select id,amount from `member` where $time - charge_date >= $tmpTime and type = 3  and is_rebate = 0 and yun_id = '".$v['yun_id']."'");
            $point = 0;
            foreach ($memberOrder as $key => $val){
                $point += $val['amount'];
                $update_member = \think\Db::table('member')->where('id = '.$val['id'])->update(
                    [
                        'is_rebate'=>1
                    ]
                );
            }
            $update = \think\Db::query("update `user` set point = point + ".$point." where yun_id = '".$v['yun_id']."'");

        }


    }

    public function main(){

        $this->updateUserPoint();
        echo '欢迎使用';
    }


}
