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

create table actionArgs
(
	id integer primary key,
	argument text,
	type text,
	routeID integer,
	position integer,
	foreign key(routeID) references routes(id)
);