<?php

namespace Youvision\ThinkSpider\Spider;

use Youvision\ThinkSpider\Spider\Concerns\QueryStat;

abstract class AbstractSpider
{
    use QueryStat;

    protected array $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    abstract public function key(): string;

    abstract protected function defaultName(): string;

    abstract protected function defaultPatterns(): array;

    public function name(): string
    {
        return (string) ($this->config['name'] ?? $this->defaultName());
    }

    public function patterns(): array
    {
        return array_merge($this->defaultPatterns(), (array) ($this->config['extra_patterns'] ?? []));
    }

    protected function defaultMobilePatterns(): array
    {
        return [];
    }

    public function mobilePatterns(): array
    {
        return array_merge($this->defaultMobilePatterns(), (array) ($this->config['mobile_patterns'] ?? []));
    }

    protected function versionPattern(): string
    {
        return '';
    }

    public function version(string $userAgent): string
    {
        $pattern = (string) ($this->config['version_pattern'] ?? '');

        if ($pattern === '') {
            $pattern = $this->versionPattern();
        }

        if ($pattern !== '' && preg_match($pattern, $userAgent, $matches) === 1) {
            return (string) ($matches[1] ?? '');
        }

        return '';
    }

    public function mobile(string $userAgent): bool
    {
        foreach ($this->mobilePatterns() as $pattern) {
            if (preg_match($pattern, $userAgent) === 1) {
                return true;
            }
        }

        return false;
    }

    public function match(string $userAgent): bool
    {
        if ($userAgent === '') {
            return false;
        }

        foreach ($this->patterns() as $pattern) {
            if (preg_match($pattern, $userAgent) === 1) {
                return true;
            }
        }

        return false;
    }

    public function enabled(): bool
    {
        return (bool) ($this->config['enable'] ?? true);
    }
}