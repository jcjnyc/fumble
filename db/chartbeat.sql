DROP TABLE IF EXISTS `run`;
CREATE TABLE `run` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `run_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `toppages`;
CREATE TABLE `toppages` (
  `batch` int(11) NOT NULL,
  `site` varchar(100) NOT NULL,
  `i` varchar(255) DEFAULT NULL,
  `path` varchar(255) NOT NULL,
  `visitors` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`batch`,`site`,`path`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

