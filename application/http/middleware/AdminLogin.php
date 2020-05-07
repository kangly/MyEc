<?php

namespace app\http\middleware;

use think\facade\View;

class AdminLogin
{
    public function handle($request, \Closure $next)
    {
        // 添加中间件执行代码
        $userInfo = is_login();
        if(!$userInfo){
            return redirect(url('/admin/login'));
        }
        View::assign('userInfo',$userInfo);

        return $next($request);
    }
}