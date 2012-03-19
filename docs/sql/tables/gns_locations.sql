SET NAMES 'utf8';
DROP TABLE IF EXISTS `gns_locations`;
CREATE TABLE IF NOT EXISTS `gns_locations` (
  `rc` tinyint(4) NOT NULL default '0',
  `ufi` int(11) NOT NULL default '0',
  `uni` int(11) NOT NULL default '0',
  `lat` double NOT NULL default '0',
  `lon` double NOT NULL default '0',
  `dms_lat` int(11) NOT NULL default '0',
  `dms_lon` int(11) NOT NULL default '0',
  `utm` varchar(4) NOT NULL,
  `jog` varchar(7) NOT NULL,
  `fc` char(1) NOT NULL,
  `dsg` varchar(5) NOT NULL,
  `pc` tinyint(4) NOT NULL default '0',
  `cc1` char(2) NOT NULL,
  `adm1` char(2) NOT NULL,
  `adm2` varchar(200) NOT NULL,
  `dim` int(11) NOT NULL default '0',
  `cc2` char(2) NOT NULL,
  `nt` char(1) NOT NULL,
  `lc` char(2) NOT NULL,
  `SHORT_FORM` varchar(128) NOT NULL,
  `GENERIC` varchar(128) NOT NULL,
  `SORT_NAME` varchar(200) NOT NULL,
  `FULL_NAME` varchar(200) NOT NULL,
  `FULL_NAME_ND` varchar(200) NOT NULL,
  `MOD_DATE` date NOT NULL default '0000-00-00',
  `admtxt1` varchar(120) NOT NULL,
  `admtxt3` varchar(120) NOT NULL,
  `admtxt4` varchar(120) NOT NULL,
  `admtxt2` varchar(120) NOT NULL,
  PRIMARY KEY  (`uni`),
  KEY `rc` (`rc`,`fc`,`dsg`,`cc1`),
  KEY `ufi` (`ufi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


