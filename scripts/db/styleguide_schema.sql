set names 'utf8';

create table sg_section
(
  id mediumint not null auto_increment,
  name varchar(50) unique,
  position tinyint,
  enabled tinyint(1) default 0,
  userCreated tinyint(1) default 0,
  primary key (id)
);

create table sg_subsection
(
  id mediumint not null auto_increment,
  name varchar(50),
  description varchar(2000),
  sectionID mediumint,
  position tinyint,
  enabled tinyint(1) default 1,
  parentSubsectionID mediumint,
  FOREIGN KEY (sectionID) REFERENCES sg_section(id) ON DELETE CASCADE,
  FOREIGN KEY (parentSubsectionID) REFERENCES sg_subsection(id) ON DELETE CASCADE,
  primary key (id)
);

create table sg_item_type
(
  id mediumint not null auto_increment,
  description varchar(200),
  code varchar(20) unique,
  primary key (id)
);

create table sg_item_type_column_min
(
  id mediumint not null auto_increment,
  typeID mediumint,
  code varchar(8),
  minLG tinyint,
  minMD tinyint,
  minSM tinyint,
  minXS tinyint,
  FOREIGN KEY (typeID) REFERENCES sg_item_type(id) ON DELETE CASCADE,
  primary key (id)
);

create table sg_item
(
  id mediumint not null auto_increment,
  name varchar(100),
  typeID mediumint,
  colLg tinyint,
  colMd tinyint,
  colSm tinyint,
  colXs tinyint,
  subsectionID mediumint,
  position tinyint,
  FOREIGN KEY (typeID) REFERENCES sg_item_type(id) ON DELETE CASCADE,
  FOREIGN KEY (subsectionID) REFERENCES sg_subsection(id) ON DELETE CASCADE,
  primary key (id)
);

create table sg_color
(
  id mediumint not null auto_increment,
  name varchar(50),
  hex varchar(6),
  variant1 varchar(6),
  variant2 varchar(6),
  primary key (id)
);

create table sg_color_default
(
  id mediumint not null auto_increment,
  color_id mediumint,
  FOREIGN KEY (color_id) REFERENCES sg_color(id) ON DELETE CASCADE,
  primary key (id)
);

create table sg_color_item
(
  baseID mediumint,
  color1ID mediumint,
  color2ID mediumint,
  color3ID mediumint,
  color4ID mediumint,
  color5ID mediumint,
  color6ID mediumint,
  FOREIGN KEY (color1ID) REFERENCES sg_color(id) ON DELETE CASCADE,
  FOREIGN KEY (color2ID) REFERENCES sg_color(id) ON DELETE CASCADE,
  FOREIGN KEY (color3ID) REFERENCES sg_color(id) ON DELETE CASCADE,
  FOREIGN KEY (color4ID) REFERENCES sg_color(id) ON DELETE CASCADE,
  FOREIGN KEY (color5ID) REFERENCES sg_color(id) ON DELETE CASCADE,
  FOREIGN KEY (color6ID) REFERENCES sg_color(id) ON DELETE CASCADE,
  FOREIGN KEY (baseID) REFERENCES sg_item(id) ON DELETE CASCADE,
  primary key (baseID)
);

create table sg_color_descriptor
(
  id mediumint not null auto_increment,
  description varchar(200),
  itemID mediumint,
  position tinyint,
  FOREIGN KEY (itemID) REFERENCES sg_color_item(baseID) ON DELETE CASCADE,
  primary key (id)
);

create table sg_font_type
(
  id mediumint not null auto_increment,
  description varchar(200),
  code varchar(20) unique,
  primary key (id)
);

create table sg_font_alphabet
(
  id mediumint not null auto_increment,
  name varchar(100),
  code varchar(8) unique,
  alphabet varchar(1000),
  primary key (id)
);

create table sg_font
(
  id mediumint not null auto_increment,
  name varchar(100),
  typeID mediumint,
  alphabetID mediumint,
  FOREIGN KEY (typeID) REFERENCES sg_font_type(id) ON DELETE CASCADE,
  FOREIGN KEY (alphabetID) REFERENCES sg_font_alphabet(id) ON DELETE CASCADE,
  primary key (id)
);

create table sg_webfont
(
  baseID mediumint not null,
  importURL varchar(1000),
  website varchar(1000),
  FOREIGN KEY (baseID) REFERENCES sg_font(id) ON DELETE CASCADE,
  primary key (baseID)
);

create table sg_cssfont
(
  baseID mediumint not null,
  directory varchar(1000),
  cssFile varchar(1000),
  FOREIGN KEY (baseID) REFERENCES sg_font(id) ON DELETE CASCADE,
  primary key (baseID)
);

create table sg_font_family
(
  baseID mediumint,
  fontID mediumint,
  FOREIGN KEY (fontID) REFERENCES sg_font(id) ON DELETE CASCADE,
  FOREIGN KEY (baseID) REFERENCES sg_item(id) ON DELETE CASCADE,
  primary key (baseID)
);

create table sg_font_listing_table
(
  baseID mediumint,
  FOREIGN KEY (baseID) REFERENCES sg_item(id) ON DELETE CASCADE,
  primary key (baseID)
);

create table sg_font_listing
(
  id mediumint not null auto_increment,
  text varchar(2000),
  itemID mediumint,
  fontID mediumint,
  position tinyint,
  FOREIGN KEY (itemID) REFERENCES sg_font_listing_table(baseID) ON DELETE CASCADE,
  FOREIGN KEY (fontID) REFERENCES sg_font(id) ON DELETE CASCADE,
  primary key (id)
);

create table sg_font_listing_css
(
  id mediumint not null auto_increment,
  css varchar(200),
  fontListingID mediumint,
  FOREIGN KEY (fontListingID) REFERENCES sg_font_listing(id) ON DELETE CASCADE,
  primary key (id)
);

create table sg_upload_type
(
  id mediumint not null auto_increment,
  code varchar(20) unique,
  primary key (id)
);

create table sg_upload
(
  id mediumint not null auto_increment,
  filePath varchar(1000),
  parentID mediumint,
  typeID mediumint,
  FOREIGN KEY (parentID) REFERENCES sg_upload(id) ON DELETE CASCADE,
  FOREIGN KEY (typeID) REFERENCES sg_upload_type(id) ON DELETE CASCADE,
  primary key (id)
);

create table sg_upload_file
(
  baseID mediumint,
  shortName varchar(50),
  fullName varchar(200),
  FOREIGN KEY (baseID) REFERENCES sg_upload(id) ON DELETE CASCADE,
  primary key (baseID)
);

create table sg_element
(
  baseID mediumint,
  upload1ID mediumint,
  upload2ID mediumint,
  upload3ID mediumint,
  upload4ID mediumint,
  upload5ID mediumint,
  upload6ID mediumint,
  FOREIGN KEY (upload1ID) REFERENCES sg_upload(id) ON DELETE CASCADE,
  FOREIGN KEY (upload2ID) REFERENCES sg_upload(id) ON DELETE CASCADE,
  FOREIGN KEY (upload3ID) REFERENCES sg_upload(id) ON DELETE CASCADE,
  FOREIGN KEY (upload4ID) REFERENCES sg_upload(id) ON DELETE CASCADE,
  FOREIGN KEY (upload5ID) REFERENCES sg_upload(id) ON DELETE CASCADE,
  FOREIGN KEY (upload6ID) REFERENCES sg_upload(id) ON DELETE CASCADE,
  FOREIGN KEY (baseID) REFERENCES sg_item(id) ON DELETE CASCADE,
  primary key (baseID)
);

create table sg_icon_listing_table
(
  baseID mediumint,
  fontID mediumint,
  FOREIGN KEY (baseID) REFERENCES sg_item(id) ON DELETE CASCADE,
  FOREIGN KEY (fontID) REFERENCES sg_font(id) ON DELETE CASCADE,
  primary key (baseID)
);

create table sg_icon_listing
(
  id mediumint not null auto_increment,
  html varchar(1000),
  itemID mediumint,
  position tinyint,
  FOREIGN KEY (itemID) REFERENCES sg_icon_listing_table(baseID) ON DELETE CASCADE,
  primary key (id)
);