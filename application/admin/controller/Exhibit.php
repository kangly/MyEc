<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/08/27
 * Time: 10:03
 */

namespace app\admin\controller;

class Exhibit extends Admin
{
    public function index()
    {
        return $this->fetch();
    }

    public function exhibitList()
    {
        return $this->fetch();
    }

    public function editExhibit()
    {
        return $this->fetch();
    }

    public function saveExhibit()
    {
        echo 'success';
    }

    public function deleteExhibit()
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