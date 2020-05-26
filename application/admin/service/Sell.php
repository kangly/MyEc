<?php

namespace app\admin\service;

use think\facade\Request;
use think\Model;

class Sell
{
    public function storeSell()
    {
        $title = Request::param('title',null,'trim');
        $cat_id = Request::param('cat_id',0);
        $member_id = Request::param('member_id',0);
        $username = Request::param('username',null,'trim');

        if(empty($title)){
            return '标题不能为空！';
        }

        if(empty($cat_id)){
            return '行业分类不能为空！';
        }

        if(empty($member_id) || empty($username)){
            return '会员名不能为空！';
        }

        $id = Request::param('id',0);
        $thumb1 = Request::param('thumb1');
        $thumb2 = Request::param('thumb2');
        $thumb3 = Request::param('thumb3');
        $content = htmlspecialchars_decode(Request::param('content'));
        $introduce = mb_substr($content,0,255);

        $data = [
            'type_id' => Request::param('type_id',0),
            'title' => $title,
            'cat_id' => $cat_id,
            'level' => Request::param('level'),
            'style' => Request::param('style'),
            'area_id' => Request::param('area_id',0),
            'brand' => Request::param('brand',null,'trim'),
            'thumb1' => $thumb1,
            'thumb2' => $thumb2,
            'thumb3' => $thumb3,
            'introduce' => $introduce,
            'to_time' => Request::param('to_time'),
            'n1' => Request::param('n1'),
            'v1' => Request::param('v1'),
            'n2' => Request::param('n2'),
            'v2' => Request::param('v2'),
            'n3' => Request::param('n3'),
            'v3' => Request::param('v3'),
            'unit' => Request::param('unit'),
            'price' => Request::param('price'),
            'min_amount' => Request::param('min_amount'),
            'amount' => Request::param('amount'),
            'days' => Request::param('days'),
            'elite' => Request::param('elite'),
            'status' => Request::param('status'),
            'add_date' => date('Y-m-d H:i:s'),
        ];

        $sell_data = [
            'content' => $content
        ];

        try {
            $sell = model('admin/Sell');
            $sellData = model('admin/SellData');
            if($id>0){
                $sell->where(['id'=>$id])->save($data);
                $sellData->where(['id'=>$id])->save($sell_data);
            }else{
                $sell->insert($data);
                $id = $sell->getLastInsID();
                if($id>0){
                    $sell_data['id'] = $id;
                    $sellData->insert($sell_data);
                }
            }
            return $id;
        }catch (\Exception $e){
            return $e->getMessage();
        }
    }
}