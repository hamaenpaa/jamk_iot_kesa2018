DROP DATABASE IF EXISTS ca;
CREATE DATABASE ca DEFAULT CHARSET=utf8 COLLATE="utf8_general_ci";
USE ca;
	
DROP TABLE IF EXISTS ca_roomlog;
CREATE TABLE ca_roomlog (
    ID INT NOT NULL AUTO_INCREMENT,
	NFC_ID varchar(50),
	room_identifier VARCHAR(50),
	dt DATETIME NOT NULL,
	PRIMARY KEY (ID)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ca_lesson;
CREATE TABLE ca_lesson (
    ID INT NOT NULL AUTO_INCREMENT,
	begin_time DATETIME NOT NULL,
	end_time DATETIME NOT NULL,
	room_identifier VARCHAR(50),
	topic VARCHAR(150),
	course_id INT,
	removed TINYINT(1) DEFAULT 0,
	PRIMARY KEY (ID)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ca_course;
CREATE TABLE ca_course (
    ID INT NOT NULL AUTO_INCREMENT,
	name varchar(50),
	description TEXT,
	removed TINYINT(1) DEFAULT 0,
	PRIMARY KEY (ID)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ca_user_failed_login_log;
CREATE TABLE ca_user_failed_login_log (
    ID INT NOT NULL AUTO_INCREMENT,
	user_ip VARCHAR(15) NOT NULL,
	dt DATETIME NOT NULL,
	PRIMARY KEY (ID)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ca_user;
CREATE TABLE ca_user (
    ID INT NOT NULL AUTO_INCREMENT,
	`Permission` TINYINT(1) DEFAULT 0, # admin=1 / teacher=0
	Username varchar(65),
	Password varchar(255), #sha 256
	removed TINYINT(1) DEFAULT 0,
    PRIMARY KEY (ID)
) ;

DROP TABLE IF EXISTS ca_topic;
CREATE TABLE ca_topic (
    ID INT NOT NULL AUTO_INCREMENT,
	name varchar(150),
	removed TINYINT(1) DEFAULT 0,
    PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS ca_lesson_topic;
CREATE TABLE ca_lesson_topic (
    ID INT NOT NULL AUTO_INCREMENT,
	lesson_id INT,
	topic_id INT,
    PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS ca_setting;
CREATE TABLE ca_setting (
    ID INT NOT NULL AUTO_INCREMENT,
	default_roomidentifier VARCHAR(50),
	usage_type INT,
	page_size INT,
	page_page_size INT,
    PRIMARY KEY (ID)
);

INSERT INTO ca_setting (default_roomidentifier, usage_type,
	page_size, page_page_size) VALUES ('',1,50,20);

INSERT INTO ca_user (`Permission`,Username,Password) 
	VALUES (1,"Admin","ba672edb750d8f4a7787e75fc1adeacd587afea9671f189234cb65015d446ad9");
