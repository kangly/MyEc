<?php

namespace app\admin\controller;

class Common extends Admin
{
    public function uploadImage()
    {
        return $this->fetch('public/upload_image');
    }

    public function saveImage()
    {
        echo 200;
    }
}