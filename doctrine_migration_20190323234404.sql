-- Doctrine Migration File Generated on 2019-03-23 23:44:04

-- Version 20190323233346
CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(254) NOT NULL, tel VARCHAR(15) NOT NULL, password VARCHAR(255) NOT NULL, type SMALLINT NOT NULL, note SMALLINT DEFAULT 0 NOT NULL, roles TINYTEXT NOT NULL COMMENT '(DC2Type:simple_array)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB;
INSERT INTO migration_versions (version, executed_at) VALUES ('20190323233346', CURRENT_TIMESTAMP);
