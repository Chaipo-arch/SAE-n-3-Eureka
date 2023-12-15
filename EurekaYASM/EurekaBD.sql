DROP DATABASE IF EXISTS Eureka;
CREATE DATABASE Eureka;
use Eureka;


-- Création de toutes les tables
Drop TABLE if EXISTs filiere;
CREATE TABLE filiere(
    
    id int(15) AUTO_INCREMENT,
    year int(1),
    field Varchar(25),
    course Varchar(10),
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
    Designation Varchar(25),
    activity_sector Varchar(25),
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
    
    id_entreprise int(15),
    id_utilisateur int(15),
    
    FOREIGN KEY (id_entreprise) REFERENCES Entreprise(id),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id),
    PRIMARY Key(id_entreprise,id_utilisateur) 
);



Drop Table if EXISTs Lieu;
CREATE Table Lieu(
    id int(15) AUTO_INCREMENT,
    ville Varchar(25),
    cp int(5),
    adresse  Varchar(50),
    num_rue int(5),
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
    id_souhait_entreprise INT(15),  -- Utiliser une colonne différente pour la référence
    id_souhait_utilisateur INT(15),  -- Utiliser une colonne différente pour la référence
    FOREIGN KEY (id_intervenant) REFERENCES Intervenants(id),
    FOREIGN KEY (id_souhait_entreprise, id_souhait_utilisateur) REFERENCES souhaitEtudiant(id_entreprise, id_utilisateur),
    
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

Drop Table if EXISTs filiereInterventions;
CREATE Table filiereInterventions(
    id int(15) AUTO_INCREMENT,
    id_filiere int(15),
    id_intervenant int(15),
    PRIMARY KEY (id),
    FOREIGN KEY(id_filiere) REFERENCES filiere(id),
    FOREIGN KEY(id_intervenant) REFERENCES Intervenants(id)
);
-- Fin de la création des table

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

INSERT INTO role(designation) Value("Admin");
INSERT INTO role(designation) Value("Etudiant");
INSERT INTO role(designation) Value("Gestionnaire");

DROP PROCEDURE IF EXISTS CreateUser;

DELIMITER //
CREATE PROCEDURE CreateUser(
  IN p_nom_utilisateur VARCHAR(30),
  IN p_mot_de_passe TEXT
)
BEGIN
    DECLARE compte_existant INT;
    START TRANSACTION;   
    SELECT COUNT(*) INTO compte_existant
    FROM utilisateur
    WHERE Username = p_nom_utilisateur OR password = p_mot_de_passe;

    IF compte_existant < 1 THEN 
        INSERT INTO Utilisateur(Username,password,id_role) Value(p_nom_utilisateur,p_mot_de_passe,1);
        SELECT TRUE;
    ELSE 
        SELECT FALSE;
    END IF;
    COMMIT;
END//
DELIMITER ;

CALL CreateUser("asa","asa"); -- exemple d'appel de procédure


DROP PROCEDURE IF EXISTS AjoutEntreprise;

DELIMITER //
CREATE PROCEDURE AjoutEntreprise(
  IN p_designation VARCHAR(25),
  IN p_activity_sector VARCHAR(25),
  IN p_logo TEXT,
  IN p_presentation TEXT
)
BEGIN
    DECLARE entreprise_existante INT;
    START TRANSACTION;   
    SELECT COUNT(*) INTO entreprise_existante
    FROM Entreprise
    WHERE p_designation = designation AND logo = p_logo AND presentation = p_presentation;

    IF entreprise_existante < 1 THEN 
        INSERT INTO Entreprise(designation,activity_sector,logo,p_presentation) Value(p_designation,p_activity_sector,p_logo,p_presentation);
        SELECT TRUE;
    ELSE 
        SELECT FALSE;
    END IF;
    COMMIT;
END//
DELIMITER ;


DROP PROCEDURE IF EXISTS DeleteAdmin;

DELIMITER //
CREATE PROCEDURE DeleteAdmin(
  IN p_id INT
  
)
BEGIN
    START TRANSACTION;   
    DELETE FROM utilisateur WHERE id = p_id;
    COMMIT;
END//
DELIMITER ;


-- procédure qui affiche la liste des étudiants
DROP PROCEDURE IF EXISTS DisplayAllStudent;

DELIMITER //
CREATE PROCEDURE DisplayAllStudent()
BEGIN
    START TRANSACTION;
    SELECT * FROM utilisateur WHERE id_role = (SELECT id FROM role WHERE designation = 'Etudiant');
    COMMIT;
END//
DELIMITER ;


-- procédure qui affiche la liste des entreprise en fonction de leur désignation
DROP PROCEDURE IF EXISTS displayEntrepriseByDesignationByFiliaire;

DELIMITER //
CREATE PROCEDURE displayEntrepriseByDesignationByFiliaire(
    IN p_designation VARCHAR(50),
    IN p_filiaire VARCHAR(20)
)
BEGIN
    START TRANSACTION;
    SELECT Id, Designation, activity_sector, logo, presentation, Filiaire.field as Filiaire 
    FROM Entreprise 
    JOIN Intervenants ON Entreprise.Id = Intervenants.id_entreprise 
    JOIN FiliaireIntervention ON Intervenants.Id = FiliaireIntervention.id_intervenant
    JOIN Filiaire ON Filiaire.Id = FiliaireIntervention.id_filiere
    WHERE Designation LIKE CONCAT('%', p_designation, '%') 
      AND (p_filiaire = 'toutes' OR Filiaire.field = p_filiaire)
    ORDER BY Designation ASC;
    
    COMMIT;
END//
DELIMITER ;


-- procédure qui affiche la liste des entreprise en fonction de leur désignation
DROP PROCEDURE IF EXISTS displayAllEntreprise;

DELIMITER //
CREATE PROCEDURE displayAllEntreprise()
BEGIN
    START TRANSACTION;
    SELECT * FROM Entreprise ;
    
    COMMIT;
END//
DELIMITER ;


DROP PROCEDURE IF EXISTS nombreEtudiantSansSouhait;

DELIMITER //
CREATE PROCEDURE nombreEtudiantSansSouhait()
BEGIN
    START TRANSACTION;
    SELECT * FROM Utilisateur WHERE id != (SELECT id_utilisateur FROM souhaitEtudiant ) AND id_role = (SELECT id FROM role WHERE Designation = 'Etudiant' ) ;
    
    COMMIT;
END//
DELIMITER ;