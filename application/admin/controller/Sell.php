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
        $fields = 'id,catId,typeId,title,thumb,price,userId,username,hits,left(addDate,16) addDate';
        $sell = Db::name('sell')->field($fields)->where('status',1)->paginate(20);
        $this->assign('sell',$sell);

        return $this->fetch();
    }

    public function editSell()
    {
        echo $this->fetch();
    }

    public function saveSell()
    {

    }

    public function deleteSell()
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

    public function setting()
    {
        return $this->fetch();
    }

    public function saveSetting()
    {

    }
}