<?php

namespace app\controller\v1;

use api\{BaseApi, Package};
use app\model\{User};
use app\exception\{WxException};
use utility\general\File;
use utility\service\{Authority, Curl};
use app\resource\MiniQRCode;
use app\auth\User as UserAuth;

class Wx extends BaseApi
{
    /**
     * 根据code获取Token令牌
     * @param $code
     * @return Package
     */
    public function getTokenByCode($code)
    {
        $app_id = config('wx.app_id');
        $app_secret = config('wx.app_secret');

        // 根据code获取微信后台给予的openid
        $param = [
            'appid'     => $app_id,
            'secret'    => $app_secret,
            'js_code'   => $code,
            'grant_type'=> 'authorization_code'
        ];

        $result = Curl::get(config('wx.api.getOpenid'), $param)->execute();

        if ($result === false) {
            throw new WxException(110001);
        }

        // 微信后台返回错误信息
        if (key_exists('errcode', $result)) {
            $e_data = [
                'code' => $code,
                'wx_result' => $result
            ];
            throw new WxException(110002, $e_data);
        }

        // 根据openid获取用户id
        $User = new User();
        $userInfo = $User->getInfoByOpenid($result['openid']);

        // 生成Token
        $payload = [
            'id' => $userInfo['id'],
            'sk' => $result['session_key'],
            'sc' => $userInfo['school_id']??0
        ];

        return $this->calls('Token', 'get', $this->cloneApiCore()->setParam([
            'payload' => $payload,
            'Authority' => Authority::set(UserAuth::class, 'COMMON')
        ]));
    }

    /**
     * 获取微信小程序二维码
     * @param $scene
     * @param $page
     * @return Package
     * @throws \ReflectionException
     */
    public function getQRCode($scene, $page)
    {
        //mini_program_qrcode
        $param = [
            'grant_type' => 'client_credential',
            'appid' => config('wx.app_id'),
            'secret' => config('wx.app_secret'),
        ];
        Curl::get(config('wx.api.access_token'), $param);

        $post = [
            'page' => (string)str_replace('.', '/', $page),
            'scene' => (string)$scene
        ];
        $file = Curl::post(config('wx.api.mini_program_qrcode'), $post, []);

        // 保存二进制流为图片文件
        $MiniQRCodeResource = new MiniQRCode;
        $Dir = $MiniQRCodeResource->getDirectory();
        $File = $Dir->newFile(md5(time().mt_rand(10, 99)) . ".png");
        $File->write($file);

        $data = [
            'path' => $File->getPath(),
            'create_time' => $File->modify_time
        ];
        return Package::created('成功获取小程序二维码', $data);
    }

    /**
     * 获取用户协议
     * @return Package
     */
    public function getAgreement()
    {
        $File = new File(APP_PATH . 'agreement/agreement.txt');

        return Package::ok('成功获取协议', [
            'title' => '换Bar微信小程序用户协议',
            'text' => $File->getContent()
        ]);
    }
}