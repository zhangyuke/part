<?php



namespace app\wechat\command\fans;

use app\wechat\command\Fans;

/**
 * 同步全部粉丝指令
 * Class FansBlack
 * @package app\wechat\command\fans
 */
class FansAll extends Fans
{
    /**
     * 配置入口
     */
    protected function configure()
    {
        $this->module = ['list', 'black', 'tags'];
        $this->setName('xfans:all')->setDescription('从微信获取所有粉丝记录和标签');
    }
}
