<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/08/27
 * Time: 09:59
 */

namespace app\admin\controller;

class Bid extends Admin
{
    public function index()
    {
        return $this->fetch();
    }

    public function bidList()
    {
        return $this->fetch();
    }

    public function editBid()
    {
        return $this->fetch();
    }

    public function saveBid()
    {
        echo 'success';
    }

    public function deleteBid()
    {
        echo 'success';
    }

    public function category()
    {
        return $this->fetch();
    }

    public function editCategory()
    {
        return $this->fetch();
    }

    public function saveCategory()
    {
        echo 'success';
    }

    public function deleteCategory()
    {
        echo 'success';
    }
}