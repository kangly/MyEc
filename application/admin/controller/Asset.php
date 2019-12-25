<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/08/27
 * Time: 09:56
 */

namespace app\admin\controller;

class Asset extends Admin
{
    public function index()
    {
        return $this->fetch();
    }

    public function assetList()
    {
        return $this->fetch();
    }

    public function editAsset()
    {
        return $this->fetch();
    }

    public function saveAsset()
    {

    }

    public function deleteAsset()
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