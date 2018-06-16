create table role
(
  id mediumint not null auto_increment,
  name varchar(50) unique,
  description varchar(200),
  primary key (id)
);

create table groups
(
  id mediumint not null auto_increment,
  name varchar(50) unique,
  description varchar(200),
  primary key (id)
);

create table group_role
(
  id mediumint not null auto_increment,
  groupID mediumint,
  role_id mediumint,
  FOREIGN KEY (groupID) REFERENCES groups(id) ON DELETE CASCADE,
  FOREIGN KEY (role_id) REFERENCES role(id) ON DELETE CASCADE,
  primary key (id)
);

alter table users add column groupID mediumint default null;
alter table users add FOREIGN KEY (groupID) REFERENCES groups(id) ON DELETE CASCADE;

alter table users add column displayName varchar(50);