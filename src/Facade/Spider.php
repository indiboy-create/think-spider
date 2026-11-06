<?php

namespace Youvision\ThinkSpider\Facade;

use think\Facade;
use Youvision\ThinkSpider\SpiderManager;

/**
 * @method static bool enabled()
 * @method static bool debug()
 * @method static array all()
 * @method static array spiders()
 * @method static \Youvision\ThinkSpider\Spider\AbstractSpider|null get(string $key)
 * @method static \Youvision\ThinkSpider\Spider\AbstractSpider|null detect(string $userAgent)
 * @method static bool isSpider(string $userAgent)
 * @method static bool shouldRecord(\think\Request $request)
 * @method static \Youvision\ThinkSpider\Model\SpiderVisit|null record(\think\Request $request, \Youvision\ThinkSpider\Spider\AbstractSpider $spider, ?\think\Response $response = null)
 * @method static array stat(string $date = '', ?int $siteId = null)
 * @method static array trend(int $days = 7, ?int $siteId = null)
 *
 * @see \Youvision\ThinkSpider\SpiderManager
 */
class Spider extends Facade
{
    protected static function getFacadeClass(): string
    {
        return SpiderManager::class;
    }
}