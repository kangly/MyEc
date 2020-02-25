<?php

namespace app\http\middleware;

class AdminLogin
{
    public function handle($request, \Closure $next)
    {
        // 添加中间件执行代码

        return $next($request);
    }
}
