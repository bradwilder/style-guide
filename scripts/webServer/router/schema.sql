create table routes
(
	id integer primary key,
	path text,
	model text,
	view text,
	templateFile text,
	controller text,
	action text,
	requiresAuth integer,
	requiredRole text
);