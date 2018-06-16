SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS users;
CREATE TABLE users
(
  id int(11) NOT NULL AUTO_INCREMENT,
  email varchar(254) DEFAULT NULL,
  phone varchar(20) DEFAULT NULL,
  password varchar(60) DEFAULT NULL,
  isActive tinyint(1) NOT NULL DEFAULT '0',
  isDeleted tinyint(1) NOT NULL DEFAULT '0',
  resetNeeded tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS attempts;
CREATE TABLE attempts
(
  id int(11) NOT NULL AUTO_INCREMENT,
  ip varchar(39) NOT NULL,
  expire datetime NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS config;
CREATE TABLE config
(
  id int(11) NOT NULL AUTO_INCREMENT,
  setting varchar(100) NOT NULL,
  value varchar(100) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS requests;
CREATE TABLE requests
(
  id int(11) NOT NULL AUTO_INCREMENT,
  userID int(11),
  emailKey varchar(20),
  smsKey varchar(20),
  expire datetime,
  type varchar(20),
  PRIMARY KEY (id),
  FOREIGN KEY (userID) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS sessions;
CREATE TABLE sessions
(
  id int(11) NOT NULL AUTO_INCREMENT,
  userID int(11),
  hash varchar(40),
  expire datetime,
  ip varchar(39),
  agent varchar(200),
  cookieCRC varchar(40),
  PRIMARY KEY (id),
  FOREIGN KEY (userID) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;