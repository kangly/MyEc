<?php

namespace app\admin\controller;

class Finance extends Admin
{
    public function index()
    {
        return $this->fetch();
    }

    public function creditList()
    {
        return $this->fetch();
    }
}