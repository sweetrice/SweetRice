DROP TABLE IF EXISTS "%--%_mailbody" CASCADE;
CREATE TABLE "%--%_mailbody" (
  "id" serial ,
  "subject" varchar(255) ,
  "body" text ,
  "text_body" text ,
  "date" int ,
  "total" int ,
  "to" text ,
  PRIMARY KEY  ("id")
) ;

DROP TABLE IF EXISTS "%--%_maillist" CASCADE;
CREATE TABLE "%--%_maillist" (
  "email" varchar(255) ,
  "date" int ,
  PRIMARY KEY  ("email")
)  ;