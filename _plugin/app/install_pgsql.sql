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