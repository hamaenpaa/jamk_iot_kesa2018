#  originally from https://github.com/adafruit/Adafruit_Python_PN532, modified for purpose of this project

# Requires Adafruit_Python_PN532
import MySQLdb
import binascii
import socket
import time
import signal
import sys
import datetime

import Adafruit_PN532 as PN532

# PN532 configuration for a Raspberry Pi GPIO:

# GPIO 18, pin 12
CS   = 18
# GPIO 23, pin 16
MOSI = 23
# GPIO 24, pin 18
MISO = 24
# GPIO 25, pin 22
SCLK = 25

# Configure the key to use for writing to the MiFare card.  You probably don't
# need to change this from the default below unless you know your card has a
# different key associated with it.
CARD_KEY = [0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0xFF]

# Number of seconds to delay after reading data. | Changed to 2 seconds from 0.5s
DELAY = 2.0

# Prefix, aka header from the card
HEADER = b'BG'

def close(signal, frame):
        sys.exit(0)

signal.signal(signal.SIGINT, close)

# Create and initialize an instance of the PN532 class
pn532 = PN532.PN532(cs=CS, sclk=SCLK, mosi=MOSI, miso=MISO)
pn532.begin()
pn532.SAM_configuration()

print('PN532 NFC RFID 13.56MHz Card Reader')
while True:
    # Wait for a card to be available
    uid = pn532.read_passive_target()
    # Try again if no card found
    if uid is None:
        continue
    # Found a card, now try to read block 4 to detect the block type
    print('')
    print('Card UID 0x{0}:'.format(binascii.hexlify(uid)))
    # Authenticate and read block 4
    if not pn532.mifare_classic_authenticate_block(uid, 4, PN532.MIFARE_CMD_AUTH_B,
                                                   CARD_KEY):
        print('Failed to authenticate with card!')
        continue
    data = pn532.mifare_classic_read_block(4)
    if data is None:
        print('Failed to read data from card!')
        continue
    # Check the header
    if data[0:2] !=  HEADER:
        print('Card is not written with proper block data!')
        continue
    # Parse out the block type and subtype

    # Database Connection
    db = MySQLdb.connect("[ip/host]", "[username]", "[password]", "ca")
    curs=db.cursor()


    # Useful commands
    # format(int(data[2:8].decode("utf-8"), 16)) | Gets the configurable 4byte ID from the card
    # format(binascii.hexlify(uid))              | Gets the tag uniqueID which is burnt to card

    # Manual configuration | Room ID | Current Datetime | Tag Unique ID
    curdate = datetime.datetime.today().strftime('%Y-%m-%d %H:%M:%S')
    tagID = format(binascii.hexlify(uid))
    roomID = 2

    mySelectQuery = ("SELECT ca_lesson.course_id, ca_student.ID FROM ca_lesson \
    INNER JOIN ca_course_student ON ca_lesson.course_id = ca_course_student.course_id \
    INNER JOIN ca_student ON ca_course_student.student_id = ca_student.ID \
    WHERE ca_lesson.room_id = %s AND \
    ca_lesson.begin_time <= %s AND \
    ca_lesson.end_time >= %s AND ca_student.NFC_ID = %s")
    curs.execute(mySelectQuery,(roomID,curdate,curdate,tagID))    
    row = curs.fetchone()
    # Debugging parse row0, row1 (course id, student id)
    # print("CourseID:",row[0]," StudentID:",row[1])

    # Parse SQL Result to variables
    if curs.rowcount > 0:
        courseID = row[0]
        studentID = row[1]
    else:
        courseID = 0
        studentID = 0

    # print curs.rowcount
    # print roomID
    # print curdate
    # print tagID

    # format(int(data[2:8].decode("utf-8"), 16))
    curs.execute("Insert into ca_roomlog (room_id,dt,NFC_ID,course_id,student_id) values (%s,%s,%s,%s,%s)",(1,curdate,format(binascii.hexlify(uid)),courseID,studentID))
    db.commit()
    print('User Id: {0}'.format(int(data[2:8].decode("utf-8"), 16)))
    print('Current timestamp: '+curdate)
    time.sleep(DELAY);
