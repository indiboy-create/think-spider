## ThinkPHP-PanGu - 盘古ThinkPHP开源索引!
面向 ThinkPHP 的开源工程索引：组件、框架、服务与发布工具，按能力组织，持续整理。
仓库地址：**<https://github.com/indiboy-create/ThinkPHP-PanGu/>**
在线站点：**<https://indiboy-create.github.io/ThinkPHP-PanGu/>**

# youvision/think-spider

ThinkPHP 8 搜索引擎蜘蛛访问统计组件，自动识别并记录各大搜索引擎蜘蛛的访问，提供按引擎、按日期、按站点的统计数据。

## 支持的搜索引擎

| 引擎 | 标识 | 类 |
| --- | --- | --- |
| 百度蜘蛛（Baiduspider） | `baidu` | `Youvision\ThinkSpider\Spider\Baidu` |
| Google 蜘蛛（Googlebot） | `google` | `Youvision\ThinkSpider\Spider\Google` |
| 搜狗蜘蛛（Sogou） | `sogou` | `Youvision\ThinkSpider\Spider\Sogou` |
| Bing 蜘蛛（Bingbot/Msnbot） | `bing` | `Youvision\ThinkSpider\Spider\Bing` |
| 神马蜘蛛（YisouSpider） | `shenma` | `Youvision\ThinkSpider\Spider\Shenma` |
| 头条蜘蛛（Bytespider/ByteDanceMCP） | `toutiao` | `Youvision\ThinkSpider\Spider\Toutiao` |
| 360 蜘蛛（360Spider） | `360` | `Youvision\ThinkSpider\Spider\Qihoo` |
| Yandex 蜘蛛（YandexBot） | `yandex` | `Youvision\ThinkSpider\Spider\Yandex` |

每个引擎独立一个类文件，均提供 `match()`、`version()`、`mobile()`、`stat()`、`trend()` 方法。

## 功能

- 全局中间件自动采集，无需改动业务控制器
- 访问明细 + 按日聚合双层统计，支持 PV、UV、移动端 PV
- 支持多站点隔离（`site_id` 支持闭包动态解析）
- 支持路径白名单/黑名单、IP 忽略、请求方法过滤
- 可选反向 DNS + 正向解析校验蜘蛛真实性，防止伪造 UA
- 内置 `spider:check` 配置自检命令、`spider:clean` 历史数据清理命令

## 安装

```bash
composer require youvision/think-spider
```

复制配置文件：

```bash
cp vendor/youvision/think-spider/config/spider.php config/spider.php
```

导入数据表：

```bash
mysql -u root -p your_database < vendor/youvision/think-spider/database/spider.sql
```

详细步骤见 [部署文档](docs/部署文档.md)。

## 快速使用

```php
use Youvision\ThinkSpider\Facade\Spider;

$today = Spider::stat();
$baidu = Spider::get('baidu')->stat();
$trend = Spider::get('google')->trend(30);
```

## 目录结构

```text
config/spider.php                 配置文件
database/spider.sql               数据表结构
docs/部署文档.md                  部署与使用文档
src/Service.php                   服务注册
src/SpiderManager.php             蜘蛛识别与统计入口
src/Facade/Spider.php             Facade
src/Middleware/SpiderRecord.php   采集中间件
src/Spider/AbstractSpider.php     蜘蛛抽象类
src/Spider/Concerns/QueryStat.php 统计查询
src/Spider/Baidu.php              百度蜘蛛
src/Spider/Google.php             Google 蜘蛛
src/Spider/Sogou.php              搜狗蜘蛛
src/Spider/Bing.php               Bing 蜘蛛
src/Spider/Shenma.php             神马蜘蛛
src/Spider/Toutiao.php            头条蜘蛛
src/Spider/Qihoo.php              360 蜘蛛
src/Spider/Yandex.php             Yandex 蜘蛛
src/Model/SpiderVisit.php         访问明细模型
src/Model/SpiderStat.php          日统计模型
src/Support/Recorder.php          写入与聚合
src/Support/Verifier.php          真实性校验
src/Command/Check.php             配置自检命令
src/Command/Clean.php             数据清理命令
```

## License

MIT