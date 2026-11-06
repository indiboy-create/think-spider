<?php

namespace Youvision\ThinkSpider\Model;

use think\Model;

class SpiderStat extends Model
{
    protected $name = 'spider_stat';

    protected $connection = '';

    protected $autoWriteTimestamp = 'datetime';

    protected $createTime = 'created_at';

    protected $updateTime = 'updated_at';

    protected $type = [
        'site_id'   => 'integer',
        'pv'        => 'integer',
        'uv'        => 'integer',
        'mobile_pv' => 'integer',
    ];

    protected function initialize(): void
    {
        parent::initialize();

        $config = (array) config('spider', []);

        $this->name = (string) ($config['tables']['stat'] ?? 'spider_stat');

        if (!empty($config['connection'])) {
            $this->connection = (string) $config['connection'];
        }
    }
}