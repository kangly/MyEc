<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/08/27
 * Time: 10:05
 */

namespace app\admin\controller;

class News extends Admin
{
    public function index()
    {
        return $this->fetch();
    }

    public function newsList()
    {
        return $this->fetch();
    }

    public function editNews()
    {
        return $this->fetch();
    }

    public function saveNews()
    {

    }

    public function deleteNews()
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