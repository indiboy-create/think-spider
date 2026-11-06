<?php

namespace Youvision\ThinkSpider\Middleware;

use Closure;
use think\facade\Log;
use think\Request;
use Youvision\ThinkSpider\SpiderManager;

class SpiderRecord
{
    protected SpiderManager $manager;

    public function __construct(SpiderManager $manager)
    {
        $this->manager = $manager;
    }

    public function handle(Request $request, Closure $next)
    {
        if (!$this->manager->enabled()) {
            return $next($request);
        }

        $userAgent = (string) $request->header('user-agent', '');
        $spider = $this->manager->detect($userAgent);

        if ($spider === null) {
            return $next($request);
        }

        $response = $next($request);

        try {
            $this->manager->record($request, $spider, $response);
        } catch (\Throwable $e) {
            if ($this->manager->debug()) {
                try {
                    Log::error('[think-spider] ' . $e->getMessage());
                } catch (\Throwable $ignore) {
                }
            }
        }

        return $response;
    }
}