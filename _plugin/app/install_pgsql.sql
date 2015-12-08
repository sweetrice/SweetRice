DROP TABLE IF EXISTS "%--%_app";
CREATE TABLE "%--%_app" (
  "id" serial ,
	"content" text,
  PRIMARY KEY  ("id")
);


DROP TABLE IF EXISTS "%--%_app_menus";
CREATE TABLE IF NOT EXISTS "%--%_app_menus" (
  "id" serial,
	"link_text" text,
	"link_url" text,
	"order" int,
	"parent_id" int,
  PRIMARY KEY  ("id")
);


DROP TABLE IF EXISTS "%--%_app_form";
CREATE TABLE "%--%_app_form" (
	"id" serial,
	"name" varchar(255),
	"fields" text,
	"method" varchar(4),
	"action" varchar(255),
	"captcha" int,
	"template" VARCHAR(255),
	PRIMARY KEY ("id")
);

DROP TABLE IF EXISTS "%--%_app_form_data";
CREATE TABLE "%--%_app_form_data" (
	"id" serial,
	"form_id" int,
	"data" text,
	"date" int,
	PRIMARY KEY ("id")
);