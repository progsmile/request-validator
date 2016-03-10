DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(72) NOT NULL,
  `email` VARCHAR(72) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `users` (`id`, `name`, `email`) VALUES
  (1, 'John Doe',    'dd@dd.dd'),
  (2, 'John Roe',    'john@roe.com'),
  (3, 'Johnnie Doe', 'johnnie@roe.com');
