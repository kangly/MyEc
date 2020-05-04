<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/08/27
 * Time: 09:37
 */

namespace app\admin\controller;

use think\Db;

class Sell extends Admin
{
    public function index()
    {
        return $this->fetch();
    }

    public function sellList()
    {
        $fields = 'id,cat_id,type_id,title,thumb1,price,user_id,username,hits,left(add_date,16) add_date';
        $sell = Db::name('sell')->field($fields)->where('status',1)->paginate(20);
        $this->assign('sell',$sell);

        return $this->fetch();
    }

    public function editSell()
    {
        return $this->fetch();
    }

    public function saveSell()
    {
        echo 'success';
    }

    public function deleteSell()
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

    public function setting()
    {
        return $this->fetch();
    }

    public function saveSetting()
    {
        echo 'success';
    }
}