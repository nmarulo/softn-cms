-- -----------------------------------------------------
-- Table `#{PREFIX}users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}users` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `user_login` VARCHAR(60) NOT NULL COMMENT 'nombre usado para acceder',
    `user_name` VARCHAR(50) NOT NULL COMMENT 'Nombre del usuario',
    `user_email` VARCHAR(100) NOT NULL COMMENT 'Correo electronico',
    `user_password` VARCHAR(64) NOT NULL COMMENT 'Contraseña',
    `user_rol` INT NOT NULL COMMENT 'Determinara el rango del usuario y los privilegios dentro del panel de administración. Por defecto 0: Tiene solo acceso para editar su información.',
    `user_registered` DATETIME NOT NULL COMMENT 'Fecha de registro',
    `user_url` VARCHAR(100) NULL,
    `user_post_count` INT NOT NULL,
    PRIMARY KEY (`ID`))
    ENGINE = InnoDB;

CREATE UNIQUE INDEX `user_email_UNIQUE` ON `#{PREFIX}users` (`user_email` ASC);

CREATE UNIQUE INDEX `user_login_UNIQUE` ON `#{PREFIX}users` (`user_login` ASC);


-- -----------------------------------------------------
-- Table `#{PREFIX}posts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}posts` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `post_title` VARCHAR(45) NOT NULL COMMENT 'Título del artículo',
    `post_status` TINYINT NOT NULL COMMENT 'Estado de publicación. (1 = Publicado, 0 = Borrador)',
    `post_date` DATETIME NOT NULL COMMENT 'Fecha de publicación',
    `post_update` DATETIME NOT NULL COMMENT 'Fecha de la ultima actualización',
    `post_contents` LONGTEXT NOT NULL COMMENT 'Contenido del artículo',
    `post_comment_status` TINYINT NOT NULL COMMENT 'Estado de comentarios, (1 = habilitado, 0 = deshabilitado)',
    `post_comment_count` INT NOT NULL COMMENT 'Total de comentarios',
    `user_ID` INT NOT NULL,
    PRIMARY KEY (`ID`, `user_ID`),
    CONSTRAINT `fk_post_user_id`
    FOREIGN KEY (`user_ID`)
    REFERENCES `#{PREFIX}users` (`ID`)
        ON DELETE RESTRICT
        ON UPDATE NO ACTION)
    ENGINE = InnoDB;

CREATE INDEX `fk_post_user_id` ON `#{PREFIX}posts` (`user_ID` ASC);


-- -----------------------------------------------------
-- Table `#{PREFIX}options`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}options` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `option_name` VARCHAR(64) NOT NULL,
    `option_value` LONGTEXT NOT NULL,
    PRIMARY KEY (`ID`))
    ENGINE = InnoDB;

CREATE UNIQUE INDEX `option_name_UNIQUE` ON `#{PREFIX}options` (`option_name` ASC);


-- -----------------------------------------------------
-- Table `#{PREFIX}comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}comments` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `comment_status` TINYINT NOT NULL COMMENT 'Estado del comentario. (1 = aprobado, 0 = sin aprobar)',
    `comment_autor` VARCHAR(60) NOT NULL,
    `comment_author_email` VARCHAR(100) NOT NULL,
    `comment_date` DATETIME NOT NULL,
    `comment_contents` TEXT NOT NULL COMMENT 'Contenido del comentario',
    `comment_user_ID` INT NULL COMMENT 'Si su valor es 0, el usuario no esta registrado en la pagina.',
    `post_ID` INT NOT NULL COMMENT 'Identificador del post',
    PRIMARY KEY (`ID`, `post_ID`),
    CONSTRAINT `fk_comments_post_id`
    FOREIGN KEY (`post_ID`)
    REFERENCES `#{PREFIX}posts` (`ID`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION)
    ENGINE = InnoDB;

CREATE INDEX `fk_comments_post_id` ON `#{PREFIX}comments` (`post_ID` ASC);


-- -----------------------------------------------------
-- Table `#{PREFIX}terms`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}terms` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `term_name` VARCHAR(60) NOT NULL,
    `term_description` TEXT NULL,
    `term_post_count` INT NOT NULL DEFAULT 0,
    PRIMARY KEY (`ID`))
    ENGINE = InnoDB;

CREATE UNIQUE INDEX `term_name_UNIQUE` ON `#{PREFIX}terms` (`term_name` ASC);


-- -----------------------------------------------------
-- Table `#{PREFIX}posts_terms`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}posts_terms` (
    `post_ID` INT NOT NULL,
    `term_ID` INT NOT NULL,
    PRIMARY KEY (`post_ID`, `term_ID`),
    CONSTRAINT `fk_terms_post_id`
    FOREIGN KEY (`post_ID`)
    REFERENCES `#{PREFIX}posts` (`ID`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION,
    CONSTRAINT `fk_post_terms_id`
    FOREIGN KEY (`term_ID`)
    REFERENCES `#{PREFIX}terms` (`ID`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION)
    ENGINE = InnoDB
    COMMENT = 'Relaciones entre posts y etiquetas';

CREATE INDEX `fk_posts_terms_id` ON `#{PREFIX}posts_terms` (`term_ID` ASC);

CREATE INDEX `fk_terms_posts_id` ON `#{PREFIX}posts_terms` (`post_ID` ASC);


-- -----------------------------------------------------
-- Table `#{PREFIX}categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}categories` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `category_name` VARCHAR(60) NOT NULL,
    `category_description` TEXT NULL,
    `category_post_count` INT NOT NULL,
    PRIMARY KEY (`ID`))
    ENGINE = InnoDB;

CREATE UNIQUE INDEX `category_name_UNIQUE` ON `#{PREFIX}categories` (`category_name` ASC);


-- -----------------------------------------------------
-- Table `#{PREFIX}posts_categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}posts_categories` (
    `post_ID` INT NOT NULL,
    `category_ID` INT NOT NULL,
    PRIMARY KEY (`post_ID`, `category_ID`),
    CONSTRAINT `fk_categories_post_id`
    FOREIGN KEY (`post_ID`)
    REFERENCES `#{PREFIX}posts` (`ID`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION,
    CONSTRAINT `fk_post_categories_id`
    FOREIGN KEY (`category_ID`)
    REFERENCES `#{PREFIX}categories` (`ID`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION)
    ENGINE = InnoDB
    COMMENT = 'Relaciones entre post y categorias';

CREATE INDEX `fk_posts_categories_id` ON `#{PREFIX}posts_categories` (`category_ID` ASC);

CREATE INDEX `fk_categories_posts_id` ON `#{PREFIX}posts_categories` (`post_ID` ASC);


-- -----------------------------------------------------
-- Table `#{PREFIX}sidebars`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}sidebars` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `sidebar_title` VARCHAR(60) NULL,
    `sidebar_contents` LONGTEXT NULL,
    `sidebar_position` SMALLINT(2) NOT NULL COMMENT 'Establece la posición en la que se mostrara el contenido',
    PRIMARY KEY (`ID`))
    ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `#{PREFIX}menus`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}menus` (
    `ID` INT NOT NULL AUTO_INCREMENT,
    `menu_title` VARCHAR(60) NOT NULL,
    `menu_url` VARCHAR(100) NULL,
    `menu_sub` INT NOT NULL COMMENT 'Identificador del elemento padre, si se da el caso',
    `menu_position` INT NOT NULL COMMENT 'Indica la posición del menu',
    `menu_total_children` INT NOT NULL,
    PRIMARY KEY (`ID`))
    ENGINE = InnoDB;

CREATE UNIQUE INDEX `menu_name_UNIQUE` ON `#{PREFIX}menus` (`menu_name` ASC);

-- -----------------------------------------------------
-- Data for table `#{PREFIX}options`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `#{PREFIX}options` (`ID`, `option_name`, `option_value`) VALUES (DEFAULT, 'optionTitle', 'Lorem Ipsum');
INSERT INTO `#{PREFIX}options` (`ID`, `option_name`, `option_value`) VALUES (DEFAULT, 'optionDescription', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec mollis.');
INSERT INTO `#{PREFIX}options` (`ID`, `option_name`, `option_value`) VALUES (DEFAULT, 'optionPaged', '10');
INSERT INTO `#{PREFIX}options` (`ID`, `option_name`, `option_value`) VALUES (DEFAULT, 'optionSiteUrl', '#{SITE_URL}');
INSERT INTO `#{PREFIX}options` (`ID`, `option_name`, `option_value`) VALUES (DEFAULT, 'optionTheme', 'default');
INSERT INTO `#{PREFIX}options` (`ID`, `option_name`, `option_value`) VALUES (DEFAULT, 'optionMenu', '0');
INSERT INTO `#{PREFIX}options` (`ID`, `option_name`, `option_value`) VALUES (DEFAULT, 'optionEmailAdmin', 'localhost@localhost.com');

COMMIT;
