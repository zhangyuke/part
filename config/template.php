<?php



return [
    // 去除HTML空格换行
    'strip_space'        => true,
    // 开启模板编译缓存
    'tpl_cache'          => !config('app_debug'),
    // 定义模板替换字符串
    'tpl_replace_string' => [
        '__APP__'    => rtrim(url('@'), '\\/'),
        '__ROOT__'   => rtrim(dirname(request()->basefile()), '\\/'),
        '__PUBLIC__' => rtrim(dirname(request()->basefile(true)), '\\/'),
    ],
];
