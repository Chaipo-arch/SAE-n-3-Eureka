DROP DATABASE IF EXISTS eureka;
CREATE DATABASE eureka;
use eureka;


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
    designation Varchar(25) NOT NULL,
    CONSTRAINT CT_designation UNIQUE(designation),
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
    PRIMARY Key(id)
);

Drop Table if EXISTs lieuEntreprise;
CREATE Table lieuEntreprise(
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
    id_entreprise int(15),
    id_Utilisateur int(15),
    
    FOREIGN KEY (id_entreprise) REFERENCES Entreprise(id),
    FOREIGN KEY (id_Utilisateur) REFERENCES Utilisateur(id),
    PRIMARY Key(id) 
);


Drop Table if EXISTs Intervenants;
CREATE Table Intervenants(
    id int(15) AUTO_INCREMENT,
    name Varchar(25),
    id_entreprise int(15),
    id_filiere int(15),
    est_disponible INT(1),
    
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
    p_duree_par_default Time,
    date_limite dateTime,
    date_debut dateTime,
    heure_debut dateTime,
    heure_fin dateTime,
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


SET GLOBAL FOREIGN_KEY_CHECKS=0;

-- Fin de la création des table
/* ======================================================= */

