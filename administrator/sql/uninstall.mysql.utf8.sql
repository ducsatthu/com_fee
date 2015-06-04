DROP TABLE IF EXISTS `#__fee_course`;
DROP TABLE IF EXISTS `#__fee_department`;
DROP TABLE IF EXISTS `#__fee_level`;
DROP TABLE IF EXISTS `#__fee_fee`;
DROP TABLE IF EXISTS `#__fee_semester`;
DROP TABLE IF EXISTS `#__fee_year`;
DROP TABLE IF EXISTS `#__fee_receipt`;

DELETE FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_fee.student';
DELETE FROM `#__ucm_history` WHERE `#__ucm_history`.`ucm_type_id` IN (SELECT `type_id` FROM `#__content_types` WHERE `#__content_types`.`type_alias` = 'com_fee.student');
DROP TABLE IF EXISTS `#__fee_student`;