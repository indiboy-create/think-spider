<?php

namespace Youvision\ThinkSpider\Spider;

class Sogou extends AbstractSpider
{
    public function key(): string
    {
        return 'sogou';
    }

    protected function defaultName(): string
    {
        return '搜狗蜘蛛';
    }

    protected function defaultPatterns(): array
    {
        return [
            '/Sogou\s+web\s+spider/i',
            '/Sogou\s+inst\s+spider/i',
            '/Sogou\s+spider/i',
            '/Sogou\s+news\s+spider/i',
            '/Sogou\s+pic\s+spider/i',
            '/Sogou\s+video\s+spider/i',
            '/SogouMobileBot/i',
            '/Sogou-Test-Spider/i',
        ];
    }

    protected function defaultMobilePatterns(): array
    {
        return ['/SogouMobileBot/i'];
    }

    protected function versionPattern(): string
    {
        return '/Sogou[^\/]*\/([\d.]+)/i';
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