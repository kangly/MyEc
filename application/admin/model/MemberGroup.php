<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2020/02/16
 * Time: 10:21
 */

namespace app\admin\model;

use think\Model;

/**
 * Class MemberGroup
 * @package app\admin\model
 */
class MemberGroup extends Model
{
    /**
     * @param int $id
     * @return array|\PDOStatement|string|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMemberGroup($id=0)
    {
        if($id>0){
            return $this->where('id',$id)->find();
        }
        return null;
    }

    /**
     * @param array $params
     */
    public function getMemberGroups($params=[])
    {

    }
}