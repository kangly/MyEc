<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/08/09
 * Time: 13:36
 */

namespace app\admin\controller;

use think\Db;

class Member extends Admin
{
    public function index()
    {
        return $this->fetch();
    }

    public function memberList()
    {
        $member_list = model('member')->getMembers();
        $this->assign('member_list',$member_list);

        return $this->fetch();
    }

    public function editMember()
    {
        if(request()->isGet())
        {
            $id = input('get.id');
            $member = model('member')->getMember($id);
            $this->assign('member',$member);
        }

        return $this->fetch();
    }

    public function saveMember()
    {
        if(request()->isPost())
        {
            $member_id = input('post.member_id');
            $username = input('post.username',null,'trim');
            $password = input('post.password',null,'trim');
            $confirm_password = input('post.confirm_password',null,'trim');
            $nickname = input('post.nickname',null,'trim');
            $realName = input('post.real_name',null,'trim');
            $gender = input('post.gender',0);
            $email = input('post.email',null,'trim');
            $mobile = input('post.mobile',null,'trim');
            $weChat = input('post.wechat',null,'trim');
            $qq = input('post.qq',null,'trim');
            $msn = input('post.msn',null,'trim');
            $ali = input('post.ali',null,'trim');
            $skype = input('post.skype',null,'trim');
            $state = input('post.state',0);

            if(!$username){
                return '用户名必填！';
            }

            if($member_id>0)
            {
                if($password)
                {
                    if(!$confirm_password){
                        return '确认登录密码必填！';
                    }

                    if($password!=$confirm_password){
                        return '两次输入的密码不一致！';
                    }
                }
            }
            else
            {
                if(!$password){
                    return '登录密码必填！';
                }

                if(!$confirm_password){
                    return '确认登录密码必填！';
                }

                if($password!=$confirm_password){
                    return '两次输入的密码不一致！';
                }
            }

            if(!$realName){
                return '真实姓名必填！';
            }

            if(!$email){
                return '邮箱必填！';
            }

            $map = [
                ['username','=',$username]
            ];
            if($member_id>0){
                $map[] = ['id','<>',$member_id];
            }

            $is_exist = Db::name('member')->field('id')->where($map)->find();
            if($is_exist && $is_exist['id']>0)
            {
                return '重复的用户名！';
            }
            else
            {
                $save_data = [
                    'username' => $username,
                    'nickname' => $nickname,
                    'real_name' => $realName,
                    'gender' => $gender,
                    'email' => $email,
                    'mobile' => $mobile,
                    'wechat' => $weChat,
                    'qq' => $qq,
                    'msn' => $msn,
                    'ali' => $ali,
                    'skype' => $skype,
                    'state' => $state,
                ];

                if($member_id>0){
                    if($password){
                        $save_data['password'] = md5($password);
                    }
                    Db::name('member')->where('id',$member_id)->update($save_data);
                }else{
                    $save_data['password'] = md5($password);
                    $save_data['reg_ip'] = request()->ip();
                    $save_data['reg_time'] = _time();
                    $save_data['create_time'] = _time();
                    Db::name('member')->where('id',$member_id)->insert($save_data);
                }

                return 200;
            }
        }
        else
        {
            return '非法操作！';
        }
    }

    public function deleteMember()
    {
        if(request()->isPost())
        {
            $id = input('post.id');

            echo $id;
        }
        else
        {
            echo '非法操作！';
        }
    }

    public function groupList()
    {
        $group_list = Db::name('member_group')->select();
        $this->assign('group_list',$group_list);

        return $this->fetch();
    }

    public function editGroup()
    {
        return $this->fetch();
    }

    public function saveGroup()
    {

    }

    public function deleteGroup()
    {

    }
}