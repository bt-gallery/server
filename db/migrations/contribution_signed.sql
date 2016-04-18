CREATE OR REPLACE ALGORITHM=
MERGE VIEW `contribution_signed` AS
SELECT contribution.id,
       contribution.time AS contribution_time,
       contribution.name AS contribution_name,
       contribution.description AS contribution_description,
       contribution.store_path,
       contribution.web_path,
       contribution.file_name,
       contribution.moderation AS contribution_moderation,
       contribution.rejection AS contribution_rejection,
       contribution.category,
       contribution.priority,
       contribution.type,
       contribution.file_size,
       contribution.id_participant,
       participant.time AS participant_time,
       participant.id_declarant,
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