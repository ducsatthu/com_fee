DELETE FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_fee.student';
DELETE FROM `#__ucm_history` WHERE `#__ucm_history`.`ucm_type_id` IN (SELECT `type_id` FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_fee.student');
DROP TABLE IF EXISTS `#__fee_student`;

DELETE FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_fee.course';
DELETE FROM `#__ucm_history` WHERE `#__ucm_history`.`ucm_type_id` IN (SELECT `type_id` FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_fee.course');
DROP TABLE IF EXISTS `#__fee_course`;

DELETE FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_fee.department';
DELETE FROM `#__ucm_history` WHERE `#__ucm_history`.`ucm_type_id` IN (SELECT `type_id` FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_fee.department');
DROP TABLE IF EXISTS `#__fee_department`;

DELETE FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_fee.level';
DELETE FROM `#__ucm_history` WHERE `#__ucm_history`.`ucm_type_id` IN (SELECT `type_id` FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_fee.level');
DROP TABLE IF EXISTS `#__fee_level`;

DELETE FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_fee.fee';
DELETE FROM `#__ucm_history` WHERE `#__ucm_history`.`ucm_type_id` IN (SELECT `type_id` FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_fee.fee');
DROP TABLE IF EXISTS `#__fee_fee`;

DELETE FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_fee.receipt';
DELETE FROM `#__ucm_history` WHERE `#__ucm_history`.`ucm_type_id` IN (SELECT `type_id` FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_fee.receipt');
DROP TABLE IF EXISTS `#__fee_receipt`;