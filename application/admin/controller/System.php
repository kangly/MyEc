<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/08/08
 * Time: 14:30
 */

namespace app\admin\controller;

use think\Db;
use think\Request;

/**
 * Class System
 * @package app\admin\controller
 */
class System extends Admin
{
    /**
     * 网站设置
     * @return mixed
     */
    public function websiteSetup()
    {
        return $this->fetch();
    }

    /**
     * 网站设置-基本设置
     * @return mixed
     */
    public function basicSetup()
    {
        return $this->fetch();
    }

    /**
     * 网站设置-保存基本设置
     */
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

    public function cacheArea()
    {
        if(request()->isGet())
        {
            $province_list = Db::name('area')
                ->field('id,name,area_code')
                ->where('pid','=',0)
                ->order('sort desc,id asc')
                ->select();
            $data = [];
            foreach($province_list as $v)
            {
                $data[100000][$v['area_code']] = $v['name'];
                $city_list = Db::name('area')
                    ->field('id,name,area_code')
                    ->where('pid','=',$v['id'])
                    ->order('sort desc,id asc')
                    ->select();
                foreach($city_list as $c)
                {
                    $data[$v['area_code']][$c['area_code']] = $c['name'];
                    $area_list = Db::name('area')
                        ->field('name,area_code')
                        ->where('pid','=',$c['id'])
                        ->order('sort desc,id asc')
                        ->select();
                    foreach($area_list as $a){
                        $data[$c['area_code']][$a['area_code']] = $a['name'];
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

    public function areaManage()
    {
        return $this->fetch();
    }

    /**
     * 地区管理列表
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function areaList(Request $request)
    {
        $pid = $request->param('pid',0);
        $where = [['pid','=',$pid]];
        $keyword = $request->param('keyword','','trim');
        if($keyword){
            $where[] = array('name','like',sprintf('%%%s%%',$keyword));
        }
        $area_list = Db::name('area')->where($where)->select();
        $parentId = 0;
        $parentName = '';
        if($pid>0){
            $parentIdData = Db::name('area')->field('pid')->where('id','=',$pid)->find();
            $parentNameData = Db::name('area')->field('name')->where('id','=',$pid)->find();
            $parentId = $parentIdData['pid'];
            $parentName = $parentNameData['name'];
        }
        $this->assign('area_list',$area_list);
        $this->assign('keyword',$keyword);
        $this->assign('pid',$pid);
        $this->assign('parentId',$parentId);
        $this->assign('parentName',$parentName);

        return $this->fetch();
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function editArea(Request $request)
    {
        if(request()->isGet())
        {
            $area = null;
            $id = $request->param('id');
            if($id>0){
                $area = Db::name('area')->where('id','=',$id)->find();
            }
            $this->assign('area',$area);
            $area_code1 = '';
            $area_code2 = '';
            $pid = $request->param('pid');
            if($pid>0){
                $aArea = Db::name('area')->field('pid,namespace,area_code')->where('id','=',$pid)->find();
                if($aArea['namespace']=='province'){
                    $area_code1 = $aArea['area_code'];
                }else{
                    $pArea = Db::name('area')->field('area_code')->where('id','=',$aArea['pid'])->find();
                    $area_code1 = $pArea['area_code'];
                    $area_code2 = $aArea['area_code'];
                }
            }
            $this->assign('area_code1',$area_code1);
            $this->assign('area_code2',$area_code2);
        }

        return $this->fetch();
    }

    /**
     * @param Request $request
     * @return mixed|string
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function saveArea(Request $request)
    {
        if(request()->isPost())
        {
            $name = $request->param('name','','trim');
            $areaCode = $request->param('area_code','','trim');

            if(!$name){
                return '地区名称必填！';
            }
            if(!$areaCode){
                return '区域代码必填！';
            }
            if(strlen($areaCode)!=6){
                return '区域代码为6位数字！';
            }

            $area_id = $request->param('area_id');
            $province_id = $request->param('province_id');
            $city_id = $request->param('city_id');
            $sort = $request->param('sort','','trim');

            $data = [
                'name'=>$name,
                'area_code'=>$areaCode,
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
                $data['pid'] = 0;
            }
            if($area_code){
                $area = Db::name('area')->field('id')->where('area_code','=',$area_code)->find();
                $data['pid'] = $area['id'];
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

    /**
     * @param Request $request
     */
    public function deleteArea(Request $request)
    {
        if(request()->isPost())
        {
            $id = $request->param('id');
            if($id>0)
            {
                // 启动事务
                Db::startTrans();
                try {
                    $aArea = Db::name('area')->field('namespace,area_code')->where('id','=',$id)->find();
                    if($aArea['namespace']=='province'){
                        // 删除省,需要同时删除市和区
                        $city = Db::name('area')->field('id')->where('pid','=',$id)->select();
                        $city_id = [];
                        foreach($city as $v){
                            $city_id[] = $v['id'];
                        }
                        Db::name('area')->where('id','=',$id)->delete();
                        if($city_id){
                            Db::name('area')->where('id','in',$city_id)->delete();
                            Db::name('area')->where('pid','in',$city_id)->delete();
                        }
                    }else if($aArea['namespace']=='city'){
                        // 删除市,需要同时删除区
                        Db::name('area')->where('id','=',$id)->delete();
                        Db::name('area')->where('pid','=',$id)->delete();
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
        $admin_list = model('member')->getMembers();
        $this->assign('admin_list',$admin_list);

        return $this->fetch();
    }

    public function editAdmin(Request $request)
    {
        return $this->fetch();
    }

    public function saveAdmin(Request $request)
    {

    }

    public function deleteAdmin(Request $request)
    {

    }
}