<?php

namespace Youvision\ThinkSpider\Support;

use think\facade\Cache;

class Verifier
{
    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function enabled(): bool
    {
        return (bool) ($this->config['enable'] ?? false);
    }

    public function verify(string $spider, string $ip): bool
    {
        if (!$this->enabled()) {
            return true;
        }

        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return false;
        }

        $suffixes = (array) ($this->config['hosts'][$spider] ?? []);
        if ($suffixes === []) {
            return false;
        }

        $cacheKey = 'think_spider_verify_' . md5($spider . '|' . $ip);
        $cached = $this->cacheGet($cacheKey);
        if ($cached !== null) {
            return (bool) $cached;
        }

        $host = @gethostbyaddr($ip);
        $result = false;

        if (is_string($host) && $host !== '' && $host !== $ip) {
            $host = strtolower(rtrim($host, '.'));

            foreach ($suffixes as $suffix) {
                $suffix = strtolower(ltrim((string) $suffix, '.'));

                if ($host === $suffix || str_ends_with($host, '.' . $suffix)) {
                    $result = true;
                    break;
                }
            }

            if ($result && (bool) ($this->config['check_forward'] ?? true)) {
                $result = $this->forward($host, $ip);
            }
        }

        $this->cacheSet($cacheKey, $result);

        return $result;
    }

    protected function forward(string $host, string $ip): bool
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) !== false) {
            $records = @dns_get_record($host, DNS_AAAA) ?: [];

            foreach ($records as $record) {
                if (($record['ipv6'] ?? '') === $ip) {
                    return true;
                }
            }

            return false;
        }

        $ips = @gethostbynamel($host) ?: [];

        return in_array($ip, $ips, true);
    }

    protected function cacheGet(string $key): ?bool
    {
        try {
            $value = Cache::get($key);

            return $value === null ? null : (bool) $value;
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function cacheSet(string $key, bool $value): void
    {
        try {
            Cache::set($key, $value, (int) ($this->config['cache_ttl'] ?? 86400));
        } catch (\Throwable $e) {
        }
    }
}