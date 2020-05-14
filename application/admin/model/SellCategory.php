<?php

namespace app\admin\model;

use think\Model;

class SellCategory extends Model
{
    /**
     * 获取供应的分类列表
     * @param array $map
     * @param array $sort
     * @param string $fields
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getCategory($map=[],$sort=[],$fields='id,pid,title,sort')
    {
        if(empty($sort)){
            $sort = ['id'=>'asc'];
        }

        $category = $this
            ->field($fields)
            ->where($map)
            ->order($sort)
            ->select();

        return $category;
    }
}