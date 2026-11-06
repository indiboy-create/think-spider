<?php

namespace Youvision\ThinkSpider\Model;

use think\Model;

class SpiderVisit extends Model
{
    protected $name = 'spider_visit';

    protected $connection = '';

    protected $autoWriteTimestamp = 'datetime';

    protected $createTime = 'created_at';

    protected $updateTime = false;

    protected $type = [
        'site_id'     => 'integer',
        'mobile'      => 'integer',
        'status_code' => 'integer',
        'visit_hour'  => 'integer',
        'verified'    => 'integer',
    ];

    protected function initialize(): void
    {
        parent::initialize();

        $config = (array) config('spider', []);

        $this->name = (string) ($config['tables']['visit'] ?? 'spider_visit');

        if (!empty($config['connection'])) {
            $this->connection = (string) $config['connection'];
        }
    }
}