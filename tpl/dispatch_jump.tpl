{__NOLAYOUT__}<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="icon" type="image/png" sizes="16x16" href="/static/images/favicon.png">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <title>跳转提示</title>
    <style type="text/css">
        * { padding:0;margin:0; }
        body { background:#fff;font-family:"Microsoft Yahei","Helvetica Neue",Helvetica,Arial,sans-serif;color:#333;font-size:16px; }
    </style>
</head>
<body>
<script type="text/javascript" src="/static/js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/static/js/layer/layer.js"></script>
<script type="text/javascript">
    /**
     * $msg 待提示的消息
     * $url 待跳转的链接
     * $time 弹出维持时间（单位秒）
     * icon 这里主要有两个layer的表情，5和6，代表（哭和笑）
     */
    (function(){
        var msg = '<?php echo(strip_tags($msg));?>';
        var url = '<?php echo($url);?>';
        var wait = '<?php echo($wait);?>';
        <?php switch ($code) {?>
            <?php case 1:?>
                layer.msg(msg,{icon:"6",time:wait*1000});
            <?php break;?>
            <?php case 0:?>
                layer.msg(msg,{icon:"5",time:wait*1000});
            <?php break;?>
         <?php } ?>
        setTimeout(function(){
            location.href=url;
        },1000)
    })();
</script>
</body>
</html>