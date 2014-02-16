DROP TABLE IF EXISTS "%--%_attachment" CASCADE;
CREATE TABLE  "%--%_attachment" (
  "id" serial,
  "post_id" int NOT NULL,
  "file_name" varchar(255) NOT NULL,
  "date" int NOT NULL,
  "downloads" int NOT NULL,
  PRIMARY KEY  ("id")
);
DROP TABLE IF EXISTS "%--%_comment" CASCADE;
CREATE TABLE  "%--%_comment" (
  "id" serial,
  "name" varchar(60) NOT NULL default '',
  "email" varchar(255) NOT NULL default '',
  "website" varchar(255) NOT NULL ,
  "info" text NOT NULL,
  "post_id" int NOT NULL default '0',
  "post_name" varchar(255) NOT NULL,
  "post_cat" varchar(128) NOT NULL,
  "post_slug" varchar(128) NOT NULL,
  "date" int NOT NULL default '0',
  "ip" varchar(39) NOT NULL default '',
  "reply_date" int NOT NULL default '0',
  PRIMARY KEY  ("id")
);

DROP TABLE IF EXISTS "%--%_posts" CASCADE;
CREATE TABLE  "%--%_posts" (
  "id" serial,
  "name" varchar(255) NOT NULL,
  "title" varchar(255) NOT NULL,
  "body" text NOT NULL,
  "keyword" varchar(255) NOT NULL default '',
  "tags" text NOT NULL,
  "description" varchar(255) NOT NULL default '',
  "sys_name" varchar(128) NOT NULL UNIQUE,
  "date" int NOT NULL default '0',
  "category" int NOT NULL default '0',
  "in_blog" int NOT NULL,
  "views" int NOT NULL,
  "allow_comment" int NOT NULL default '1',
  "template" varchar(60) NOT NULL,
  PRIMARY KEY  ("id")
);

DROP TABLE IF EXISTS "%--%_category" CASCADE;
CREATE TABLE  "%--%_category" (
  "id" serial,
  "name" varchar(255) NOT NULL,
  "link" varchar(128) NOT NULL UNIQUE,
  "title" text NOT NULL,
  "description" varchar(255) NOT NULL,
  "keyword" varchar(255) NOT NULL,
  "sort_word" text NOT NULL,
  "parent_id" int NOT NULL default '0',
  "template" varchar(60) NOT NULL,
  PRIMARY KEY  ("id")
);

DROP TABLE IF EXISTS "%--%_options" CASCADE;
CREATE TABLE "%--%_options" (
  "id" serial,
  "name" varchar(255) NOT NULL UNIQUE,
  "content" text NOT NULL,
  "date" int NOT NULL default '0',
  PRIMARY KEY  ("id")
);

DROP TABLE IF EXISTS "%--%_item_plugin" CASCADE;
CREATE TABLE "%--%_item_plugin" (
  "id" serial,
  "item_id" int NOT NULL,
  "item_type" varchar(255) NOT NULL,
  "plugin" varchar(255) NOT NULL,
  PRIMARY KEY  ("id")
);


DROP TABLE IF EXISTS "%--%_links" CASCADE;
CREATE TABLE "%--%_links"(
  "lid" serial,
  "request" text NOT NULL,
  "url" text NOT NULL,
  "plugin" varchar(255) NOT NULL,
  PRIMARY KEY  ("lid")
);