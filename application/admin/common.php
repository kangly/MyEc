<?php
// 应用admin模块公共文件

/**
 * 检测是否登录
 * @return mixed
 */
function is_login(){
    return model('admin/Member')->is_login();
}