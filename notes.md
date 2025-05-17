# Notes / Docs
## Structure de fichiers de base 
/assets/
    /css
        style.css
    /img/
        jeu-defaut.svg
        logo.webp
        profil-defaut.jpg
    /js/
/dal
    /dao/
        BaseDao.php
        ConfigDao.php 
        JeuDao.php
        RoleDao.php
        StatistiqueDao.php
        UtilisateurDao.php
    /modeles/
        Jeu.php
        Partie.php
        ResultatClassementGlobale.php
        Role.php
        Utilisateur.php
/section/
    entete.phtml
    head.phtml
    pied.phtml
    retroaction.phmtl
/autoloader.php
/config.php
/final.sql
/index.phtml
/session.php

## Template utilisé 
[Material Dashboard](https://github.com/creativetimofficial/material-dashboard?tab=readme-ov-file)

## Utilisation du template 
1. Copier les fichiers dans les bons répertoires 
cp -r * /assets/css

## Structure du site web : 
1. Pages 
    - Accueil
    - Classement 
    - Liste des parties 
    - Connexion
    - Inscription
    - Gestion de compte pour un user authentifié :  
        - Gérer compte 
        - Profil 
2. 
