<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/08/27
 * Time: 09:52
 */

namespace app\admin\controller;

class Sale extends Admin
{
    public function index()
    {
        return $this->fetch();
    }

    public function saleList()
    {
        return $this->fetch();
    }

    public function editSale()
    {
        return $this->fetch();
    }

    public function saveSale()
    {
        echo 'success';
    }

    public function deleteSale()
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