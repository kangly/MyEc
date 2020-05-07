<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/07/29
 * Time: 10:42
 */

namespace app\admin\controller;

use think\Controller;
use think\Request;

/**
 * Class Login
 * @package app\admin\controller
 */
class Login extends Controller
{
    /**
     * 登录首页
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
     * @param Request $request
     * @return mixed|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function login(Request $request)
    {
        if(is_login()){
            return '请勿重复登录！';
        }

        if(request()->isPost())
        {
            $username = $request->param('username');
            $password = $request->param('password');
            $member = model('member');
            $member_data = $member->login($username,$password);
            if(!$member_data){
                return $member->getError();
            }else{
                $member_data = $member_data->toArray();
            }

            $save_data = [
                'login_ip'=>request()->ip(),
                'login_time'=>_time(),
                'login_times'=>$member_data['login_times']+1
            ];
            $member->where('id','=',$member_data['id'])->update($save_data);

            $member->autoLogin(array_merge($member_data,$save_data));

            return 'success';
        }
        else
        {
            return '非法操作！';
        }
    }
}