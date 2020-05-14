<?php

namespace app\admin\model;

use think\Model;

class Sell extends Model
{
    /**
     * 获取供应列表
     * @param array $map
     * @param string $fields
     * @param null $listRows
     * @param array $sort
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getSell($map=[],$fields='*',$listRows=null,$sort=[])
    {
        $map[] = ['status','=',1];

        $sell = $this
            ->field($fields)
            ->where($map)
            ->order($sort)
            ->paginate($listRows);

        return $sell;
    }
}