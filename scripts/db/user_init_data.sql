insert into role (name, description) values ('Root', '');
insert into role (name, description) values ('Admin', '');
insert into role (name, description) values ('Edit', '');
insert into role (name, description) values ('View', '');
insert into role (name, description) values ('Comment', '');

insert into groups (name, description) values ('Root', '');
insert into groups (name, description) values ('Admin', '');
insert into groups (name, description) values ('Editor', '');
insert into groups (name, description) values ('User', '');

insert into group_role (groupID, role_id) values ((select id from groups where name = 'Root'), (select id from role where name = 'Root'));
insert into group_role (groupID, role_id) values ((select id from groups where name = 'Root'), (select id from role where name = 'Admin'));
insert into group_role (groupID, role_id) values ((select id from groups where name = 'Root'), (select id from role where name = 'Edit'));
insert into group_role (groupID, role_id) values ((select id from groups where name = 'Root'), (select id from role where name = 'View'));
insert into group_role (groupID, role_id) values ((select id from groups where name = 'Root'), (select id from role where name = 'Comment'));
insert into group_role (groupID, role_id) values ((select id from groups where name = 'Admin'), (select id from role where name = 'Admin'));
insert into group_role (groupID, role_id) values ((select id from groups where name = 'Admin'), (select id from role where name = 'Edit'));
insert into group_role (groupID, role_id) values ((select id from groups where name = 'Admin'), (select id from role where name = 'View'));
insert into group_role (groupID, role_id) values ((select id from groups where name = 'Admin'), (select id from role where name = 'Comment'));
insert into group_role (groupID, role_id) values ((select id from groups where name = 'Editor'), (select id from role where name = 'Edit'));
insert into group_role (groupID, role_id) values ((select id from groups where name = 'Editor'), (select id from role where name = 'View'));
insert into group_role (groupID, role_id) values ((select id from groups where name = 'Editor'), (select id from role where name = 'Comment'));
insert into group_role (groupID, role_id) values ((select id from groups where name = 'User'), (select id from role where name = 'View'));
insert into group_role (groupID, role_id) values ((select id from groups where name = 'User'), (select id from role where name = 'Comment'));
