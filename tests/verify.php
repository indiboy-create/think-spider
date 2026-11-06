<?php

$base = dirname(__DIR__) . '/src/';

spl_autoload_register(function (string $class) use ($base): void {
    $prefix = 'Youvision\\ThinkSpider\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $file = $base . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

use Youvision\ThinkSpider\Spider\Baidu;
use Youvision\ThinkSpider\Spider\Bing;
use Youvision\ThinkSpider\Spider\Google;
use Youvision\ThinkSpider\Spider\Qihoo;
use Youvision\ThinkSpider\Spider\Shenma;
use Youvision\ThinkSpider\Spider\Sogou;
use Youvision\ThinkSpider\Spider\Toutiao;
use Youvision\ThinkSpider\Spider\Yandex;

$cases = [
    [
        'class'   => Baidu::class,
        'key'     => 'baidu',
        'ua'      => 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)',
        'version' => '2.0',
        'mobile'  => false,
    ],
    [
        'class'   => Baidu::class,
        'key'     => 'baidu',
        'ua'      => 'Mozilla/5.0 (Linux; Android 10) AppleWebKit/537.36 (KHTML, like Gecko) Mobile Baiduspider',
        'version' => '',
        'mobile'  => true,
    ],
    [
        'class'   => Google::class,
        'key'     => 'google',
        'ua'      => 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)',
        'version' => '2.1',
        'mobile'  => false,
    ],
    [
        'class'   => Sogou::class,
        'key'     => 'sogou',
        'ua'      => 'Sogou web spider/4.0(+http://www.sogou.com/docs/help/webmasters.htm#07)',
        'version' => '4.0',
        'mobile'  => false,
    ],
    [
        'class'   => Bing::class,
        'key'     => 'bing',
        'ua'      => 'Mozilla/5.0 (compatible; bingbot/2.0; +http://www.bing.com/bingbot.htm)',
        'version' => '2.0',
        'mobile'  => false,
    ],
    [
        'class'   => Shenma::class,
        'key'     => 'shenma',
        'ua'      => 'Mozilla/5.0 (Linux; U; Android 9; zh-CN) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/72.0.3626.121 Mobile Safari/537.36 (compatible; YisouSpider; +http://www.yisou.com/)',
        'version' => '',
        'mobile'  => true,
    ],
    [
        'class'   => Toutiao::class,
        'key'     => 'toutiao',
        'ua'      => 'Mozilla/5.0 (Linux; Android 5.0) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/40.0.2214.89 Mobile Safari/537.36 (compatible; Bytespider; spider-feedback@bytedance.com)',
        'version' => '',
        'mobile'  => true,
    ],
    [
        'class'   => Toutiao::class,
        'key'     => 'toutiao',
        'ua'      => 'Mozilla/5.0 (compatible; ByteDanceMCP/1.0)',
        'version' => '1.0',
        'mobile'  => false,
    ],
    [
        'class'   => Qihoo::class,
        'key'     => '360',
        'ua'      => 'Mozilla/5.0 (compatible; 360Spider/1.0; +http://www.360.cn/)',
        'version' => '1.0',
        'mobile'  => false,
    ],
    [
        'class'   => Yandex::class,
        'key'     => 'yandex',
        'ua'      => 'Mozilla/5.0 (compatible; YandexBot/3.0; +http://yandex.com/bots)',
        'version' => '3.0',
        'mobile'  => false,
    ],
];

$failed = 0;
$total = 0;

foreach ($cases as $case) {
    $total++;

    $spider = new $case['class']([]);
    $matched = $spider->match($case['ua']) && $spider->key() === $case['key'];
    $version = $spider->version($case['ua']);
    $mobile = $spider->mobile($case['ua']);

    if (!$matched || $version !== $case['version'] || $mobile !== $case['mobile']) {
        $failed++;
        fwrite(STDERR, sprintf(
            "失败：%s UA=%s 匹配=%s 版本=%s(期望 %s) 移动=%s(期望 %s)\n",
            $case['class'],
            $case['ua'],
            $matched ? '是' : '否',
            $version,
            $case['version'],
            $mobile ? '是' : '否',
            $case['mobile'] ? '是' : '否'
        ));
    }
}

$total++;
$browser = new Baidu([]);

if ($browser->match('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36')) {
    $failed++;
    fwrite(STDERR, "失败：普通浏览器被识别为百度蜘蛛\n");
}

if ($failed > 0) {
    fwrite(STDERR, sprintf("%d/%d 项失败\n", $failed, $total));
    exit(1);
}

echo sprintf("%d/%d 项通过\n", $total, $total);