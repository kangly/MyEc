<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/08/27
 * Time: 09:37
 */

namespace app\admin\controller;

use lmxdawn\tree\Tree;
use think\Request;

class Sell extends Admin
{
    /**
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * @return mixed
     */
    public function sellList()
    {
        $fields = 'id,cat_id,type_id,title,thumb1,price,member_id,username,hits,add_date';
        $sell = model('admin/Sell')->getSell([],$fields,20);
        $this->assign('sell',$sell);

        return $this->fetch();
    }

    public function editSell(Request $request)
    {
        $sell = null;
        $id = $request->param('id');
        if($id>0){
            $sell = model('admin/Sell')->field('*')->where(['id'=>$id])->find();
        }
        $this->assign('sell',$sell);

        $category_data = model('admin/SellCategory')->getCategory()->toArray();
        $category_list = Tree::build($category_data)->getRootFormat();
        $this->assign('category_list',$category_list);

        return $this->fetch();
    }

    public function saveSell()
    {
        $sell = new \app\admin\service\Sell();
        $res = $sell->storeSell();

        echo $res;
    }

    public function deleteSell(Request $request)
    {
        echo 'success';
    }

    /**
     * @return mixed
     */
    public function category()
    {
        $category_data = model('admin/SellCategory')->getCategory()->toArray();
        $category_list = Tree::build($category_data)->getRootFormat();
        $this->assign('category_list',$category_list);

        return $this->fetch();
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function editCategory(Request $request)
    {
        $category = null;
        $id = $request->param('id');
        if($id>0){
            $category = model('admin/SellCategory')->field('id,pid,title,sort')->where(['id'=>$id])->find();
        }
        $this->assign('category',$category);

        $category_data = model('admin/SellCategory')->getCategory()->toArray();
        $category_list = Tree::build($category_data)->getRootFormat();
        $this->assign('category_list',$category_list);

        return $this->fetch();
    }

    /**
     * @param Request $request
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function saveCategory(Request $request)
    {
        $title = $request->param('title','','trim');
        if($title)
        {
            $data = [
                'pid' => $request->param('pid'),
                'title' => $title,
                'sort' => $request->param('sort')
            ];

            $category = model('admin/SellCategory');

            $id = $request->param('id');
            if($id>0){
                $category->where('id','=',$id)->update($data);
            }else{
                $data['admin_id'] = $this->userInfo['id'];
                $data['create_time'] = _time();
                $res = $category->create($data);
                $id = $res->id;
            }

            echo $id;
        }
    }

    public function deleteCategory(Request $request)
    {
        $id = $request->param('id');

        echo $id;
    }

    public function setting()
    {
        return $this->fetch();
    }

    public function saveSetting()
    {
        echo 200;
    }
}