<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/08/27
 * Time: 09:48
 */

namespace app\admin\controller;

class Buy extends Admin
{
    public function index()
    {
        return $this->fetch();
    }

    public function buyList()
    {
        return $this->fetch();
    }

    public function editBuy()
    {
        return $this->fetch();
    }

    public function saveBuy()
    {
        echo 'success';
    }

    public function deleteBuy()
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