<?php

namespace Youvision\ThinkSpider\Support;

use think\Request;
use think\Response;
use Youvision\ThinkSpider\Model\SpiderStat;
use Youvision\ThinkSpider\Model\SpiderVisit;
use Youvision\ThinkSpider\Spider\AbstractSpider;

class Recorder
{
    protected array $config;

    protected Verifier $verifier;

    public function __construct(array $config = [])
    {
        $this->config = $config;
        $this->verifier = new Verifier((array) ($config['verify'] ?? []));
    }

    public function record(Request $request, AbstractSpider $spider, ?Response $response = null): ?SpiderVisit
    {
        $now = time();
        $time = date('Y-m-d H:i:s', $now);
        $date = date('Y-m-d', $now);
        $hour = (int) date('G', $now);

        $siteId = $this->siteId($request);
        $ip = (string) $request->ip();
        $userAgent = $this->limit((string) $request->header('user-agent', ''), 1000);
        $mobile = $spider->mobile($userAgent);
        $verified = 1;

        if ($this->verifier->enabled()) {
            $verified = $this->verifier->verify($spider->key(), $ip) ? 1 : 0;

            if ($verified === 0 && (bool) ($this->config['verify']['discard_unverified'] ?? false)) {
                return null;
            }
        }

        $visit = null;

        if ((bool) ($this->config['log_visit'] ?? true)) {
            $visit = SpiderVisit::create([
                'site_id'     => $siteId,
                'spider'      => $spider->key(),
                'spider_name' => $spider->name(),
                'version'     => $this->limit($spider->version($userAgent), 20),
                'mobile'      => $mobile ? 1 : 0,
                'user_agent'  => $userAgent,
                'ip'          => $this->limit($ip, 45),
                'ip_hash'     => md5($ip),
                'url'         => $this->limit((string) $request->url(true), 1000),
                'path'        => $this->limit('/' . ltrim((string) $request->pathinfo(), '/'), 255),
                'host'        => $this->limit((string) $request->host(), 255),
                'referer'     => $this->limit((string) $request->header('referer', ''), 1000),
                'method'      => strtoupper((string) $request->method()),
                'status_code' => $response ? (int) $response->getCode() : 0,
                'visit_date'  => $date,
                'visit_hour'  => $hour,
                'verified'    => $verified,
            ]);
        }

        if ((bool) ($this->config['stat_daily'] ?? true)) {
            $isNewIp = $visit !== null
                && (bool) ($this->config['unique_uv'] ?? true)
                && SpiderVisit::where('spider', $spider->key())
                    ->where('site_id', $siteId)
                    ->where('visit_date', $date)
                    ->where('ip_hash', md5($ip))
                    ->count() === 1;

            $this->updateStat($siteId, $spider, $date, $time, $mobile, $isNewIp);
        }

        return $visit;
    }

    protected function updateStat(
        int $siteId,
        AbstractSpider $spider,
        string $date,
        string $time,
        bool $mobile,
        bool $isNewIp
    ): void {
        $stat = SpiderStat::where('site_id', $siteId)
            ->where('spider', $spider->key())
            ->where('stat_date', $date)
            ->find();

        if ($stat === null) {
            try {
                SpiderStat::create([
                    'site_id'       => $siteId,
                    'spider'        => $spider->key(),
                    'stat_date'     => $date,
                    'pv'            => 1,
                    'uv'            => $isNewIp ? 1 : 0,
                    'mobile_pv'     => $mobile ? 1 : 0,
                    'first_visit_at' => $time,
                    'last_visit_at'  => $time,
                ]);

                return;
            } catch (\Throwable $e) {
                $stat = SpiderStat::where('site_id', $siteId)
                    ->where('spider', $spider->key())
                    ->where('stat_date', $date)
                    ->find();

                if ($stat === null) {
                    return;
                }
            }
        }

        $query = SpiderStat::where('id', $stat->id)->inc('pv');

        if ($mobile) {
            $query->inc('mobile_pv');
        }

        if ($isNewIp) {
            $query->inc('uv');
        }

        $query->update(['last_visit_at' => $time, 'updated_at' => $time]);
    }

    protected function siteId(Request $request): int
    {
        $siteId = $this->config['site_id'] ?? 0;

        if (is_callable($siteId)) {
            $siteId = $siteId($request);
        }

        return (int) $siteId;
    }

    protected function limit(string $value, int $length): string
    {
        if ($length <= 0) {
            return '';
        }

        return mb_substr($value, 0, $length);
    }
}