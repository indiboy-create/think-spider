<?php
// +----------------------------------------------------------------------
// | think-spider 搜索引擎蜘蛛统计配置
// | 安装后将本文件复制到项目 config/spider.php 按需修改
// +----------------------------------------------------------------------

return [
    // 统计总开关，false 时中间件不记录任何蜘蛛访问
    'enable' => true,

    // 是否自动注册全局中间件
    // true：由服务自动注册；false：需在项目 app/middleware.php 手动添加
    'auto_middleware' => true,

    // 站点 ID，多站点项目可传闭包，闭包参数为 think\Request
    // 示例：function (\think\Request $request) { return (int) $request->param('site_id', 0); }
    'site_id' => 0,

    // 数据库连接名，留空使用项目默认连接
    'connection' => '',

    // 数据表名，不含表前缀（模型会自动读取项目配置的 prefix）
    // 如修改表名，需同步修改 database/spider.sql
    'tables' => [
        'visit' => 'spider_visit',
        'stat'  => 'spider_stat',
    ],

    // 是否记录每次访问明细
    // 关闭后只维护日统计，无法统计 UV 与访问明细
    'log_visit' => true,

    // 是否维护按日聚合统计表
    'stat_daily' => true,

    // 是否按 IP 去重统计 UV，关闭后 UV 恒为 0
    'unique_uv' => true,

    // 需要统计的请求方法，留空表示不限制
    'record_methods' => ['GET', 'HEAD'],

    // 只统计匹配的路径，支持 * 通配，如 ['/article/*']，留空表示不限制
    'only_paths' => [],

    // 忽略的路径，支持 * 通配，如 ['/admin/*', '/api/*']
    'ignore_paths' => [],

    // 忽略的 IP，精确匹配
    'ignore_ips' => [],

    // 蜘蛛真实性校验
    // 通过反向 DNS 解析 IP 得到主机名，再校验主机名后缀与正向解析结果
    'verify' => [
        // 是否开启真实性校验；默认关闭，反向 DNS 会增加请求耗时
        'enable' => false,

        // 校验结果缓存秒数，建议 86400
        'cache_ttl' => 86400,

        // 是否对主机名做正向解析，确认解析结果中包含来访 IP
        'check_forward' => true,

        // 校验失败时是否丢弃该访问
        // false：仍然记录，明细表 verified 字段为 0，便于排查伪造 UA
        'discard_unverified' => false,

        // 各引擎允许的主机名后缀
        'hosts' => [
            'baidu'   => ['baidu.com', 'baidu.jp'],
            'google'  => ['googlebot.com', 'google.com'],
            'sogou'   => ['sogou.com'],
            'bing'    => ['search.msn.com', 'msn.com', 'bing.com'],
            'shenma'  => ['sm.cn', 'uc.cn', 'ucweb.com'],
            'toutiao' => ['bytedance.com', 'bytespider.com'],
            '360'     => ['360.cn', 'so.com'],
            'yandex'  => ['yandex.com', 'yandex.ru', 'yandex.net'],
        ],
    ],

    // 各搜索引擎配置
    // enable：是否启用该引擎
    // name：统计展示名称
    // extra_patterns：追加 UA 正则，需带定界符，如 '/MyBot/i'
    // mobile_patterns：追加移动端 UA 正则
    // version_pattern：版本号提取正则，留空使用内置规则
    'spiders' => [
        'baidu' => [
            'enable' => true,
            'name' => '百度蜘蛛',
            'extra_patterns' => [],
            'mobile_patterns' => [],
            'version_pattern' => '',
        ],
        'google' => [
            'enable' => true,
            'name' => 'Google蜘蛛',
            'extra_patterns' => [],
            'mobile_patterns' => [],
            'version_pattern' => '',
        ],
        'sogou' => [
            'enable' => true,
            'name' => '搜狗蜘蛛',
            'extra_patterns' => [],
            'mobile_patterns' => [],
            'version_pattern' => '',
        ],
        'bing' => [
            'enable' => true,
            'name' => 'Bing蜘蛛',
            'extra_patterns' => [],
            'mobile_patterns' => [],
            'version_pattern' => '',
        ],
        'shenma' => [
            'enable' => true,
            'name' => '神马蜘蛛',
            'extra_patterns' => [],
            'mobile_patterns' => [],
            'version_pattern' => '',
        ],
        'toutiao' => [
            'enable' => true,
            'name' => '头条蜘蛛',
            'extra_patterns' => [],
            'mobile_patterns' => [],
            'version_pattern' => '',
        ],
        '360' => [
            'enable' => true,
            'name' => '360蜘蛛',
            'extra_patterns' => [],
            'mobile_patterns' => [],
            'version_pattern' => '',
        ],
        'yandex' => [
            'enable' => true,
            'name' => 'Yandex蜘蛛',
            'extra_patterns' => [],
            'mobile_patterns' => [],
            'version_pattern' => '',
        ],
    ],

    // 历史数据清理命令 spider:clean 的默认保留天数
    'clean' => [
        'keep_days' => 90,
    ],

    // 调试模式，开启后记录异常会写入应用日志
    'debug' => false,
];