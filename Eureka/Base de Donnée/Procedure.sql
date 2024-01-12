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
        SELECT Entreprise.id, Entreprise.Designation, Entreprise.activity_sector, Entreprise.logo, Entreprise.presentation,Filiere.field as Filiere 
        FROM Entreprise 
        JOIN intervenants ON Entreprise.id = Intervenants.id_entreprise
        JOIN filiereinterventions ON intervenants.id = filiereinterventions.id_intervenant
        JOIN Filiere ON filiere.id = filiereinterventions.id_filiere
        WHERE Designation LIKE CONCAT('%', p_designation, '%')  AND Filiere.field = p_Filiere
        ORDER BY Designation ASC;
   
    ELSE
     SELECT Entreprise.id, Entreprise.Designation, Entreprise.activity_sector, Entreprise.logo, Entreprise.presentation
        FROM Entreprise
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
    SELECT DISTINCT * FROM role ;
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
	 IN p_entreprise INT(15),
     IN p_Utilisateur INT(15)
)
BEGIN
    START TRANSACTION;
    INSERT INTO souhaitEtudiant (id_entreprise,id_Utilisateur) VALUES (p_entreprise,p_Utilisateur);
    COMMIT;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS deleteSouhait;
DELIMITER //
CREATE PROCEDURE deleteSouhait(
	 IN p_entreprise INT(15),
     IN p_Utilisateur INT(15)
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
    SELECT Utilisateur.id,nom,prenom,Entreprise.Designation,filiere.field 
    FROM Utilisateur 
    JOIN souhaitEtudiant ON souhaitEtudiant.id_Utilisateur = Utilisateur.id 
    JOIN Entreprise ON Entreprise.id = souhaitEtudiant.id_Entreprise
    JOIN filiere ON filiere.id = Utilisateur.id_Filiere 
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
        SET Entreprise.Designation = p_designation,activity_sector = p_activity_sector,logo = p_logo,presentation = p_presentation 
        WHERE Entreprise.id = p_id;
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
    DELETE FROM lieuEntreprise 
    WHERE id_entreprise = p_id;
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

DROP PROCEDURE IF EXISTS displayEntrepriseByid;
DELIMITER //
CREATE PROCEDURE displayEntrepriseByid(
    IN p_id INT(15) 
)
BEGIN
    SELECT * FROM Entreprise WHERE id = p_id;
END//
DELIMITER ;

DROP PROCEDURE IF EXISTS EditIntervenant;
DELIMITER //
CREATE PROCEDURE EditIntervenant(
    IN p_id INT,
    IN p_name VARCHAR(25),
    IN p_id_entreprise INT,
    IN p_date_limite DateTime   
)
BEGIN
    START TRANSACTION;
    UPDATE Forum 
    SET date = p_date,duree_par_default = p_duree_par_default,date_debut = p_date_debut;
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

DROP PROCEDURE IF EXISTS genPointEntreprise;
DELIMITER //
CREATE PROCEDURE genPointEntreprise(
    IN p_id_entreprise INT,
    IN p_duree TIME
)
BEGIN
    DECLARE nbSouhait INT;
    DECLARE puntos INT;

    -- Obtenir le nombre de souhaits pour une entreprise spécifique
    SELECT COUNT(id) INTO nbSouhait FROM souhaitetudiant WHERE id_entreprise = p_id_entreprise;

    -- Calculer les points en multipliant la durée par le nombre de souhaits
    SET puntos = TIME_TO_SEC(p_duree) * nbSouhait;

    -- Renvoyer la valeur des points (puntos)
    SELECT puntos;
END //
DELIMITER ;


/*DROP PROCEDURE IF EXISTS genEtudiantPriorite;
DELIMITER //
CREATE PROCEDURE genPointEntreprise(
    IN p_duree TIME
)
BEGIN
    DECLARE nbSouhait INT;
    DECLARE puntos INT;

    -- Obtenir le nombre de souhaits pour une entreprise spécifique
    SELECT COUNT(id) INTO nbSouhait FROM souhaitetudiant WHERE id_entreprise = p_id_entreprise;

    -- Calculer les points en multipliant la durée par le nombre de souhaits
    SET puntos = TIME_TO_SEC(p_duree) * nbSouhait;

    -- Renvoyer la valeur des points (puntos)
    SELECT puntos;
END //
DELIMITER ;
*/

DROP PROCEDURE IF EXISTS genPlanning;
DELIMITER //
CREATE PROCEDURE genPlanning(
    IN p_duree TIME,
    IN id_entreprise
)
BEGIN
    DECLARE nbCrenaux INT,

    SELECT TIMESTAMPDIFF(SECOND, heure_debut, heure_fin) / TIME_TO_SEC(p_duree) INTO nbCrenaux FROM forum;
    SELECT nbCrenaux;
    END //

DELIMITER ;
