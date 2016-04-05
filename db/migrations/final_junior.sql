CREATE OR REPLACE ALGORITHM=
MERGE VIEW `final_junior` AS

SELECT COUNT(*),
       `moderation_stack_grouped`.*
FROM `vote`
JOIN `moderation_stack_grouped` ON `vote`.`competitive_work_id_competitive_work` = `moderation_stack_grouped`.`id_competitive_work`
WHERE `moderation_stack_grouped`.`age` BETWEEN 7 AND 12
GROUP BY `vote`.`competitive_work_id_competitive_work`
ORDER BY COUNT(*) DESC