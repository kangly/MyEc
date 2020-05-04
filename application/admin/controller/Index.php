<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/07/28
 * Time: 14:42
 */

namespace app\admin\controller;

use think\Db;
use think\Request;

/**
 * Class Index
 * @package app\admin\controller
 */
class Index extends Admin
{
    /**
     * 工作台
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 系统首页
     * @return mixed
     */
    public function systemInfo()
    {
        $sqlVersion = Db::query('SELECT VERSION() version;');
        $this->assign('sqlVersion',$sqlVersion[0]['version']);//MySQL版本
        $this->assign('sysTime',_time());//系统当前时间

        return $this->fetch();
    }

    /**
     * 修改密码
     * @return mixed
     */
    public function changePassword()
    {
        return $this->fetch();
    }

    /**
     * 保存修改密码
     * @param Request $request
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function saveChangePassword(Request $request)
    {
        forbid();

        if(request()->isPost())
        {
            $old_password = $request->param('old_password',null,'trim');
            $new_password = $request->param('new_password',null,'trim');
            $confirm_password = $request->param('confirm_password',null,'trim');

            $member = model('member');
            $result = $member->validatePassword($old_password,$new_password,$confirm_password);
            if($result)
            {
                $map = ['id'=>$this->userInfo['id']];
                $member_data = $member->field('password')->where($map)->find();
                if($member_data['password']!=md5($old_password)){
                    echo '旧密码错误！';
                    exit;
                }
                $member->where($map)->update(['password'=>md5($new_password)]);

                controller('login')->clearCache();//修改密码后需清空缓存重新登录

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