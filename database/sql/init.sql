CREATE SCHEMA challenge AUTHORIZATION postgres;
CREATE TABLE challenge.messages (message_id INTEGER, message VARCHAR,CONSTRAINT message_id_pk PRIMARY KEY (message_id));