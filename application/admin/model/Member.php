<?php
/**
 * Created by PhpStorm.
 * User: kangly
 * Date: 2019/07/29
 * Time: 10:21
 */

namespace app\admin\model;

use think\Model;

/**
 * Class Member
 * @package app\admin\model
 */
class Member extends Model
{
    protected $error = ''; //错误信息

    /**
     * 用户登录
     * @param $username
     * @param $password
     * @param array $map
     * @return array|bool|\PDOStatement|string|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function login($username,$password,$map=[])
    {
        $map[] = ['state','=',0];

        if (checkEmail($username)) {
            $map[] = ['email','=',$username]; //邮箱
        } elseif (checkMobile($username)) {
            $map[] = ['mobile','=',$username]; //手机号
        } else {
            $map[] = ['username','=',$username]; //用户名
        }

        $member = $this->where($map)->find();
        if (!$member) {
            $this->error = '用户不存在或被禁用！';
        } else {
            if (md5($password) !== $member['password']) {
                $this->error = '密码错误！';
            } else {
                return $member;
            }
        }

        return false;
    }

    /**
     * 检测用户是否已登录
     * @return bool|mixed
     */
    public function isLogin()
    {
        $member = session('member_auth');
        if (empty($member)) {
            return false;
        } else {
            return $member;
        }
    }

    /**
     * 设置登录session
     * @param $member
     * @return bool|mixed
     */
    public function autoLogin($member)
    {
        session('member_auth', $member);
        return $this->isLogin();
    }

    /**
     * 验证用户信息
     * @param $uid
     * @param $name
     * @param $value
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function validateUer($uid,$name,$value)
    {
        if(!$uid || !$name || !$value){
            $this->error = '参数异常！';
            return false;
        }

        switch ($name)
        {
            case 'username':
                $this->validateName($uid,$value);
                break;
            case 'email':
                $this->validateEmail($uid,$value);
                break;
            case 'mobile':
                $this->validateMobile($uid,$value);
                break;
        }

        return true;
    }

    /**
     * 验证用户名
     * @param $id
     * @param $value
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function validateName($id,$value)
    {
        if(!$value){
            $this->error = '用户名必填！';
            return false;
        }

        $member = $this
            ->field('id')
            ->where([['id','<>',$id],['username','=',$value]])
            ->find();

        if($member){
            $this->error = '用户名与其他用户重复！';
            return false;
        }

        return true;
    }

    /**
     * 验证用户邮箱
     * @param $id
     * @param $value
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function validateEmail($id,$value)
    {
        if(!checkEmail($value)){
            $this->error = '邮箱格式错误！';
            return false;
        }

        $member = $this
            ->field('id')
            ->where([['id','<>',$id],['email','=',$value]])
            ->find();

        if($member){
            $this->error = '邮箱与其他用户重复！';
            return false;
        }

        return true;
    }

    /**
     * 验证用户电话
     * @param $id
     * @param $value
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function validateMobile($id,$value)
    {
        if(!checkMobile($value)){
            $this->error = '电话格式错误！';
            return false;
        }

        $member = $this
            ->field('id')
            ->where([['id','<>',$id],['mobile','=',$value]])
            ->find();

        if($member){
            $this->error = '电话与其他用户重复！';
            return false;
        }

        return true;
    }

    /**
     * 验证用户密码
     * @param $old_password
     * @param $new_password
     * @param $confirm_password
     * @return bool
     */
    public function validatePassword($old_password,$new_password,$confirm_password)
    {
        if(!$old_password)
        {
            $this->error = '旧密码必填！';
            return false;
        }
        else if(!$new_password)
        {
            $this->error = '新密码必填！';
            return false;
        }
        else if(!$confirm_password)
        {
            $this->error = '确认新密码必填！';
            return false;
        }
        else if($new_password != $confirm_password)
        {
            $this->error = '两次输入密码不一致！';
            return false;
        }

        return true;
    }

    /**
     * 用户和角色,多对多关联,获取用户所属权限组
     * @return \think\model\relation\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('AuthGroup','auth_group_access','group_id','uid');
    }

    /**
     * 获取会员列表数据
     * @param array $params 查询参数
     * @param int $limit 每页显示数量
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getMembers($params=[],$limit=20)
    {
        $fields = 'id,username,company,reg_time,login_time,login_times';
        $member = $this;

        if(empty($params)){
            return $member->field($fields)->paginate($limit);
        }

        if(isset($params['username'])){
            $member = $member->whereLike('username','%'.$params['username'].'%');
        }
        if(isset($params['email'])){
            $member = $member->whereLike('email','%'.$params['email'].'%');
        }

        return $member->field($fields)->paginate($limit,false,['query'=>$params]);
    }

    /**
     * @param $id
     * @return array|\PDOStatement|string|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getMember($id=0)
    {
        if($id>0){
            return $this->where('id',$id)->find();
        }
        return null;
    }

    /**
     * @param int $id
     * @param $data
     */
    public function saveMember($id=0,$data)
    {

    }

    /**
     * 获取错误信息
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}