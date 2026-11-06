<?php

namespace Youvision\ThinkSpider\Spider;

class Google extends AbstractSpider
{
    public function key(): string
    {
        return 'google';
    }

    protected function defaultName(): string
    {
        return 'Google蜘蛛';
    }

    protected function defaultPatterns(): array
    {
        return ['/Googlebot/i', '/Google-InspectionTool/i'];
    }

    protected function defaultMobilePatterns(): array
    {
        return ['/Googlebot-Mobile/i', '/Mobile\s+Googlebot/i'];
    }

    protected function versionPattern(): string
    {
        return '/Googlebot\/([\d.]+)/i';
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