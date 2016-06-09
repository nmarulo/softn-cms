SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Table `{DB_PREFIX}users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}users` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `user_login` VARCHAR(60) NOT NULL COMMENT 'nombre usado para acceder',
  `user_name` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'Nombre del usuario',
  `user_email` VARCHAR(100) NOT NULL COMMENT 'Correo electronico',
  `user_pass` VARCHAR(64) NOT NULL COMMENT 'Contraseña',
  `user_rol` INT NOT NULL DEFAULT 0 COMMENT 'Determinara el rango del usuario y los privilegios dentro del panel de administración. Por defecto 0: Tiene solo acceso para editar su información.',
  `user_registred` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de registro',
  `user_url` VARCHAR(100) NULL,
  PRIMARY KEY (`ID`));

CREATE UNIQUE INDEX `user_email_UNIQUE` ON `{DB_PREFIX}users` (`user_email` ASC);

CREATE UNIQUE INDEX `user_login_UNIQUE` ON `{DB_PREFIX}users` (`user_login` ASC);

-- -----------------------------------------------------
-- Table `{DB_PREFIX}posts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}posts` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `post_title` VARCHAR(45) NOT NULL DEFAULT '' COMMENT 'Título del artículo',
  `post_status` TINYINT NOT NULL DEFAULT 1 COMMENT 'Estado de publicación. (1 = Publicado, 0 = Borrador)',
  `post_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de publicación',
  `post_update` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha de la ultima actualización',
  `post_contents` LONGTEXT NOT NULL COMMENT 'Contenido del artículo',
  `comment_status` TINYINT NOT NULL DEFAULT 1 COMMENT 'Estado de comentarios, (1 = habilitado, 0 = deshabilitado)',
  `comment_count` INT NOT NULL DEFAULT 0 COMMENT 'Total de comentarios',
  `users_ID` INT NOT NULL,
  `post_type` VARCHAR(20) NOT NULL DEFAULT 'post' COMMENT 'Representa el tipo de post. post, page',
  PRIMARY KEY (`ID`, `users_ID`),
  CONSTRAINT `fk_post_users_id`
    FOREIGN KEY (`users_ID`)
    REFERENCES `{DB_PREFIX}users` (`ID`)
    ON DELETE RESTRICT
    ON UPDATE NO ACTION);

CREATE INDEX `fk_posts_users_id` ON `{DB_PREFIX}posts` (`users_ID` ASC);

-- -----------------------------------------------------
-- Table `{DB_PREFIX}options`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}options` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `option_name` VARCHAR(64) NOT NULL DEFAULT '',
  `option_value` LONGTEXT NOT NULL,
  PRIMARY KEY (`ID`));

CREATE UNIQUE INDEX `option_name_UNIQUE` ON `{DB_PREFIX}options` (`option_name` ASC);

-- -----------------------------------------------------
-- Table `{DB_PREFIX}comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}comments` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `comment_status` TINYINT NOT NULL DEFAULT 0 COMMENT 'Estado de aprovación del comentario. (1 = aprobado, 0 = sin aprobar)',
  `comment_autor` VARCHAR(60) NOT NULL,
  `comment_author_email` VARCHAR(100) NOT NULL,
  `comment_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment_contents` TEXT NOT NULL COMMENT 'Contenido del comentario',
  `comment_user_ID` INT NULL DEFAULT 0 COMMENT 'Si su valor es 0, el usuario no esta registrado en la pagina.',
  `post_ID` INT NOT NULL COMMENT 'Identificador del post',
  PRIMARY KEY (`ID`, `post_ID`),
  CONSTRAINT `fk_comments_post_id`
    FOREIGN KEY (`post_ID`)
    REFERENCES `{DB_PREFIX}posts` (`ID`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION);

CREATE INDEX `fk_comments_post_id` ON `{DB_PREFIX}comments` (`post_ID` ASC);

-- -----------------------------------------------------
-- Table `{DB_PREFIX}terms`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}terms` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `term_name` VARCHAR(60) NOT NULL DEFAULT '',
  `term_description` TEXT NULL,
  `term_count` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`));

CREATE UNIQUE INDEX `term_name_UNIQUE` ON `{DB_PREFIX}terms` (`term_name` ASC);

-- -----------------------------------------------------
-- Table `{DB_PREFIX}posts_terms`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}posts_terms` (
  `relationships_post_ID` INT NOT NULL,
  `relationships_term_ID` INT NOT NULL,
  PRIMARY KEY (`relationships_post_ID`, `relationships_term_ID`),
  CONSTRAINT `fk_terms_post_id`
    FOREIGN KEY (`relationships_post_ID`)
    REFERENCES `{DB_PREFIX}posts` (`ID`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_terms_id`
    FOREIGN KEY (`relationships_term_ID`)
    REFERENCES `{DB_PREFIX}terms` (`ID`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
COMMENT = 'Relaciones entre posts y etiquetas';

CREATE INDEX `fk_posts_terms_id` ON `{DB_PREFIX}posts_terms` (`relationships_term_ID` ASC);

CREATE INDEX `fk_terms_posts_id` ON `{DB_PREFIX}posts_terms` (`relationships_post_ID` ASC);

-- -----------------------------------------------------
-- Table `{DB_PREFIX}categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}categories` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `category_name` VARCHAR(60) NOT NULL DEFAULT '',
  `category_description` TEXT NULL,
  `category_count` INT NOT NULL DEFAULT 0,
  PRIMARY KEY (`ID`));

CREATE UNIQUE INDEX `category_name_UNIQUE` ON `{DB_PREFIX}categories` (`category_name` ASC);

-- -----------------------------------------------------
-- Table `{DB_PREFIX}posts_categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}posts_categories` (
  `relationships_post_ID` INT NOT NULL,
  `relationships_category_ID` INT NOT NULL,
  PRIMARY KEY (`relationships_post_ID`, `relationships_category_ID`),
  CONSTRAINT `fk_categories_post_id`
    FOREIGN KEY (`relationships_post_ID`)
    REFERENCES `{DB_PREFIX}posts` (`ID`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_post_categories_id`
    FOREIGN KEY (`relationships_category_ID`)
    REFERENCES `{DB_PREFIX}categories` (`ID`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
COMMENT = 'Relaciones entre post y categorias';

CREATE INDEX `fk_posts_categories_id` ON `{DB_PREFIX}posts_categories` (`relationships_category_ID` ASC);

CREATE INDEX `fk_categories_posts_id` ON `{DB_PREFIX}posts_categories` (`relationships_post_ID` ASC);

-- -----------------------------------------------------
-- Table `{DB_PREFIX}sidebars`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}sidebars` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `sidebar_title` VARCHAR(60) NOT NULL DEFAULT '',
  `sidebar_contents` LONGTEXT NULL,
  `sidebar_position` SMALLINT(2) NOT NULL DEFAULT 1 COMMENT 'Establece la posición en la que se mostrara el contenido',
  PRIMARY KEY (`ID`));

-- -----------------------------------------------------
-- Table `{DB_PREFIX}menus`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `{DB_PREFIX}menus` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `menu_name` VARCHAR(60) NOT NULL,
  `menu_url` VARCHAR(100) NULL,
  `menu_sub` MEDIUMINT NOT NULL DEFAULT 0 COMMENT 'Identificador del elemento padre, si se da el caso',
  `menu_position` INT NOT NULL DEFAULT 1 COMMENT 'Indica la posición del menu',
  `menu_title` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`ID`));

CREATE UNIQUE INDEX `menu_name_UNIQUE` ON `{DB_PREFIX}menus` (`menu_name` ASC);

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `{DB_PREFIX}options`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `{DB_PREFIX}options` (`ID`, `option_name`, `option_value`) VALUES (NULL, 'optionTitle', 'Lorem Ipsum');
INSERT INTO `{DB_PREFIX}options` (`ID`, `option_name`, `option_value`) VALUES (NULL, 'optionDescription', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec mollis.');
INSERT INTO `{DB_PREFIX}options` (`ID`, `option_name`, `option_value`) VALUES (NULL, 'optionPaged', '10');
INSERT INTO `{DB_PREFIX}options` (`ID`, `option_name`, `option_value`) VALUES (NULL, 'optionSiteUrl', '{URL_WEB}');
INSERT INTO `{DB_PREFIX}options` (`ID`, `option_name`, `option_value`) VALUES (NULL, 'optionTheme', 'default');
COMMIT;


-- -----------------------------------------------------
-- Data for table `{DB_PREFIX}categories`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `{DB_PREFIX}categories` (`ID`, `category_name`, `category_description`, `category_count`) VALUES (NULL, 'Uncategorized', NULL, 0);
COMMIT;

DELIMITER &&

CREATE DEFINER = CURRENT_USER TRIGGER `{DB_PREFIX}users_BEFORE_DELETE` BEFORE DELETE ON `{DB_PREFIX}users` FOR EACH ROW
BEGIN
	DECLARE exist INT;
    DECLARE post_ID INT;
	DECLARE cursor_posts_ID CURSOR FOR SELECT ID FROM {DB_PREFIX}posts WHERE users_ID = OLD.ID;

	SELECT count(*) INTO exist FROM {DB_PREFIX}posts WHERE users_ID = OLD.ID;
    OPEN cursor_posts_ID;
		loop_posts: LOOP
			IF exist = 0 THEN
				LEAVE loop_posts;
            END IF;
			FETCH cursor_posts_ID INTO post_ID;
            DELETE FROM {DB_PREFIX}posts WHERE ID = post_ID;
            SET exist = exist - 1;
        END LOOP;
    CLOSE cursor_posts_ID;
END&&

CREATE DEFINER = CURRENT_USER TRIGGER `{DB_PREFIX}posts_BEFORE_DELETE` BEFORE DELETE ON `{DB_PREFIX}posts` FOR EACH ROW
BEGIN
	DECLARE exist INT;
	DECLARE count INT;
    DECLARE category_ID INT;
    DECLARE term_ID INT;
    DECLARE cursor_category_ID CURSOR FOR SELECT relationships_category_ID FROM {DB_PREFIX}posts_categories WHERE relationships_post_ID = OLD.ID;
    DECLARE cursor_term_ID CURSOR FOR SELECT relationships_term_ID FROM {DB_PREFIX}posts_terms WHERE relationships_post_ID = OLD.ID;
    
    SELECT count(*) INTO exist FROM {DB_PREFIX}posts_categories WHERE relationships_post_ID = OLD.ID;
    OPEN cursor_category_ID;
		loop_category: LOOP
			IF exist = 0 THEN
				LEAVE loop_category;
            END IF;
			FETCH cursor_category_ID INTO category_ID;
            SELECT category_count INTO count FROM {DB_PREFIX}categories WHERE ID = category_ID;
			UPDATE {DB_PREFIX}categories SET category_count = count - 1 WHERE ID = category_ID;
            SET exist = exist - 1;
        END LOOP;
    CLOSE cursor_category_ID;
    
    SELECT count(*) INTO exist FROM {DB_PREFIX}posts_terms WHERE relationships_post_ID = OLD.ID;
    OPEN cursor_term_ID;
		loop_term: LOOP
			IF exist = 0 THEN
				LEAVE loop_term;
            END IF;
			FETCH cursor_term_ID INTO term_ID;
			SELECT term_count INTO count FROM {DB_PREFIX}terms WHERE ID = term_ID;
			UPDATE {DB_PREFIX}terms SET term_count = count - 1 WHERE ID = term_ID;
            SET exist = exist - 1;
        END LOOP loop_term;
    CLOSE cursor_term_ID;    
END&&

CREATE DEFINER = CURRENT_USER TRIGGER `{DB_PREFIX}comments_AFTER_INSERT` AFTER INSERT ON `{DB_PREFIX}comments` FOR EACH ROW
BEGIN
	DECLARE count INT;
    SELECT comment_count INTO count FROM {DB_PREFIX}posts WHERE ID = NEW.post_ID;
    UPDATE {DB_PREFIX}posts SET comment_count = count + 1 WHERE ID = NEW.post_ID;
END&&

CREATE DEFINER = CURRENT_USER TRIGGER `{DB_PREFIX}comments_BEFORE_DELETE` BEFORE DELETE ON `{DB_PREFIX}comments` FOR EACH ROW
BEGIN
	DECLARE count INT;
    SELECT comment_count INTO count FROM {DB_PREFIX}posts WHERE ID = OLD.post_ID;
	UPDATE {DB_PREFIX}posts SET comment_count = count - 1 WHERE ID = OLD.post_ID;
END&&

CREATE DEFINER = CURRENT_USER TRIGGER `{DB_PREFIX}posts_terms_AFTER_INSERT` AFTER INSERT ON `{DB_PREFIX}posts_terms` FOR EACH ROW
BEGIN
	DECLARE count INT;
    SELECT term_count INTO count FROM {DB_PREFIX}terms WHERE ID = NEW.relationships_term_ID;
	UPDATE {DB_PREFIX}terms SET term_count = count + 1 WHERE ID = NEW.relationships_term_ID;
END&&

CREATE DEFINER = CURRENT_USER TRIGGER `{DB_PREFIX}posts_categories_AFTER_INSERT` AFTER INSERT ON `{DB_PREFIX}posts_categories` FOR EACH ROW
BEGIN
	DECLARE count INT;
    SELECT category_count INTO count FROM {DB_PREFIX}categories WHERE ID = NEW.relationships_category_ID;
	UPDATE {DB_PREFIX}categories SET category_count = count + 1 WHERE ID = NEW.relationships_category_ID;
END&&

DELIMITER ;