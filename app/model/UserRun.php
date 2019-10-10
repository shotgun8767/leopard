<?php

namespace app\model;

use app\exception\UserRunException;
use model\BaseModel;
use utility\general\Time;

/**
 * 用户运动
 * Class UserRun
 * @package app\model
 */
class UserRun extends BaseModel
{
    protected $hidden = ['status'];

    /**
     * 根据步数换取积分
     * @param $userId
     * @param $step
     * @param $timestamp
     * @return array
     */
    public function add($userId, $step, $timestamp)
    {
        $day = Time::instance($timestamp)->day();

        # ++day: 微信是按1970年1月1日8:00am开始算起的时间戳，具体到日（每日00:00）
        $res = $this->hasRecord($userId, ++$day);
        if ($res) {
            throw new UserRunException(200002);
        } else {
            $_step = config('user.score.user_run.step');
            $max_score = config('user.score.user_run.max_score');
            $score_plus = floor($step / $_step);
            if ($score_plus > $max_score) $score_plus = $max_score;

            $this->inserts([
                'user_id'   => $userId,
                'sign_day'  => $day,
                'step'      => $step,
                'score_plus'=> $score_plus
            ]);

            // 用户增加积分
            (new User)->addScore($userId, $score_plus);

            return [
                'score_plus'=> $score_plus,
                'step'      => $step,
                'timestamp' => $timestamp
            ];
        }
    }

    /**
     * 获取一段时间内的步数总和
     * @param int $userId
     * @param int $start
     * @param int $end
     * @return array
     * @throws UserRunException
     */
    public function getSum(int $userId, int $start, int $end)
    {
        try {
            $start_day = Time::instance($start)->day();
            $end_day = Time::instance($end)->day();
        } catch (\Exception $e) {
            throw new UserRunException(200004);
        }

        $where = [
            'user_id' => $userId,
            ['sign_day', 'gt', $start_day-1],
            ['sign_day', 'lt', $end_day+1],
        ];

        $sum = $this
            ->whereBase($where)
            ->sum('step');

        return ['sum' => $sum];
    }

    public function hasRecord(int $userId, int $day) : bool
    {
        return $this->get([
            'user_id' => $userId,
            'sign_day' => $day
        ]) ? true : false;
    }
}