<?php

namespace Youvision\ThinkSpider\Spider\Concerns;

use Youvision\ThinkSpider\Model\SpiderStat;

trait QueryStat
{
    protected function dailyStat(string $date = '', ?int $siteId = null): array
    {
        $date = $date !== '' ? $date : date('Y-m-d');

        $stat = SpiderStat::where('spider', $this->key())
            ->where('stat_date', $date)
            ->when($siteId !== null, function ($query) use ($siteId) {
                $query->where('site_id', $siteId);
            })
            ->find();

        return [
            'spider'    => $this->key(),
            'name'      => $this->name(),
            'date'      => $date,
            'pv'        => $stat ? (int) $stat->pv : 0,
            'uv'        => $stat ? (int) $stat->uv : 0,
            'mobile_pv' => $stat ? (int) $stat->mobile_pv : 0,
        ];
    }

    protected function trendStat(int $days = 7, ?int $siteId = null): array
    {
        $days = max(1, min($days, 90));
        $start = date('Y-m-d', strtotime('-' . ($days - 1) . ' days'));

        $rows = SpiderStat::where('spider', $this->key())
            ->where('stat_date', '>=', $start)
            ->when($siteId !== null, function ($query) use ($siteId) {
                $query->where('site_id', $siteId);
            })
            ->field('stat_date,pv,uv,mobile_pv')
            ->select();

        $map = [];
        foreach ($rows as $row) {
            $map[(string) $row->getData('stat_date')] = $row;
        }

        $items = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime('-' . $i . ' days'));
            $row = $map[$date] ?? null;

            $items[] = [
                'date'      => $date,
                'pv'        => $row ? (int) $row->pv : 0,
                'uv'        => $row ? (int) $row->uv : 0,
                'mobile_pv' => $row ? (int) $row->mobile_pv : 0,
            ];
        }

        return [
            'spider' => $this->key(),
            'name'   => $this->name(),
            'days'   => $days,
            'items'  => $items,
        ];
    }
}