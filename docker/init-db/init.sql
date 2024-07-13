CREATE SCHEMA todo DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS `todo`.`tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `edited` binary(1) NOT NULL DEFAULT '0',
  `done` binary(1) NOT NULL DEFAULT '0',
  `email` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `body` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `todo`.`users` (
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `is_admin` binary(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO
    `todo`.`users` (`name`, `email`, `password`, `is_admin`)
VALUES
    (
        'admin',
        'admin@admin.com',
        '$2y$10$DEpY3x7ThZg.kOhoapUnF.HhDg1Up7fjavbpxTkxccI5OXabAx2zu',
        '1'
    );