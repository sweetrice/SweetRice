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
	"order" int(10),
	"parent_id" int(10),
  PRIMARY KEY  ("id")
);


CREATE TABLE "%--%_app_form" (
	"id" serial,
	"name" varchar(255),
	"fields" text,
	"method" enum('post','get') DEFAULT 'post',
	"action" varchar(255),
	"captcha" int(1),
	"template" VARCHAR(255),
	PRIMARY KEY ("id")
);

CREATE TABLE "%--%_app_form_data" (
	"id" serial,
	"form_id" int(10),
	"data" text,
	"date" int(10),
	PRIMARY KEY ("id")
);