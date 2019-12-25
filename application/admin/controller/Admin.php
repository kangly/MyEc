<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/07/29
 * Time: 10:17
 */

namespace app\admin\controller;

use think\Controller;

/**
 * Class Admin
 * @package app\admin\controller
 */
class Admin extends Controller
{
    public $userInfo = [];//当前登录用户信息

    //初始化数据
    public function initialize()
    {
        $this->userInfo = is_login();
        if(!$this->userInfo){
            $this->redirect(url('/admin/login'));
        }
        $this->assign('userInfo',$this->userInfo);
    }
}