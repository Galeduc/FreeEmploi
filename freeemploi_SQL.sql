-- Adminer 4.8.1 MySQL 10.4.32-MariaDB dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `admins` (`id`, `pseudo`, `password`) VALUES
(1,	'admin',	'$2y$10$PRO5Gv4AOHAafcsqjd7xR.1sF//nNEmNsCfSTCO5YlrXw.F86wThm');

DROP TABLE IF EXISTS `annonces`;
CREATE TABLE `annonces` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `banniere` varchar(255) DEFAULT NULL,
  `salaire` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pro_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pro_id` (`pro_id`),
  CONSTRAINT `annonces_ibfk_1` FOREIGN KEY (`pro_id`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `annonces` (`id`, `titre`, `description`, `banniere`, `salaire`, `created_at`, `pro_id`) VALUES
(3,	'f',	'f',	'img_annonce/annonce_banniere_665da22c163401.71258771.png',	44.00,	'2024-06-03 10:59:56',	4),
(4,	'yhtjntfg',	'hrtgbfrdtfth',	'img_annonce/annonce_banniere_665db800a20535.59656775.png',	4355.00,	'2024-06-03 12:33:04',	4),
(6,	'egfdrvssdefrgvfdx',	'gfdesrvvvfdfderxvg',	'img_annonce/annonce_banniere_66696e971f0be4.77017518.png',	3245332.00,	'2024-06-12 09:47:03',	4),
(7,	'Auchan Recrute',	'rfdegvrteg\r\nCDI',	'img_annonce/annonce_banniere_666982bde30493.52969078.png',	1200.00,	'2024-06-12 11:13:01',	4),
(8,	'gfhder4s4hgt',	'rgevrdeg',	'img_annonce/annonce_banniere_66699dfa6ddd22.47978836.png',	21432.00,	'2024-06-12 13:09:14',	4),
(9,	'fderbgbfb',	'rtfdegbfertdgb',	'img_annonce/annonce_banniere_666c41793e4083.95609520.png',	13243.00,	'2024-06-14 13:11:21',	4);

DROP TABLE IF EXISTS `archives`;
CREATE TABLE `archives` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `code_postal` varchar(10) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `archives` (`id`, `nom`, `adresse`, `ville`, `code_postal`, `telephone`, `email`, `message`, `date_creation`) VALUES
(1,	'rvfd',	'vf dc',	'vdsfxz',	'svdxc ',	'5435345534',	'tg@gmail.com',	'ferdevfgredfverfvreds',	'2024-06-05 09:44:02'),
(2,	'juhn',	'hjun',	'jyu',	'jyu',	'65475467',	'dwef@gmail.com',	'grtfegtrgt',	'2024-06-05 09:57:29');

DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE `commentaires` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `annonce_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL,
  `contenu` text NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `annonce_id` (`annonce_id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  CONSTRAINT `commentaires_ibfk_1` FOREIGN KEY (`annonce_id`) REFERENCES `annonces` (`id`),
  CONSTRAINT `commentaires_ibfk_2` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `commentaires` (`id`, `annonce_id`, `utilisateur_id`, `contenu`, `date_creation`, `created_at`) VALUES
(11,	3,	12,	'Ceci est un test',	'2024-06-03 12:08:02',	'2024-06-03 12:08:02'),
(12,	3,	12,	'frr',	'2024-06-03 12:26:54',	'2024-06-03 12:26:54'),
(13,	3,	12,	'frr',	'2024-06-03 12:31:23',	'2024-06-03 12:31:23'),
(14,	3,	12,	'ff',	'2024-06-03 12:31:31',	'2024-06-03 12:31:31'),
(15,	3,	12,	'fgbgg',	'2024-06-03 13:11:01',	'2024-06-03 13:11:01'),
(16,	3,	12,	'nnnnnnnnnnn',	'2024-06-03 13:11:14',	'2024-06-03 13:11:14');

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `code_postal` varchar(10) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


DROP TABLE IF EXISTS `likes`;
CREATE TABLE `likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int(11) NOT NULL,
  `annonce_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `utilisateur_id` (`utilisateur_id`),
  KEY `annonce_id` (`annonce_id`),
  CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`),
  CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`annonce_id`) REFERENCES `annonces` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `likes` (`id`, `utilisateur_id`, `annonce_id`) VALUES
(12,	9,	4),
(14,	12,	4);

DROP TABLE IF EXISTS `professionnels`;
CREATE TABLE `professionnels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `societe` varchar(100) NOT NULL,
  `email_pro` varchar(255) NOT NULL,
  `siret` varchar(14) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `profil_image` varchar(255) DEFAULT NULL,
  `creation` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_pro` (`email_pro`),
  UNIQUE KEY `siret` (`siret`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `professionnels` (`id`, `societe`, `email_pro`, `siret`, `mdp`, `profil_image`, `creation`) VALUES
(1,	'grtfde',	'frdgbd@gmail.com',	'2313545465464',	'$2y$10$JpZLad6p5c00d9OkslwJq.FvBXPYv8mJ1OWcYzu7uHbvbjTOtSzqC',	NULL,	'2024-05-29 10:01:18'),
(2,	'cc',	'cc@gmail.com',	'25465466434',	'$2y$10$YHlGXGruhbdmm0Ucc7m.V.HdCO9C3BQ2k.QzrSQqa2lA4LGDEY2tO',	NULL,	'2024-05-29 10:01:43'),
(4,	'ccc',	'getgetduc@gmail.com',	'46745847547',	'$2y$10$NSGxABRNcLqVw2sSBxkBUe2UCVdxqPEvA.if8Lyc13n2ZB4gZ3VuG',	'img_profil/lidl.png',	'2024-05-29 10:03:02');

DROP TABLE IF EXISTS `reponse`;
CREATE TABLE `reponse` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `cover_letter_path` varchar(255) DEFAULT NULL,
  `annonce_id` int(11) DEFAULT NULL,
  `pro_id` int(11) DEFAULT NULL,
  `accepted` tinyint(1) DEFAULT 0,
  `date_envoi` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `reponse_ibfk_1` (`pro_id`),
  CONSTRAINT `reponse_ibfk_1` FOREIGN KEY (`pro_id`) REFERENCES `professionnels` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `reponse` (`id`, `name`, `email`, `cv_path`, `cover_letter_path`, `annonce_id`, `pro_id`, `accepted`, `date_envoi`) VALUES
(22,	'Gaetan Viaud',	'getgetduc@gmail.com',	'uploads/transfert_free.pdf',	'uploads/transfert_free.pdf',	4,	4,	1,	'2024-06-05 10:17:07');

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `profil_image` varchar(255) DEFAULT NULL,
  `date_inscription` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `email`, `mdp`, `profil_image`, `date_inscription`) VALUES
(1,	'few',	'fvdeg',	'fder@gmail.com',	'$2y$10$JzytbZdqh4gz7MvybKzWN.H62FFOkCR3qZ/m2Ol9dsrqANWGrk.HC',	NULL,	'2024-05-28 12:24:05'),
(2,	'p',	'p',	'p@gmail.com',	'$2y$10$Yd/aY7.6cZUHptEvN4hj..dx2eryHEHFqYhZe2t9n4TjBwAANn8xm',	NULL,	'2024-05-28 12:29:52'),
(3,	'hbtr',	'hrfb',	'frbdhxn@gmail.com',	'$2y$10$ayt8F7Uy1ronycAgBitGyOHyRWg31S4nTV4WH0onXTrYtyege8Xki',	NULL,	'2024-05-28 12:31:50'),
(4,	'fwsedc',	'aDCSQF',	'dcaswqX@GMAIL.COM',	'$2y$10$Vc/p/2mTLeGEtS1OH/pjlueHJwwVJHkD96VGO9AlLQGA8IQ6SKlt.',	NULL,	'2024-05-28 12:35:40'),
(5,	'trgfhdv',	'rdhb',	'dhb@gmail.com',	'$2y$10$HtHb3/i5.EQEeGeU/szI0esVu/GufvdbAt.kFrmR.Zhpw1Opz1EHe',	NULL,	'2024-05-28 12:38:02'),
(6,	'test',	'test',	'test@gmail.com',	'$2y$10$y8NdqGUGdkiSHghaATE63uHOtRoFGqTRJm5ycmI8/HlkHi9M/E/r2',	NULL,	'2024-05-28 12:41:34'),
(7,	'dw',	'dwq',	'dwq@gmail.com',	'$2y$10$hhahJbBKg7XSkAYJt/1UgOwOtRM//5sHyMn/1luqbj3DRrsarUo6u',	NULL,	'2024-05-28 12:42:14'),
(8,	'fe',	'fe',	'few@gmail.com',	'$2y$10$xARkaSw4xWbdSu62RTX0Du9x0UiAFs916cVJ7e35EiMqJyK8.ax42',	NULL,	'2024-05-28 12:48:16'),
(9,	'tt',	'tt',	'iaia4gaetan@gmail.com',	'$2y$10$GkpThAXNQ/I1imS1teGP3O8eWl57p.MtlftEcDVQmob8P.S2x/CeK',	'img_profil/background.png',	'2024-05-29 06:57:51'),
(10,	'd',	'd',	't@gmail.com',	'$2y$10$V9L4CrqQURXflncNvMCjf.zNKnba/l3cW/rDEjqg5VmOvgPxB1ICq',	NULL,	'2024-05-30 09:54:45'),
(11,	'u',	'u',	'u@gmail.com',	'$2y$10$qW5W3kbv.DQH5ZvoJIkExezp4Ha05Gr2M5JKErlMiMw4njgsPgUby',	NULL,	'2024-05-30 09:55:04'),
(12,	'Duc',	'Gale',	'getgetduc@gmail.com',	'$2y$10$/lM7C5DY9lndpZ.f6J/acOd/fYXzU6q9RcgCCr4vHobD.xZjALw2y',	'img_profil/yahoo.png',	'2024-05-30 11:54:14');

DROP TABLE IF EXISTS `visites`;
CREATE TABLE `visites` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL,
  `date_visite` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
