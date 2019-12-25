<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->assign('content','This is a Index Index');

        return $this->fetch();
    }
}