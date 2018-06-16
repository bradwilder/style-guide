set names 'utf8';

create table mb_image
(
  id mediumint not null auto_increment,
  name varchar(50) unique,
  description varchar(200),
  primary key (id)
);

create table mb_mode
(
  id mediumint not null auto_increment,
  name varchar(50) unique,
  description varchar(200),
  code varchar(20) unique,
  primary key (id)
);

create table mb_size
(
  id mediumint not null auto_increment,
  name varchar(50) unique,
  description varchar(200),
  code varchar(20) unique,
  primary key (id)
);

create table mb_section
(
  id mediumint not null auto_increment,
  name varchar(50) unique,
  description varchar(200),
  modeID mediumint,
  position tinyint,
  FOREIGN KEY (modeID) REFERENCES mb_mode(id) ON DELETE CASCADE,
  primary key (id)
);

create table mb_section_image
(
  id mediumint not null auto_increment,
  sectionID mediumint,
  imageID mediumint,
  position tinyint,
  sizeID mediumint,
  FOREIGN KEY (sectionID) REFERENCES mb_section(id) ON DELETE CASCADE,
  FOREIGN KEY (imageID) REFERENCES mb_image(id) ON DELETE CASCADE,
  FOREIGN KEY (sizeID) REFERENCES mb_size(id) ON DELETE CASCADE,
  primary key (id)
);

create table mb_comment
(
  id mediumint not null auto_increment,
  text varchar(2000),
  postTime datetime,
  commentReplyingToID mediumint,
  sectionImageID mediumint,
  userID int(11),
  FOREIGN KEY (commentReplyingToID) REFERENCES mb_comment(id) ON DELETE CASCADE,
  FOREIGN KEY (sectionImageID) REFERENCES mb_section_image(id) ON DELETE CASCADE,
  FOREIGN KEY (userID) REFERENCES users(id) ON DELETE CASCADE,
  primary key (id)
);
