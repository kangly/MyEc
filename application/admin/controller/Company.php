<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/08/27
 * Time: 10:27
 */

namespace app\admin\controller;

class Company extends Admin
{
    public function index()
    {
        return $this->fetch();
    }

    public function companyList()
    {
        return $this->fetch();
    }

    public function editCompany()
    {
        return $this->fetch();
    }

    public function saveCompany()
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