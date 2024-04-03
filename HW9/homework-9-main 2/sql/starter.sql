CREATE TABLE `posts`
(
    `id`            int(11) NOT NULL AUTO_INCREMENT,
    `title`         varchar(254) NOT NULL,
    `description`   varchar(254) NOT NULL,
    primary key (`id`)
);

insert into posts (title, description) values ('test post title 1', 'test post description 1');
insert into posts (title, description) values ('test post title 2', 'test post description 2');

