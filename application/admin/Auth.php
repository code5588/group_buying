<?php
namespace app\admin;
use think\App;

class Auth extends \think\Controller
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
//        262144 =>[
//            'title' => '办事处奖励列表',
//            'src'   => 'reward/agenctshowlist'
//        ],
        524288 =>[
            'title' => '今日出单',
            'src'   => 'member/todaylist'
        ],
    ];

    public function _initialize()
    {
        $controller = \think\Request::instance()->controller();
        $action     = \think\Request::instance()->action();
        if(session('isAdmin') != 1){
            $this->redirect(url('site/login'));
        }

        $pass = \think\Db::table('admin')->where(['id'=>session('id')])->value('permission');

        if(session('isSuper')=='0'){
            if(empty($pass)){
                $this->error('您没有权限');
                exit;
            }
            foreach(self::power as $k=>$v){
                if($v['src'] == strtolower($controller.'/'.$action)){
                    if(($pass&$k) == 0){
                        $this->error('您没有权限!','admin/index/main');
                        exit;
                    }
                }
            }
        }
    }
}
