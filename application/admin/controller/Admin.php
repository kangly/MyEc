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
    protected $middleware = ['AdminLogin'];// 后台判断是否登录的中间件
    protected $userInfo = [];//当前登录的用户信息

    protected function initialize()
    {
        parent::initialize();

        $this->userInfo = is_login();
    }
}