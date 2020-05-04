<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\facade\Config;

/**
 * 返回当前时间
 * @return bool|string
 */
function _time(){
    return date('Y-m-d H:i:s');
}

/**
 * 验证邮箱格式
 * @param $email
 * @return bool
 */
function checkEmail($email){
    if($email){
        $pattern = "/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/";
        if(preg_match($pattern,$email)){
            return true;
        }
    }
    return false;
}

/**
 * 验证手机格式
 * @param $mobile
 * @return bool
 */
function checkMobile($mobile){
    if($mobile){
        if(preg_match("/^1\d{10}$/", $mobile)){
            return true;
        }
    }
    return false;
}

/**
 * 递归实现无限分类(树形结构)
 * @param $list
 * @param string $limiter
 * @param int $pid
 * @param int $level
 * @return array
 */
function list_to_tree($list,$limiter='--',$pid=0,$level=0){
    $tree = [];
    foreach($list as $k=>$v){
        if($v['parentId'] == $pid){
            $v['level'] = $level + 1;
            $v['limiter'] = str_repeat($limiter,$level);
            unset($list[$k]);
            $tree[] = $v;
            $tree = array_merge($tree,list_to_tree($list,$limiter,$v['id'],$v['level']));
        }
    }
    return $tree;
}

/**
 * 将数据集转换成Tree
 * @param array $list 要转换的数据集
 * @param string $pk ID标记字段
 * @param string $pid parent标记字段
 * @param string $child 子代key名称
 * @param int $root 返回的根节点ID
 * @param bool $strict 默认非严格模式
 * @return array
 */
function list2tree($list, $pk = 'id', $pid = 'parent_id', $child = '_child', $root = 0, $strict = false){
    $tree = array();// 创建Tree
    if (is_array($list)) {
        $refer = array();// 创建基于主键的数组引用
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }
        foreach ($list as $key => $data) {
            $parent_id = isset($data[$pid]) ? $data[$pid] : null;// 判断是否存在parent
            if ($parent_id === null || (String) $root === $parent_id) {
                $tree[] = &$list[$key];
            } else {
                if (isset($refer[$parent_id])) {
                    $parent           = &$refer[$parent_id];
                    $parent[$child][] = &$list[$key];
                } else {
                    if ($strict === false) {
                        $tree[] = &$list[$key];
                    }
                }
            }
        }
    }
    return $tree;
}

function forbid(){
    $is_online = Config::get('app.is_online');
    if($is_online==1){
        echo '禁止操作！';
        exit;
    }
}