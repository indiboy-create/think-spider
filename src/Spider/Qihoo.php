<?php

namespace Youvision\ThinkSpider\Spider;

class Qihoo extends AbstractSpider
{
    public function key(): string
    {
        return '360';
    }

    protected function defaultName(): string
    {
        return '360蜘蛛';
    }

    protected function defaultPatterns(): array
    {
        return ['/360Spider/i', '/HaosouSpider/i'];
    }

    protected function defaultMobilePatterns(): array
    {
        return ['/360Spider.*Mobile/i', '/HaosouSpider.*Mobile/i'];
    }

    protected function versionPattern(): string
    {
        return '/(?:360Spider|HaosouSpider)\/([\d.]+)/i';
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