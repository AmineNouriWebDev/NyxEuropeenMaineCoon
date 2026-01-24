-- Structure de la base de données pour Nyx European Maine Coon

-- Table des administrateurs
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertion d'un admin par défaut (Password: admin123)
-- Le hash doit être généré avec password_hash() en PHP, ceci est un placeholder si vous ne pouvez pas utiliser la page de création.
-- Mieux vaut utiliser le script d'installation ou créer un compte via une page dédiée si possible.
-- INSERT INTO `users` (`username`, `password`, `email`) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com');

-- Table des chats
DROP TABLE IF EXISTS `cat_images`; -- Supprimer d'abord car clé étrangère
DROP TABLE IF EXISTS `chats`;

CREATE TABLE `chats` (
  `id` varchar(50) NOT NULL, -- Identifiant textuel unique (ex: 'luna', 'thor') pour les URLs
  `name` varchar(100) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `birth_date` date DEFAULT NULL,
  `age_text` varchar(50) DEFAULT NULL, -- Pour "4 months" si on ne veut pas calculer
  `color` varchar(100) NOT NULL,
  `quality` varchar(100) DEFAULT 'Pet Quality',
  `weight` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `old_price` decimal(10,2) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `status` enum('available','reserved','sold') DEFAULT 'available',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table des images de chats
CREATE TABLE `cat_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cat_id` varchar(50) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `cat_id` (`cat_id`),
  CONSTRAINT `fk_cat_images` FOREIGN KEY (`cat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table du blog
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `excerpt` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `author_id` int(11) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Données de test pour les chats (Basé sur le fichier index.php actuel)
INSERT IGNORE INTO `chats` (`id`, `name`, `gender`, `age_text`, `color`, `quality`, `weight`, `price`, `old_price`, `video_url`) VALUES
('luna', 'Luna', 'Female', '4 months', 'Blue Smoke Tortie', 'Pet & Breeding Quality', 'Expected: 15-18 lbs', 2950.00, NULL, 'https://www.youtube.com/embed/g69awDfW054'),
('thor', 'Thor', 'Male', '5 months', 'Red Tabby', 'Pet Quality', 'Expected: 20-22 lbs', 3550.00, 3950.00, 'https://www.youtube.com/embed/-VwNjeZXsMY'),
('nala', 'Nala', 'Female', '2 months', 'Black Smoke', 'Breeding Quality', 'Expected: 14-16 lbs', 3750.00, NULL, 'https://www.youtube.com/embed/g_LNu6Aaxvk');

INSERT IGNORE INTO `cat_images` (`cat_id`, `image_path`, `sort_order`) VALUES
('luna', '1.jpg', 0), ('luna', '2.jpg', 1), ('luna', '3.jpg', 2),
('thor', '4.jpg', 0), ('thor', '5.jpg', 1), ('thor', '6.jpg', 2),
('nala', '7.jpg', 0), ('nala', '8.jpg', 1), ('nala', '9.jpg', 2);
