<?php

namespace app\admin\controller;

class Offline extends Admin
{
    public function index()
    {
        return $this->fetch();
    }

    public function offlineList()
    {
        return $this->fetch();
    }
}