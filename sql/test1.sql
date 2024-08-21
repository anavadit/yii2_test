CREATE TABLE `article_views` (
    `article_id` int(10) unsigned NOT NULL,
    `user_id` int(10) unsigned NOT NULL DEFAULT 0,
    `date` date,
    `count_views` smallint(6) unsigned NOT NULL DEFAULT 0,
    CONSTRAINT pk_av_id PRIMARY KEY (`article_id`, `user_id`, `date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `articles`  (
  `id` int(0) NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `articles` (content) VALUES ('text1');
INSERT INTO `articles` (content) VALUES ('text2');
INSERT INTO `articles` (content) VALUES ('text3');
INSERT INTO `articles` (content) VALUES ('text4');
INSERT INTO `articles` (content) VALUES ('text5');

