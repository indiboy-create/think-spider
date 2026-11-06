-- +----------------------------------------------------------------------
-- | youvision/think-spider 数据表
-- | MySQL 5.7+ / 8.0，字符集 utf8mb4
-- +----------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `spider_visit` (
    `id`            bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
    `site_id`       int unsigned NOT NULL DEFAULT 0 COMMENT '站点ID，用于多站点隔离',
    `spider`        varchar(32) NOT NULL COMMENT '蜘蛛标识：baidu/google/sogou/bing/shenma/toutiao/360/yandex',
    `spider_name`   varchar(50) NOT NULL DEFAULT '' COMMENT '蜘蛛名称',
    `version`       varchar(20) NOT NULL DEFAULT '' COMMENT '蜘蛛版本号',
    `mobile`        tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否移动端：0否 1是',
    `user_agent`    varchar(1000) NOT NULL DEFAULT '' COMMENT 'User-Agent',
    `ip`            varchar(45) NOT NULL DEFAULT '' COMMENT '访问IP，兼容IPv6',
    `ip_hash`       char(32) NOT NULL DEFAULT '' COMMENT 'IP的MD5值，用于UV去重',
    `url`           varchar(1000) NOT NULL DEFAULT '' COMMENT '完整访问URL',
    `path`          varchar(255) NOT NULL DEFAULT '' COMMENT '访问路径，不含域名和查询参数',
    `host`          varchar(255) NOT NULL DEFAULT '' COMMENT '访问域名',
    `referer`       varchar(1000) NOT NULL DEFAULT '' COMMENT '来源地址',
    `method`        varchar(10) NOT NULL DEFAULT 'GET' COMMENT '请求方法',
    `status_code`   smallint unsigned NOT NULL DEFAULT 0 COMMENT 'HTTP响应状态码',
    `visit_date`    date NOT NULL COMMENT '访问日期',
    `visit_hour`    tinyint unsigned NOT NULL DEFAULT 0 COMMENT '访问小时 0-23',
    `verified`      tinyint(1) NOT NULL DEFAULT 1 COMMENT '真实性校验：0未通过 1通过',
    `created_at`    datetime DEFAULT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`),
    KEY `idx_spider_date` (`spider`, `visit_date`),
    KEY `idx_site_date` (`site_id`, `visit_date`),
    KEY `idx_date_ip` (`visit_date`, `ip_hash`),
    KEY `idx_date_path` (`visit_date`, `path`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='蜘蛛访问明细表';

CREATE TABLE IF NOT EXISTS `spider_stat` (
    `id`             bigint unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
    `site_id`        int unsigned NOT NULL DEFAULT 0 COMMENT '站点ID，用于多站点隔离',
    `spider`         varchar(32) NOT NULL COMMENT '蜘蛛标识：baidu/google/sogou/bing/shenma/toutiao/360/yandex',
    `stat_date`      date NOT NULL COMMENT '统计日期',
    `pv`             int unsigned NOT NULL DEFAULT 0 COMMENT '当日访问量',
    `uv`             int unsigned NOT NULL DEFAULT 0 COMMENT '当日独立IP数',
    `mobile_pv`      int unsigned NOT NULL DEFAULT 0 COMMENT '当日移动端访问量',
    `first_visit_at` datetime DEFAULT NULL COMMENT '当日首次访问时间',
    `last_visit_at`  datetime DEFAULT NULL COMMENT '当日最后访问时间',
    `created_at`     datetime DEFAULT NULL COMMENT '创建时间',
    `updated_at`     datetime DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_site_spider_date` (`site_id`, `spider`, `stat_date`),
    KEY `idx_stat_date` (`stat_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='蜘蛛日统计表';