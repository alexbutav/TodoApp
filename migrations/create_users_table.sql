CREATE TABLE IF NOT EXISTS `users` (
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `is_admin` binary(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
