-- usage: mysql -u root movie < mysql_example.sql


drop table if exists starsIn;
drop table if exists movies;
drop table if exists stars;


create table movies (
	title char(20),
	year int,
	length int,
	genre char(10),
	primary key (title, year)
);

insert into movies values ("Gone With the Wind", 1939, 231, "drama");
insert into movies values ("Star Wars", 1977, 124, "sciFi");


create table stars (
	name char(20),
	DOB DATE, 
        address varchar(100),
        primary key (name)
);

insert into stars values ("Vivien Leigh", "1913-11-05", "Atlanta, GA");
insert into stars values ("Harrison Ford", "1942-07-13", "Chicago, IL");


create table starsIn (
 	name char(20),
	title char(20),
	year int,
        constraint foreign key (name) references stars(name), 
        constraint foreign key (title, year) references movies(title, year) 
);

insert into starsIn values ("Vivien Leigh", "Gone With the Wind", 1939);
insert into starsIn values ("Harrison Ford", "Star Wars", 1977);
