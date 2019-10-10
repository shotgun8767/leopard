<?php

namespace app\controller\v1;

use api\{BaseApi, Package};
use app\auth\User as UserAuth;
use app\exception\{UserException};
use app\model\User as model;

class User extends BaseApi
{
    protected $authorityClass = UserAuth::class;

    /**
     * 获取客户端用户信息
     * @return Package|null
     */
    public function getInfo()
    {
        return $this
            ->setParam(['userId' => $this->getTokenPayload('id')])
            ->call('getInfoById');
    }

    /**
     * 获取用户信息
     * @param $userId
     * @return Package
     */
    public function getInfoById($userId)
    {
        $uid = $this->getTokenPayload('id');
        $info = (new model)->getInfo($userId, $uid);

        return $info ?
            Package::ok('successfully get user info', $info) :
            Package::error(UserException::class, 100006);
    }

    /**
     * 编辑用户信息
     * @return Package
     */
    public function edit()
    {
        $userId = $this->getTokenPayload('id');
        $data = self::param();

        $res = (new model)->put($userId, $data);

        // 处理邀请码
        $_res = [];
        if ($code = $this->param('invitation_code')) {
            $code = $this->param('invitation_code');
            $ApiCore = $this->cloneApiCore()->setParam(['code' => $code]);
            $_res = $this->call('invitationGetScore', $ApiCore)->data();
            if (!$_res) $_res = [];
            $_res['inv_success'] = true;
        }

        return $res ?
            Package::ok('成功编辑用户信息', $_res) :
            Package::error(UserException::class, 100007);
    }

    /**
     * 签到
     * @return Package
     */
    public function sign()
    {
        $userId = $this->getTokenPayload('id');
        $res = (new model)->signIn($userId);

        return $res ?
            Package::ok('签到成功', $res) :
            Package::error(UserException::class, 100008);
    }

    /**
     * 获取用户签到状态
     * @return Package
     */
    public function getSignStatus()
    {
        $userId = $this->getTokenPayload('id');
        $res = (new model)->getSignStatus($userId);

        return Package::ok('成功获取用户签到状态', ['status' => $res]);
    }

    /**
     * 获取用户邀请码
     * @return Package
     * @throws UserException
     */
    public function getInvitationCode()
    {
        $userId = $this->getTokenPayload('id');
        $code = (new model)->getInvitationCode($userId);

        return Package::ok('成功获取用户邀请码', [
            'code' => $code
        ]);
    }

    /**
     * 根据邀请码为邀请者添加积分
     * @param $code
     * @return Package
     * @throws UserException
     * @throws \ReflectionException
     */
    public function invitationGetScore($code)
    {
        if (!is_numeric($code) || strlen($code) != 16) {
            throw new UserException(100009);
        }

        $userId = $this->getTokenPayload('id');
        $res = (new model)->invitationGetScore($userId, $code);

        return Package::ok('成功为邀请者添加积分', $res);
    }
}