<?php

namespace Youvision\ThinkSpider;

use think\Service as ThinkService;
use Youvision\ThinkSpider\Command\Check;
use Youvision\ThinkSpider\Command\Clean;
use Youvision\ThinkSpider\Middleware\SpiderRecord;

class Service extends ThinkService
{
    public function register(): void
    {
        $defaults = require dirname(__DIR__) . '/config/spider.php';
        $current = (array) $this->app->config->get('spider', []);
        $config = $this->mergeConfig($defaults, $current);

        $this->app->config->set($config, 'spider');

        $app = $this->app;

        $this->app->bind('think-spider', function () use ($app) {
            return new SpiderManager((array) $app->config->get('spider', []));
        });

        $this->app->bind(SpiderManager::class, 'think-spider');

        if ($this->app->runningInConsole()) {
            $this->commands([Clean::class, Check::class]);
        } elseif ((bool) $this->app->config->get('spider.enable', true)
            && (bool) $this->app->config->get('spider.auto_middleware', true)
        ) {
            $this->app->middleware->add(SpiderRecord::class, 'global');
        }
    }

    public function boot(): void
    {
    }

    protected function mergeConfig(array $defaults, array $current): array
    {
        foreach ($current as $key => $value) {
            if (is_array($value)
                && isset($defaults[$key])
                && is_array($defaults[$key])
                && !$this->isList($value)
            ) {
                $defaults[$key] = $this->mergeConfig($defaults[$key], $value);
            } else {
                $defaults[$key] = $value;
            }
        }

        return $defaults;
    }

    protected function isList(array $array): bool
    {
        if ($array === []) {
            return true;
        }

        return array_keys($array) === range(0, count($array) - 1);
    }
}