use ca;
INSERT INTO ca_staff 
(FirstName,LastName,PhoneNumber,Email,
Password,
Active,`Permission`) 
VALUES
('Niko', 'Kiviaho','0503888777', 'nikox@jamk.fi',
'1de976a3936188685831883312ad8c81586db5c0eb1c317737dea14724d22989',
1,0);
INSERT INTO ca_staff 
(FirstName,LastName,PhoneNumber,Email,
Password,
Active,`Permission`) 
VALUES
('Mikko', 'Tenhonen','0403333423', 'mikkox@jamk.fi',
'32a7ff5643d85573005412dee3a2c43da5cd70143488c66b3dd924bc8fa74b3f',
1,0);

-- student
INSERT INTO ca_student
(FirstName, LastName, PhoneNumber, Email, 
Student_ID, NFC_ID)
VALUES 
('Mikko','Kivinen','0402323888', 'L4433@student.jamk.fi', 
'L4433','UIEYEJJ');

INSERT INTO ca_student
(FirstName, LastName, PhoneNumber, Email, 
Student_ID, NFC_ID)
VALUES 
('Nina','Heikkinen','0403243288', 'L5577@student.jamk.fi', 
'L5577','HEIOEEJK');

INSERT INTO ca_student
(FirstName, LastName, PhoneNumber, Email, 
Student_ID, NFC_ID)
VALUES 
('Kalle','Nieminen','0403247778', 'L9922@student.jamk.fi', 
'L9922','HEHSDGHD');

INSERT INTO ca_student
(FirstName, LastName, PhoneNumber, Email, 
Student_ID, NFC_ID)
VALUES 
('Jaana','Tenhonen','0402344884', 'L9955@student.jamk.fi', 
'L9955','HEHYYWEE');

INSERT INTO ca_student
(FirstName, LastName, PhoneNumber, Email, 
Student_ID, NFC_ID)
VALUES 
('Unto','Verkkonen','0402373889', 'L9977@student.jamk.fi', 
'L9977','HEREETTT');


-- guest
INSERT INTO ca_guest
(FirstName,LastName)
VALUES 
('Satu', 'Vesalainen');


-- courses
INSERT INTO ca_course 
(Course_ID,Course_name,Course_description) 
VALUES 
('IOT-245', 'Iot kurssi',
'Mitä IoT on ja mihin sitä käytetään? Laitteita ja sensoreita. Esimerkkinä opetellaan, miten Raspberry Pi:llä saadaan joku sovellus toimimaan');

INSERT INTO ca_course 
(Course_ID,Course_name,Course_description) 
VALUES 
('RU-245', 'Ruotsin kielen alkeet','Perus small talk');


-- course teacher
INSERT INTO ca_course_teacher (course_id, staff_id)
VALUES (1,2);
INSERT INTO ca_course_teacher (course_id, staff_id)
VALUES (2,3);

-- course student
INSERT INTO ca_course_student (course_id, student_id)
VALUES (1,1);
INSERT INTO ca_course_student (course_id, student_id)
VALUES (1,2);
INSERT INTO ca_course_student (course_id, student_id)
VALUES (1,3);
INSERT INTO ca_course_student (course_id, student_id)
VALUES (2,4);
INSERT INTO ca_course_student (course_id, student_id)
VALUES (2,5);

-- room
INSERT INTO ca_room (room_name) VALUES ('A105'),('A102');

-- lesson
INSERT INTO ca_lesson (course_id,room_id,begin_time,end_time) VALUES 
(1,1,'2018-09-01 12:00:00', '2018-09-01 13:45:00'),
(1,2,'2018-09-02 12:00:00', '2018-09-02 13:45:00'),
(2,1,'2018-09-01 14:00:00', '2018-09-01 15:45:00'),
(2,2,'2018-09-02 14:00:00', '2018-09-01 15:45:00');

INSERT INTO ca_nfc_tag (NFC_ID,active) VALUES ('HDDDJKJK',1), ('GSHJDHH',1);

-- roomlog
DELETE FROM ca_roomlog WHERE ID > 0;


