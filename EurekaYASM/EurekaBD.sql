DROP DATABASE IF EXISTS Eureka;
CREATE DATABASE Eureka;
use Eureka;


-- Création de toutes les tables
Drop TABLE if EXISTs filiere;
CREATE TABLE filiere(
    
    id int(15) AUTO_INCREMENT,
    year int(1),
    field Varchar(25),
    abreviation Varchar(10),
    PRIMARY KEY(id)
    );


Drop TABLE if EXISTs role;
CREATE Table role(
    id int(15) AUTO_INCREMENT,
    designation Varchar(25),
    PRIMARY key(id)
);

Drop Table if EXISTs Utilisateur;
CREATE Table Utilisateur(
    id int(15) AUTO_INCREMENT,
    Username Varchar(30),
    nom Varchar(40),
    prenom Varchar(25),
    password TEXT,
    id_role int(15),
    id_filiere int(15),
    FOREIGN KEY (id_role) REFERENCES role(id),
    FOREIGN KEY (id_filiere) REFERENCES filiere(id),
    PRIMARY Key(id) 
);

Drop Table if EXISTs Entreprise;
CREATE Table Entreprise(
    id int(15) AUTO_INCREMENT ,
    Designation Varchar(100),
    activity_sector Varchar(100),
    logo TEXT,
    presentation Text,
    PRIMARY Key(id)
);

Drop Table if EXISTs souhaitEntreprise;
CREATE Table souhaitEntreprise(
    
    DesignationDuPoste Varchar(50),
    id_entreprise int(15),
    id_filiere int(15),
    FOREIGN KEY (id_entreprise) REFERENCES Entreprise(id),
    FOREIGN KEY (id_filiere) REFERENCES filiere(id),
    PRIMARY Key(id_entreprise,id_filiere) 
);

Drop Table if EXISTs souhaitEtudiant;
CREATE Table souhaitEtudiant(
    id int(15) AUTO_INCREMENT,
    id_entreprise int(15),
    id_utilisateur int(15),
    
    FOREIGN KEY (id_entreprise) REFERENCES Entreprise(id),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id),
    PRIMARY Key(id) 
);



Drop Table if EXISTs Lieu;
CREATE Table Lieu(
    id int(15) AUTO_INCREMENT,
    ville Varchar(25),
    cp int(5),
    adresse  Varchar(50),
    id_entreprise int(15),
   
    FOREIGN KEY (id_entreprise) REFERENCES Entreprise(id),
    PRIMARY KEY (id)
    
 
);

Drop Table if EXISTs Intervenants;
CREATE Table Intervenants(
    id int(15) AUTO_INCREMENT,
    name Varchar(25),
    id_entreprise int(15),
    id_filiere int(15),
    FOREIGN KEY (id_entreprise) REFERENCES Entreprise(id),
    PRIMARY KEY (id)
    
 
);
Drop Table if EXISTs RDV;
CREATE Table RDV(
    id int(15) AUTO_INCREMENT,
    Heure_debut Time,
    Heure_fin Time,
    dateRDV date,
    id_souhait int(30),
    id_intervenant int(15),
    FOREIGN KEY (id_intervenant) REFERENCES Intervenants(id),
    FOREIGN KEY (id_souhait) REFERENCES souhaitEtudiant(id),
    PRIMARY KEY (id)
);

Drop Table if EXISTs Forum;
CREATE Table Forum(
    id int(15) AUTO_INCREMENT,
    Heure_debut Time,
    Heure_fin Time,
    duree Time,
    date_limite dateTime,
    PRIMARY KEY (id)
    
 
);

Drop Table if EXISTs FiliereInterventions;
CREATE Table FiliereInterventions(
    id int(15) AUTO_INCREMENT,
    id_filiere int(15),
    id_intervenant int(15),
    PRIMARY KEY (id),
    FOREIGN KEY(id_filiere) REFERENCES filiere(id),
    FOREIGN KEY(id_intervenant) REFERENCES Intervenants(id)
);
-- Fin de la création des table

SET GLOBAL FOREIGN_KEY_CHECKS=0;

-- Procedure de connexion en cours de réalisation
DROP PROCEDURE IF EXISTS connexion;

DELIMITER //
CREATE PROCEDURE connexion(
    IN p_nom_utilisateur VARCHAR(25),
    IN p_mot_de_passe TEXT
)
BEGIN
    DECLARE compte_existant INT;
    START TRANSACTION;   
    SELECT COUNT(*) INTO compte_existant
    FROM utilisateur
    WHERE Username = p_nom_utilisateur AND password = p_mot_de_passe;

    IF compte_existant < 1 THEN 
        SELECT FALSE AS est_utilisateur;
    ELSE 
        SELECT id,id_role FROM utilisateur WHERE BINARY Username = p_nom_utilisateur AND BINARY password = p_mot_de_passe;
    END IF;
    COMMIT;
END//
DELIMITER ;

CALL connexion('assssqsa','asa');


DROP PROCEDURE IF EXISTS CreateUser;
-- Renvoie un boolean correspondant au succés de l'ajout d'un utilisateur
DELIMITER //
CREATE PROCEDURE CreateUser(
  IN p_Username VARCHAR(30),
  IN p_nom VARCHAR(40),
  IN p_prenom VARCHAR(25),
  IN p_mot_de_passe TEXT,
  IN p_role INT(15),
  IN p_filiere INT(15)
)
BEGIN
    DECLARE log_identique INT;      
    SELECT COUNT(*) INTO log_identique
    FROM utilisateur
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
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS DeleteUser;

DELIMITER //
CREATE PROCEDURE DeleteUser(
  IN p_id INT  
)
BEGIN
    DECLARE compte_existant INT;
    -- Vérification de l'existence de l'étudiant
    SELECT COUNT(*) INTO compte_existant
    FROM Utilisateur
    WHERE Utilisateur.id = p_id;


    START TRANSACTION;   
    DELETE FROM utilisateur WHERE Utilisateur.id = p_id;
    COMMIT;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS EditPasswordUser;

DELIMITER //
CREATE PROCEDURE EditPasswordUser(
  IN p_id_etudiant INT,
  IN p_new_Password INT  
)
BEGIN
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
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS AjoutEntreprise;

DELIMITER //
CREATE PROCEDURE AjoutEntreprise(
  IN p_designation VARCHAR(100),
  IN p_activity_sector VARCHAR(100),
  IN p_logo TEXT,
  IN p_presentation TEXT,
  IN p_ville VARCHAR(25),
  IN p_cp INT(5),
  IN p_adresse VARCHAR(50)
)
BEGIN
    DECLARE entreprise_existante INT;
    DECLARE lieu_existante INT;
    DECLARE entrepriseID INT(15);
    SELECT COUNT(*) INTO lieu_existante
    FROM lieu
    WHERE p_ville = lieu.ville AND p_cp = lieu.cp AND p_adresse = lieu.adresse;
    SELECT COUNT(*) INTO entreprise_existante
    FROM Entreprise
    WHERE p_designation = Entreprise.designation AND logo = p_logo AND presentation = p_presentation;
    
    START TRANSACTION;   
    IF entreprise_existante < 1 THEN 
        INSERT INTO Entreprise(designation,activity_sector,logo,presentation) Value(p_designation,p_activity_sector,p_logo,p_presentation);
        SELECT TRUE;
        IF lieu_existante < 1 THEN
            SELECT max(id) INTO entrepriseID FROM entreprise;
            SET entrepriseID = entrepriseID;
            INSERT INTO Lieu(ville,cp,adresse,id_entreprise) Value(p_ville,p_cp,p_adresse,entrepriseID);
        ELSE 
            UPDATE Lieu
            SET id_entreprise = entrepriseID
            WHERE ville = p_ville AND cp = p_cp AND adresse = p_adresse;
        END IF;    
    ELSE 
        SELECT FALSE;
    END IF;
    COMMIT;
END//
DELIMITER ;

-- procédure qui affiche la liste des étudiants
DROP PROCEDURE IF EXISTS DisplayAllStudent;

DELIMITER //
CREATE PROCEDURE DisplayAllStudent()
BEGIN
    START TRANSACTION;
    SELECT * FROM Utilisateur WHERE id_role = 3;
    COMMIT;
END//
DELIMITER ;


-- procédure qui affiche la liste des entreprise en fonction de leur désignation
DROP PROCEDURE IF EXISTS displayEntrepriseByDesignationByFiliere;

DELIMITER //
CREATE PROCEDURE displayEntrepriseByDesignationByFiliere(
    IN p_designation VARCHAR(50),
    IN p_Filiere VARCHAR(20)
)
BEGIN
    START TRANSACTION;
     IF p_Filiere != 'Toutes' THEN
        SELECT Entreprise.Id, Entreprise.Designation, Entreprise.activity_sector, Entreprise.logo, Entreprise.presentation, Filiere.field as Filiere 
        FROM Entreprise 
        JOIN Intervenants ON Entreprise.Id = Intervenants.id_entreprise 
        JOIN FiliereInterventions ON Intervenants.Id = FiliereInterventions.id_intervenant
        JOIN Filiere ON Filiere.Id = FiliereInterventions.id_filiere
        WHERE Designation LIKE CONCAT('%', p_designation, '%')  AND Filiere.field = p_Filiere
        ORDER BY Designation ASC;
   
    ELSE
     SELECT Entreprise.Id, Entreprise.Designation, Entreprise.activity_sector, Entreprise.logo, Entreprise.presentation, Filiere.field as Filiere 
        FROM Entreprise 
        JOIN Intervenants ON Entreprise.Id = Intervenants.id_entreprise 
        JOIN FiliereInterventions ON Intervenants.Id = FiliereInterventions.id_intervenant
        JOIN Filiere ON Filiere.Id = FiliereInterventions.id_filiere
        WHERE Designation LIKE CONCAT('%', p_designation, '%')
        ORDER BY Designation ASC;
    END IF;
     
    COMMIT;
END//
DELIMITER ;


-- procédure qui affiche la liste des roles
DROP PROCEDURE IF EXISTS displayAllRole;

DELIMITER //
CREATE PROCEDURE displayAllRole()
BEGIN
    START TRANSACTION;
    SELECT * FROM role ;
    
    COMMIT;
END//
DELIMITER ;

-- procédure qui affiche la liste des filieres
DROP PROCEDURE IF EXISTS displayAllFiliere;

DELIMITER //
CREATE PROCEDURE displayAllFiliere()
BEGIN
    START TRANSACTION;
    SELECT DISTINCT field FROM filiere ;
    
    COMMIT;
END//
DELIMITER ;


DROP PROCEDURE IF EXISTS nombreEtudiantSansSouhait;
 
DELIMITER //
CREATE PROCEDURE nombreEtudiantSansSouhait()
BEGIN
    START TRANSACTION;
    SELECT * FROM Utilisateur WHERE id != (SELECT id_utilisateur FROM souhaitEtudiant ) 	AND id_role = (SELECT id FROM role WHERE Designation = 'Etudiant');
    
    COMMIT;
END//
DELIMITER ;

-- procédure qui affiche la filiere d'un étudiant donné
DROP PROCEDURE IF EXISTS DisplayFiliereOfStudent;

DELIMITER //
CREATE PROCEDURE DisplayFiliereOfStudent(
	IN p_id_etudiant INT    
)
BEGIN
    START TRANSACTION;
    SELECT filiere.year FROM Utilisateur 
    JOIN filiere 
    ON utilisateur.id_filiere = filiere.id 
    WHERE id_role = p_id_etudiant;
    COMMIT;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS getSouhait;

DELIMITER //
CREATE PROCEDURE getSouhait(
	IN p_utilisateur INT(15)
)
BEGIN
    START TRANSACTION;
    SELECT DISTINCT souhaitEtudiant.id_entreprise FROM Utilisateur JOIN souhaitEtudiant ON souhaitEtudiant.id_Utilisateur = p_utilisateur;
    COMMIT;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS setSouhait;
DELIMITER //
CREATE PROCEDURE setSouhait(
	 IN p_entreprise INT(15),
     IN p_utilisateur INT(15)
)
BEGIN
    START TRANSACTION;
    INSERT INTO souhaitEtudiant (id_entreprise,id_Utilisateur) VALUES (p_entreprise,p_utilisateur);
    COMMIT;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS deleteSouhait;
DELIMITER //
CREATE PROCEDURE deleteSouhait(
	 IN p_entreprise INT(15),
     IN p_utilisateur INT(15)
)
BEGIN
    START TRANSACTION;
    DELETE FROM souhaitEtudiant WHERE souhaitEtudiant.id_entreprise = p_entreprise AND souhaitEtudiant.id_Utilisateur;
    COMMIT;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS displayEtudiantEtSouhait;
DELIMITER //
CREATE PROCEDURE displayEtudiantEtSouhait()
BEGIN
    START TRANSACTION;
    SELECT Utilisateur.id,nom,prenom,souhaitEtudiant.Entreprise.Designation,filiere.field 
    FROM Utilisateur 
    JOIN souhaitEtudiant ON souhaitEtudiant.id_Utilisateur = Utilisateur.id 
    JOIN Entreprise ON Entreprise.id = souhaitEtudiant.id_Entreprise
    JOIN filiere ON filiere.id = Utilisateur.id_Filiere 
    WHERE Utilisateur.id_role = 3;
    COMMIT;
END//
DELIMITER ;
