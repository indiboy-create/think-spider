<?php

namespace Youvision\ThinkSpider\Command;

use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use Youvision\ThinkSpider\Model\SpiderStat;
use Youvision\ThinkSpider\Model\SpiderVisit;

class Clean extends Command
{
    protected function configure(): void
    {
        $this->setName('spider:clean')
            ->addOption('days', null, Option::VALUE_OPTIONAL, '保留最近多少天的数据')
            ->setDescription('清理 think-spider 历史蜘蛛统计数据');
    }

    protected function execute(Input $input, Output $output)
    {
        $days = (int) ($input->getOption('days') ?: $this->app->config->get('spider.clean.keep_days', 90));
        $days = max(1, $days);
        $before = date('Y-m-d', strtotime('-' . $days . ' days'));

        $visits = SpiderVisit::where('visit_date', '<', $before)->delete();
        $stats = SpiderStat::where('stat_date', '<', $before)->delete();

        $output->writeln(sprintf(
            '<info>think-spider: 已清理 %s 之前的数据，访问明细 %d 条，日统计 %d 条</info>',
            $before,
            $visits,
            $stats
        ));

        return 0;
    }
}