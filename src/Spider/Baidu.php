<?php

namespace Youvision\ThinkSpider\Spider;

class Baidu extends AbstractSpider
{
    public function key(): string
    {
        return 'baidu';
    }

    protected function defaultName(): string
    {
        return '百度蜘蛛';
    }

    protected function defaultPatterns(): array
    {
        return ['/Baiduspider/i', '/BaiduSpider/i'];
    }

    protected function defaultMobilePatterns(): array
    {
        return ['/Mobile\s+Baiduspider/i'];
    }

    protected function versionPattern(): string
    {
        return '/Baiduspider\/([\d.]+)/i';
    }

    public function stat(string $date = '', ?int $siteId = null): array
    {
        return $this->dailyStat($date, $siteId);
    }

    public function trend(int $days = 7, ?int $siteId = null): array
    {
        return $this->trendStat($days, $siteId);
    }
}