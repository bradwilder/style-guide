set names 'utf8';

create table page_options
(
  id mediumint not null auto_increment,
  code varchar(20),
  setting varchar(20),
  value varchar(20),
  primary key (id)
);

create table branding
(
  id mediumint not null auto_increment,
  companyName varchar(50),
  primary key (id)
);