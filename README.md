
# MétéoExpress

## Auteurs

- [Pierre Gouedar](https://www.github.com/pierregouedar)

## Sommaire 

- [Description de la réalisation du projet](#description-de-la-réalisation-du-projet)
- [Réalisations annexes](#réalisations-annexes)
  
  - [MCD](#mcd)
  - [UC](#diagramme-de-cas-dutilisation-use-case)
  - [Dump de la base de données](#dump-de-la-base-de-données)
- [Installation](#installation)
- [Utilisation](#utilisation)

## Description de la réalisation du projet

- Composants symfony utilisés :

    - EasyAdmin
    - Foundry

- Outils utilisés :

    - PhpStorm
    - Git

- Difficultés rencontrées :

    - Problème de conflits entre les versions des composants symfony installés

## Réalisations annexes

### MCD

![](MCD_UC/MCD_Meteo.png)

### Diagramme de cas d'utilisation (use-case)

![](MCD_UC/UC_Meteo.png)

### Dump de la base de données

```sql
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `city` varchar(128) DEFAULT NULL,
  `postcode` int(11) DEFAULT NULL,
  `house_number` varchar(8) DEFAULT NULL,
  `street` varchar(1024) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `address` (`id`, `latitude`, `longitude`, `city`, `postcode`, `house_number`, `street`, `user_id`, `type`) VALUES
(1, 48.95355, 4.365717, 'Châlons-en-Champagne', 51000, NULL, NULL, 1, 'municipality');

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20240105144536', '2024-01-10 17:08:10', 88),
('DoctrineMigrations\\Version20240106181024', '2024-01-10 17:08:10', 35),
('DoctrineMigrations\\Version20240106185002', '2024-01-10 17:08:10', 4);

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) NOT NULL,
  `first_name` varchar(64) NOT NULL,
  `last_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `first_name`, `last_name`) VALUES
(1, 'root@example.com', '[\"ROLE_ADMIN\"]', '$2y$13$7gp6lsh.HROuD.CjyR0yBuwhuEOWzMpc.ZpJvD8jWKZJpFFIfNYWS', 'Tony', 'Stark'),
(2, 'user@example.com', '[\"ROLE_USER\"]', '$2y$13$yvk9ie1MCDFW9QohxAedWercKXE1Q10W4aA8sdxLS9ay2gtaWVI2W', 'Peter', 'Parker');

ALTER TABLE `address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D4E6F81A76ED395` (`user_id`);

ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `address`
  ADD CONSTRAINT `FK_D4E6F81A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;
```

## Installation

- Avant d'installer ce projet, veuillez créer une copie du fichier `.env` en `.env.local` et configurer une base de données MySQL dans ce fichier en éditant la ligne : `DATABASE_URL="mysql://!user!:!password!@!IP!:3306/!DB_Name!?serverVersion=mariadb-10.2.25" `

- Assurez-vous également d'avoir PHP (version 8.1 et ultérieure) et Composer pour pouvoir installer le projet.

Pour installer les dépendances nécessaires au projet, exécutez la commande suivante :

```bash
  composer install
```

Pour initialiser automatiquement votre base de données, exécutez la commande suivante :
```bash
    composer db
```

Ensuite pour lancer le projet, exécutez la commande suivante :
```bash
    symfony serve
```
## Utilisation

Lors de l'initialisation de la base de données deux comptes utilisateur sont créé :


- E-mail : root@example.com
- Mot de passe : default
- Rôle : Administrateur

---

- E-mail : user@example.com
- Mot de passe : default
- Rôle : utilisateur

Avec le compte administrateur, vous avez accès au panneau d'administration de l'application vous permettant d'ajouter manuellement des utilisateur et des adresses. 