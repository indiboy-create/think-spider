<?php

namespace Youvision\ThinkSpider\Spider;

class Shenma extends AbstractSpider
{
    public function key(): string
    {
        return 'shenma';
    }

    protected function defaultName(): string
    {
        return '神马蜘蛛';
    }

    protected function defaultPatterns(): array
    {
        return ['/YisouSpider/i', '/ShenmaSpider/i'];
    }

    protected function defaultMobilePatterns(): array
    {
        return ['/YisouSpider/i', '/ShenmaSpider/i'];
    }

    protected function versionPattern(): string
    {
        return '/(?:YisouSpider|ShenmaSpider)\/([\d.]+)/i';
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