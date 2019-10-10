<?php

namespace app\controller\v1;

use api\BaseApi;
use api\Package;
use app\auth\User as UserAuth;
use app\exception\service\TokenException;
use utility\general\Time;
use utility\service\Authority;

class Token extends BaseApi
{
    protected $authorityClass = UserAuth::class;

    /**
     * 获取Token
     * @param $payload
     * @param null $Authority
     * @return Package
     */
    public function get($payload, $Authority = null)
    {
        $Token = \service\Token::new(
            $payload,
            config('token.exp_time'),
            $Authority,
            config('token.config')
        );

        if ($token = $Token->token()) {
            $exp = $Token->expire();
            $data = [
                'token' => $token,
                'expire_time' => $exp,
                'expire_date' => Time::instance($exp)->toString()
            ];
            return Package::ok('Successfully get token.', $data);
        } else {
            switch ($Token->getError()) {
                case $Token::ERROR_INVALID_HEADER :
                    return Package::error(TokenException::class, 31001);
                case $Token::ERROR_INVALID_PAYLOAD :
                    return Package::error(TokenException::class, 31002);
                case $Token::ERROR_FAIL_TO_GENERATE :
                default:
                    return Package::error(TokenException::class, 31005);
            }
        }
    }

    /**
     * 获取测试用Token
     * @param $user_id
     * @param string $dev_pwd 开发者密码: leopard123456
     * @return Package
     */
    public function getTest($user_id, $dev_pwd)
    {
        if ($dev_pwd != 'leopard123456') {
            throw new TokenException(31010);
        }

        // 生成Token
        $payload = [
            'id' => $user_id,
            'sc' => 2   # 学校：暨南大学珠海校区
        ];

        return $this->calls('Token', 'get', $this->cloneApiCore()->setParam([
            'payload' => $payload,
            'Authority' => Authority::set(UserAuth::class, 'DEVELOPER')
        ]));
    }

    /**
     * 获取Token信息
     * @param string $token
     * @return Package
     */
    public function getInfo(string $token)
    {
        $Token = \utility\service\Token::build($token, config('token.config'));

        if (!$Token->isValid()) {
            return Package::error(TokenException::class, 31008);
        }

        $data = [
            'is_expire' => $Token->isExpire(),
            'expire_time' => $Token->expire(),
            'payload' => $Token->getPayload(),
        ];

        return Package::ok('成功获取Token信息', $data);
    }

    /**
     * @param string $dev_pwd
     * @param int $auth
     * @return Package
     */
    public function TokenAuthUpdate(string $dev_pwd, int $auth)
    {
        if ($dev_pwd != 'leopard123456') {
            throw new TokenException(31010);
        }

        $data = $this->call('getInfo')->data();

        return $this->call('get', $this->cloneApiCore()->setParam([
            'payload' => $data['payload'],
            'Authority' => Authority::set($this->authorityClass, $auth)
        ]))->message('Successfully update auth, now auth level is ' . $auth);
    }
}