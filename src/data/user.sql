-- This SQL script is intended to be used with a SQLITE DB

-- initial user is:
-- username: meiben
-- password: Eiben1

PRAGMA foreign_keys = ON;

DROP TABLE IF EXISTS "user";
CREATE TABLE `user`
(
	`id` integer PRIMARY KEY AUTOINCREMENT NOT NULL,
	`username` varchar(50) UNIQUE NOT NULL,
	`name_first` varchar(50) NOT NULL,
	`name_last` varchar(50) NOT NULL,
	`auth_key` varchar(32) NOT NULL,
	`password_hash` varchar(255) NOT NULL,
	`password_reset_token` varchar(255),
	`email` varchar(50) UNIQUE NOT NULL,
	`created_at` integer NOT NULL,
	`updated_at` integer NOT NULL,
	`usertype` smallint NOT NULL DEFAULT 20
);

INSERT INTO "user" VALUES(1,'meiben','Matt','Eiben','KA-ti3mttfF3OZKNx4yXIO69ZS3ScaM0','$2y$13$Iy9qO563l70EDMuacgkIHeF43Kz2dCSpZHbNnlckbJFyuafxNrf6C',NULL,'eibenm@gmail.com',1436832267,1436832267,10);
INSERT INTO "user" VALUES(2,'brwerner','Ben','Werner','vk2SdMJeBgw-RADgm7m8RAYIoqjRym1x','$2y$13$z3OBaJLmjMOYM.jgfJm3WOd10GJegs0dD5KZ5IllPFK8qybP7Vkrq',NULL,'brwerner@gmail.com',1436832320,1436832320,20);
INSERT INTO "user" VALUES(3,'ty','Ty','Morrison-Heath','gGxYxOBLT9F0SZP6Ok-2csEdocIjZFZy','$2y$13$ngWSnboaxxwtopbCAYdWT.mm0uGnwhRN.mHm0.YLGpxVOVVk3RBQy',NULL,'tymorrisonheath@gmail.com',1436832355,1436832355,20);

DROP TABLE IF EXISTS skiareas;
CREATE TABLE skiareas (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name_area TEXT,
    conditions TEXT,
    color TEXT,
    short_desc TEXT,
    bounds_southwest TEXT,
    bounds_northeast TEXT,
    image_id INTEGER,
    permissions INTEGER,
    FOREIGN KEY(image_id) REFERENCES file(id)
);

CREATE INDEX areaimageindex ON skiareas(image_id);

DROP TABLE IF EXISTS "file";
CREATE TABLE `file`
(
    id integer PRIMARY KEY AUTOINCREMENT NOT NULL,
    filename TEXT NOT NULL,
    avatar TEXT NOT NULL,
    caption TEXT,
    kml_image INTEGER
);

DROP TABLE IF EXISTS skiroutes;
CREATE TABLE skiroutes (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name_route TEXT,
    quip TEXT,
    overview TEXT,
    short_desc TEXT,
    notes TEXT,
    avalanche_info TEXT,
    directions TEXT,
    gps_guidance TEXT,
    elevation_gain INTEGER,
    vertical INTEGER,
    aspects TEXT,
    distance FLOAT,
    snowfall TEXT,
    avalanche_danger TEXT,
    skier_traffic TEXT,
    kml TEXT,
    bounds_southwest TEXT,
    bounds_northeast TEXT,
    mbtiles TEXT,
    skiarea_id INTEGER,
    FOREIGN KEY(skiarea_id) REFERENCES skiareas(id)
);

DROP TABLE IF EXISTS "skiroutes_image";
CREATE TABLE `skiroutes_image`
(
    id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
    route_id INTEGER NOT NULL,
    image_id INTEGER NOT NULL,
    FOREIGN KEY(route_id) REFERENCES skiroutes(id),
    FOREIGN KEY(image_id) REFERENCES file(id)
);

CREATE INDEX routeimageindex ON skiroutes_image(route_id);
CREATE INDEX routefileindex ON skiroutes_image(image_id);

DROP TABLE IF EXISTS gps;
CREATE TABLE gps (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    waypoint TEXT,
    lat FLOAT,
    lon FLOAT,
    lat_dms TEXT,
    lon_dms TEXT,
    route_id INTEGER,
    FOREIGN KEY(route_id) REFERENCES skiroutes(id)
);

CREATE INDEX gpsrouteindex ON gps(route_id);

DROP TABLE IF EXISTS glossary;
CREATE TABLE glossary (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    term TEXT,
    description TEXT
);

DROP TABLE IF EXISTS version;
CREATE TABLE version (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    version TEXT,
    live INT
);

-- ALTER TABLE gps ADD lat_dms TEXT;
-- ALTER TABLE gps ADD lon_dms TEXT;
-- ALTER TABLE skiroutes ADD gps_guidance TEXT;

-- UPDATE skiareas SET permissions = 1

-- ALTER TABLE skiroutes ADD mbtiles TEXT;

-- ALTER TABLE skiareas ADD short_desc TEXT;

-- ALTER TABLE version ADD live INT;

-- ALTER TABLE skiroutes ADD quip TEXT;
-- UPDATE skiroutes SET quip = '' WHERE quip IS NULL;

-- ALTER TABLE file ADD kml_image TEXT;
-- UPDATE file SET kml_image = 0;
-- UPDATE file SET caption = '' where caption IS NULL;