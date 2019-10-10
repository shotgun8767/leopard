<?php

namespace app\model;

use app\exception\UserException;
use model\BaseModel;
use utility\general\{Now, Time, Random};

class User extends BaseModel
{
    protected $hidden = ['status', 'open_id', 'auth'];

    protected $fields = [
        'info' => ['id', 'score', 'name', 'nick_name', 'number', 'school_id', 'address', 'major', 'telephone', 'year', 'avatar_url'],
    ];

    /**
     * 获取用户信息
     * @param int $id
     * @param int $uid 客户端用户id
     * @return array|null
     */
    public function getInfo(int $id, ?int $uid = null)
    {
        $info = $this
            ->advancedWith(['SchoolInfo'])
            ->get($id, 'info');

        if (!is_null($info)) {
            # 用户被关注数
            $UserSubscription = new UserSubscription;
            $info['by_subs_num'] = $UserSubscription->getCount(['foreign_id' => $id]);

            # 用户关注数
            $UserSubscription->removeOption();
            $info['subs_num'] = $UserSubscription->getCount(['user_id' => $id]);

            if ($uid && $id != $uid) {
                $info['subs'] = $UserSubscription->isSubs($uid, $id);
            }
        }

        return $info;
    }

    /**
     * 根据openid获取用户id
     * @param $openid
     * @return array|null
     */
    public function getInfoByOpenid(string $openid) : ?array
    {
        $where = ['openid' => $openid];
        $info = $this->getArray($where, ['school_id', 'id']);
        return $info ? $info : ['id' => $this->inserts($where)];
    }

    /**
     * 编辑用户信息
     * @param $id
     * @param $data
     * @return int
     * @throws \app\exception\DatabaseException
     */
    public function put($id, $data)
    {
        if (key_exists('nick_name', $data)) {
            $data['nick_name'] = base64_encode($data['nick_name']);
        }
        return $this->updates($id, $data);
    }

    /**
     * 完成签到
     * @param int $id       用户id
     * @return array
     */
    public function signIn(int $id)
    {
        $last_sign = $this->getLastSign($id);
        $Time   = Time::instance($last_sign);
        $dif    = Now::day() - $Time->day();
        if (0 == ($week = Now::week())) $week = 7;

        if ($dif == 0) {
            // 今天已签到
            return [];
        } else {
            $sign = $this->getField('sign', $id);
            if ($week > $dif) {
                // 本周前的若干天最后一次签到
                $sign += pow(2, --$week);;
            } else {
                $sign = pow(2, --$week);
            }

            $this->updates($id, [
                'sign' => $sign,
                'last_sign' => time()
            ]);

            // 计算本周签到次数
            $status = [];
            $c = 0;
            for ($i = 1; $i < 128; $i *= 2 ) {
                $s = $i & $sign ? 1 : 0;
                array_push($status, $s);
                $c += $s;
            }

            // 积分更新
            $score = config('user.score.sign_in');
            $this->addScore($id, $score);

            return [
                'score_plus'=> $score,
                'status'    => $status,
                'sign_times'=> $c
            ];
        }
    }

    /**
     * 获取用户签到状态
     * @param int $userId
     * @return array
     */
    public function getSignStatus(int $userId)
    {
        $data = $this->getArray($userId, ['id', 'sign', 'last_sign']);

        $Time = Time::instance($data['last_sign']);
        $dif = Now::day() - $Time->day();
        $week = Now::week();

        if (0 == $week) $week = 7;
        if ($week > $dif) {
            $status = [];
            $sign = $data['sign'];
            for ($i = 1; $i < 128; $i *= 2 ) {
                array_push($status, ($i & $sign ? 1 : 0));
            }
            return $status;
        } else {
            return [0,0,0,0,0,0,0];
        }
    }

    /**
     * 获取用户积分
     * @param $id
     * @return array|string|null
     */
    public function getScore($id)
    {
        return $this->getField('score', $id);
    }

    /**
     * 增加积分
     * @param $id
     * @param $score
     * @return int
     */
    public function addScore($id, $score)
    {
        $this->whereBase($id)
            ->inc('score', $score);
        return $this->getQuery()->update();
    }

    /**
     * 减少积分
     * @param $id
     * @param $score
     * @return int
     */
    public function minusScore($id, $score)
    {
        $this->whereBase($id)
            ->dec('score', $score);
        return $this->getQuery()->update();
    }

    /**
     * 获取邀请码
     * @param $id
     * @return string
     */
    public function getInvitationCode($id) : string
    {
        $code = $this->getField('invitation_code', $id);
        if (is_null($code)) {
            throw new UserException(100001);
        } else {
            if ($code == 0) {
                $code = Random::fixed(3)->includeDigit()->remove(0)->getString();
                $code = $code . time() * 2 . substr(microtime(), 4,3);
                $this->updates($id, ['invitation_code' => $code]);
            }
        }
        return $code;
    }

    /**
     * 根据邀请码，邀请者获取积分
     * @param int $userId   受邀者id
     * @param string $code  邀请码
     * @return array
     */
    public function invitationGetScore(int $userId, string $code)
    {
        # inviter为邀请者，即邀请码对应的用户
        $inviterId = $this->getField('id', ['invitation_code' => $code]);
        if ($inviterId) {
            if ($inviterId == $userId) {
                throw new UserException(100003);
            }

            // 增加邀请信息
            if (!(new UserInvitation)->add($inviterId, $userId)) {
                throw new UserException(100004);
            }

            // 增加邀请者积分
            $inviter_score = config('user.score.invitation.inviter');
            $this->addScore($inviterId, $inviter_score);

            // 增加被邀请者积分
            $invitee_score = config('user.score.invitation.invitee');
            $this->addScore($userId, $invitee_score);

            return [
                'invitee_score_plus' => $invitee_score,
                'inviter_id' => $inviterId,
                'inviter_score_plus' => $inviter_score
            ];
        } else {
            throw new UserException(100005);
        }
    }

    /**
     * 获取最后一次签到日期
     * @param int $userId
     * @return array|string|null
     */
    public function getLastSign(int $userId)
    {
        return $this->getField('last_sign', $userId);
    }

    public function getAvatarUrlAttr($url)
    {
        return $url ? $url :
            'https://api.bar.rehellinen.cn/resource/image/upload/20190819/5f94f70306770ea05cc14c6b208b1ed9.jpeg';
    }

    public function SchoolInfo()
    {
        return $this->belongsTo('school', 'school_id', 'id');
    }

    public function getNickNameAttr($value)
    {
        return base64_decode($value);
    }
}