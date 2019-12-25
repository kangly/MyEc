<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/08/27
 * Time: 10:08
 */

namespace app\admin\controller;

class Goods extends Admin
{
    public function index()
    {
        return $this->fetch();
    }

    public function goodsList()
    {
        return $this->fetch();
    }

    public function editGoods()
    {
        return $this->fetch();
    }

    public function saveGoods()
    {

    }

    public function deleteGoods()
    {

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

    }

    public function deleteCategory()
    {

    }
}