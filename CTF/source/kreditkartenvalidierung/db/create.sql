DROP TABLE IF EXISTS cards;
CREATE TABLE cards (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	num VARCHAR(16),
	validMonth SMALLINT,
	validYear SMALLINT,
	cvv SMALLINT,
	owner VARCHAR(255)
);

INSERT INTO cards(num, validMonth, validYear, cvv, owner) VALUES('1234123412341234', 8, 2015, 123, 'Peter Meter');
INSERT INTO cards(num, validMonth, validYear, cvv, owner) VALUES('2102333390984594', 1, 2016, 768, 'Siegfried Roy');
INSERT INTO cards(num, validMonth, validYear, cvv, owner) VALUES('4447653498217322', 2, 2019, 337, 'Jack Sparrow');
INSERT INTO cards(num, validMonth, validYear, cvv, owner) VALUES('2304928394756218', 6, 2023, 101, 'Leonard Nemoy');
INSERT INTO cards(num, validMonth, validYear, cvv, owner) VALUES('3393846538262121', 5, 2043, 165, 'Gimli Gloinssohn');
INSERT INTO cards(num, validMonth, validYear, cvv, owner) VALUES('4090874603947283', 5, 2020, 445, 'Han Solo');
INSERT INTO cards(num, validMonth, validYear, cvv, owner) VALUES('1223525849309258', 3, 2019, 432, 'George Bush');
INSERT INTO cards(num, validMonth, validYear, cvv, owner) VALUES('4930285653873822', 12, 2016, 034, 'Vladimir Putin');
INSERT INTO cards(num, validMonth, validYear, cvv, owner) VALUES('1119203984736273', 2, 2018, 232, 'Gustav Klimt');

DROP TABLE IF EXISTS ip;
CREATE TABLE ip (
	address VARCHAR(255) PRIMARY KEY,
	lastAccess DATETIME,
    description VARCHAR(255)
);

INSERT INTO ip(address, lastAccess, description) VALUES('192.168.0.3', '2015-03-15 13:08:33', '2015-03-15 13:08:33');
INSERT INTO ip(address, lastAccess, description) VALUES('192.168.0.4', '2015-03-15 13:01:22', '2015-03-15 13:08:33');
INSERT INTO ip(address, lastAccess, description) VALUES('192.168.0.5', '2015-03-15 13:09:34', '2015-03-15 13:08:33');
INSERT INTO ip(address, lastAccess, description) VALUES('192.168.0.6', '2015-03-15 13:02:54', '2015-03-15 13:08:33');
INSERT INTO ip(address, lastAccess, description) VALUES('192.168.0.77', '2015-03-15 13:26:51', '2015-03-15 13:08:33');
INSERT INTO ip(address, lastAccess, description) VALUES('192.168.0.45', '2015-03-15 13:29:01', '2015-03-15 13:08:33');
INSERT INTO ip(address, lastAccess, description) VALUES('192.168.0.76', '2015-03-15 13:28:21', '2015-03-15 13:08:33');
INSERT INTO ip(address, lastAccess, description) VALUES('192.168.0.98', '2015-03-15 13:23:12', '2015-03-15 13:08:33');
INSERT INTO ip(address, lastAccess, description) VALUES('192.168.0.1', '2015-03-15 12:24:43', '2015-03-15 13:08:33');
