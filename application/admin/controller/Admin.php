<?php
namespace app\admin\controller;

class Admin extends \app\admin\Auth
{
    public function showList(){

        if($this->request->isAjax()){
            $page = $this->request->get('page');
            $limit = $this->request->get('limit');
            $page = $page>0?$page-1:0;

            $count = \think\Db::table("admin")->count();
            $data = \think\Db::table("admin")->limit($page*$limit,$limit)->select();

            foreach($data as $k=>$v){


                $permission = '';
                foreach(self::power as $key=>$value){
                    if($data[$k]['permission']&$key){
                        $permission .=','.$value['title'];
                    }
                }
                $data[$k]['permission'] =  $data[$k]['is_super']=='1'?'超级管理':ltrim($permission,',');
                $data[$k]['is_super'] = $data[$k]['is_super']=='1'?'超级管理':'普通管理';
            }

            return json(['code'=>0,'count'=>$count,'data'=>$data]);
        }

        return $this->fetch('showList');
    }

    public function create(){

        if($this->request->isPost()){
            $data = [
                'username' => $this->request->post('username'),
                'password' => md5($this->request->post('password')),
                'permission'  => 0
            ];
            if(!empty( $_POST['permission'])){
                $permission = $_POST['permission'];
                $p = 0;
                foreach($permission as $v){
                    $p = $p|$v;
                }
                $data['permission'] = $p;
            }

            $return = \think\Db::table('admin')->insertGetId($data);
            if($return){
                $this->success('新增成功', 'admin/admin/showList');
            }else{
                $this->error('新增失败');
            }
        }

        $this->assign('power',self::power);
        return $this->fetch('create');
    }

    public function update($id){
        $admin = \think\Db::table('admin')->where(['id'=>$id])->find();
//        if(session('isSuper')=='1' && $admin['is_super']){
//            $this->error('您不能编辑超级管理员');
//        }
        if($this->request->isPost()){
            $data = [
                'username' => $this->request->post('username'),
                'permission'  => 0
            ];

            if(!empty($_POST['permission'])){
                $permission = $_POST['permission'];
                $p = 0;
                foreach($permission as $v){
                    $p = $p|$v;
                }
                $data['permission'] = $p;
            }

            if($this->request->post('is_pwd')){
                $data['password'] = md5($this->request->post('password'));
            }

            $return = \think\Db::table('admin')->where(['id'=>$id])->update($data);
            if($return!==false){
                $this->success('编辑成功', 'admin/admin/showList');
            }else{
                $this->error('编辑失败');
            }
        }
        $this->assign('power',self::power);
        return $this->fetch('update',['admin'=>$admin]);
    }

    public function delete($id){
        $admin = \think\Db::table('admin')->where(['id'=>$id])->find();
        if(session('isSuper')=='1' && $admin['is_super']){
            return json(['code'=>-1,'msg'=>'您不能删除超级管理员']);
        }
        $delete =\think\Db::table('admin')->where(['id'=>$id,'is_super'=>0])->delete();
        if($delete){
            return json(['code'=>0,'msg'=>'删除成功']);
        }else{
            return json(['code'=>1]);
        }

    }
}
