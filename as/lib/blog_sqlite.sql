CREATE TABLE  "%--%_attachment" (
  "id" INTEGER PRIMARY KEY ,
  "post_id" int(10) ,
  "file_name" varchar(255) ,
  "date" int(10) ,
  "downloads" int(10) 
) ;

CREATE TABLE "%--%_comment" (
  "id" INTEGER PRIMARY KEY ,
  "name" varchar(60)  default '',
  "email" varchar(255)  default '',
  "website" varchar(255)  ,
  "info" text ,
  "post_id" INTEGER  default '0',
  "post_name" varchar(255) ,
  "post_cat" varchar(128) ,
  "post_slug" varchar(128) ,
  "date" int(10)  default '0',
  "ip" varchar(39)  default '',
  "reply_date" int(10)  default '0'
);

CREATE TABLE "%--%_posts" (
  "id" INTEGER PRIMARY KEY ,
  "name" varchar(255) ,
  "title" varchar(255) ,
  "body" longtext ,
  "keyword" varchar(255)  default '',
  "tags" text ,
  "description" varchar(255)  default '',
  "sys_name" varchar(128) UNIQUE,
  "date" int(10)  default '0',
  "category" int(10)  default '0',
  "in_blog" tinyint(1) ,
  "views" int(10) ,
  "allow_comment" tinyint(1)  default '1',
  "template" varchar(60) 
) ;

CREATE TABLE  "%--%_category" (
  "id" INTEGER PRIMARY KEY ,
  "name" varchar(255) ,
  "link" varchar(128) UNIQUE,
  "title" text ,
  "description" varchar(255) ,
  "keyword" varchar(255) ,
  "sort_word" text ,
  "parent_id" int(10)  default '0',
  "template" varchar(60)
) ;

CREATE TABLE  "%--%_options" (
  "id" INTEGER PRIMARY KEY ,
  "name" varchar(255) UNIQUE,
  "content" text,
  "date" int(10)  default '0'
) ;

CREATE TABLE "%--%_item_plugin" (
  "id" INTEGER PRIMARY KEY ,
  "item_id" int(10),
  "item_type" varchar(255),
  "plugin" varchar(255)
) ;

CREATE TABLE "%--%_links"(
  "lid" INTEGER PRIMARY KEY ,
  "request" text,
  "url" text,
  "plugin" varchar(255)
);