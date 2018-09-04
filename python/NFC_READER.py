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

# Default roomidentifier is not got from target database
roomIdentifier = "HUONE"

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

    # catch current time lesson room identifier or use default
    today = datetime.utcnow().date()
    begin_dt = datetime(today.year, today.month, today.day, tzinfo=tz.tzutc())
    end_dt = begin_dt + timedelta(1)
    begin = begin_dt.strftime('%Y-%m-%d %H:%M:%S')
    end = end_dt.strftime('%Y-%m-%d %H:%M:%S')
    curs.execute("SELECT room_identifier FROM ca_lesson WHERE (" + begin_time + \
        " <=  ca_lesson.begin_time AND ca_lesson.begin_time <= " + end_time + \
        ") OR (" + begin_time + \
        " <=  ca_lesson.end_time AND ca_lesson.end_time <= " + end_time + ")")
    if curs.rowcount == 1: 
        row = curs.fetchone()
	    roomIdentifier = row['room_identifier']
    else 
        curs.execute("SELECT default_roomidentifier FROM ca_setting WHERE id=1")
	    row = curs.fetchone()
	    if not row['default_roomidentifier']:
	        roomIdentifier = row['default_roomidentifier']

    # format(int(data[2:8].decode("utf-8"), 16))
    curs.execute("Insert into ca_roomlog (room_identifier,dt,NFC_ID) values (%s,%s,%s)",(roomIdentifier,curdate,format(binascii.hexlify(uid))))
    db.commit()
    print('User Id: {0}'.format(int(data[2:8].decode("utf-8"), 16)))
    print('Current timestamp: '+curdate)
    time.sleep(DELAY);
