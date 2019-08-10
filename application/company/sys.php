<?php



// 注册系统指令
use think\Console;

Console::addDefaultCommands([
    'app\company\command\Subversion',
]);
