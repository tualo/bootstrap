delimiter ;

-- https://github.com/twbs/bootstrap

create table if not exists getbootstrap_scss (
    filename varchar(255) not null primary key,
    content longtext not null
);
