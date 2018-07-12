DROP DATABASE IF EXISTS ca;
CREATE DATABASE ca;

DROP TABLE IF EXISTS ca_staff;
CREATE TABLE ca_staff (
    ID INT(11) NOT NULL AUTO_INCREMENT,
	FirstName varchar(25) NOT NULL,
    LastName varchar(25) NOT NULL,
    Email varchar(320) NOT NULL UNIQUE,
    PhoneNumber varchar(13),
	Permission TINYINT(1) DEFAULT 0, # admin=1 / teacher=0
	Password varchar(64), #sha 256
	Token varchar(32), # md5
	Active TINYINT(1),
    PRIMARY KEY (ID)
); 

DROP TABLE IF EXISTS ca_staff_failed_login_log;
CREATE TABLE ca_staff_failed_login_log (
    ID INT(11) NOT NULL AUTO_INCREMENT,
	user_ip VARCHAR(15) NOT NULL,
	dt DATETIME NOT NULL,
	PRIMARY KEY (ID)
);

DROP TABLE IF EXISTS ca_student;
CREATE TABLE ca_student (
    ID INT(11) NOT NULL AUTO_INCREMENT,
	Student_ID CHAR(6) NOT NULL, # L99.. etc.
	FirstName varchar(25) NOT NULL,
    LastName varchar(25) NOT NULL,
    Email varchar(320) NOT NULL UNIQUE,
    PhoneNumber varchar(13) NOT NULL,
	NFC_ID varchar(50) NOT NULL UNIQUE, 
    PRIMARY KEY (ID)
); 

DROP TABLE IF EXISTS ca_guest;
CREATE TABLE ca_guest (
    ID INT(11) NOT NULL AUTO_INCREMENT,
	FirstName varchar(25) NOT NULL,
    LastName varchar(25) NOT NULL,	
    PRIMARY KEY (ID)
); 

DROP TABLE IF EXISTS ca_course;
CREATE TABLE ca_course (
    ID INT(11) NOT NULL AUTO_INCREMENT,
	Course_ID VARCHAR(20), 
	Course_name varchar(50) NOT NULL,
    Course_description varchar(500), 
    PRIMARY KEY (ID)
); 

DROP TABLE IF EXISTS ca_course_teacher;
CREATE TABLE ca_course_teacher (
    ID INT(11) NOT NULL AUTO_INCREMENT,
	staff_id INT(11) NOT NULL,
	course_id INT(11) NOT NULL,
    PRIMARY KEY (ID)
);	

DROP TABLE IF EXISTS ca_course_student;
CREATE TABLE ca_course_student (
    ID INT(11) NOT NULL AUTO_INCREMENT,
	student_id INT(11) NOT NULL,
	course_id INT(11) NOT NULL,
    PRIMARY KEY (ID)
);	
	
DROP TABLE IF EXISTS ca_roomlog;
CREATE TABLE ca_roomlog (
    ID INT(11) NOT NULL AUTO_INCREMENT,
	NFC_ID varchar(50),
	guest_id INT(11),
	dt DATETIME NOT NULL,
    room_id INT(11) NOT NULL,
	PRIMARY KEY (ID)
);
