CREATE OR REPLACE ALGORITHM=TEMPTABLE VIEW `moderation_stack_filtered` AS
SELECT `competitive_work`.`id_competitive_work` AS `id_competitive_work`,
       `competitive_work`.`priority` AS `priority`,
       `declarant`.`email` AS `email`,
       `participant`.`name` AS `name`,
       `participant`.`surname` AS `surname`,
       `participant`.`age` AS `age`,
       `moderation_stack`.`queue_num` AS `queue_num`,
       `moderation_stack`.`status` AS `status`,
       `competitive_work`.`moderation` AS `moderation`,
       concat('http://konkurs.mir24.tv',`competitive_work`.`web_path`) AS `web_url`,
       `moderation_process`.`result` AS `result`,
       `moderation_process`.`notice` AS `notice`
FROM (((((`competitive_work`
          LEFT JOIN `participant` on((`competitive_work`.`id_participant` = `participant`.`id_participant`)))
         LEFT JOIN `declarant` on((`competitive_work`.`id_declarant` = `declarant`.`id_declarant`)))
        LEFT JOIN `moderation_stack` on((`competitive_work`.`id_competitive_work` = `moderation_stack`.`id_competitive_work`)))
       LEFT JOIN `address` on((`declarant`.`id_declarant` = `address`.`declarant_id_declarant`)))
      LEFT JOIN `moderation_process` on((`moderation_process`.`id_competitive_work` = `competitive_work`.`id_competitive_work`)))
WHERE ((`competitive_work`.`bet` = 1)
       AND (not((`declarant`.`email` LIKE '%yurchev%')))
       AND (not((`declarant`.`email` LIKE '%yourchev%')))
       AND (not((`declarant`.`email` LIKE '%vatasi%')))
       AND (not((`declarant`.`email` LIKE '%konshin%')))
       AND (not((`declarant`.`email` LIKE '%asda%')))
       AND (not((`declarant`.`email` LIKE '%kovalchuk_dk%'))))