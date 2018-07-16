INSERT INTO ca_staff 
(FirstName,LastName,PhoneNumber,Email,
Password,
Active,`Permission`) 
VALUES
('Niko', 'Kiviaho','0503888777', 'nikox@jamk.fi',
'959f7afe3efbee4ebf60ac731204Q98b317126178a86181af0abda5612390b68b',
1,0);
INSERT INTO ca_staff 
(FirstName,LastName,PhoneNumber,Email,
Password,
Active,`Permission`) 
VALUES
('Mikko', 'Tenhonen','0403333423', 'mikkox@jamk.fi',
'2ed518af60c5c3b04fdbed95a7e6cd50aee2df23a8726e48303a2b8744dae2f8',
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

-- roomlog
DELETE FROM ca_roomlog WHERE ID > 0;


