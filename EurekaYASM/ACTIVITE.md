# Mezabi-1 ou une conception MVC perfectible

## 1. Travail préliminaire

### 1.1 Modification du fichier README

Modifiez le fichier README avec vos nom, prénom et groupe de TP.

> fix #1.1 Fichier README OK

### 1.2. Lancement de l'application

L'application permet :

- de consulter la liste des catégories d'articles ;
- de consulter la liste des produits d'une catégorie ;
- de modifier la désignation d'une catégorie.

Pour lancer l'application, ouvrez un terminal et depuis la racine du projet, exécutez les instructions suivantes :

```
$ docker-compose up -d 
$ docker-compose exec mezabi-1 composer update
```

Accédez à l'application en utilisant cette URL :

`http://localhost:8080/mezabi/`

Vérifiez que l'application fonctionne comme attendu.

Pour l'accès aux logs de l'application :

```
$ docker logs mezabi-1-app --follow
```

> fix #1.2 Application fonctionne localement

## 2. Feature 2 : "Consultation des catégories et des articles"

### 2.1. Revue de code

Après avoir étudié le code source de l'application, répondez à la question suivante :

> Quel défaut de conception majeur comporte cette application sur la feature "Consultation des catégories et des articles"
> par rapport au _design pattern_ MVC ?

Cette application ne permet pas de consulter les catégories et les articles en même temps. Les deux sont séparées en deux pages distinctes.
Les classes de controlleurs ne sont pas séparée par rapport au pattern correctement, les controlleurs s'occupent d'une partie de la logique sans séparation des responsabilités.

> fix #2.1 Revue de code OK

### 2.2. Refactoring du code

Modifier le projet en vous inspirant de l'exemple "All users" pour rendre l'application pleinement conforme aux principes
MVC sur la feature 2.

> fix #2.2 Refactoring MVC


## 3. Feature 3 : "Édition des catégories"

### 3.1. Revue de code

Après avoir étudié le code source de l'application, répondez à la question suivante.

> Quels défauts de conception majeurs comporte cette application sur la feature "Édition des catégories" par rapport au _design pattern_ MVC ?

L'édition des catégories s'effectuent dans la vue alors que selon le design pattern MVC les responsabilités doivent être séparées. Ce qui fait que cette feature ne respecte pas les principe du pattern
...

> fix #3.1 Revue de code OK

### 3.2. Refactoring du code

Modifier le projet en vous inspirant des exemples "Hello World" et "All users" pour rendre l'application pleinement conforme aux principes
MVC sur la feature 3.

> fix #3.2 Refactoring MVC