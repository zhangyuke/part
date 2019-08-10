<?php



namespace app\company\service;

/**
 * 企业数据处理服务
 * Class DataService
 * @package app\company\service
 */
class DataService
{
    /**
     * 格式化MAC地址信息
     * @param string $mac
     * @return string
     */
    public static function applyMacValue(&$mac)
    {
        $mac = strtoupper(str_replace('-', ':', $mac));
        if (preg_match('/([A-F0-9]{2}:){5}[A-F0-9]{2}/', $mac)) {
            return $mac;
        } else {
            return false;
        }
    }

}
