CREATE OR REPLACE ALGORITHM=
MERGE VIEW `contribution_signed` AS
SELECT contribution.id,
       contribution.time AS contribution_time,
       contribution.id_declarant AS contribution_id_declarant,
       contribution.name AS contribution_name,
       contribution.description AS contribution_description,
       contribution.persons,
       contribution.store_path,
       contribution.web_path,
       contribution.file_name,
       contribution.moderation AS contribution_moderation,
       contribution.rejection AS contribution_rejection,
       contribution.category,
       contribution.priority,
       contribution.type,
       contribution.file_size,
       contribution.thumb_store_path,
       contribution.thumb_web_path,
       contribution.id_participant,
       participant.time AS participant_time,
       participant.id_declarant AS participant_id_declarant,
       participant.name AS participant_name,
       participant.surname,
       participant.patronymic,
       participant.description AS participant_description,
       participant.year,
       participant.moderation AS participant_moderation,
       participant.rejection AS participant_rejection,
       participant.team
FROM contribution
LEFT JOIN participant ON contribution.id_participant = participant.id
WHERE contribution.moderation = 3