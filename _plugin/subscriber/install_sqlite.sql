CREATE TABLE "%--%_mailbody" (
  "id" integer PRIMARY KEY,
  "subject" varchar(255) NOT NULL,
  "body" text ,
  "text_body" text ,
  "date" int(10) ,
  "total" int(10) ,
  "to" longtext 
) ;

CREATE TABLE "%--%_maillist" (
  "email" varchar(255) PRIMARY KEY,
  "date" int(10)
) ;