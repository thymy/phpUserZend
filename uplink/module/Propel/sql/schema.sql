
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(25) NOT NULL,
    `first_name` VARCHAR(128) NOT NULL,
    `last_name` VARCHAR(128) NOT NULL,
    `password` VARCHAR(128) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- ---------------------------------------------------------------------
-- rights
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `rights`;

CREATE TABLE `rights`
(
    `user_id` INTEGER NOT NULL,
    `unlocked` TINYINT(1) NOT NULL,
    `right1` TINYINT(1) NOT NULL,
    `right2` TINYINT(1) NOT NULL,
    PRIMARY KEY (`user_id`)
) ENGINE=MyISAM;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
