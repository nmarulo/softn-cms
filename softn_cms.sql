-- -----------------------------------------------------
-- Table `#{PREFIX}profiles`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}profiles` (
    `id`                  INT         NOT NULL AUTO_INCREMENT,
    `profile_name`        VARCHAR(45) NOT NULL,
    `profile_description` TEXT        NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB;

CREATE UNIQUE INDEX `profile_name_UNIQUE`
    ON `#{PREFIX}profiles` (`profile_name` ASC);

-- -----------------------------------------------------
-- Table `#{PREFIX}users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}users` (
    `id`              INT          NOT NULL AUTO_INCREMENT,
    `user_login`      VARCHAR(60)  NOT NULL
    COMMENT 'nombre usado para acceder',
    `user_name`       VARCHAR(50)  NOT NULL
    COMMENT 'Nombre del usuario',
    `user_email`      VARCHAR(100) NOT NULL
    COMMENT 'Correo electrónico',
    `user_password`   VARCHAR(64)  NOT NULL
    COMMENT 'Contraseña',
    `user_registered` DATETIME     NOT NULL
    COMMENT 'Fecha de registro',
    `user_url`        VARCHAR(100) NULL,
    `user_post_count` INT          NOT NULL,
    `profile_id`      INT          NOT NULL,
    PRIMARY KEY (`id`, `profile_id`),
    CONSTRAINT `fk_users_profiles`
    FOREIGN KEY (`profile_id`)
    REFERENCES `#{PREFIX}profiles` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

CREATE UNIQUE INDEX `user_email_UNIQUE`
    ON `#{PREFIX}users` (`user_email` ASC);

CREATE UNIQUE INDEX `user_login_UNIQUE`
    ON `#{PREFIX}users` (`user_login` ASC);

CREATE INDEX `fk_users_profile_id`
    ON `#{PREFIX}users` (`profile_id` ASC);

-- -----------------------------------------------------
-- Table `#{PREFIX}posts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}posts` (
    `id`                  INT         NOT NULL AUTO_INCREMENT,
    `post_title`          VARCHAR(45) NOT NULL
    COMMENT 'Título del artículo',
    `post_status`         TINYINT     NOT NULL
    COMMENT 'Estado de publicación. (1 = Publicado, 0 = Borrador)',
    `post_date`           DATETIME    NOT NULL
    COMMENT 'Fecha de publicación',
    `post_update`         DATETIME    NOT NULL
    COMMENT 'Fecha de la ultima actualización',
    `post_contents`       LONGTEXT    NOT NULL
    COMMENT 'Contenido del artículo',
    `post_comment_status` TINYINT     NOT NULL
    COMMENT 'Estado de comentarios, (1 = habilitado, 0 = deshabilitado)',
    `post_comment_count`  INT         NOT NULL
    COMMENT 'Total de comentarios',
    `user_id`             INT         NOT NULL,
    PRIMARY KEY (`id`, `user_id`),
    CONSTRAINT `fk_posts_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `#{PREFIX}users` (`id`)
        ON DELETE RESTRICT
        ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

CREATE INDEX `fk_posts_user_id`
    ON `#{PREFIX}posts` (`user_id` ASC);

-- -----------------------------------------------------
-- Table `#{PREFIX}options`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}options` (
    `id`           INT         NOT NULL AUTO_INCREMENT,
    `option_name`  VARCHAR(64) NOT NULL,
    `option_value` LONGTEXT    NOT NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB;

CREATE UNIQUE INDEX `option_name_UNIQUE`
    ON `#{PREFIX}options` (`option_name` ASC);

-- -----------------------------------------------------
-- Table `#{PREFIX}comments`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}comments` (
    `id`                   INT          NOT NULL AUTO_INCREMENT,
    `comment_status`       TINYINT      NOT NULL
    COMMENT 'Estado del comentario. (1 = aprobado, 0 = sin aprobar)',
    `comment_autor`        VARCHAR(60)  NOT NULL,
    `comment_author_email` VARCHAR(100) NOT NULL,
    `comment_date`         DATETIME     NOT NULL,
    `comment_contents`     TEXT         NOT NULL
    COMMENT 'Contenido del comentario',
    `comment_user_id`      INT          NULL
    COMMENT 'Si su valor es 0, el usuario no esta registrado en la pagina.',
    `post_id`              INT          NOT NULL
    COMMENT 'Identificador del post',
    PRIMARY KEY (`id`, `post_id`),
    CONSTRAINT `fk_comments_posts`
    FOREIGN KEY (`post_id`)
    REFERENCES `#{PREFIX}posts` (`id`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

CREATE INDEX `fk_comments_posts`
    ON `#{PREFIX}comments` (`post_id` ASC);

-- -----------------------------------------------------
-- Table `#{PREFIX}terms`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}terms` (
    `id`               INT         NOT NULL AUTO_INCREMENT,
    `term_name`        VARCHAR(60) NOT NULL,
    `term_description` TEXT        NULL,
    `term_post_count`  INT         NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB;

CREATE UNIQUE INDEX `term_name_UNIQUE`
    ON `#{PREFIX}terms` (`term_name` ASC);

-- -----------------------------------------------------
-- Table `#{PREFIX}posts_terms`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}posts_terms` (
    `post_id` INT NOT NULL,
    `term_id` INT NOT NULL,
    PRIMARY KEY (`post_id`, `term_id`),
    CONSTRAINT `fk_posts_terms_posts`
    FOREIGN KEY (`post_id`)
    REFERENCES `#{PREFIX}posts` (`id`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION,
    CONSTRAINT `fk_posts_terms_terms`
    FOREIGN KEY (`term_id`)
    REFERENCES `#{PREFIX}terms` (`id`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION
)
    ENGINE = InnoDB
    COMMENT = 'Relaciones entre posts y etiquetas';

CREATE INDEX `fk_posts_terms_term_id`
    ON `#{PREFIX}posts_terms` (`term_id` ASC);

CREATE INDEX `fk_terms_posts_post_id`
    ON `#{PREFIX}posts_terms` (`post_id` ASC);

-- -----------------------------------------------------
-- Table `#{PREFIX}categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}categories` (
    `id`                   INT         NOT NULL AUTO_INCREMENT,
    `category_name`        VARCHAR(60) NOT NULL,
    `category_description` TEXT        NULL,
    `category_post_count`  INT         NOT NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB;

CREATE UNIQUE INDEX `category_name_UNIQUE`
    ON `#{PREFIX}categories` (`category_name` ASC);

-- -----------------------------------------------------
-- Table `#{PREFIX}posts_categories`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}posts_categories` (
    `post_id`     INT NOT NULL,
    `category_id` INT NOT NULL,
    PRIMARY KEY (`post_id`, `category_id`),
    CONSTRAINT `fk_posts_categories_posts`
    FOREIGN KEY (`post_id`)
    REFERENCES `#{PREFIX}posts` (`id`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION,
    CONSTRAINT `fk_posts_categories_categories`
    FOREIGN KEY (`category_id`)
    REFERENCES `#{PREFIX}categories` (`id`)
        ON DELETE CASCADE
        ON UPDATE NO ACTION
)
    ENGINE = InnoDB
    COMMENT = 'Relaciones entre post y categorías';

CREATE INDEX `fk_posts_categories_category_id`
    ON `#{PREFIX}posts_categories` (`category_id` ASC);

CREATE INDEX `fk_posts_categories_post_id`
    ON `#{PREFIX}posts_categories` (`post_id` ASC);

-- -----------------------------------------------------
-- Table `#{PREFIX}sidebars`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}sidebars` (
    `id`               INT         NOT NULL AUTO_INCREMENT,
    `sidebar_title`    VARCHAR(60) NULL,
    `sidebar_contents` LONGTEXT    NULL,
    `sidebar_position` SMALLINT(2) NOT NULL
    COMMENT 'Establece la posición en la que se mostrara el contenido',
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `#{PREFIX}menus`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}menus` (
    `id`                  INT          NOT NULL AUTO_INCREMENT,
    `menu_title`          VARCHAR(60)  NOT NULL,
    `menu_url`            VARCHAR(100) NULL,
    `menu_sub`            INT          NOT NULL
    COMMENT 'Identificador del elemento padre, si se da el caso',
    `menu_position`       INT          NOT NULL
    COMMENT 'Indica la posición del menu',
    `menu_total_children` INT          NOT NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `#{PREFIX}licenses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}licenses` (
    `id`                  INT         NOT NULL AUTO_INCREMENT,
    `license_name`        VARCHAR(45) NOT NULL,
    `license_description` TEXT        NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB
    COMMENT = 'Permisos de los usuarios.';

CREATE UNIQUE INDEX `license_name_UNIQUE`
    ON `#{PREFIX}licenses` (`license_name` ASC);

-- -----------------------------------------------------
-- Table `#{PREFIX}pages`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}pages` (
    `id`                  INT         NOT NULL AUTO_INCREMENT,
    `page_title`          VARCHAR(45) NOT NULL,
    `page_status`         TINYINT     NOT NULL,
    `page_date`           DATETIME    NOT NULL,
    `page_contents`       LONGTEXT    NULL,
    `page_comment_status` TINYINT     NULL,
    `page_comment_count`  INT         NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `#{PREFIX}options_licenses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}options_licenses` (
    `id`                             INT         NOT NULL AUTO_INCREMENT,
    `option_license_controller_name` VARCHAR(45) NOT NULL,
    `option_license_method_name`     VARCHAR(45) NOT NULL,
    `option_license_can_insert`      TINYINT     NULL,
    `option_license_can_update`      TINYINT     NULL,
    `option_license_can_delete`      TINYINT     NULL,
    `option_license_fields_name`     TEXT        NULL,
    `license_id`                     INT         NOT NULL,
    PRIMARY KEY (`id`, `license_id`),
    CONSTRAINT `fk_options_licenses_licenses`
    FOREIGN KEY (`license_id`)
    REFERENCES `#{PREFIX}licenses` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

CREATE INDEX `fk_options_licenses_license_id`
    ON `#{PREFIX}options_licenses` (`license_id` ASC);
-- -----------------------------------------------------
-- Table `#{PREFIX}profiles_licenses`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#{PREFIX}profiles_licenses` (
    `profile_id` INT NOT NULL,
    `license_id` INT NOT NULL,
    PRIMARY KEY (`profile_id`, `license_id`),
    CONSTRAINT `fk_profiles_licenses_profiles`
    FOREIGN KEY (`profile_id`)
    REFERENCES `#{PREFIX}profiles` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION,
    CONSTRAINT `fk_profiles_licenses_licenses`
    FOREIGN KEY (`license_id`)
    REFERENCES `#{PREFIX}licenses` (`id`)
        ON DELETE NO ACTION
        ON UPDATE NO ACTION
)
    ENGINE = InnoDB;

CREATE INDEX `fk_profiles_licenses_license_id`
    ON `#{PREFIX}profiles_licenses` (`license_id` ASC);

CREATE INDEX `fk_profiles_licenses_profile_id`
    ON `#{PREFIX}profiles_licenses` (`profile_id` ASC);
-- -----------------------------------------------------
-- Data for table `#{PREFIX}options`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `#{PREFIX}options` (`id`, `option_name`, `option_value`) VALUES (DEFAULT, 'optionTitle', 'Lorem Ipsum');
INSERT INTO `#{PREFIX}options` (`id`, `option_name`, `option_value`)
VALUES (DEFAULT, 'optionDescription', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec mollis.');
INSERT INTO `#{PREFIX}options` (`id`, `option_name`, `option_value`) VALUES (DEFAULT, 'optionPaged', '10');
INSERT INTO `#{PREFIX}options` (`id`, `option_name`, `option_value`)
VALUES (DEFAULT, 'optionSiteUrl', 'http://localhost/');
INSERT INTO `#{PREFIX}options` (`id`, `option_name`, `option_value`) VALUES (DEFAULT, 'optionTheme', 'default');
INSERT INTO `#{PREFIX}options` (`id`, `option_name`, `option_value`) VALUES (DEFAULT, 'optionMenu', '0');
INSERT INTO `#{PREFIX}options` (`id`, `option_name`, `option_value`)
VALUES (DEFAULT, 'optionEmailAdmin', 'localhost@localhost.com');

COMMIT;
