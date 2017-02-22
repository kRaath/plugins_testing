CREATE TABLE `xplugin_jtl_example_foo` (
		`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`foo` INT NOT NULL,
		`bar` TINYINT NOT NULL,
		`text` TEXT
);

CREATE TABLE IF NOT EXISTS `xplugin_jtl_example_bar` (
		`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		`foo` INT NOT NULL,
		`bar` TINYINT NOT NULL
);

INSERT INTO `xplugin_jtl_example_foo` (`foo`, `bar`, `text`) VALUES (22, 1, 'Foobar!');
INSERT INTO `xplugin_jtl_example_foo` (`foo`, `bar`, `text`) VALUES (44, 3, 'Foobar text 2!');
INSERT INTO `xplugin_jtl_example_bar` (`foo`, `bar`) VALUES (123456, 2);