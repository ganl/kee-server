SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

DROP USER i2soft;
CREATE USER i2soft WITH PASSWORD 'i2s@Shanghai' CREATEDB;
ALTER USER i2soft WITH PASSWORD 'i2s@Shanghai' CREATEDB;

--
-- Name: i2soft; Type: DATABASE; Schema: -; Owner: i2soft
--

CREATE DATABASE i2soft WITH TEMPLATE = template0 ENCODING = 'UTF8';
ALTER DATABASE i2soft OWNER TO i2soft;

CREATE TABLE users (username VARCHAR(255) NOT NULL, password VARCHAR(2000), first_name VARCHAR(255), last_name VARCHAR(255), CONSTRAINT username_pk PRIMARY KEY (username));
INSERT INTO users (username, password, first_name, last_name) VALUES('admin', 'pass', 'default', 'admin');

ALTER TABLE public.users OWNER TO i2soft;
