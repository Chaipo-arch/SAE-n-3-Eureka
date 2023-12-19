-- Insérer des données dans la table 'filiere'
INSERT INTO filiere (year, field, abreviation) VALUES
(1, 'Informatique', 'BUTINFO1'),
(1, 'GEA', 'BUTGEA1'),
(1, 'QLIO', 'BUTQLIO1'),
(1, 'Infocom', 'BUTINFOC1'),
(1, 'Carrieres Juridique', 'BUTCAR1'),
(2, 'Informatique', 'BUTINFO2'),
(2, 'GEA', 'BUTGEA2'),
(2, 'QLIO', 'BUTQLIO2'),
(2, 'Infocom', 'BUTINFOC2'),
(2, 'Carrieres Juridique', 'BUTCAR2'),
(3, 'Informatique', 'BUTINFO3'),
(3, 'GEA', 'BUTGEA3'),
(3, 'QLIO', 'BUTQLIO3'),
(3, 'Infocom', 'BUTINFOC3'),
(3, 'Carrieres Juridique', 'BUTCAR3');

-- Insérer des données dans la table 'role'
INSERT INTO role (designation) VALUES
('Administrateur'),
('Gestionnaire'),
('Étudiant');

-- Insérer des données dans la table 'Utilisateur'
INSERT INTO Utilisateur (Username, nom, prenom, password, id_role, id_filiere) VALUES
('admin', 'Admin', 'Admin', 'admin123', 1, NULL),
('gestionnaire', 'gestionnaire', 'Un', 'prof123', 2, 1),
('etudiant1', 'Étudiant', 'Un', 'etudiant123', 3, 1);

-- Insérer des données dans la table 'Entreprise'
INSERT INTO Entreprise (Designation, activity_sector, logo, presentation) VALUES
('Entreprise A', 'Informatique', 'logo1.jpg', 'Présentation de l\'entreprise A'),
('Entreprise B', 'Biologie', 'logo2.jpg', 'Présentation de l\'entreprise B'),
('Entreprise C', 'Finance', 'logo3.jpg', 'Présentation de l\'entreprise C');

-- Insérer des données dans la table 'souhaitEntreprise'
INSERT INTO souhaitEntreprise (DesignationDuPoste, id_entreprise, id_filiere) VALUES
('Développeur Java', 1, 1),
('Biologiste Moléculaire', 2, 2),
('Analyste Financier', 3, 3);

-- Insérer des données dans la table 'souhaitEtudiant'
INSERT INTO souhaitEtudiant (id_entreprise, id_utilisateur) VALUES
(1, 3),
(2, 3),
(3, 3);

-- Insérer des données dans la table 'Lieu'
INSERT INTO Lieu (ville, cp, adresse, id_entreprise) VALUES
('Ville A', 12345, 'Rue Principale', 1),
('Ville B', 67890, 'Avenue Centrale', 2),
('Ville C', 54321, 'Boulevard Principal', 3);

-- Insérer des données dans la table 'Intervenants'
INSERT INTO Intervenants (name, id_entreprise, id_filiere) VALUES
('Intervenant A', 1, 1),
('Intervenant B', 2, 2),
('Intervenant C', 3, 3);

-- Insérer des données dans la table 'RDV'
INSERT INTO RDV (Heure_debut, Heure_fin, dateRDV, id_souhait, id_intervenant, id_souhait_entreprise, id_souhait_utilisateur) VALUES
('09:00:00', '10:00:00', '2023-01-15', 1, 1, 1, 3),
('14:00:00', '15:00:00', '2023-01-16', 2, 2, 2, 3),
('11:30:00', '12:30:00', '2023-01-17', 3, 3, 3, 3);

-- Insérer des données dans la table 'Forum'
INSERT INTO Forum (Heure_debut, Heure_fin, duree, date_limite) VALUES
('08:00:00', '17:00:00', '09:00:00', '2023-01-20');

-- Insérer des données dans la table 'filiereInterventions'
INSERT INTO filiereInterventions (id_filiere, id_intervenant) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3);
