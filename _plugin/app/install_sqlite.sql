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