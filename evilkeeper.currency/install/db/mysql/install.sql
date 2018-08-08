create table if not exists evilkeeper_currency_course
(
    ID int primary key auto_increment,
    CODE text not null,
    DATE datetime,
    COURSE float not null
);
