<?php
namespace app\admin\controller;
use think\App;

class Site extends \think\Controller
{
    public function login()
    {
        if($this->request->isPost()){

            $user = \think\Db::table('admin')->where(['username'=>$this->request->post('userName'),'password'=>md5($this->request->post('password'))])->find();
            if(!empty($user)){
                \think\Session::set ('id',$user['id']);
                \think\Session::set('username',$user['username']);
                \think\Session::set ('isAdmin',1);
                \think\Session::set ('isSuper',$user['is_super']);
                return json(['code'=>0,'msg'=>'登录成功']);
            }
            return json(['code'=>10000,'msg'=>'用户名或密码错误']);
        }
        return $this->fetch();
    }

    public function logout(){
        \think\Session::clear();
        $this->redirect(url('login'));
    }
}
