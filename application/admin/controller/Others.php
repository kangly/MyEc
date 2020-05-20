<?php

namespace app\admin\controller;

class Others extends Admin
{
    public function index()
    {
        return $this->fetch();
    }

    public function messageList()
    {
        return $this->fetch();
    }

    public function mailList()
    {
        return $this->fetch();
    }

    public function smsList()
    {
        return $this->fetch();
    }

    public function logList()
    {
        return $this->fetch();
    }
}