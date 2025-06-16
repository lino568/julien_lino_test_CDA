-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : mysql:3306
-- Généré le : ven. 13 juin 2025 à 13:11
-- Version du serveur : 8.0.41
-- Version de PHP : 8.2.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `reservation_materiel_greta`
--

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `idCategorie` int NOT NULL,
  `libelle` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`idCategorie`, `libelle`) VALUES
(1, 'Électronique'),
(2, 'Affichage'),
(3, 'Audio');

-- --------------------------------------------------------

--
-- Structure de la table `materiels`
--

CREATE TABLE `materiels` (
  `idMateriels` int NOT NULL,
  `reference` varchar(50) DEFAULT NULL,
  `etat` varchar(50) NOT NULL,
  `idTypeMateriel` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `materiels`
--

INSERT INTO `materiels` (`idMateriels`, `reference`, `etat`, `idTypeMateriel`) VALUES
(2, 'RET253-53', 'disponible', 3),
(3, 'ENC457-32', 'disponible', 1),
(4, 'ENC459-34', 'disponible', 1),
(5, 'RET420-10', 'disponible', 3),
(6, 'RET255-62', 'disponible', 3),
(7, 'RET700-50', 'disponible', 3),
(8, 'TAB101-01', 'disponible', 2),
(9, 'TAB255-32', 'disponible', 2),
(10, 'TAB205-02', 'disponible', 2),
(11, 'TAB400-12', 'disponible', 2);

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `idReservation` int NOT NULL,
  `dateDebutReservation` int NOT NULL,
  `dateFinReservation` int NOT NULL,
  `statut` varchar(50) NOT NULL,
  `idUtilisateur` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`idReservation`, `dateDebutReservation`, `dateFinReservation`, `statut`, `idUtilisateur`) VALUES
(8, 1749708000, 1749722400, 'en_cours', 2),
(11, 1750068000, 1750078800, 'en_cours', 4),
(12, 1749816000, 1749826800, 'en_cours', 4),
(13, 1749808800, 1749826800, 'en_cours', 2),
(16, 1750230000, 1750248000, 'en_cours', 2);

-- --------------------------------------------------------

--
-- Structure de la table `reservations_materiels`
--

CREATE TABLE `reservations_materiels` (
  `idReservation` int NOT NULL,
  `idMateriels` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `reservations_materiels`
--

INSERT INTO `reservations_materiels` (`idReservation`, `idMateriels`) VALUES
(8, 2),
(11, 2),
(13, 2),
(12, 3),
(16, 3),
(12, 4),
(8, 5),
(11, 5),
(8, 6),
(11, 6),
(8, 7),
(11, 7);

-- --------------------------------------------------------

--
-- Structure de la table `typeMateriels`
--

CREATE TABLE `typeMateriels` (
  `idTypeMateriel` int NOT NULL,
  `libelle` varchar(80) NOT NULL,
  `quantite` int NOT NULL,
  `idCategorie` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `typeMateriels`
--

INSERT INTO `typeMateriels` (`idTypeMateriel`, `libelle`, `quantite`, `idCategorie`) VALUES
(1, 'Enceinte', 10, 3),
(2, 'Tablette Lenovo', 15, 1),
(3, 'vidéoprojecteur', 4, 2);

-- --------------------------------------------------------

--
-- Structure de la table `Utilisateurs`
--

CREATE TABLE `Utilisateurs` (
  `idUtilisateur` int NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `email` varchar(80) NOT NULL,
  `motDePasse` varchar(80) NOT NULL,
  `role` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `Utilisateurs`
--

INSERT INTO `Utilisateurs` (`idUtilisateur`, `nom`, `prenom`, `email`, `motDePasse`, `role`) VALUES
(1, 'Lino', 'Julien', 'test@gmail.com', '$2y$10$Tn77uPnt1dGjw87vqB0PoeRONT.RkbPTT6uCDfBxyBnZq6SrH7HrO', 'Administrateur'),
(2, 'Lino', 'Julien', 'test0@gmail.com', '$2y$10$Tn77uPnt1dGjw87vqB0PoeRONT.RkbPTT6uCDfBxyBnZq6SrH7HrO', 'Enseignant'),
(3, 'LINO', 'julien', 'john@example.com', '$2y$10$hEKQu.9S6W0EjM9ktUAxBukCcaQzpXYdZw58KPTbZRcshJxhDbZyi', 'Administrateur'),
(4, 'Le fèvre', 'Pierre', 'prof@gmail.com', '$2y$10$IIXBdU2Sl3MvE5nmvipcieSVJSKbhrOr7l5RHEXv48qYoQCwJ/Ki2', 'Enseignant');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`idCategorie`);

--
-- Index pour la table `materiels`
--
ALTER TABLE `materiels`
  ADD PRIMARY KEY (`idMateriels`),
  ADD UNIQUE KEY `reference` (`reference`),
  ADD KEY `idTypeMateriel` (`idTypeMateriel`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`idReservation`),
  ADD KEY `idUtilisateur` (`idUtilisateur`);

--
-- Index pour la table `reservations_materiels`
--
ALTER TABLE `reservations_materiels`
  ADD PRIMARY KEY (`idReservation`,`idMateriels`),
  ADD KEY `idMateriels` (`idMateriels`);

--
-- Index pour la table `typeMateriels`
--
ALTER TABLE `typeMateriels`
  ADD PRIMARY KEY (`idTypeMateriel`),
  ADD KEY `idCategorie` (`idCategorie`);

--
-- Index pour la table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  ADD PRIMARY KEY (`idUtilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `idCategorie` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `materiels`
--
ALTER TABLE `materiels`
  MODIFY `idMateriels` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `idReservation` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `typeMateriels`
--
ALTER TABLE `typeMateriels`
  MODIFY `idTypeMateriel` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `Utilisateurs`
--
ALTER TABLE `Utilisateurs`
  MODIFY `idUtilisateur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `materiels`
--
ALTER TABLE `materiels`
  ADD CONSTRAINT `materiels_ibfk_1` FOREIGN KEY (`idTypeMateriel`) REFERENCES `typeMateriels` (`idTypeMateriel`);

--
-- Contraintes pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`idUtilisateur`) REFERENCES `Utilisateurs` (`idUtilisateur`);

--
-- Contraintes pour la table `reservations_materiels`
--
ALTER TABLE `reservations_materiels`
  ADD CONSTRAINT `reservations_materiels_ibfk_1` FOREIGN KEY (`idReservation`) REFERENCES `reservations` (`idReservation`),
  ADD CONSTRAINT `reservations_materiels_ibfk_2` FOREIGN KEY (`idMateriels`) REFERENCES `materiels` (`idMateriels`);

--
-- Contraintes pour la table `typeMateriels`
--
ALTER TABLE `typeMateriels`
  ADD CONSTRAINT `typeMateriels_ibfk_1` FOREIGN KEY (`idCategorie`) REFERENCES `categories` (`idCategorie`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
