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
    /**
     * 登录
     * @return mixed
     */
    public function index()
    {
        if(is_login()){
            $this->redirect(url('/admin/index'));
        }

        return $this->fetch();
    }

    /**
     * 登录验证
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
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
                'login_ip'=>request()->ip(),
                'login_time'=>_time(),
                'login_times'=>$member_data['login_times']+1
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

    /**
     * 退出登录
     */
    public function logout()
    {
        $this->clearCache();
        $this->redirect(url('/admin/login'));
    }

    /**
     * 清空登录session
     */
    public function clearCache()
    {
        if(is_login()){
            Session::clear(Config::get('session.prefix'));
        }
    }
}