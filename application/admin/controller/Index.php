<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/07/28
 * Time: 14:42
 */

namespace app\admin\controller;

use think\Db;

/**
 * Class Index
 * @package app\admin\controller
 */
class Index extends Admin
{
    public function index()
    {
        return $this->fetch();
    }

    public function systemInfo()
    {
        $sqlVersion = Db::query('SELECT VERSION() version;');//MySQL版本
        $this->assign('sqlVersion',$sqlVersion[0]['version']);
        $this->assign('sysTime',_time());//系统当前时间

        return $this->fetch();
    }

    public function changePassword()
    {
        return $this->fetch();
    }

    public function saveChangePassword()
    {
        if(request()->isPost())
        {
            $old_password = input('post.old_password',null,'trim');
            $new_password = input('post.new_password',null,'trim');
            $confirm_password = input('post.confirm_password',null,'trim');

            $member = model('admin/Member');
            $result = $member->validate_password($old_password,$new_password,$confirm_password);
            if($result)
            {
                $member_data = $member->field('password')->where('id',$this->userInfo['id'])->find();
                if($member_data['password']!=md5($old_password)){
                    echo '旧密码错误！';
                    exit;
                }
                $member->where('id',$this->userInfo['id'])->update(['password'=>md5($new_password)]);

                controller('Admin/Login')->clearCache();//修改密码后需清空缓存重新登录

                echo $this->userInfo['id'];
            }
            else
            {
                echo $member->getError();
            }
        }
        else
        {
            echo '非法操作！';
        }
    }
}