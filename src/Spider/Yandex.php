<?php

namespace Youvision\ThinkSpider\Spider;

class Yandex extends AbstractSpider
{
    public function key(): string
    {
        return 'yandex';
    }

    protected function defaultName(): string
    {
        return 'Yandex蜘蛛';
    }

    protected function defaultPatterns(): array
    {
        return [
            '/YandexBot/i',
            '/YandexMobileBot/i',
            '/YandexImages/i',
            '/YandexVideo/i',
            '/YandexNews/i',
            '/YandexWebmaster/i',
        ];
    }

    protected function defaultMobilePatterns(): array
    {
        return ['/YandexMobileBot/i'];
    }

    protected function versionPattern(): string
    {
        return '/Yandex\w*\/([\d.]+)/i';
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