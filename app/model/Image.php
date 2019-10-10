<?php

namespace app\model;

use app\exception\ImageException;
use app\resource\Upload;
use resource\Resource;
use service\Thumb;
use model\BaseModel;
use think\facade\Filesystem;
use think\facade\Request;
use Qcloud\Cos\Client;

class Image extends BaseModel
{
    protected $hidden = ['id', 'status'];

    /**
     * 上传图片
     * @param $filename
     * @return bool|int
     */
    public function upload(string $filename)
    {
        $image = Request::file($filename);
        $upload_dir = (new Upload)->getDir();
        if (empty($image) || !$upload_dir) return false;

        // $saveName的根目录为资源文件夹(\public\resource\)
        $saveName = Filesystem::disk('resource')->putFile($upload_dir, $image);
        $saveName = str_replace(['\\', '//'], '/', $saveName);

        // 将图片上传到数据库中，返回image的id
        return $this->inserts(['image_url' => $saveName]);
    }

    /**
     * 压缩图片
     * @param int $id
     * @param array $resolution
     * @return array|bool
     */
    public function thumb(int $id, array $resolution = [])
    {
        if (!$image = $this->get($id)) {
            return false;
        }

        $image_url = (new Resource)->getDirName() .$image->getOrigin('image_url');
        $return = [];
        foreach ($resolution as $item) {
            $Thumb = new Thumb($image_url);
            $thumb_url = $Thumb->create($item);
            // 图片存入数据库中
            if (is_numeric($thumb_url)) return false;
            $thumb_id = $this->inserts([
                'image_url' => $thumb_url,
                'original'  => $id,
                'width'=> $item[0],
                'height' => $item[1]
            ]);
            array_push($return, ['thumb_id' => $thumb_id]);
        }
        return $return;
    }

    public function uploadObj()
    {
        $secretId   = config('cos.secretId');
        $secretKey  = config('cos.secretKey');
        $region     = config('cos.region');
        $appid      = config('cos.appid');

        // 初始化
        $cosClient = new Client([
            'region' => $region,
            'schema' => 'https',
            'credentials'=> [
                'secretId'  => $secretId ,
                'secretKey' => $secretKey
            ]
        ]);

        // 创建储存桶
        $bucketName = config('cos.bucket_prefix') . '_image';
        try {
            $bucket = "$bucketName-$appid";
            $result = $cosClient->createBucket([
                'Bucket' => $bucket
            ]);
            var_dump($result);
        } catch (\Exception $e) {
            // 请求失败
            throw new ImageException();
        }

        // 上传对象
        # 使用putObject接口上传文件，最大5G
        # 使用Upload接口分块上传文件，最大50T

    }

    public function dumpList()
    {
        $secretId   = config('cos.secretId');
        $secretKey  = config('cos.secretKey');
        $region     = config('cos.region');
        $appid      = config('cos.appid');

        // 初始化
        $cosClient = new Client([
            'region' => $region,
            'schema' => 'https',
            'credentials'=> [
                'secretId'  => $secretId ,
                'secretKey' => $secretKey
            ]
        ]);

        // 指定储存桶
        $bucketName = config('cos.bucket_prefix') . '_image';
        $result = $cosClient->listObjects([
            'Bucket' => "{$bucketName}-{$appid}"
        ]);

        var_dump($result);
    }

    /**
     * 根据图片id获取图片信息
     * @param int $id 图片id
     * @param bool $detail
     * @return array|mixed|string
     */
    public function getById(int $id, bool $detail = false)
    {
        return $this->getArray($id, $detail ? [] : ['hidden', 'original', 'height', 'width']);
    }

    public function getImageUrlAttr($imageUrl)
    {
        return DOMAIN_RESOURCE . $imageUrl;
    }
}