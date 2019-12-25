<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/08/09
 * Time: 13:50
 */

namespace app\admin\controller;

class Extend extends Admin
{
    public function ad()
    {
        return $this->fetch();
    }

    public function adList()
    {
        return $this->fetch();
    }

    public function link()
    {
        return $this->fetch();
    }

    public function linkList()
    {
        return $this->fetch();
    }

    public function page()
    {
        return $this->fetch();
    }

    public function pageList()
    {
        return $this->fetch();
    }
}