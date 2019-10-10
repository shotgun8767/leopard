<?php

namespace app\controller\v1;

use api\{BaseApi, Package};
use app\exception\{service\TokenException, UserRunException};
use app\model\UserRun as model;
use utility\general\Now;
use wx\WxBizDataCrypt;

/**
 * 微信运动
 * Class UserRun
 * @package app\controller\v1
 */
class UserRun extends BaseApi
{
    /**
     * 根据微信步数换取积分
     * @return Package
     * @throws UserRunException
     */
    public function getScore()
    {
        $userId = $this->getTokenPayload('id');
        $data = $this->call('getSteps')->data();

        $step = $data['step'];
        $timestamp = $data['timestamp'];

        if (!is_numeric($step) || $step < 0) {
            throw new UserRunException(200001);
        }

        $res = (new model)->add($userId, $step, $timestamp);

        return Package::ok('成功以步数换积分', $res);
    }


    /**
     * 获取一段时间内的总微信步数（聚合）
     * @param $start
     * @param $end
     * @return Package
     */
    public function sum($start, $end)
    {
        $userId = $this->getTokenPayload('id');

        $res = (new model)->getSum($userId, $start, $end);

        return Package::ok('成功获取用户步数！', $res);
    }

    /**
     * @param $encrypted_data
     * @param $iv
     * @return Package
     * @throws TokenException
     */
    public function getSteps($encrypted_data, $iv)
    {
        $sessionKey = $this->getTokenPayload('sk');

        if (!$sessionKey) {
            throw new TokenException(31009);
        }

        $appId = config('wx.app_id');
        $iv = str_replace(' ', '+', $iv);
        $encrypted_data = str_replace(' ', '+', $encrypted_data);

        $data = '';
        $WxCrypt = new WxBizDataCrypt($appId, $sessionKey);
        $WxCrypt->decryptData($encrypted_data, $iv, $data);
        $data = json_decode($data, true);

        if (!$data) {
            throw new UserRunException(200005);
        }
        array_pop($data);
        $data = array_pop($data);
        $data = array_pop($data);

        return Package::ok('获取当前微信步数', $data);
    }

    /**
     * 是否已兑换步数
     * @return Package
     */
    public function getStatus()
    {
        $userId = $this->getTokenPayload('id');

        $res = (new model)->hasRecord($userId, Now::day());

        return Package::ok('成功', [
            'is_converted' => $res
        ]);
    }
}