<?php



namespace app\wechat\command\fans;

use app\wechat\command\Fans;

/**
 * 粉丝黑名单指令
 * Class FansBlack
 * @package app\wechat\command\fans
 */
class FansBlack extends Fans
{
    /**
     * 配置入口
     */
    protected function configure()
    {
        $this->module = ['black'];
        $this->setName('xfans:black')->setDescription('从微信获取黑名单中的粉丝记录');
    }
}
