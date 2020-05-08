<?php
namespace app\member\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->assign('content','This is a Member Index。');

        return $this->fetch();
    }
}
