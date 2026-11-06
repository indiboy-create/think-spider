<?php

namespace Youvision\ThinkSpider\Spider;

class Bing extends AbstractSpider
{
    public function key(): string
    {
        return 'bing';
    }

    protected function defaultName(): string
    {
        return 'Bing蜘蛛';
    }

    protected function defaultPatterns(): array
    {
        return ['/bingbot/i', '/msnbot/i', '/BingPreview/i', '/adidxbot/i'];
    }

    protected function defaultMobilePatterns(): array
    {
        return ['/bingbot.*Mobile/i', '/msnbot.*Mobile/i'];
    }

    protected function versionPattern(): string
    {
        return '/(?:bingbot|msnbot)\/([\d.]+)/i';
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