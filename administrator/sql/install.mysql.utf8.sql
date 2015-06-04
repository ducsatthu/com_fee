CREATE TABLE IF NOT EXISTS `#__fee_student` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`student_id` VARCHAR(255)  NOT NULL ,
`title` VARCHAR(255)  NOT NULL ,
`department_alias` VARCHAR(255)  NOT NULL ,
`course_alias` VARCHAR(255)  NOT NULL ,
`level_alias` VARCHAR(255)  NOT NULL ,
`special` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__fee_course` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`title` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__fee_department` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`title` VARCHAR(255)  NOT NULL ,
`department_alias` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__fee_level` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`title` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__fee_fee` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`student_alias` VARCHAR(255)  NOT NULL ,
`semester_alias` VARCHAR(255)  NOT NULL ,
`year_alias` VARCHAR(255)  NOT NULL ,
`rate` FLOAT NOT NULL ,
`payable` FLOAT NOT NULL ,
`owed` FLOAT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__fee_semester` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`title` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__fee_year` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`start` YEAR NOT NULL ,
`end` YEAR NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__fee_receipt` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`ordering` INT(11)  NOT NULL ,
`state` TINYINT(1)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`created_by` INT(11)  NOT NULL ,
`alias` VARCHAR(255)  NOT NULL ,
`title` INT(11)  NOT NULL ,
`student_alias` VARCHAR(255)  NOT NULL ,
`semester_alias` VARCHAR(255)  NOT NULL ,
`year_alias` VARCHAR(255)  NOT NULL ,
`date` DATE NOT NULL DEFAULT '0000-00-00',
`paid` FLOAT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

/** Support Content history 
	fee_student
**/
INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`, `content_history_options`) 
SELECT 'student', 'com_fee.student', '{"special": {"dbtable": "#__fee_student","key": "id","type": "student","prefix":"FeeTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}', '', '{"common": {"core_content_item_id":"id","core_title":"title","core_state":"state","core_alias":"alias","core_created_time":"created","core_modified_time":"modified","core_body":"introtext", "core_hits":"hits","core_publish_up":"publish_up","core_publish_down":"publish_down","core_access":"access", "core_params":"attribs", "core_featured":"featured", "core_metadata":"metadata", "core_language":"language", "core_images":"images", "core_urls":"urls", "core_version":"version", "core_ordering":"ordering", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"catid", "core_xreference":"xreference", "asset_id":"asset_id"},"special":{}}', 'FeeHelperRoute::getStudentRoute', '{"formFile":"administrator\\/components\\/com_fee\\/models\\/forms\\/student.xml", "hideFields": ["asset_id","checked_out","checked_out_time","access"], "ignoreChanges": [ "checked_out", "checked_out_time"],"convertToInt": [], "displayLookup": [{"sourceColumn":"department_alias","targetTable":"#__fee_department","targetColumn":"alias","displayColumn":"title"},{"sourceColumn":"course_alias","targetTable":"#__fee_course","targetColumn":"alias","displayColumn":"title"},{"sourceColumn":"level_alias","targetTable":"#__fee_level","targetColumn":"alias","displayColumn":"title"}]}'
FROM DUAL
WHERE NOT EXISTS (SELECT `type_alias` FROM `#__content_types` WHERE `type_alias`='com_fee.student');



INSERT INTO `#__fee_course` (`id`, `asset_id`, `ordering`, `state`, `checked_out`, `checked_out_time`, `created_by`, `alias`, `title`) VALUES
(1, 89, 1, 1, 0, '0000-00-00 00:00:00', 301, 'C2AAD91D-B673-4058-982C-AAF3DF009D09', 1),
(2, 89, 2, 1, 0, '0000-00-00 00:00:00', 301, 'AAD1A419-4D26-48F8-BCDD-C35AC098E81E', 2),
(3, 89, 3, 1, 0, '0000-00-00 00:00:00', 301, 'C570B76D-57C3-4A55-8966-531E0AD7C8B9', 3),
(4, 89, 4, 1, 0, '0000-00-00 00:00:00', 301, 'C90572C6-5C24-4713-8F8D-DD5D7A9C464C', 4),
(5, 89, 5, 1, 0, '0000-00-00 00:00:00', 301, 'B8FF9835-2500-40C2-A230-F4DA5B197DBE', 5),
(6, 89, 6, 1, 0, '0000-00-00 00:00:00', 301, '5170B975-C99F-47AF-B3B3-110EF7034735', 6),
(7, 89, 7, 1, 0, '0000-00-00 00:00:00', 301, '0EE3DEF4-9EBB-4783-8754-7573A8D970B3', 7),
(8, 89, 8, 1, 0, '0000-00-00 00:00:00', 301, '3DF1C9BF-A763-4D64-A9E6-73DD18C5716E', 8),
(9, 89, 9, 1, 0, '0000-00-00 00:00:00', 301, '592C0D28-D2F8-4F5A-9DCA-853F0CAE0B5E', 9),
(10, 89, 10, 1, 0, '0000-00-00 00:00:00', 301, 'FCFEE50F-C406-4747-BDD5-D7B884C7AF95', 10),
(11, 89, 11, 1, 0, '0000-00-00 00:00:00', 301, '4D495C69-9C64-48F3-B77A-0170B9ECC8FA', 11),
(12, 89, 12, 1, 0, '0000-00-00 00:00:00', 301, '6C270B44-C59B-41A0-A21D-1159BC0C143F', 12),
(13, 89, 13, 1, 0, '0000-00-00 00:00:00', 301, '97ECCD1B-C691-4AD7-9115-6E0F32A8A6FC', 13),
(14, 89, 14, 1, 0, '0000-00-00 00:00:00', 301, 'A112B28D-F2F5-4F42-AADA-4F56958628CD', 14),
(15, 89, 15, 1, 0, '0000-00-00 00:00:00', 301, '391C8E33-1D32-452B-A04A-6BC077422BA7', 15),
(16, 89, 16, 1, 0, '0000-00-00 00:00:00', 301, 'C65485C7-BE38-4AFD-A70E-840320C0039E', 16),
(17, 89, 17, 1, 0, '0000-00-00 00:00:00', 301, 'F99417E0-2FC7-409A-BC39-12444AAEC47A', 17),
(18, 89, 18, 1, 0, '0000-00-00 00:00:00', 301, 'FCD7E5A4-44A8-4410-B19A-5E7E838FFC92', 18),
(19, 89, 19, 1, 0, '0000-00-00 00:00:00', 301, 'F6E57743-0EFC-4C17-BD79-58BC1F0F612F', 19),
(20, 89, 20, 1, 0, '0000-00-00 00:00:00', 301, '772425F3-6499-49DD-8560-F6EF83C49F82', 20),
(21, 89, 21, 1, 0, '0000-00-00 00:00:00', 301, '3E5F8A1B-605B-4027-9D16-61E5AE4517EB', 21),
(22, 89, 22, 1, 0, '0000-00-00 00:00:00', 301, 'A7CC2B51-AB4B-459B-828E-F9E9DD350BD5', 22),
(23, 89, 23, 1, 0, '0000-00-00 00:00:00', 301, '0D737ED4-BA47-4336-AB0E-0F87FF757C73', 23),
(24, 89, 24, 1, 0, '0000-00-00 00:00:00', 301, 'C82C9584-C8FF-48C8-A996-A6142558B6E4', 24),
(25, 89, 25, 1, 0, '0000-00-00 00:00:00', 301, 'A08EDD97-B25F-4893-988E-2A63F5C1B8DB', 25),
(26, 89, 26, 1, 0, '0000-00-00 00:00:00', 301, '03CCF5EF-79FA-4B8F-B3BD-05DC07B244A7', 26),
(27, 89, 27, 1, 0, '0000-00-00 00:00:00', 301, '8A90D76D-3A37-44FD-9CE8-191594B1E7FD', 27),
(28, 89, 28, 1, 0, '0000-00-00 00:00:00', 301, '51000315-A6A7-4362-A789-3F999B33F985', 28),
(29, 89, 29, 1, 0, '0000-00-00 00:00:00', 301, 'F924A2CD-4759-475C-8CFF-E59653323D8D', 29),
(30, 89, 30, 1, 0, '0000-00-00 00:00:00', 301, '31102FAF-EAF5-4384-B422-3663C5BB093A', 30),
(31, 89, 31, 1, 0, '0000-00-00 00:00:00', 301, '7C091A31-1CAC-4DC7-BA30-7CF1DF328C46', 31),
(32, 89, 32, 1, 0, '0000-00-00 00:00:00', 301, 'CD047F56-AC9B-4E5A-85C1-566D169A857A', 32),
(33, 89, 33, 1, 0, '0000-00-00 00:00:00', 301, '31125A88-A5EC-4CD5-8497-617B9702213B', 33),
(34, 89, 34, 1, 0, '0000-00-00 00:00:00', 301, '77EC7740-9F97-4142-88D3-4797C6CA073A', 34),
(35, 89, 35, 1, 0, '0000-00-00 00:00:00', 301, 'F2F01E0F-587A-4ACE-9A00-BEE69A86A701', 35),
(36, 89, 36, 1, 0, '0000-00-00 00:00:00', 301, 'D022367A-99C1-47EF-A60A-0FDB6E41B9F2', 36),
(37, 89, 37, 1, 0, '0000-00-00 00:00:00', 301, '3B0114A8-678F-47B7-B5D6-D3B90D22659F', 37),
(38, 89, 38, 1, 0, '0000-00-00 00:00:00', 301, '87A5B29D-C15C-4ACA-B51D-3CB407C0A84A', 38),
(39, 89, 39, 1, 0, '0000-00-00 00:00:00', 301, 'BB89A6B6-F963-4D6A-93D7-C3A3EDCF0E14', 39),
(40, 89, 40, 1, 0, '0000-00-00 00:00:00', 301, '937085F8-2E4F-41CD-92F4-E19C1C89F73C', 40),
(41, 89, 41, 1, 0, '0000-00-00 00:00:00', 301, '40454E6D-6374-4D55-B6F9-EF1189166D79', 41),
(42, 89, 42, 1, 0, '0000-00-00 00:00:00', 301, '64170028-C15D-49AB-8046-94A12218250D', 42),
(43, 89, 43, 1, 0, '0000-00-00 00:00:00', 301, 'D2364895-6589-4668-9464-6134B91131FA', 43),
(44, 89, 44, 1, 0, '0000-00-00 00:00:00', 301, '1CE04BBB-DD73-4C2E-AB8C-B4BD44FC51DC', 44),
(45, 89, 45, 1, 0, '0000-00-00 00:00:00', 301, 'A10D4D72-0805-4B31-BC0F-B672D5789F2B', 45),
(46, 89, 46, 1, 0, '0000-00-00 00:00:00', 301, '226BB6BE-ECC6-4EF1-9AC8-201CA33AEDF4', 46),
(47, 89, 47, 1, 0, '0000-00-00 00:00:00', 301, '00B61019-D939-44F8-A823-441830795DE6', 47),
(48, 89, 48, 1, 0, '0000-00-00 00:00:00', 301, '5DBCD293-555B-4516-995F-38615311CC1C', 48),
(49, 89, 49, 1, 0, '0000-00-00 00:00:00', 301, '06E0C3CF-22B1-4866-BC61-0424C20C87DD', 49),
(50, 89, 50, 1, 0, '0000-00-00 00:00:00', 301, 'C1C0C538-949F-4170-BF29-EF6A36CBDC2A', 50),
(51, 89, 51, 1, 0, '0000-00-00 00:00:00', 301, '52BB6C6D-DB1A-493C-B6CC-B4E376DB1492', 51),
(52, 89, 52, 1, 0, '0000-00-00 00:00:00', 301, '59610960-9E99-424A-A43E-D1440B3B5C7A', 52),
(53, 89, 53, 1, 0, '0000-00-00 00:00:00', 301, '27F93D90-757F-4B4B-9861-53A87141E883', 53),
(54, 89, 54, 1, 0, '0000-00-00 00:00:00', 301, '0BF0F2FD-008D-46F6-A0F4-5878C90C2EA3', 54),
(55, 89, 55, 1, 0, '0000-00-00 00:00:00', 301, '2CFAAFB6-E9F0-4FFE-B966-205514CE122D', 55),
(56, 89, 56, 1, 0, '0000-00-00 00:00:00', 301, 'E9345C19-4EC9-4F22-A168-F7629727EC5E', 56),
(57, 89, 57, 1, 0, '0000-00-00 00:00:00', 301, 'FD93AE46-ABAB-4E11-932D-B9AB674A45C8', 57),
(58, 89, 58, 1, 0, '0000-00-00 00:00:00', 301, '060F5A79-07CA-4904-89F6-51F8D426DAC5', 58),
(59, 89, 59, 1, 0, '0000-00-00 00:00:00', 301, '60F6CDD8-B000-4822-82EA-7B8EEFDEEF48', 59),
(60, 89, 60, 1, 0, '0000-00-00 00:00:00', 301, '4390C684-772D-4BC6-BAB9-67ED5C2FA6AF', 60),
(61, 89, 61, 1, 0, '0000-00-00 00:00:00', 301, '9870CE95-8C6F-4BD9-B402-6B7D6EDE34AB', 61),
(62, 89, 62, 1, 0, '0000-00-00 00:00:00', 301, '010AF362-F5F8-4CDD-91E9-106836805D5D', 62);

INSERT INTO `#__fee_level` (`id`, `asset_id`, `ordering`, `state`, `checked_out`, `checked_out_time`, `created_by`, `alias`, `title`) VALUES
(1, 242, 1, 1, 0, '0000-00-00 00:00:00', 668, 'C2D90233-C3A3-40C8-AB7E-B4C2726E1A15', 'Đại học'),
(2, 243, 2, 1, 0, '0000-00-00 00:00:00', 668, 'BA875AD5-B5CD-4AE8-8251-C90B19EF8BB0', 'Cao học'),
(3, 244, 3, 1, 0, '0000-00-00 00:00:00', 668, '339BEE83-8555-4B56-A665-BA99F1460496', 'Cao đẳng'),
(4, 245, 4, 1, 0, '0000-00-00 00:00:00', 668, '8C3B8FC5-1F03-4D0A-81EF-764867D35E33', 'Liên thông');

INSERT INTO `#__fee_semester` (`id`, `asset_id`, `ordering`, `state`, `checked_out`, `checked_out_time`, `created_by`, `alias`, `title`) VALUES
(1, 247, 1, 1, 0, '0000-00-00 00:00:00', 668, '4CEC3DF2-759F-49E4-B20E-78718B5C356B', 1),
(2, 248, 2, 1, 0, '0000-00-00 00:00:00', 668, '30338A28-7BE5-4D36-9DFD-7CB287785129', 2),
(3, 249, 3, 1, 0, '0000-00-00 00:00:00', 668, '82CCCF60-8C11-43EE-B2B8-E02D79A6A684', 3);