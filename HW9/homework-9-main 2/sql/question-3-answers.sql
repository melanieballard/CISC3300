-- select by id 1:

select * from `posts` where `id` = 1;

-- select all posts where title = includes "title 2":

select * from `posts` where `title` like "%title 2%";

-- select all posts and order by the title column alphabetically:

select * from `posts` order by `title`;

-- insert 3 new posts

insert into posts (title, description) values ('test post title 3', 'test post description 3');
insert into posts (title, description) values ('test post title 4', 'test post description 4');
insert into posts (title, description) values ('test post title 5', 'test post description 5');

-- update posts where id = 1, set the title to "new title"

update `posts` set `title` = "Updated Post" where `id` = 1;

-- delete post where id = 2

delete from `posts` where `id` = 2;