<?php

namespace Youvision\ThinkSpider;

use think\Request;
use think\Response;
use Youvision\ThinkSpider\Model\SpiderVisit;
use Youvision\ThinkSpider\Spider\AbstractSpider;
use Youvision\ThinkSpider\Spider\Baidu;
use Youvision\ThinkSpider\Spider\Bing;
use Youvision\ThinkSpider\Spider\Google;
use Youvision\ThinkSpider\Spider\Qihoo;
use Youvision\ThinkSpider\Spider\Shenma;
use Youvision\ThinkSpider\Spider\Sogou;
use Youvision\ThinkSpider\Spider\Toutiao;
use Youvision\ThinkSpider\Spider\Yandex;
use Youvision\ThinkSpider\Support\Recorder;

class SpiderManager
{
    public const SPIDERS = [
        'baidu'   => Baidu::class,
        'google'  => Google::class,
        'sogou'   => Sogou::class,
        'bing'    => Bing::class,
        'shenma'  => Shenma::class,
        'toutiao' => Toutiao::class,
        '360'     => Qihoo::class,
        'yandex'  => Yandex::class,
    ];

    protected array $config;

    protected Recorder $recorder;

    protected ?array $instances = null;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->recorder = new Recorder($config);
    }

    public function enabled(): bool
    {
        return (bool) ($this->config['enable'] ?? true);
    }

    public function debug(): bool
    {
        return (bool) ($this->config['debug'] ?? false);
    }

    public function all(): array
    {
        if ($this->instances !== null) {
            return $this->instances;
        }

        $this->instances = [];

        foreach (self::SPIDERS as $key => $class) {
            $this->instances[$key] = new $class((array) ($this->config['spiders'][$key] ?? []));
        }

        return $this->instances;
    }

    public function spiders(): array
    {
        $enabled = [];

        foreach ($this->all() as $key => $spider) {
            if ($spider->enabled()) {
                $enabled[$key] = $spider;
            }
        }

        return $enabled;
    }

    public function get(string $key): ?AbstractSpider
    {
        return $this->spiders()[$key] ?? null;
    }

    public function detect(string $userAgent): ?AbstractSpider
    {
        if (!$this->enabled() || $userAgent === '') {
            return null;
        }

        foreach ($this->spiders() as $spider) {
            if ($spider->match($userAgent)) {
                return $spider;
            }
        }

        return null;
    }

    public function isSpider(string $userAgent): bool
    {
        return $this->detect($userAgent) !== null;
    }

    public function shouldRecord(Request $request): bool
    {
        if (!$this->enabled()) {
            return false;
        }

        $methods = array_map('strtoupper', (array) ($this->config['record_methods'] ?? []));

        if ($methods !== [] && !in_array(strtoupper((string) $request->method()), $methods, true)) {
            return false;
        }

        $path = '/' . ltrim((string) $request->pathinfo(), '/');
        $only = (array) ($this->config['only_paths'] ?? []);

        if ($only !== [] && !$this->matchPath($path, $only)) {
            return false;
        }

        if ($this->matchPath($path, (array) ($this->config['ignore_paths'] ?? []))) {
            return false;
        }

        if (in_array((string) $request->ip(), (array) ($this->config['ignore_ips'] ?? []), true)) {
            return false;
        }

        return true;
    }

    public function record(Request $request, AbstractSpider $spider, ?Response $response = null): ?SpiderVisit
    {
        if (!$this->shouldRecord($request)) {
            return null;
        }

        return $this->recorder->record($request, $spider, $response);
    }

    public function stat(string $date = '', ?int $siteId = null): array
    {
        $date = $date !== '' ? $date : date('Y-m-d');
        $spiders = [];
        $total = ['pv' => 0, 'uv' => 0, 'mobile_pv' => 0];

        foreach ($this->spiders() as $key => $spider) {
            $item = $spider->stat($date, $siteId);
            $spiders[$key] = $item;

            $total['pv'] += $item['pv'];
            $total['uv'] += $item['uv'];
            $total['mobile_pv'] += $item['mobile_pv'];
        }

        return [
            'date'    => $date,
            'total'   => $total,
            'spiders' => $spiders,
        ];
    }

    public function trend(int $days = 7, ?int $siteId = null): array
    {
        $days = max(1, min($days, 90));
        $spiders = [];

        foreach ($this->spiders() as $key => $spider) {
            $spiders[$key] = $spider->trend($days, $siteId);
        }

        return [
            'days'    => $days,
            'spiders' => $spiders,
        ];
    }

    protected function matchPath(string $path, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            $pattern = '/' . ltrim((string) $pattern, '/');
            $regex = '#^' . str_replace('\*', '.*', preg_quote($pattern, '#')) . '$#i';

            if (preg_match($regex, $path) === 1) {
                return true;
            }
        }

        return false;
    }
}