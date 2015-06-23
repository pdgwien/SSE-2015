drop table if exists user;
create table user (
    id integer primary key autoincrement,
    name text not null,
    password text not null
);
