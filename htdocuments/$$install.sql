
DROP TABLE IF EXISTS automaat;

CREATE TABLE automaat
(
	id				char(36)		NOT NULL,
	name			varchar(64)		NOT NULL,
	valid_begin		date			NOT NULL,
	valid_end		date			NOT NULL,
	content			text			NOT NULL,
	
	CONSTRAINT automaat_pkey PRIMARY KEY (id)
);

CREATE INDEX id_ix ON automaat (id);
ALTER TABLE automaat DROP PRIMARY KEY;

INSERT INTO automaat VALUES('1290b9a2-9a70-4ad8-bcd0-7785792ad89b', 'AAN1', '2016-01-01', '9999-12-31',
	'{"automaat":{"automaatID":"AAN1","dagdeel":"morgen","temperatuur":"20","aanwezig":"ja","status":""}}');
INSERT INTO automaat VALUES('0ac2d5e9-e4f7-437c-8430-91aa48858166', 'UIT1', '2016-01-01', '9999-12-31',
	'{"automaat":{"automaatID":"UIT1","dagdeel":"middag","temperatuur":"16","aanwezig":"nee","status":""}}');

