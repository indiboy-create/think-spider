<?php

namespace Youvision\ThinkSpider\Spider;

class Toutiao extends AbstractSpider
{
    public function key(): string
    {
        return 'toutiao';
    }

    protected function defaultName(): string
    {
        return '头条蜘蛛';
    }

    protected function defaultPatterns(): array
    {
        return ['/Bytespider/i', '/ByteDanceMCP/i', '/ByteDance-Bot/i', '/TikTokSpider/i'];
    }

    protected function defaultMobilePatterns(): array
    {
        return ['/Mobile/i', '/Android/i', '/iPhone/i'];
    }

    protected function versionPattern(): string
    {
        return '/(?:Bytespider|ByteDanceMCP|ByteDance-Bot|TikTokSpider)\/([\d.]+)/i';
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