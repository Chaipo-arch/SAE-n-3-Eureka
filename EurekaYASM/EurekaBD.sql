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
Drop Table if EXISTs Lieu;
CREATE Table Lieu(
    id int(15) AUTO_INCREMENT,
    ville Varchar(25),
    cp int(5),
    adresse  Varchar(50),
    PRIMARY KEY (id)
);
Drop Table if EXISTs Entreprise;
CREATE Table Entreprise(
    id int(15) AUTO_INCREMENT,
    Designation Varchar(100),
    activity_sector Varchar(100),
    logo TEXT,
    presentation Text,
    id_lieu int(15),
    FOREIGN KEY (id_lieu) REFERENCES Lieu(id),
    PRIMARY Key(id)
);

Drop Table if EXISTs lieuEntrprise;
CREATE Table lieuEntrprise(
    id int(15) AUTO_INCREMENT,
    id_entreprise int(15),
    id_lieu int(15),
    
    FOREIGN KEY (id_entreprise) REFERENCES Entreprise(id),
    FOREIGN KEY (id_lieu) REFERENCES Lieu(id),
    PRIMARY Key(id) 
);


Drop Table if EXISTs souhaitEtudiant;
CREATE Table souhaitEtudiant(
    id int(15) AUTO_INCREMENT,
    id_intervenant int(15),
    id_Utilisateur int(15),
    FOREIGN KEY (id_intervenant) REFERENCES Intervenants(id),
    FOREIGN KEY (id_Utilisateur) REFERENCES Utilisateur(id),
    PRIMARY Key(id) 
);


Drop Table if EXISTs Intervenants;
CREATE Table Intervenants(
    id int(15) AUTO_INCREMENT,
    name Varchar(25),
    id_entreprise int(15),
    id_filiere int(15),
    est_disponible int(3),

    FOREIGN KEY (id_entreprise) REFERENCES Entreprise(id),
    PRIMARY KEY (id)
    
 
);
Drop Table if EXISTs RDV;
CREATE Table RDV(
    id int(15) AUTO_INCREMENT,
    Heure_debut Time,
    Heure_fin Time,
    id_souhait int(30),
    FOREIGN KEY (id_souhait) REFERENCES souhaitEtudiant(id),
    PRIMARY KEY (id)
);

Drop Table if EXISTs Forum;
CREATE Table Forum(
    id int(15) AUTO_INCREMENT,
    p_duree_par_default Time,
    date_limite date,
    date_forum date,
    heure_debut Time,
    heure_fin Time,
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
    IN p_nom_Utilisateur VARCHAR(25),
    IN p_mot_de_passe TEXT
)
BEGIN
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
END//
DELIMITER ;

CALL connexion('assssqsa','asa');


DROP PROCEDURE IF EXISTS CreateUser;
-- Renvoie un boolean correspondant au succés de l'ajout d'un Utilisateur
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
    DELETE FROM Utilisateur WHERE Utilisateur.id = p_id;
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
    DECLARE lieuid INT(15);
    DECLARE Entrepriseid INT(15);

    SELECT COUNT(*) INTO lieu_existante
    FROM lieu
    WHERE p_ville = lieu.ville AND p_cp = lieu.cp AND p_adresse = lieu.adresse;
    SELECT COUNT(*) INTO entreprise_existante
    FROM Entreprise
    WHERE p_designation = Entreprise.designation AND logo = p_logo AND presentation = p_presentation;
    
    START TRANSACTION;   
    IF entreprise_existante < 1 THEN 
        INSERT INTO Entreprise(designation,activity_sector,logo,presentation,id_lieu) Value(p_designation,p_activity_sector,p_logo,p_presentation,NULL);
        SELECT TRUE;
        IF lieu_existante < 1 THEN
            
            INSERT INTO Lieu(ville,cp,adresse) Value(p_ville,p_cp,p_adresse);
            SELECT max(id) INTO lieuid FROM lieu;
            SELECT max(id) INTO Entrepriseid FROM lieu;
            UPDATE Entreprise SET id_lieu = lieuid WHERE id = entrepriseid;
        ELSE 
            SELECT id INTO lieuid FROM lieu WHERE p_designation = Entreprise.designation AND logo = p_logo AND presentation = p_presentation;
            UPDATE Lieu
            SET id_entreprise = entrepriseid
            WHERE ;
        END IF;    
    ELSE 
        SELECT FALSE;
    END IF;
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
     IF p_Filiere != 'Toutes' THEN
        SELECT Entreprise.id, Entreprise.Designation, Entreprise.activity_sector, Entreprise.logo, Entreprise.presentation, Filiere.field as Filiere 
        FROM Entreprise 
        JOIN Intervenants ON Entreprise.id = Intervenants.id_entreprise 
        JOIN FiliereInterventions ON Intervenants.id = FiliereInterventions.id_intervenant
        JOIN Filiere ON Filiere.id = FiliereInterventions.id_filiere
        WHERE Designation LIKE CONCAT('%', p_designation, '%')  AND Filiere.field = p_Filiere
        ORDER BY Designation ASC;
   
    ELSE
     SELECT Entreprise.id, Entreprise.Designation, Entreprise.activity_sector, Entreprise.logo, Entreprise.presentation, Filiere.field as Filiere 
        FROM Entreprise 
        JOIN Intervenants ON Entreprise.id = Intervenants.id_entreprise 
        JOIN FiliereInterventions ON Intervenants.id = FiliereInterventions.id_intervenant
        JOIN Filiere ON Filiere.id = FiliereInterventions.id_filiere
        WHERE Designation LIKE CONCAT('%', p_designation, '%')
        ORDER BY Designation ASC;
    END IF;
END//
DELIMITER ;


-- procédure qui affiche la liste des roles
DROP PROCEDURE IF EXISTS displayAllRole;

DELIMITER //
CREATE PROCEDURE displayAllRole()
BEGIN
    SELECT * FROM role ;
END//
DELIMITER ;

-- procédure qui affiche la liste des filieres
DROP PROCEDURE IF EXISTS displayAllFiliere;

DELIMITER //
CREATE PROCEDURE displayAllFiliere()
BEGIN
    SELECT DISTINCT field FROM filiere ;
END//
DELIMITER ;


DROP PROCEDURE IF EXISTS nombreEtudiantSansSouhait;
 
DELIMITER //
CREATE PROCEDURE nombreEtudiantSansSouhait()
BEGIN
    SELECT * FROM Utilisateur WHERE id != (SELECT id_Utilisateur FROM souhaitEtudiant ) 	
    AND id_role = (SELECT id FROM role WHERE Designation = 'Etudiant');
END//
DELIMITER ;

-- procédure qui affiche la filiere d'un étudiant donné
DROP PROCEDURE IF EXISTS DisplayFiliereOfStudent;

DELIMITER //
CREATE PROCEDURE DisplayFiliereOfStudent(
	IN p_id_etudiant INT    
)
BEGIN
    SELECT filiere.year FROM Utilisateur 
    JOIN filiere 
    ON Utilisateur.id_filiere = filiere.id 
    WHERE id_role = p_id_etudiant;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS getSouhait;

DELIMITER //
CREATE PROCEDURE getSouhait(
	IN p_Utilisateur INT(15)
)
BEGIN
    SELECT DISTINCT souhaitEtudiant.id_entreprise FROM Utilisateur 
    JOIN souhaitEtudiant ON souhaitEtudiant.id_Utilisateur = p_Utilisateur;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS setSouhait;
DELIMITER //
CREATE PROCEDURE setSouhait(
	 IN p_intervenant INT(15),
     IN p_Utilisateur INT(15)
)
BEGIN
    START TRANSACTION;
    INSERT INTO souhaitEtudiant (id_intervenant,id_Utilisateur) VALUES (p_entreprise,p_Utilisateur);
    COMMIT;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS deleteSouhait;
DELIMITER //
CREATE PROCEDURE deleteSouhait(
	 IN p_Intervenant INT(15),
     IN p_Utilisateur INT(15)
)
BEGIN
    START TRANSACTION;
    DELETE FROM souhaitEtudiant WHERE souhaitEtudiant.id_Intervenant = p_entreprise AND souhaitEtudiant.id_Utilisateur;
    COMMIT;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS displayEtudiantEtSouhait;
DELIMITER //
CREATE PROCEDURE displayEtudiantEtSouhait()
BEGIN
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
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS EditEntreprise;
DELIMITER //
CREATE PROCEDURE EditEntreprise(
    IN p_id INT(15),
    IN p_designation VARCHAR(100),
    IN p_activity_sector VARCHAR(100),
    IN p_logo TEXT,
    IN p_presentation TEXT,
    IN p_ville VARCHAR(25),
    IN p_cp INT(5),
    IN p_adresse VARCHAR(50)    
)
BEGIN
    DECLARE entrepriseExistante INT(15);
    DECLARE lieuxDifferent INT(15);

    SELECT COUNT(*) INTO entrepriseExistante 
    FROM entreprise
    WHERE entreprise.id = p_id;

    
    SELECT COUNT(*) INTO lieuxExistant 
    FROM lieu 
    WHERE ville = p_ville
    AND cp = p_cp
    AND adresse = p_adresse;
   

    IF entrepriseExistante = 1 THEN 
        UPDATE Entreprise
        SET designation = p_designation 
        SET activity_sector = p_activity_sector
        SET logo = p_logo
        SET presentation = p_presentation
    END IF;

    IF lieuxExistant = 1 THEN 
        UPDATE Lieu
        SET designation = p_designation 
        SET activity_sector = p_activity_sector
        SET logo = p_logo
        SET presentation = p_presentation
    END IF;

    
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS DeleteEntreprise;
DELIMITER //
CREATE PROCEDURE DeleteEntreprise(
    IN p_id INT(15)  
)
BEGIN
    START TRANSACTION;
    DELETE FROM Entreprise 
    WHERE entreprise.id = p_id; 
    COMMIT;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS EditForum;
DELIMITER //
CREATE PROCEDURE EditForum(
    IN p_date DateTime,
    IN p_duree_par_default TIME,
    IN p_date_limite DateTime   
)
BEGIN
    START TRANSACTION;
    UPDATE Forum 
    SET date = p_date,duree_par_default = p_duree_par_default,date_debut = p_date_debut;
    COMMIT;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS DisplayForum;
DELIMITER //
CREATE PROCEDURE DisplayForum()
BEGIN
    SELECT duree_par_default,date_limite,date_debut FROM Forum;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS DisplayUser;
DELIMITER //
CREATE PROCEDURE DisplayUser()
BEGIN
    SELECT * FROM Utilisateur LIMIT 20;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS DisplayUser;
DELIMITER //
CREATE PROCEDURE DisplayUser()
BEGIN
    SELECT * FROM Utilisateur LIMIT 20;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS displayEntrepriseByid;
DELIMITER //
CREATE PROCEDURE displayEntrepriseByid(
    IN p_id INT(15) 
)
BEGIN
    SELECT 
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS EditIntervenant;
DELIMITER //
CREATE PROCEDURE EditIntervenant(
    IN p_id INT 
    IN p_name VARCHAR(25),
    IN p_id_entreprise INT,
    IN p_date_limite DateTime   
)
BEGIN
    START TRANSACTION;
    UPDATE Forum 
    SET date = p_date 
    SET duree_par_default = p_duree_par_default
    SET date_debut = p_date_debut;
    COMMIT;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS CreateIntervenant;
DELIMITER //
CREATE PROCEDURE CreateIntervenant(
    IN p_date DateTime,
    IN p_duree_par_default TIME,
    IN p_date_limite DateTime   
)
BEGIN

    START TRANSACTION;
    INSERT INTO Intervenants(date,duree_par_default,date_limite)
    VALUES (p_date,p_duree_par_default,p_date_limite);
    COMMIT;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS DeleteIntervenant;
DELIMITER //
CREATE PROCEDURE DeleteIntervenant(
    IN p_id INT  
)
BEGIN
    START TRANSACTION;
    DELETE FROM Intervenants WHERE id = p_id;
    COMMIT;
END//
DELIMITER ;

-- procedure pour avoir les Utilisateur par nom prenom
-- procedure pour diplay les étudiants 
-- procedure pour avoir la liste des des étudiant par intervenant
-- gerer filiere dans le jdd

DROP PROCEDURE IF EXISTS getIntervenantPaticipant;
DELIMITER //
CREATE PROCEDURE getIntervenantPaticipant(
    IN p_id_intervenant INT
)
BEGIN
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
    select * FROM intervenants WHERE id = p_id_intervenant ORDER BY priorite;
END//
DELIMITER ;





