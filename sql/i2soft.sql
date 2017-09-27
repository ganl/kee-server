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

\connect i2soft

CREATE SCHEMA i2soft AUTHORIZATION i2soft;

SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

DROP TABLE IF EXISTS tenant;
create table tenant (
  id serial,
  tenant_name VARCHAR(255) NOT NULL,
  tenant_type INTEGER DEFAULT 0,
  description VARCHAR(255),
  create_time BIGINT NOT NULL,
  enabled boolean NOT NULL DEFAULT TRUE,
  PRIMARY KEY (id)
);
ALTER TABLE public.tenant OWNER TO i2soft;

DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id serial,
  tenant_id integer NOT NULL REFERENCES tenant ("id") ON DELETE CASCADE ON UPDATE NO ACTION,
  user_uuid character varying(40) NOT NULL,
  username VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(2000),
  salt VARCHAR (255) DEFAULT NULL,
  first_name VARCHAR(255),
  last_name VARCHAR(255),
  email character varying(4096),
  active integer DEFAULT 0,
  create_time BIGINT NOT NULL,
  last_login BIGINT DEFAULT NULL,
  ip_address varchar(15),
  PRIMARY KEY (id)
);
ALTER TABLE public.users OWNER TO i2soft;

DROP TABLE IF EXISTS login_attempts;
CREATE TABLE login_attempts (
  "id" serial NOT NULL,
  "ip_address" varchar(15) NOT NULL,
  "login" varchar(255) NOT NULL,
  "time" BIGINT,
  "result" integer DEFAULT 0,
  PRIMARY KEY ("id")
);
ALTER TABLE public.login_attempts OWNER TO i2soft;

DROP TABLE IF EXISTS roles;
CREATE TABLE roles (
  id serial,
  name varchar(64) NOT NULL, -- amdin, owner, operator
  display_name varchar(255),
  description varchar (255),
  PRIMARY KEY (id)
);
ALTER TABLE public.roles OWNER TO i2soft;

DROP TABLE IF EXISTS role_user;
CREATE TABLE role_user (
  "id" serial NOT NULL ,
  "user_id" integer NOT NULL REFERENCES users ("id") ON DELETE CASCADE ON UPDATE NO ACTION,
  "role_id" integer NOT NULL REFERENCES roles ("id") ON DELETE CASCADE ON UPDATE NO ACTION,
  PRIMARY KEY ("id")
);
ALTER TABLE public.role_user OWNER TO i2soft;

--DROP INDEX IF EXISTS "role_user_idx";
CREATE UNIQUE INDEX "role_user_idx" ON "role_user" ("user_id","role_id");
DROP INDEX IF EXISTS "role_user_users1_idx";
CREATE INDEX "role_user_users_idx" ON "role_user" ("user_id");
DROP INDEX IF EXISTS "role_user_roles_idx";
CREATE INDEX "role_user_roles_idx" ON "role_user" ("role_id");

DROP TABLE IF EXISTS permissions;
CREATE TABLE permissions (
  id serial,
  name varchar(64), -- add-node, del-node, add-rep
  display_name varchar(255),
  description varchar(255),
  PRIMARY KEY (id)
);
ALTER TABLE public.permissions OWNER TO i2soft;

DROP TABLE IF EXISTS perms_role;
CREATE TABLE perms_role(
  "perm_id" integer NOT NULL REFERENCES permissions ("id") ON DELETE CASCADE ON UPDATE NO ACTION,
  "role_id" integer NOT NULL REFERENCES roles ("id") ON DELETE CASCADE ON UPDATE NO ACTION,
  PRIMARY KEY ("perm_id","role_id")
);
ALTER TABLE public.perms_role OWNER TO i2soft;

--DROP INDEX IF EXISTS "perms_role_idx";
CREATE UNIQUE INDEX "perms_role_idx" ON "perms_role" ("perm_id","role_id");
DROP INDEX IF EXISTS "perms_role_perm_idx";
CREATE INDEX "perms_role_perm_idx" ON "perms_role" ("perm_id");
DROP INDEX IF EXISTS "perms_role_role_idx";
CREATE INDEX "perms_role_role_idx" ON "perms_role" ("role_id");

DROP TABLE IF EXISTS user_groups;
CREATE TABLE user_groups (
  id serial,
  name varchar(255),
  description varchar(255),
  PRIMARY KEY (id)
);
ALTER TABLE public.user_groups OWNER TO i2soft;

DROP TABLE IF EXISTS "group_to_group";
CREATE TABLE "group_to_group" (
  "group_id" integer NOT NULL ,
  "subgroup_id" integer NOT NULL ,
  PRIMARY KEY ("group_id","subgroup_id")
);
ALTER TABLE public.group_to_group OWNER TO i2soft;

DROP TABLE IF EXISTS "user_to_group";
CREATE TABLE "user_to_group" (
  "user_id" integer NOT NULL REFERENCES users ("id") ON DELETE CASCADE ON UPDATE NO ACTION,
  "group_id" integer NOT NULL REFERENCES user_groups ("id") ON DELETE CASCADE ON UPDATE NO ACTION,
  PRIMARY KEY ("user_id","group_id")
);
ALTER TABLE public.user_to_group OWNER TO i2soft;

DROP TABLE IF EXISTS "perm_to_group";
CREATE TABLE "perm_to_group" (
  "perm_id" integer NOT NULL REFERENCES permissions ("id") ON DELETE CASCADE ON UPDATE NO ACTION,
  "group_id" integer NOT NULL REFERENCES user_groups ("id") ON DELETE CASCADE ON UPDATE NO ACTION,
  PRIMARY KEY ("perm_id","group_id")
);
ALTER TABLE public.perm_to_group OWNER TO i2soft;

DROP TABLE IF EXISTS api_token;
CREATE TABLE api_token (
  access_token VARCHAR(40) NOT NULL,
  user_id VARCHAR(255) NOT NULL,--username
  client_id VARCHAR(80),--platform
  level INTEGER NOT NULL,
  ignore_limits SMALLINT NOT NULL DEFAULT '0',
  is_private_key SMALLINT NOT NULL DEFAULT '0',
  ip_addresses text,
  scope VARCHAR(2000),
  create_time BIGINT NOT NULL,
  expires BIGINT,
  last_login_ip character varying(45),
  last_access timestamp,
  CONSTRAINT api_token_pk PRIMARY KEY (access_token)
);
ALTER TABLE public.api_token OWNER TO i2soft;

DROP TABLE IF EXISTS api_logs;
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


--
-- default (init) data
---

INSERT INTO tenant (id, tenant_name, tenant_type, description, create_time) VALUES
(1, '__default__', 1, 'system', extract(epoch from now()));

--
--系统管理员：用户增删改、系统级用户权限管理、审计外的安全策略配置(系统参数配置)
--审计管理员：查看系统日志（记录系统及管理员相关操作）
--审核管理员：对关键的系统（如新建账号、权限变更）操作必须经由该角色进行审批通过后才能生效
--业务管理员：给业务操作员分配权限，不同的业务操作员有不同的权限：比如创建工作机、建立规则
--业务配置员：配置灾备机
--业务操作员：具有业务操作权限
--业务审计员：查看业务操作员的操作日志（记录业务相关操作）
--

--
--内置admin（系统管理员），operator （业务操作员、普通用户）， auditor （审计员，操作日志审计等，不包括节点日志）
--其他类型用户通过 role permission 控制，遇到安全监测要求时，功能权限再细分
--
INSERT INTO roles (id, name, display_name, description) VALUES
('1', 'admin', 'Administrator', 'Super User'),
('2', 'auditor', 'Auditor', 'Business auditor'),
('3', 'operator', 'Operator', 'Operation Clerk');

INSERT INTO users (id, tenant_id, user_uuid, username, password, first_name, last_name, create_time) VALUES
(1, 1, '1BCFCAA3-E3C8-3E28-BDC5-BE36FDC2B5DC', 'admin', '$2y$10$sz.Nyy677HMNNM4TU9j1muwMRdeHDPoIFC51hRv1rqxOGjb0NC04m', 'default', 'admin', extract(epoch from now())),
(2, 1, '1EE80198-20FB-33F9-7C24-F835A34C7440', 'auditor', '$2y$10$sz.Nyy677HMNNM4TU9j1muwMRdeHDPoIFC51hRv1rqxOGjb0NC04m', 'default', 'auditor', extract(epoch from now())),
(3, 1, 'EFB53F11-7BA2-2001-3418-85865EA58E47', 'operator', '$2y$10$sz.Nyy677HMNNM4TU9j1muwMRdeHDPoIFC51hRv1rqxOGjb0NC04m', 'default', 'operator', extract(epoch from now()));

INSERT INTO role_user(user_id, role_id) VALUES
(1, 1),
(2, 2),
(3, 3);


