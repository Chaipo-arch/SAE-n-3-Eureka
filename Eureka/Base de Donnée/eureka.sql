-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 15 jan. 2024 à 16:28
-- Version du serveur : 5.7.11
-- Version de PHP : 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `eureka`
--
CREATE DATABASE IF NOT EXISTS `eureka` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `eureka`;

DELIMITER $$
--
-- Procédures
--
DROP PROCEDURE IF EXISTS `AjoutEntreprise`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AjoutEntreprise` (IN `p_designation` VARCHAR(100), IN `p_activity_sector` VARCHAR(100), IN `p_logo` TEXT, IN `p_presentation` TEXT, IN `p_ville` VARCHAR(25), IN `p_cp` INT(5), IN `p_adresse` VARCHAR(50))   BEGIN
    DECLARE entreprise_existante INT;
    DECLARE lieu_existant INT;
    DECLARE lieuid INT(15);
    DECLARE EntrepriseId INT(15);

    SELECT COUNT(*) INTO lieu_existant
    FROM lieu
    WHERE p_ville = lieu.ville AND p_cp = lieu.cp AND p_adresse = lieu.adresse;
    SELECT COUNT(*) INTO entreprise_existante
    FROM Entreprise
    WHERE p_designation = Designation AND logo = p_logo AND presentation = p_presentation;
    
    START TRANSACTION;   
    IF entreprise_existante < 1 THEN 
        INSERT INTO Entreprise(Designation,activity_sector,logo,presentation) Value(p_designation,p_activity_sector,p_logo,p_presentation);
        SELECT TRUE;
        IF lieu_existant < 1 THEN            
            INSERT INTO Lieu(ville,cp,adresse) Value(p_ville,p_cp,p_adresse);
        END IF; 
        SELECT MAX(id) INTO EntrepriseId FROM entreprise;
        SELECT id INTO lieuid FROM lieu WHERE p_ville = lieu.ville AND p_cp = lieu.cp AND p_adresse = lieu.adresse;
        INSERT INTO lieuEntreprise(id_entreprise,id_lieu)
        VALUES(EntrepriseId,lieuid);        
    ELSE 
        SELECT FALSE;
    END IF;
    COMMIT;
END$$

DROP PROCEDURE IF EXISTS `AjoutTime`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `AjoutTime` (IN `p_temps_base` TIME, IN `p_temps_ajouter` TIME)   BEGIN
    DECLARE temps TIME;
   SELECT ADDTIME(p_temps_base,p_temps_ajouter) INTO temps;
   SELECT temps;
END$$

DROP PROCEDURE IF EXISTS `connexion`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `connexion` (IN `p_nom_Utilisateur` VARCHAR(25), IN `p_mot_de_passe` TEXT)   BEGIN
    DECLARE compte_existant INT;
    START TRANSACTION;   
    SELECT COUNT(*) INTO compte_existant
    FROM Utilisateur
    WHERE Username = p_nom_Utilisateur AND password = p_mot_de_passe;

    IF compte_existant < 1 THEN 
        SELECT FALSE AS est_Utilisateur;
    ELSE 
        SELECT id,id_role FROM Utilisateur WHERE BINARY Username = p_nom_Utilisateur AND BINARY password = p_mot_de_passe;
    END IF;
    COMMIT;
END$$

DROP PROCEDURE IF EXISTS `CreateIntervenant`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateIntervenant` (IN `p_date` DATETIME, IN `p_duree_par_default` TIME, IN `p_date_limite` DATETIME)   BEGIN

    START TRANSACTION;
    INSERT INTO Intervenants(date,duree_par_default,date_limite)
    VALUES (p_date,p_duree_par_default,p_date_limite);
    COMMIT;
END$$

DROP PROCEDURE IF EXISTS `CreateUser`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `CreateUser` (IN `p_Username` VARCHAR(30), IN `p_nom` VARCHAR(40), IN `p_prenom` VARCHAR(25), IN `p_mot_de_passe` TEXT, IN `p_role` INT(15), IN `p_filiere` INT(15))   BEGIN
    DECLARE log_identique INT;      
    SELECT COUNT(*) INTO log_identique
    FROM Utilisateur
    WHERE Username = p_Username AND password = p_mot_de_passe;
    
    START TRANSACTION; 
    IF log_identique < 1 THEN 
        INSERT INTO Utilisateur(Username,nom,prenom,password,id_role,id_filiere) 
        VALUES (p_Username,p_nom,p_prenom,p_mot_de_passe,p_role,p_filiere);
        SELECT TRUE;
    ELSE 
        SELECT FALSE;
    END IF;

    COMMIT;
END$$

DROP PROCEDURE IF EXISTS `creneauxDisponible`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `creneauxDisponible` (IN `p_id_souhait` INT, IN `p_heure_debut` TIME, IN `p_heure_fin` TIME)   BEGIN
    DECLARE creneauxPossible BOOLEAN;
    DECLARE dateDeGeneration Date;
    DECLARE etudiantDisponible INT(1);
    DECLARE intervenantDisponible INT(1);
    DECLARE idIntervenantATester INT(15);
    DECLARE idEtudiantATester INT(15);

   DECLARE intervenantsDispo INT(10);
   DECLARE utilisateurDispo INT(10);
   

    SELECT Intervenants.id INTO idIntervenantATester
    FROM souhaitEtudiant
    JOIN Intervenants
    ON souhaitEtudiant.id_Intervenant = Intervenants.id
    WHERE souhaitEtudiant.id = p_id_souhait;
   
    SELECT Utilisateur.id INTO idEtudiantATester
    FROM souhaitEtudiant
    JOIN Utilisateur
    ON souhaitEtudiant.id_Utilisateur = Utilisateur.id
    WHERE souhaitEtudiant.id = p_id_souhait;

   SELECT COUNT(*) INTO intervenantsDispo
    FROM rdv
    JOIN souhaitEtudiant
    ON souhaitEtudiant.id = RDV.id_souhait
    WHERE souhaitEtudiant.id_intervenant = idIntervenantATester
    AND ((rdv.Heure_debut <= p_heure_debut  AND rdv.Heure_fin >= p_heure_debut)
    OR (rdv.Heure_debut <=p_heure_fin AND rdv.Heure_fin >= p_heure_fin )
    OR (rdv.Heure_debut >= p_heure_debut AND rdv.Heure_fin <= p_heure_fin));
    
   SELECT COUNT(*) INTO utilisateurDispo
    FROM rdv
    JOIN souhaitetudiant
    ON souhaitetudiant.id = RDV.id_souhait
    WHERE souhaitEtudiant.id_Utilisateur = idEtudiantATester
    AND ((rdv.Heure_debut <= p_heure_debut  AND rdv.Heure_fin >= p_heure_debut)
    OR (rdv.Heure_debut <=p_heure_fin AND rdv.Heure_fin >= p_heure_fin )
    OR (rdv.Heure_debut >= p_heure_debut AND rdv.Heure_fin <= p_heure_fin));
   
   

    
   
 
    SELECT date_limite INTO dateDeGeneration FROM Forum;

    IF intervenantsDispo = 0 AND utilisateurDispo= 0  THEN
        IF CURDATE() >= dateDeGeneration THEN
   INSERT INTO RDV(Heure_debut,Heure_fin,id_souhait,est_valide)
   VALUES(p_heure_debut,p_heure_fin,p_id_souhait,0);
            SET creneauxPossible = TRUE;
        ELSE
   SET creneauxPossible = FALSE;
        END IF;
    ELSE
        SET creneauxPossible = FALSE;
    END IF;
 
    SELECT creneauxPossible;
END$$

DROP PROCEDURE IF EXISTS `DateDuJourPasser`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DateDuJourPasser` ()   BEGIN
    DECLARE dateLimiteForum DATE;
    SELECT date_limite INTO dateLimiteForum
    FROM FORUM;
    IF dateLimiteForum <= CURDATE() THEN 
        SELECT FALSE AS Result; -- Assigne FALSE à une variable Result
    ELSE 
        SELECT TRUE AS Result;  -- Assigne TRUE à une variable Result
    END IF;
END$$

DROP PROCEDURE IF EXISTS `DeleteEntreprise`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteEntreprise` (IN `p_id` INT(15))   BEGIN
    START TRANSACTION;
    DELETE FROM Entreprise 
    WHERE entreprise.id = p_id; 
    DELETE FROM lieuEntreprise 
    WHERE id_entreprise = p_id;
    COMMIT;
END$$

DROP PROCEDURE IF EXISTS `DeleteIntervenant`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteIntervenant` (IN `p_id` INT)   BEGIN
    START TRANSACTION;
    DELETE FROM Intervenants WHERE id = p_id;
    COMMIT;
END$$

DROP PROCEDURE IF EXISTS `deleteSouhait`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteSouhait` (IN `p_intervenant` INT(15), IN `p_Utilisateur` INT(15))   BEGIN
   START TRANSACTION;
    DELETE FROM souhaitEtudiant WHERE souhaitEtudiant.id_Intervenant = p_intervenant AND souhaitEtudiant.id_Utilisateur;
    COMMIT;
END$$

DROP PROCEDURE IF EXISTS `DeleteUser`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DeleteUser` (IN `p_id` INT)   BEGIN
    DECLARE compte_existant INT;
    -- Vérification de l'existence de l'étudiant
    SELECT COUNT(*) INTO compte_existant
    FROM Utilisateur
    WHERE Utilisateur.id = p_id;

    START TRANSACTION;   
    DELETE FROM Utilisateur WHERE Utilisateur.id = p_id;
    COMMIT;
END$$

DROP PROCEDURE IF EXISTS `displayAllFiliere`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `displayAllFiliere` ()   BEGIN
    SELECT DISTINCT field FROM filiere ;
END$$

DROP PROCEDURE IF EXISTS `displayAllRole`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `displayAllRole` ()   BEGIN
    SELECT DISTINCT * FROM role ;
END$$

DROP PROCEDURE IF EXISTS `displayEntrepriseByDesignationByFiliere`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `displayEntrepriseByDesignationByFiliere` (IN `p_designation` VARCHAR(50), IN `p_Filiere` VARCHAR(20))   BEGIN
     IF p_Filiere != 'Toutes' THEN
        SELECT intervenants.id as interId, Entreprise.id, Entreprise.Designation, Entreprise.activity_sector, Entreprise.logo, Entreprise.presentation, Filiere.field as Filiere
        FROM Entreprise 
        JOIN Intervenants ON Entreprise.id = Intervenants.id_entreprise 
        JOIN FiliereInterventions ON Intervenants.id = FiliereInterventions.id_intervenant
        JOIN Filiere ON Filiere.id = FiliereInterventions.id_filiere
        WHERE Designation LIKE CONCAT('%', p_designation, '%')  AND Filiere.field = p_Filiere
        ORDER BY Designation ASC;
   
    ELSE
     SELECT Entreprise.id, Entreprise.Designation, Entreprise.activity_sector, Entreprise.logo, Entreprise.presentation
        FROM Entreprise 
        WHERE Designation LIKE CONCAT('%', p_designation, '%')
        ORDER BY Designation ASC;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `displayEntrepriseByid`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `displayEntrepriseByid` (IN `p_id` INT(15))   BEGIN
    SELECT * FROM Entreprise WHERE id = p_id;
END$$

DROP PROCEDURE IF EXISTS `displayEtudiantEtSouhait`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `displayEtudiantEtSouhait` ()   BEGIN
    SELECT Utilisateur.id,nom,prenom,Entreprise.Designation,filiere.field 
    FROM Utilisateur 
    JOIN souhaitEtudiant 
    ON souhaitEtudiant.id_Utilisateur = Utilisateur.id 
    JOIN Intervenant
    ON souhaitEtudiant.id_intervenant = Intervenant.id
    JOIN Entreprise
    ON Intervenant.id_entreprise = Entreprise.id
    JOIN filiere 
    ON filiere.id = Utilisateur.id_Filiere 
    WHERE Utilisateur.id_role = 3
    ORDER BY nom ASC;
END$$

DROP PROCEDURE IF EXISTS `DisplayFiliereOfStudent`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DisplayFiliereOfStudent` (IN `p_id_etudiant` INT)   BEGIN
    SELECT filiere.year FROM Utilisateur 
    JOIN filiere 
    ON Utilisateur.id_filiere = filiere.id 
    WHERE id_role = p_id_etudiant;
END$$

DROP PROCEDURE IF EXISTS `DisplayForum`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DisplayForum` ()   BEGIN
    SELECT duree_par_default,date_limite,date_debut FROM Forum;
END$$

DROP PROCEDURE IF EXISTS `DisplayUser`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DisplayUser` ()   BEGIN
    SELECT * FROM Utilisateur LIMIT 20;
END$$

DROP PROCEDURE IF EXISTS `EditEntreprise`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `EditEntreprise` (IN `p_id` INT(15), IN `p_designation` VARCHAR(100), IN `p_activity_sector` VARCHAR(100), IN `p_logo` TEXT, IN `p_presentation` TEXT, IN `p_ville` VARCHAR(25), IN `p_cp` INT(5), IN `p_adresse` VARCHAR(50))   BEGIN
    DECLARE entrepriseExistante INT(15);
    DECLARE lieuxExistant INT(15);
    DECLARE idlieu INT(15);

    SELECT COUNT(*) INTO entrepriseExistante 
    FROM Entreprise
    WHERE Entreprise.id = p_id;

    SELECT COUNT(*) INTO lieuxExistant 
    FROM lieu 
    WHERE ville = p_ville
    AND cp = p_cp
    AND adresse = p_adresse;
   

    IF entrepriseExistante = 1 THEN 
        UPDATE Entreprise
        SET Entreprise.Designation = p_designation,activity_sector = p_activity_sector,logo = p_logo,presentation = p_presentation WHERE Entreprise.id = p_id;
        SELECT id INTO entrepriseExistante FROM Entreprise 
        WHERE entreprise.id = p_id;
        UPDATE lieuEntreprise SET id_entreprise = entrepriseExistante WHERE id_entreprise = p_id; 
    END IF;

    IF lieuxExistant = 1 THEN 
        SELECT id INTO idlieu 
        FROM lieu 
        WHERE ville = p_ville
        AND cp = p_cp
        AND adresse = p_adresse;
        UPDATE Lieu 
        SET Entreprise.Designation = p_designation,activity_sector = p_activity_sector,logo = p_logo,presentation = p_presentation WHERE id = idlieu;
        UPDATE lieuEntreprise SET id_entreprise = entrepriseExistante WHERE id_lieu = idlieu;
    END IF;

    
END$$

DROP PROCEDURE IF EXISTS `EditForum`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `EditForum` (IN `p_date` DATETIME, IN `p_duree_par_default` TIME, IN `p_date_limite` DATETIME)   BEGIN
    START TRANSACTION;
    UPDATE Forum 
    SET date = p_date,duree_par_default = p_duree_par_default,date_debut = p_date_debut;
    COMMIT;
END$$

DROP PROCEDURE IF EXISTS `EditIntervenant`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `EditIntervenant` (IN `p_id` INT, IN `p_name` VARCHAR(25), IN `p_id_entreprise` INT, IN `p_date_limite` DATETIME)   BEGIN
    START TRANSACTION;
    UPDATE Forum 
    SET date = p_date,duree_par_default = p_duree_par_default,date_debut = p_date_debut;
    COMMIT;
END$$

DROP PROCEDURE IF EXISTS `EditPasswordUser`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `EditPasswordUser` (IN `p_id_etudiant` INT, IN `p_new_Password` INT)   BEGIN
    DECLARE etudiant_existant INT;

    -- Vérification de l'existence de l'étudiant
    SELECT COUNT(*) INTO etudiant_existant
    FROM Utilisateur
    WHERE Utilisateur.id = p_id_etudiant
    AND Utilisateur.id_role = (SELECT role.id FROM role WHERE designation = 'Etudiant');

    START TRANSACTION;   
    IF etudiant_existant > 0 THEN
        UPDATE Utilisateur
        SET password = p_new_Password
        WHERE Utilisateur.id = p_id_etudiant;
        SELECT TRUE;
    ELSE 
        SELECT FALSE;
    END IF;
    COMMIT;
END$$

DROP PROCEDURE IF EXISTS `getIntervenantPaticipant`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getIntervenantPaticipant` (IN `p_id_intervenant` INT)   BEGIN
    DECLARE nbSouhait INT(2) DEFAULT 0; 
    DECLARE priorite INT(3); 
    SELECT COUNT(*) INTO nbSouhait FROM souhaitEtudiant
    WHERE souhaitEtudiant.id_intervenant = p_id_intervenant;
    SELECT priorite(nbSouhait) INTO priorite;
    IF priorite != -1 THEN  
        START TRANSACTION;
        UPDATE Intervenants SET est_disponible = priorite WHERE id = p_id_intervenant;
        COMMIT;
    ELSE 
        START TRANSACTION;
        UPDATE Intervenants SET est_disponible = -1 WHERE id = p_id_intervenant; 
        COMMIT;
    END IF; 
    select * FROM intervenants WHERE id = p_id_intervenant ORDER BY  est_disponible ASC;
END$$

DROP PROCEDURE IF EXISTS `getSouhait`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getSouhait` (IN `p_Utilisateur` INT(15))   BEGIN
    SELECT DISTINCT intervenants.id_entreprise FROM Utilisateur 
    JOIN souhaitEtudiant ON souhaitEtudiant.id_Utilisateur = p_Utilisateur
    JOIN intervenants ON souhaitetudiant.id_intervenant = intervenants.id;
END$$

DROP PROCEDURE IF EXISTS `NbIntervenantsSansSouhait`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `NbIntervenantsSansSouhait` (IN `p_id_entreprise` INT)   BEGIN
    SELECT COUNT(intervenants.name) FROM intervenants
    JOIN entreprise
    ON entreprise.id = intervenants.id_entreprise
    WHERE intervenants.id != (SELECT id_intervenant FROM souhaitetudiant)
     AND Entreprise.id = 3;
 
END$$

DROP PROCEDURE IF EXISTS `NbSouhaitIntervenant`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `NbSouhaitIntervenant` (IN `p_id_intervenant` INT)   BEGIN
    SELECT COUNT(Intervenants.id) FROM Intervenants
    JOIN souhaitEtudiant
    ON souhaitEtudiant.id_intervenant = Intervenants.id
    WHERE souhaitEtudiant.id_intervenant = p_id_intervenant;
END$$

DROP PROCEDURE IF EXISTS `nombreEtudiantSansSouhait`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `nombreEtudiantSansSouhait` ()   BEGIN
    SELECT * FROM Utilisateur WHERE id != (SELECT id_Utilisateur FROM souhaitEtudiant ) 	
    AND id_role = (SELECT id FROM role WHERE Designation = 'Etudiant');
END$$

DROP PROCEDURE IF EXISTS `NombreIntervenant`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `NombreIntervenant` (IN `p_id_entreprise` INT)   BEGIN
    SELECT COUNT(Intervenants.id) FROM Intervenants
    JOIN Entreprise
    ON Entreprise.id = Intervenants.id_entreprise
    WHERE Entreprise.id = p_id_entreprise;
END$$

DROP PROCEDURE IF EXISTS `ResetDonnee`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `ResetDonnee` ()   BEGIN
    START TRANSACTION;
    
    DELETE FROM FiliereInterventions;
    
    UPDATE FORUM
    SET p_duree_par_default = '00:15:00',
        date_limite = DATE_ADD(NOW(), INTERVAL 1 YEAR),
        date_debut= DATE_ADD(DATE_ADD(NOW(), INTERVAL 1 YEAR), INTERVAL 1 MONTH),
        heure_debut = '08:00:00',
        heure_fin = '12:00:00';
    
    DELETE FROM RDV;
    DELETE FROM souhaitEtudiant;
    DELETE FROM Intervenants;
    DELETE FROM lieuEntreprise;
    DELETE FROM Entreprise;
    DELETE FROM Lieu;
    
    DELETE FROM Utilisateur WHERE id_role = 3;
    
    COMMIT;
END$$

DROP PROCEDURE IF EXISTS `setSouhait`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `setSouhait` (IN `p_intervenant` INT(15), IN `p_Utilisateur` INT(15))   BEGIN
    START TRANSACTION;
    INSERT INTO souhaitEtudiant (id_intervenant,id_Utilisateur) VALUES (p_intervenant,p_Utilisateur);
    COMMIT;
END$$

--
-- Fonctions
--
DROP FUNCTION IF EXISTS `priorite`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `priorite` (`p_nbSouhait` DOUBLE) RETURNS INT(11)  BEGIN
    DECLARE dureeForum INT;
    DECLARE dureeRendezVous INT;
    DECLARE minutes DOUBLE;
    DECLARE rendezVous DOUBLE;
    DECLARE priorite DOUBLE;
	
    SELECT p_duree_par_default INTO dureeRendezVous FROM Forum;
    SET rendezVous = HOUR(dureeRendezVous) *60 + MINUTE(dureeRendezVous);
    SELECT TIMEDIFF(heure_Fin,heure_debut)INTO dureeForum FROM Forum  ;
    
    SET minutes = HOUR(dureeForum) *60 + MINUTE(dureeForum);
    SET priorite = Round(((p_nbSouhait + p_nbSouhait *rendezVous) / minutes)+0,49);
    IF priorite >1 THEN 
        RETURN -1;
    END IF;
    IF  priorite = 0  THEN
    	RETURN -1;
    ELSE 
        RETURN priorite *100;
    END IF ;
    
    
    
    
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

DROP TABLE IF EXISTS `entreprise`;
CREATE TABLE IF NOT EXISTS `entreprise` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `Designation` varchar(100) DEFAULT NULL,
  `activity_sector` varchar(100) DEFAULT NULL,
  `logo` text,
  `presentation` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `entreprise`
--

INSERT INTO `entreprise` (`id`, `Designation`, `activity_sector`, `logo`, `presentation`) VALUES
(1, 'VERBUS', 'Transport', 'verbus_logo.jpg', 'Fournisseur de services de transport de haute qualité'),
(3, 'Doxio (Edokial)', 'Technologie', 'edokial_logo.jpg', 'Développeur de solutions logicielles innovantes'),
(4, 'UNICOR', 'Finance', 'unicor_logo.jpg', 'Société de gestion financière et d\'investissement'),
(5, 'NEXTEAM GROUP', 'Consulting', 'nexteam_logo.jpg', 'Cabinet de conseil en stratégie d\'entreprise'),
(6, 'Laboratoire Haut Ségala', 'Santé et bien-être', 'haut_segala_logo.jpg', 'Fabricant de produits de soins naturels'),
(9, 'EDF Une rivière, Un territoire', 'Énergie', 'edf_logo.jpg', 'Fournisseur d\'énergie et services associés'),
(11, 'GROUPE 2B', 'Industrie', 'groupe2b_logo.jpg', 'Groupe industriel diversifié'),
(12, 'DOULS ASSOCIES', 'Services juridiques', 'douls_associes_logo.jpg', 'Cabinet d\'avocats spécialisé en droit fiscal'),
(13, 'Fiducial', 'Finance', 'fiducial_logo.jpg', 'Services financiers et comptables'),
(15, 'Sébastien Saint-Martin', 'Consulting', 'sebastien_saint_martin_logo.jpg', 'Consultant en gestion et stratégie d\'entreprise'),
(17, 'OGEA 12', 'Services environnementaux', 'ogea_12_logo.jpg', 'Société de gestion environnementale'),
(18, 'Préfecture Aveyron', 'Administration publique', 'prefecture_aveyron_logo.jpg', 'Administration publique départementale'),
(20, 'En', 'Technologie', 'a', 'Développeur de solutions logicielle'),
(21, 'Immo de France', 'Immobilier', 'immo_de_france_logo.jpg', 'Agence immobilière'),
(22, 'Crédit Agricole', 'Finance', 'credit_agricole_logo.jpg', 'Institution financière'),
(23, 'Lactalis', 'Agroalimentaire', 'lactalis_logo.jpg', 'Groupe agroalimentaire'),
(25, 'Demain Eco-Concept - Offre 2', 'Écologie', 'demain_eco_concept_logo.jpg', 'Entreprise œuvrant pour un avenir durable'),
(26, 'SUPER U', 'Distribution', 'super_u_logo.jpg', 'Chaîne de supermarchés'),
(27, 'EDF Agence Une rivière Un Territoire', 'Énergie', 'edf_agence_logo.jpg', 'Agence locale d\'EDF'),
(29, 'FINDIS ART DE LA CUISINE', 'Commerce de détail', 'findis_art_cuisine_logo.jpg', 'Spécialiste des arts de la cuisine'),
(30, 'Demain Eco-Concept - Offre 1', 'Écologie', 'demain_eco_concept_logo.jpg', 'Entreprise œuvrant pour un avenir durable'),
(31, 'SGCD - secrétariat général commun départemental, Préfecture de l\'Aveyron', 'Administration publique', 'sgcd_logo.jpg', 'Secrétariat général commun départemental'),
(32, 'Fondation OPTEO', 'Organisation à but non lucratif', 'opteo_logo.jpg', 'Fondation œuvrant pour la promotion de l\'éducation'),
(37, 'Entreprise', 'info', '../images/doxio.jpg', 'qssq'),
(38, 'Entreprise', 'info', '../images/doxio.jpg', 'qssq'),
(39, 'Entreprise', 'info', '../images/doxio.jpg', 'qssq'),
(40, 'Entreprise a', 'zaazza', '../images/doxio.jpg', 'zazaa'),
(41, 'Entreprise aaa', 'aaa', '../images/doxio.jpg', 'zzazaz'),
(42, 'Entreprise aaa', 'aaa', '../images/doxio.jpg', 'zzazaz'),
(43, 'Entreprise aaa', 'aaa', '../images/doxio.jpg', 'zzazaz'),
(44, 'Entreprise aaa', 'aaa', '../images/doxio.jpg', 'zzazaz'),
(45, 'Entreprise aaa', 'aaa', '../images/doxio.jpg', 'zzazaz'),
(46, 'Entreprise aaa', 'aaa', '../images/doxio.jpg', 'zzazaz'),
(47, 'Entreprise aaa', 'aaa', '../images/doxio.jpg', 'zzazaz'),
(48, 'Entreprise aaa', 'aaa', '../images/doxio.jpg', 'zzazaz'),
(49, 'Entreprise aaa', 'aaa', '../images/doxio.jpg', 'zzazaz'),
(50, 'En', 'Test', '../images/doxio.jpg', 't');

-- --------------------------------------------------------

--
-- Structure de la table `filiere`
--

DROP TABLE IF EXISTS `filiere`;
CREATE TABLE IF NOT EXISTS `filiere` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `year` int(1) DEFAULT NULL,
  `field` varchar(25) DEFAULT NULL,
  `abreviation` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `filiere`
--

INSERT INTO `filiere` (`id`, `year`, `field`, `abreviation`) VALUES
(1, 1, 'Informatique', 'BUTINFO1'),
(2, 1, 'GEA', 'BUTGEA1'),
(3, 1, 'QLIO', 'BUTQLIO1'),
(4, 1, 'Infocom', 'BUTINFOC1'),
(5, 1, 'Carrieres Juridique', 'BUTCAR1'),
(6, 2, 'Informatique', 'BUTINFO2'),
(7, 2, 'GEA', 'BUTGEA2'),
(8, 2, 'QLIO', 'BUTQLIO2'),
(9, 2, 'Infocom', 'BUTINFOC2'),
(10, 2, 'Carrieres Juridique', 'BUTCAR2'),
(11, 3, 'Informatique', 'BUTINFO3'),
(12, 3, 'GEA', 'BUTGEA3'),
(13, 3, 'QLIO', 'BUTQLIO3'),
(14, 3, 'Infocom', 'BUTINFOC3'),
(15, 3, 'Carrieres Juridique', 'BUTCAR3'),
(16, 1, 'Economie', 'BUTEco'),
(17, 1, 'Economie', 'BUTEco'),
(18, 1, 'Economie', 'BUTEc'),
(19, 1, 'Test', 'BUTTEST');

-- --------------------------------------------------------

--
-- Structure de la table `filiereinterventions`
--

DROP TABLE IF EXISTS `filiereinterventions`;
CREATE TABLE IF NOT EXISTS `filiereinterventions` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `id_filiere` int(15) DEFAULT NULL,
  `id_intervenant` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_filiere` (`id_filiere`),
  KEY `id_intervenant` (`id_intervenant`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `filiereinterventions`
--

INSERT INTO `filiereinterventions` (`id`, `id_filiere`, `id_intervenant`) VALUES
(1, 11, 2),
(2, 11, 1),
(3, 15, 3),
(4, 18, 4),
(5, 11, 5),
(6, 11, 6),
(7, 13, 7),
(8, 14, 9),
(9, 11, 9),
(10, 14, 10),
(11, 15, 11),
(12, 18, 12),
(13, 15, 13),
(14, 12, 14),
(15, 11, 14);

-- --------------------------------------------------------

--
-- Structure de la table `forum`
--

DROP TABLE IF EXISTS `forum`;
CREATE TABLE IF NOT EXISTS `forum` (
  `id` int(15) NOT NULL,
  `p_duree_par_default` time DEFAULT NULL,
  `date_limite` date DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `heure_debut` time DEFAULT NULL,
  `heure_Fin` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `forum`
--

INSERT INTO `forum` (`id`, `p_duree_par_default`, `date_limite`, `date_debut`, `heure_debut`, `heure_Fin`) VALUES
(1, '00:15:00', '2024-01-01', '2024-01-17', '07:00:00', '12:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `intervenants`
--

DROP TABLE IF EXISTS `intervenants`;
CREATE TABLE IF NOT EXISTS `intervenants` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) DEFAULT NULL,
  `id_entreprise` int(15) DEFAULT NULL,
  `est_disponible` int(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_entreprise` (`id_entreprise`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `intervenants`
--

INSERT INTO `intervenants` (`id`, `name`, `id_entreprise`, `est_disponible`) VALUES
(1, 'en', 1, 16),
(2, 'ENZO', 50, 16),
(3, 'AZ', 3, 5),
(4, 'FS', 3, -1),
(5, 'AEZ', 3, 16),
(6, 'eric', 22, 16),
(7, 'thomas', 5, -1),
(8, 'Ana', 15, -1),
(9, 'emma', 6, 16),
(10, 'eric', 4, 5),
(11, 'adam', 6, 5),
(12, 'homard', 26, -1),
(13, 'carr', 30, 5),
(14, 'britell', 12, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `lieu`
--

DROP TABLE IF EXISTS `lieu`;
CREATE TABLE IF NOT EXISTS `lieu` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `ville` varchar(25) DEFAULT NULL,
  `cp` int(5) DEFAULT NULL,
  `adresse` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `lieu`
--

INSERT INTO `lieu` (`id`, `ville`, `cp`, `adresse`) VALUES
(5, 'Ville ', 12100, 'Rue Princip'),
(6, 'zaza', 12101, 'zaezea'),
(7, 'Rod', 12100, 'Rue Principal'),
(16, 'Paris', 10000, 'A'),
(18, 'Paris', 10000, 'A'),
(19, 'R', 12000, 'A');

-- --------------------------------------------------------

--
-- Structure de la table `lieuentreprise`
--

DROP TABLE IF EXISTS `lieuentreprise`;
CREATE TABLE IF NOT EXISTS `lieuentreprise` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `id_entreprise` int(15) DEFAULT NULL,
  `id_lieu` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_entreprise` (`id_entreprise`),
  KEY `id_lieu` (`id_lieu`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `lieuentreprise`
--

INSERT INTO `lieuentreprise` (`id`, `id_entreprise`, `id_lieu`) VALUES
(1, 50, 19),
(2, 20, 19);

-- --------------------------------------------------------

--
-- Structure de la table `rdv`
--

DROP TABLE IF EXISTS `rdv`;
CREATE TABLE IF NOT EXISTS `rdv` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `Heure_debut` time DEFAULT NULL,
  `Heure_fin` time DEFAULT NULL,
  `id_souhait` int(30) DEFAULT NULL,
  `est_valide` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_souhait` (`id_souhait`)
) ENGINE=InnoDB AUTO_INCREMENT=589 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `rdv`
--

INSERT INTO `rdv` (`id`, `Heure_debut`, `Heure_fin`, `id_souhait`, `est_valide`) VALUES
(570, '07:00:00', '07:15:00', 38, 1),
(571, '07:16:00', '07:31:00', 42, 0),
(572, '07:32:00', '07:47:00', 48, 0),
(573, '07:00:00', '07:15:00', 41, 0),
(574, '07:16:00', '07:31:00', 49, 0),
(575, '07:32:00', '07:47:00', 54, 1),
(576, '07:00:00', '07:15:00', 46, 0),
(577, '07:16:00', '07:31:00', 37, 1),
(578, '07:32:00', '07:47:00', 40, 0),
(579, '07:48:00', '08:03:00', 39, 0),
(580, '08:04:00', '08:19:00', 45, 0),
(581, '08:20:00', '08:35:00', 53, 1),
(582, '07:00:00', '07:15:00', 43, 0),
(583, '07:48:00', '08:03:00', 47, 0),
(584, '08:04:00', '08:19:00', 55, 1),
(585, '07:00:00', '07:15:00', 51, 0),
(586, '07:16:00', '07:31:00', 44, 0),
(587, '07:16:00', '07:31:00', 52, 0),
(588, '07:32:00', '07:47:00', 50, 0);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

DROP TABLE IF EXISTS `role`;
CREATE TABLE IF NOT EXISTS `role` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `designation` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `CT_designation` (`designation`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`id`, `designation`) VALUES
(1, 'Administrateur'),
(3, 'Étudiant'),
(2, 'Gestionnaire');

-- --------------------------------------------------------

--
-- Structure de la table `souhaitetudiant`
--

DROP TABLE IF EXISTS `souhaitetudiant`;
CREATE TABLE IF NOT EXISTS `souhaitetudiant` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `id_intervenant` int(15) DEFAULT NULL,
  `id_Utilisateur` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_intervenant` (`id_intervenant`),
  KEY `id_Utilisateur` (`id_Utilisateur`)
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `souhaitetudiant`
--

INSERT INTO `souhaitetudiant` (`id`, `id_intervenant`, `id_Utilisateur`) VALUES
(37, 5, 2),
(38, 1, 2),
(39, 6, 6),
(40, 5, 6),
(41, 2, 6),
(42, 1, 6),
(43, 9, 15),
(44, 10, 15),
(45, 6, 11),
(46, 5, 11),
(47, 9, 11),
(48, 1, 11),
(49, 2, 11),
(50, 13, 16),
(51, 3, 16),
(52, 11, 16),
(53, 6, 2),
(54, 2, 2),
(55, 9, 2);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `Username` varchar(30) DEFAULT NULL,
  `nom` varchar(40) DEFAULT NULL,
  `prenom` varchar(25) DEFAULT NULL,
  `password` text,
  `id_role` int(15) DEFAULT NULL,
  `id_filiere` int(15) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_role` (`id_role`),
  KEY `id_filiere` (`id_filiere`)
) ENGINE=InnoDB AUTO_INCREMENT=101 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `Username`, `nom`, `prenom`, `password`, `id_role`, `id_filiere`) VALUES
(2, 'BODominique', 'BO', 'Dominique', 'motdepasse2', 3, 1),
(6, 'COCélia', 'CO', 'Célia', 'motdepasse6', 3, 1),
(7, 'COThibault', 'CO', 'Thibault', 'motdepasse7', 3, 1),
(8, 'ENMathis', 'EN', 'Mathis', 'motdepasse8', 3, 1),
(9, 'GEFlorian', 'GE', 'Florian', 'motdepasse9', 3, 1),
(10, 'IDAyoub', 'ID', 'Ayoub', 'motdepasse10', 3, 1),
(11, 'MAMatheo', 'MA', 'Matheo', 'motdepasse11', 3, 1),
(12, 'A', 'A', 'A', 'A', 1, NULL),
(13, 'a@a.a', 'a', 'a', 'aaaaaaaa', 1, 1),
(14, 'MarcTa@mail.com', 'marc', 'ta', '12345678', 3, 5),
(15, 'ericsama@mail.com', 'eric', 'sama', 'ericsama', 3, 4),
(16, 'packtom@iutf.fr', 'pack', 'tom', 'packtoma', 3, 5),
(17, 'G@G.G', 'g', 'g', 'gggggggg', 2, 5),
(18, 'a', 'BE', 'Paul', 'a', 1, 1),
(19, 'email1@example.com', 'BE', 'Paul', 'motdepasse1', 3, 1),
(20, 'email2@example.com', 'BO', 'Dominique', 'motdepasse2', 3, 1),
(21, 'email3@example.com', 'BO', 'Enzo', 'motdepasse3', 3, 1),
(22, 'email4@example.com', 'BR', 'Simon', 'motdepasse4', 3, 1),
(23, 'email5@example.com', 'CH', 'Quentin', 'motdepasse5', 3, 1),
(24, 'email6@example.com', 'CO', 'Célia', 'motdepasse6', 3, 1),
(25, 'email7@example.com', 'CO', 'Thibault', 'motdepasse7', 3, 1),
(26, 'email8@example.com', 'EN', 'Mathis', 'motdepasse8', 3, 1),
(27, 'email9@example.com', 'GE', 'Florian', 'motdepasse9', 3, 1),
(28, 'email10@example.com', 'ID', 'Ayoub', 'motdepasse10', 3, 1),
(29, 'email11@example.com', 'MA', 'Matheo', 'motdepasse11', 3, 1),
(30, 'email12@example.com', 'MA', 'Coraline', 'motdepasse12', 3, 1),
(31, 'email13@example.com', 'MU', 'Laura', 'motdepasse13', 3, 1),
(32, 'email14@example.com', 'PE', 'Baptiste', 'motdepasse14', 3, 1),
(33, 'email15@example.com', 'PR', 'Joris', 'motdepasse15', 3, 1),
(34, 'email16@example.com', 'RA', 'Clément', 'motdepasse16', 3, 1),
(35, 'email17@example.com', 'WO', 'OCEANE', 'motdepasse17', 3, NULL),
(36, 'email18@example.com', 'CO', 'Célia', 'motdepasse18', 3, NULL),
(37, 'email19@example.com', 'Br', 'Lucie', 'motdepasse19', 3, NULL),
(38, 'email122@example.com', 'SO', 'Elodie', 'motdepasse124', 3, NULL),
(39, 'email123@example.com', 'Iv', 'Maelyn', 'motdepasse125', 3, NULL),
(40, 'email124@example.com', 'SO', 'Hugo', 'motdepasse94', 3, NULL),
(41, 'email125@example.com', 'RE', 'Adrien', 'motdepasse95', 3, NULL),
(42, 'email126@example.com', 'AT1', 'AT1', 'motdepasse96', 3, NULL),
(43, 'email127@example.com', 'AL', 'Lucas', 'motdepasse97', 3, NULL),
(44, 'email128@example.com', 'BA', 'Meissa', 'motdepasse98', 3, NULL),
(45, 'email129@example.com', 'BE', 'Manon', 'motdepasse99', 3, NULL),
(46, 'email130@example.com', 'BL', 'Antoine', 'motdepasse100', 3, NULL),
(47, 'email131@example.com', 'BO', 'Anaïs', 'motdepasse101', 3, NULL),
(48, 'email132@example.com', 'BO', 'Antoine', 'motdepasse102', 3, NULL),
(49, 'email133@example.com', 'CH', 'Laetitia', 'motdepasse103', 3, NULL),
(50, 'email134@example.com', 'CL', 'Emma', 'motdepasse104', 3, NULL),
(51, 'email135@example.com', 'CU', 'Baptiste', 'motdepasse105', 3, NULL),
(52, 'email136@example.com', 'DE', 'Eloïse', 'motdepasse106', 3, NULL),
(53, 'email137@example.com', 'DE', 'Emilie', 'motdepasse107', 3, NULL),
(54, 'email138@example.com', 'DE', 'Pénélope', 'motdepasse108', 3, NULL),
(55, 'email139@example.com', 'EN', 'Flavie', 'motdepasse109', 3, NULL),
(56, 'email140@example.com', 'ET', 'Elisa', 'motdepasse110', 3, NULL),
(57, 'email141@example.com', 'FO', 'Perrine', 'motdepasse111', 3, NULL),
(58, 'email142@example.com', 'GI', 'Mélodie', 'motdepasse112', 3, NULL),
(59, 'email143@example.com', 'GO', 'Andréa', 'motdepasse113', 3, NULL),
(60, 'email144@example.com', 'LA', 'Fanchon', 'motdepasse114', 3, NULL),
(61, 'email145@example.com', 'MA', 'Léonie', 'motdepasse115', 3, NULL),
(62, 'email146@example.com', 'PI', 'Margot', 'motdepasse116', 3, NULL),
(63, 'email147@example.com', 'RO', 'Lison', 'motdepasse117', 3, NULL),
(64, 'email148@example.com', 'VA', 'Quentin', 'motdepasse118', 3, NULL),
(65, 'email149@example.com', 'VI', 'Lola', 'motdepasse119', 3, NULL),
(66, 'email150@example.com', 'BE', 'Léane', 'motdepasse120', 3, NULL),
(67, 'email151@example.com', 'BE', 'Oriane', 'motdepasse121', 3, NULL),
(68, 'email152@example.com', 'CA', 'Constance', 'motdepasse122', 3, NULL),
(69, 'email153@example.com', 'CH', 'Anaïs', 'motdepasse123', 3, NULL),
(70, 'email154@example.com', 'SO', 'Elodie', 'motdepasse124', 3, NULL),
(71, 'email155@example.com', 'Iv', 'Maelyn', 'motdepasse125', 3, NULL),
(72, 'email156@example.com', 'AL', 'Lucas', 'motdepasse97', 3, NULL),
(73, 'email157@example.com', 'BA', 'Meissa', 'motdepasse98', 3, NULL),
(74, 'email158@example.com', 'BE', 'Manon', 'motdepasse99', 3, NULL),
(75, 'email159@example.com', 'BL', 'Antoine', 'motdepasse100', 3, NULL),
(76, 'email160@example.com', 'BO', 'Anaïs', 'motdepasse101', 3, NULL),
(77, 'email161@example.com', 'BO', 'Antoine', 'motdepasse102', 3, NULL),
(78, 'email162@example.com', 'CH', 'Laetitia', 'motdepasse103', 3, NULL),
(79, 'email163@example.com', 'CL', 'Emma', 'motdepasse104', 3, NULL),
(80, 'email164@example.com', 'CU', 'Baptiste', 'motdepasse105', 3, NULL),
(81, 'email165@example.com', 'DE', 'Eloïse', 'motdepasse106', 3, NULL),
(82, 'email166@example.com', 'DE', 'Emilie', 'motdepasse107', 3, NULL),
(83, 'email167@example.com', 'DE', 'Pénélope', 'motdepasse108', 3, NULL),
(84, 'email168@example.com', 'EN', 'Flavie', 'motdepasse109', 3, NULL),
(85, 'email169@example.com', 'ET', 'Elisa', 'motdepasse110', 3, NULL),
(86, 'email170@example.com', 'FO', 'Perrine', 'motdepasse111', 3, NULL),
(87, 'email171@example.com', 'GI', 'Mélodie', 'motdepasse112', 3, NULL),
(88, 'email172@example.com', 'GO', 'Andréa', 'motdepasse113', 3, NULL),
(89, 'email173@example.com', 'LA', 'Fanchon', 'motdepasse114', 3, NULL),
(90, 'email174@example.com', 'MA', 'Léonie', 'motdepasse115', 3, NULL),
(91, 'email175@example.com', 'PI', 'Margot', 'motdepasse116', 3, NULL),
(92, 'email176@example.com', 'RO', 'Lison', 'motdepasse117', 3, NULL),
(93, 'email177@example.com', 'VA', 'Quentin', 'motdepasse118', 3, NULL),
(94, 'email178@example.com', 'VI', 'Lola', 'motdepasse119', 3, NULL),
(95, 'email179@example.com', 'BE', 'Léane', 'motdepasse120', 3, NULL),
(96, 'email180@example.com', 'BE', 'Oriane', 'motdepasse121', 3, NULL),
(97, 'email181@example.com', 'CA', 'Constance', 'motdepasse122', 3, NULL),
(98, 'email182@example.com', 'CH', 'Anaïs', 'motdepasse123', 3, NULL),
(99, 'email183@example.com', 'SO', 'Elodie', 'motdepasse124', 3, NULL),
(100, 'email184@example.com', 'Iv', 'Maelyn', 'motdepasse125', 3, NULL);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `filiereinterventions`
--
ALTER TABLE `filiereinterventions`
  ADD CONSTRAINT `filiereinterventions_ibfk_1` FOREIGN KEY (`id_filiere`) REFERENCES `filiere` (`id`),
  ADD CONSTRAINT `filiereinterventions_ibfk_2` FOREIGN KEY (`id_intervenant`) REFERENCES `intervenants` (`id`);

--
-- Contraintes pour la table `intervenants`
--
ALTER TABLE `intervenants`
  ADD CONSTRAINT `intervenants_ibfk_1` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprise` (`id`);

--
-- Contraintes pour la table `lieuentreprise`
--
ALTER TABLE `lieuentreprise`
  ADD CONSTRAINT `lieuentreprise_ibfk_1` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprise` (`id`),
  ADD CONSTRAINT `lieuentreprise_ibfk_2` FOREIGN KEY (`id_lieu`) REFERENCES `lieu` (`id`);

--
-- Contraintes pour la table `rdv`
--
ALTER TABLE `rdv`
  ADD CONSTRAINT `rdv_ibfk_1` FOREIGN KEY (`id_souhait`) REFERENCES `souhaitetudiant` (`id`);

--
-- Contraintes pour la table `souhaitetudiant`
--
ALTER TABLE `souhaitetudiant`
  ADD CONSTRAINT `souhaitetudiant_ibfk_1` FOREIGN KEY (`id_intervenant`) REFERENCES `intervenants` (`id`),
  ADD CONSTRAINT `souhaitetudiant_ibfk_2` FOREIGN KEY (`id_Utilisateur`) REFERENCES `utilisateur` (`id`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id`),
  ADD CONSTRAINT `utilisateur_ibfk_2` FOREIGN KEY (`id_filiere`) REFERENCES `filiere` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
