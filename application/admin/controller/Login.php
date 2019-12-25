<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/07/29
 * Time: 10:42
 */

namespace app\admin\controller;

use think\Controller;
use think\facade\Config;
use think\facade\Session;

/**
 * Class Login
 * @package app\admin\controller
 */
class Login extends Controller
{
    //登录界面
    public function index()
    {
        if(is_login()){
            $this->redirect(url('/admin/index'));
        }

        return $this->fetch();
    }

    //验证登录
    public function login()
    {
        $url = url('/admin/index');
        if(is_login()){
            $this->error('请勿重复登录！',$url);
        }

        if(request()->isPost())
        {
            $username = input('post.username');
            $password = input('post.password');

            $member = model('admin/Member');
            $member_data = $member->login($username,$password);
            if(!$member_data){
                $this->error($member->getError());
            }else{
                $member_data = $member_data->toArray();
            }

            $save_data = [
                'loginIp'=>request()->ip(),
                'loginTime'=>_time(),
                'loginTimes'=>$member_data['loginTimes']+1
            ];
            $member->where('id','=',$member_data['id'])->update($save_data);

            $member->auto_login(array_merge($member_data,$save_data));

            $this->success('登录成功，正在跳转...',$url);
        }
        else
        {
            $this->error('非法操作！');
        }
    }

    //退出系统
    public function logout()
    {
        $this->clearCache();
        $this->redirect(url('/admin/login'));
    }

    //清空session
    public function clearCache()
    {
        if(is_login()){
            $prefix = Config::get('session.prefix');
            Session::clear($prefix);
        }
    }
}