CREATE TABLE "%--%_app" (
  "id" integer PRIMARY KEY,
	"content" TEXT
) ;

CREATE TABLE "%--%_app_menus" (
  "id" integer PRIMARY KEY,
	"link_text" text,
	"link_url" text,
	"order" integer,
	"parent_id" integer
);


CREATE TABLE "%--%_app_form" (
	"id" integer PRIMARY KEY,
	"name" varchar(255),
	"fields" text,
	"method" varchar(4),
	"action" varchar(255),
	"captcha" integer,
	"template" varchar(255)
);


CREATE TABLE "%--%_app_form_data" (
	"id" integer PRIMARY KEY,
	"form_id" integer,
	"data" text,
	"date" integer
);