-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 03 Mai 2016 à 18:11
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `symfony`
--

-- --------------------------------------------------------

--
-- Structure de la table `oc_advert`
--

CREATE TABLE IF NOT EXISTS `oc_advert` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `image_id` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `published` tinyint(1) NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `nb_applications` int(11) NOT NULL,
  `slug` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B1931752B36786B` (`title`),
  UNIQUE KEY `UNIQ_B193175989D9B62` (`slug`),
  UNIQUE KEY `UNIQ_B1931753DA5256D` (`image_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Contenu de la table `oc_advert`
--

INSERT INTO `oc_advert` (`id`, `image_id`, `date`, `title`, `author`, `content`, `published`, `updated_at`, `nb_applications`, `slug`) VALUES
(3, 3, '2016-04-13 00:00:00', 'Recherche Developpeur Symfony 2', 'YoYo', 'test', 1, NULL, 0, 'recherche-developpeur-symfony-2');

-- --------------------------------------------------------

--
-- Structure de la table `oc_advert_oc_category`
--

CREATE TABLE IF NOT EXISTS `oc_advert_oc_category` (
  `advert_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`advert_id`,`category_id`),
  KEY `IDX_EA0C057D07ECCB6` (`advert_id`),
  KEY `IDX_EA0C05712469DE2` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `oc_advert_oc_category`
--

INSERT INTO `oc_advert_oc_category` (`advert_id`, `category_id`) VALUES
(3, 6);

-- --------------------------------------------------------

--
-- Structure de la table `oc_advert_oc_skill`
--

CREATE TABLE IF NOT EXISTS `oc_advert_oc_skill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advert_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `level` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F6622B89D07ECCB6` (`advert_id`),
  KEY `IDX_F6622B895585C142` (`skill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `oc_application`
--

CREATE TABLE IF NOT EXISTS `oc_application` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `advert_id` int(11) NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_39F85DD8D07ECCB6` (`advert_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `oc_category`
--

CREATE TABLE IF NOT EXISTS `oc_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

--
-- Contenu de la table `oc_category`
--

INSERT INTO `oc_category` (`id`, `name`) VALUES
(6, 'Développement web'),
(7, 'Développement mobile'),
(8, 'Graphisme'),
(9, 'Intégration'),
(10, 'Réseau');

-- --------------------------------------------------------

--
-- Structure de la table `oc_image`
--

CREATE TABLE IF NOT EXISTS `oc_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `alt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Contenu de la table `oc_image`
--

INSERT INTO `oc_image` (`id`, `url`, `alt`) VALUES
(3, 'svg', 'symfony.svg');

-- --------------------------------------------------------

--
-- Structure de la table `oc_skill`
--

CREATE TABLE IF NOT EXISTS `oc_skill` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Contenu de la table `oc_skill`
--

INSERT INTO `oc_skill` (`id`, `name`) VALUES
(8, 'PHP'),
(9, 'Symfony2'),
(10, 'C++'),
(11, 'Java'),
(12, 'Photoshop'),
(13, 'Blender'),
(14, 'Bloc-note');

-- --------------------------------------------------------

--
-- Structure de la table `oc_user`
--

CREATE TABLE IF NOT EXISTS `oc_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7866CFC992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_7866CFC9A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Contenu de la table `oc_user`
--

INSERT INTO `oc_user` (`id`, `username`, `password`, `salt`, `roles`, `username_canonical`, `email`, `email_canonical`, `enabled`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `credentials_expired`, `credentials_expire_at`) VALUES
(4, 'user', 'pBXKTrLkIZ5213M3knUDby0qwGDvjsspcMIU91180V3PE4W9FHKvgmJE1ocMGuXN7ryWLEzaFM6Zq7lbhs8f1Q==', '40533f6rvuw4k8s88g0wosk8oks8wko', 'a:0:{}', 'user', 'user@user.com', 'user@user.com', 1, '2016-04-14 23:21:47', 0, 0, NULL, NULL, NULL, 0, NULL),
(5, 'admin', 'q+/a00bZ6f1r+k1z19cHqCU2iY9/bg2k4GX+Vpgr+pBnsKxHcQ786tOI2tlakvNQdnSn+NHnKFkTEgUDVXkQrg==', '2bmg6i7pcxwk88oko8wkokc8wwk8w4s', 'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}', 'admin', 'admin@admin.com', 'admin@admin.com', 1, '2016-05-03 18:09:15', 0, 0, NULL, NULL, NULL, 0, NULL),
(6, 'anthonys', 'ygNvSxRru+Rl/JtxY1pS4EgoQOhLPS+Ag3YnpMOu/9lkFIdSb96R9Q1NsT1EBAN8nGaJm4++HIxfxvqoDZxxMg==', 'h3efc0s1r6okscg488k8goc0okckssc', 'a:0:{}', 'anthonys', 'anthonysitbon@gmail.com', 'anthonysitbon@gmail.com', 1, '2016-04-14 23:24:15', 0, 0, NULL, NULL, NULL, 0, NULL);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `oc_advert`
--
ALTER TABLE `oc_advert`
  ADD CONSTRAINT `FK_B1931753DA5256D` FOREIGN KEY (`image_id`) REFERENCES `oc_image` (`id`);

--
-- Contraintes pour la table `oc_advert_oc_category`
--
ALTER TABLE `oc_advert_oc_category`
  ADD CONSTRAINT `FK_EA0C05712469DE2` FOREIGN KEY (`category_id`) REFERENCES `oc_category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_EA0C057D07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `oc_advert` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `oc_advert_oc_skill`
--
ALTER TABLE `oc_advert_oc_skill`
  ADD CONSTRAINT `FK_F6622B895585C142` FOREIGN KEY (`skill_id`) REFERENCES `oc_skill` (`id`),
  ADD CONSTRAINT `FK_F6622B89D07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `oc_advert` (`id`);

--
-- Contraintes pour la table `oc_application`
--
ALTER TABLE `oc_application`
  ADD CONSTRAINT `FK_39F85DD8D07ECCB6` FOREIGN KEY (`advert_id`) REFERENCES `oc_advert` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
