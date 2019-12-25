<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/08/08
 * Time: 14:30
 */

namespace app\admin\controller;

use think\Db;

/**
 * Class System
 * @package app\admin\controller
 */
class System extends Admin
{
    public function cacheArea()
    {
        if(request()->isGet())
        {
            $province_list = Db::name('area')
                ->field('id,name,areaCode')
                ->where('parentId','=',0)
                ->order('sort desc,id asc')
                ->select();
            $data = [];
            foreach($province_list as $v)
            {
                $data[100000][$v['areaCode']] = $v['name'];
                $city_list = Db::name('area')
                    ->field('id,name,areaCode')
                    ->where('parentId','=',$v['id'])
                    ->order('sort desc,id asc')
                    ->select();
                foreach($city_list as $c)
                {
                    $data[$v['areaCode']][$c['areaCode']] = $c['name'];
                    $area_list = Db::name('area')
                        ->field('name,areaCode')
                        ->where('parentId','=',$c['id'])
                        ->order('sort desc,id asc')
                        ->select();
                    foreach($area_list as $a){
                        $data[$c['areaCode']][$a['areaCode']] = $a['name'];
                    }
                }
            }
            $data_json = json_encode($data,JSON_UNESCAPED_UNICODE);
            $data_json = 'var DISTRICTS = '.$data_json;
            $content_start = file_get_contents('static/js/distpicker/distpicker_start.js');
            $content_end = file_get_contents('static/js/distpicker/distpicker_end.js');
            file_put_contents('static/js/distpicker/distpicker.js',$content_start."\n\n".$data_json."\n\n".$content_end);
            echo 'success';
        }
    }

    public function websiteSetup()
    {
        return $this->fetch();
    }

    public function basicSetup()
    {
        return $this->fetch();
    }

    public function saveBasicSetup()
    {
        echo 'saveBasicSetup';
    }

    public function seoSetup()
    {
        return $this->fetch();
    }

    public function saveSeoSetup()
    {
        echo 'saveSeoSetup';
    }

    public function serverSetup()
    {
        return $this->fetch();
    }

    public function saveServerSetup()
    {
        return $this->fetch();
    }

    public function areaManage()
    {
        return $this->fetch();
    }

    /**
     * 地区管理列表
     */
    public function areaList()
    {
        $pid = input('post.pid',0);
        $where = [['parentId','=',$pid]];
        $keyword = input('post.keyword','','trim');
        if($keyword){
            $where[] = array('name','like',sprintf('%%%s%%',$keyword));
        }
        $area_list = Db::name('area')->where($where)->select();
        $parentId = 0;
        $parentName = '';
        if($pid>0){
            $parentIdData = Db::name('area')->field('parentId')->where('id','=',$pid)->find();
            $parentNameData = Db::name('area')->field('name')->where('id','=',$pid)->find();
            $parentId = $parentIdData['parentId'];
            $parentName = $parentNameData['name'];
        }
        $this->assign('area_list',$area_list);
        $this->assign('keyword',$keyword);
        $this->assign('pid',$pid);
        $this->assign('parentId',$parentId);
        $this->assign('parentName',$parentName);

        echo $this->fetch();
    }

    public function editArea()
    {
        if(request()->isGet())
        {
            $area = null;
            $id = input('get.id');
            if($id>0){
                $area = Db::name('area')->where('id','=',$id)->find();
            }
            $this->assign('area',$area);
            $area_code1 = '';
            $area_code2 = '';
            $pid = input('get.pid');
            if($pid>0){
                $aArea = Db::name('area')->field('parentId,namespace,areaCode')->where('id','=',$pid)->find();
                if($aArea['namespace']=='province'){
                    $area_code1 = $aArea['areaCode'];
                }else{
                    $pArea = Db::name('area')->field('areaCode')->where('id','=',$aArea['parentId'])->find();
                    $area_code1 = $pArea['areaCode'];
                    $area_code2 = $aArea['areaCode'];
                }
            }
            $this->assign('area_code1',$area_code1);
            $this->assign('area_code2',$area_code2);
        }

        echo $this->fetch();
    }

    public function saveArea()
    {
        if(request()->isPost())
        {
            $name = input('post.name','','trim');
            $areaCode = input('post.areaCode','','trim');

            if(!$name){
                return '地区名称必填！';
            }
            if(!$areaCode){
                return '区域代码必填！';
            }
            if(strlen($areaCode)!=6){
                return '区域代码为6位数字！';
            }

            $area_id = input('post.area_id');
            $province_id = input('post.province_id');
            $city_id = input('post.city_id');
            $sort = input('post.sort','','trim');

            $data = [
                'name'=>$name,
                'areaCode'=>$areaCode,
                'sort'=>$sort,
            ];

            $area_code = '';
            if($city_id){
                $area_code = $city_id;
                $data['namespace'] = 'area';
            }else if($province_id){
                $area_code = $province_id;
                $data['namespace'] = 'city';
            }else{
                $data['namespace'] = 'province';
                $data['parentId'] = 0;
            }
            if($area_code){
                $area = Db::name('area')->field('id')->where('areaCode','=',$area_code)->find();
                $data['parentId'] = $area['id'];
            }

            if($area_id>0){
                Db::name('area')->where('id','=',$area_id)->update($data);
            }else{
                Db::name('area')->insert($data);
                $area_id = Db::name('area')->getLastInsID();
            }

            return $area_id;
        }
        else
        {
            return '非法请求！';
        }
    }

    public function viewArea()
    {
        echo $this->fetch();
    }

    public function deleteArea()
    {
        if(request()->isPost())
        {
            $id = input('post.id');
            if($id>0)
            {
                // 启动事务
                Db::startTrans();
                try {
                    $aArea = Db::name('area')->field('namespace,areaCode')->where('id','=',$id)->find();
                    if($aArea['namespace']=='province'){
                        // 删除省,需要同时删除市和区
                        $city = Db::name('area')->field('id')->where('parentId','=',$id)->select();
                        $city_id = [];
                        foreach($city as $v){
                            $city_id[] = $v['id'];
                        }
                        Db::name('area')->where('id','=',$id)->delete();
                        if($city_id){
                            Db::name('area')->where('id','in',$city_id)->delete();
                            Db::name('area')->where('parentId','in',$city_id)->delete();
                        }
                    }else if($aArea['namespace']=='city'){
                        // 删除市,需要同时删除区
                        Db::name('area')->where('id','=',$id)->delete();
                        Db::name('area')->where('parentId','=',$id)->delete();
                    }else{
                        // 删除区
                        Db::name('area')->where('id','=',$id)->delete();
                    }
                    // 提交事务
                    Db::commit();
                } catch (\Exception $e) {
                    // 回滚事务
                    Db::rollback();
                }

                echo $id;
            }
        }
        else
        {
            echo '非法请求！';
        }
    }

    public function adminManage()
    {
        return $this->fetch();
    }

    public function adminList()
    {
        return $this->fetch();
    }
}