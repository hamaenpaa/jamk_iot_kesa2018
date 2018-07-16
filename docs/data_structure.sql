DROP DATABASE IF EXISTS ca;
CREATE DATABASE ca DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

DROP TABLE IF EXISTS ca_staff;
CREATE TABLE ca_staff (
    ID INT NOT NULL AUTO_INCREMENT,
	FirstName varchar(25) NOT NULL,
    LastName varchar(25) NOT NULL,
    Email varchar(255) NOT NULL UNIQUE,
	Permission TINYINT(1) DEFAULT 0, # admin=1 / teacher=0
	Password varchar(64), #sha 256
	Token varchar(32), # md5
	Active TINYINT(1),    
	PhoneNumber varchar(13),
    PRIMARY KEY (ID)
) DEFAULT CHARSET=utf8; 

DROP TABLE IF EXISTS ca_staff_failed_login_log;
CREATE TABLE ca_staff_failed_login_log (
    ID INT NOT NULL AUTO_INCREMENT,
	user_ip VARCHAR(15) NOT NULL,
	dt DATETIME NOT NULL,
	PRIMARY KEY (ID)
) DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS ca_student;
CREATE TABLE ca_student (
    ID INT NOT NULL AUTO_INCREMENT,
	Student_ID CHAR(6) NOT NULL, # L99.. etc.
	FirstName varchar(25) NOT NULL,
    LastName varchar(25) NOT NULL,
    Email varchar(255) NOT NULL UNIQUE,
    PhoneNumber varchar(13) NOT NULL,
	NFC_ID varchar(50) NOT NULL UNIQUE, 
    PRIMARY KEY (ID)
) DEFAULT CHARSET=utf8; 

DROP TABLE IF EXISTS ca_guest;
CREATE TABLE ca_guest (
    ID INT NOT NULL AUTO_INCREMENT,
	FirstName varchar(25) NOT NULL,
    LastName varchar(25) NOT NULL,	
    PRIMARY KEY (ID)
) DEFAULT CHARSET=utf8; 

DROP TABLE IF EXISTS ca_course;
CREATE TABLE ca_course (
    ID INT NOT NULL AUTO_INCREMENT,
	Course_ID VARCHAR(20), 
	Course_name varchar(50) NOT NULL,
    Course_description varchar(500), 
    PRIMARY KEY (ID)
) DEFAULT CHARSET=utf8; 

DROP TABLE IF EXISTS ca_course_teacher;
CREATE TABLE ca_course_teacher (
    ID INT NOT NULL AUTO_INCREMENT,
	staff_id INT NOT NULL,
	course_id INT NOT NULL,
    PRIMARY KEY (ID)
) DEFAULT CHARSET=utf8;	

DROP TABLE IF EXISTS ca_course_student;
CREATE TABLE ca_course_student (
    ID INT NOT NULL AUTO_INCREMENT,
	student_id INT NOT NULL,
	course_id INT NOT NULL,
    PRIMARY KEY (ID)
) DEFAULT CHARSET=utf8;	
	
DROP TABLE IF EXISTS ca_roomlog;
CREATE TABLE ca_roomlog (
    ID INT NOT NULL AUTO_INCREMENT,
	NFC_ID varchar(50),
	guest_id INT,
	dt DATETIME NOT NULL,
    room_id INT NOT NULL,
	PRIMARY KEY (ID)
) DEFAULT CHARSET=utf8;

INSERT INTO ca_staff (FirstName, LastName, Email, 
Password,
Active, Permission) 
VALUES 
('Admin', 'User', 'admin@admin.com', 
'9871b263033d86b566266e8c1c09f2d80596fc3eefd2a8be633e46213cd7dd2e',
1,1);