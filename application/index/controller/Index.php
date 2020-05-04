<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->assign('content','This is a Index Index，<a href="/admin">访问后台</a>，用户名：admin，密码：abcd1234');

        return $this->fetch();
    }
}