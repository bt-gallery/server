CREATE ALGORITHM=MERGE VIEW `moderation_stack_grouped` AS select * from `moderation_stack_filtered` group by `email`,`name`,`surname`,`age` order by `email`,`surname`,`name`
