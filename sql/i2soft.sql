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

CREATE TABLE users (
  user_uuid character varying(40) NOT NULL,
  username VARCHAR(255) NOT NULL,
  password VARCHAR(2000),
  first_name VARCHAR(255),
  last_name VARCHAR(255),
  email character varying(4096),
  active integer DEFAULT 0,
  role integer DEFAULT 0,
  CONSTRAINT username_pk PRIMARY KEY (username)
);
ALTER TABLE public.users OWNER TO i2soft;

INSERT INTO users (user_uuid, username, password, first_name, last_name) VALUES('1BCFCAA3-E3C8-3E28-BDC5-BE36FDC2B5DC', 'admin', 'pass', 'default', 'admin');

CREATE TABLE api_token (
  access_token VARCHAR(40) NOT NULL,
  user_id VARCHAR(255) NOT NULL,--username
  client_id VARCHAR(80),--platform
  level INTEGER NOT NULL,
  ignore_limits SMALLINT NOT NULL DEFAULT '0',
  is_private_key SMALLINT NOT NULL DEFAULT '0',
  ip_addresses text,
  scope VARCHAR(2000),
  create_time REAL NOT NULL,
  expires BIGINT,
  last_login_ip character varying(45),
  last_access timestamp,
  CONSTRAINT api_token_pk PRIMARY KEY (access_token)
);
ALTER TABLE public.api_token OWNER TO i2soft;

CREATE TABLE api_logs (
   id serial PRIMARY KEY,
   uri VARCHAR(512) NOT NULL,
   method VARCHAR(6) NOT NULL,
   params TEXT DEFAULT NULL,
   api_key VARCHAR(40) NOT NULL,
   ip_address VARCHAR(45) NOT NULL,
   time BIGINT NOT NULL,
   rtime REAL DEFAULT NULL,
   authorized boolean NOT NULL,
   response_code smallint DEFAULT '0'
);

ALTER TABLE public.api_logs OWNER TO i2soft;
