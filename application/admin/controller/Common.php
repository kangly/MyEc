<?php

namespace app\admin\controller;

use think\Request;

class Common extends Admin
{
    /**
     * @return mixed
     */
    public function uploadImage()
    {
        return $this->fetch('public/upload_image');
    }

    /**
     * @return \think\response\Json
     */
    public function saveImage()
    {
        $file = request()->file('upload_img');
        $info = $file->move('../public/uploads/images');
        if($info){
            // 成功上传后 获取上传信息
            $file_name = $info->getFilename();

            $watermark = '../public/static/images/watermark.png';
            $img_path = '/uploads/images/'.date('Ymd').'/';
            $path = '../public'.$img_path;

            // 获取上传图片
            $image = \think\Image::open($path.$file_name);
            // 按照原图的比例生成一个最大为150*150的缩略图并保存
            $image->thumb(150,150,\think\Image::THUMB_SCALING)->save($path.'thumb_'.$file_name);

            // 获取上传图片
            $image = \think\Image::open($path.$file_name);
            // 给原图右下角添加水印并保存
            $image->water($watermark,\think\Image::WATER_SOUTHEAST)->save($path.$file_name);

            return json(['code'=>200,'info'=>'上传成功','img_path'=>$img_path.$file_name,'thumb_path'=>$img_path.'thumb_'.$file_name]);
        }else{
            // 上传失败获取错误信息
            return json(['code'=>1001,'info'=>$file->getError(),'img_path'=>'','thumb_path'=>'']);
        }
    }

    /**
     * @param Request $request
     */
    public function delImage(Request $request)
    {
        $img_path = $request->param('img_path');
        if($img_path && file_exists('../public'.$img_path)){
            unlink('../public'.$img_path);
        }

        $thumb_path = $request->param('thumb_path');
        if($thumb_path && file_exists('../public'.$thumb_path)){
            unlink('../public'.$thumb_path);
        }

        echo 'success';
    }
}