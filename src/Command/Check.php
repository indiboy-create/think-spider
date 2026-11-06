<?php

namespace Youvision\ThinkSpider\Command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use Youvision\ThinkSpider\Model\SpiderStat;
use Youvision\ThinkSpider\Model\SpiderVisit;
use Youvision\ThinkSpider\SpiderManager;

class Check extends Command
{
    protected function configure(): void
    {
        $this->setName('spider:check')
            ->setDescription('检查 think-spider 配置、蜘蛛规则与数据表状态');
    }

    protected function execute(Input $input, Output $output)
    {
        $config = (array) $this->app->config->get('spider', []);
        $manager = $this->app->make(SpiderManager::class);

        $output->writeln('统计总开关：' . ((bool) ($config['enable'] ?? true) ? '开启' : '关闭'));
        $output->writeln('自动中间件：' . ((bool) ($config['auto_middleware'] ?? true) ? '开启' : '关闭'));
        $output->writeln('记录明细：' . ((bool) ($config['log_visit'] ?? true) ? '开启' : '关闭'));
        $output->writeln('日统计：' . ((bool) ($config['stat_daily'] ?? true) ? '开启' : '关闭'));
        $output->writeln('UV去重：' . ((bool) ($config['unique_uv'] ?? true) ? '开启' : '关闭'));
        $output->writeln('真实性校验：' . ((bool) ($config['verify']['enable'] ?? false) ? '开启' : '关闭'));
        $output->writeln('数据库连接：' . (((string) ($config['connection'] ?? '')) !== '' ? $config['connection'] : '默认连接'));
        $output->writeln('明细表：' . ($config['tables']['visit'] ?? 'spider_visit'));
        $output->writeln('统计表：' . ($config['tables']['stat'] ?? 'spider_stat'));
        $output->writeln('');

        $output->writeln('蜘蛛规则：');

        foreach ($manager->all() as $key => $spider) {
            $output->writeln(sprintf(
                '  %-8s %-12s %s  规则 %d 条  移动规则 %d 条',
                $key,
                $spider->name(),
                $spider->enabled() ? '<info>启用</info>' : '<comment>停用</comment>',
                count($spider->patterns()),
                count($spider->mobilePatterns())
            ));
        }

        $output->writeln('');

        try {
            $output->writeln('spider_visit：正常，共 ' . SpiderVisit::count() . ' 条');
        } catch (\Throwable $e) {
            $output->writeln('<error>spider_visit：' . $e->getMessage() . '</error>');
        }

        try {
            $output->writeln('spider_stat：正常，共 ' . SpiderStat::count() . ' 条');
        } catch (\Throwable $e) {
            $output->writeln('<error>spider_stat：' . $e->getMessage() . '</error>');
        }

        $output->writeln('');

        $stat = $manager->stat();
        $output->writeln('今日统计：PV ' . $stat['total']['pv'] . '，UV ' . $stat['total']['uv'] . '，移动PV ' . $stat['total']['mobile_pv']);

        return 0;
    }
}