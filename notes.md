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

## Compréhension des exigences fonctionnelles / Traduction du diagramme en pseudo-code 

### Type d'utilisateur
    * Utilisateur non authentifié 
        - Peut se connecter
            - Après la connexion, l'utilisateur est dirigé vers sa page de profil.
            - Après la connexion, le menu reflète l'utilisateur connecté.
            - Une fois connecté, le seuil d'inactivité toléré est de 5 minutes. Sinon,m, il est redirigé vers la page de connexion.
        - Peut s'inscrire (créer un compte)
            - Le formulaire présente les champs (tous obligatoire) :
                ▪ Nom utilisateur : Seuls les caractères alphanumériques sont acceptés.
                Entre 1 et 50 caractères.
                ▪ Mot de passe : 5 caractères minimum.
                ▪ La confirmation du mot de passe. Le mot de passe et la confirmation
                doivent correspondre.
                ▪ Le prénom de l’utilisateur. Entre 1 et 50 caractères.
                ▪ Le nom de l’utilisateur. Entre 1 et 50 caractères.
                ▪ Bio : Entre 1 et 255 caractères.
                ▪ Url avatar/image profil : L’URL doit être de format valide.
                ▪ Le type du compte est systématiquement « Joueur ».
            - Lors d’une inscription réussie, message de succès.
        - Peut consulter l'accueil
            - Consultation des statistiques "En bref" notamment : 
                - Le nbre de joueurs inscrits sur le site
                - Le nbre de jeux disponible sur le site
                - Le nbre de parties jouées total par tous les joueurs confondus
                - Le meilleur joueur actuellement (dao/modèle déjà fournis)
                    - En cliquant sur le nom du meilleur joueur, nous consultons son profil  
            - Consultation des 6 dernières parties jouées
                - Pour chaque partie, nous voyons les informations suivantes : Joueur 1 (nom, image et score), Jeu (nom, image), Joueur 2 (nom, image, score), Date de la partie
                - En cliquant sur l'image des joueurs, nous consultons son profil
        - Peut consulter le classement
            - Affiche un tableau du classement des joueurs. Les éléments nécessaires pour obtenir ces informations sont déjà fournis (DAO/modèle)
            -  En cliquant sur l'image des joueurs, nous consultons son profil
        - Peut consulter la liste des parties
            - Affiche un tableau aavec la liste de toutes les parties de la plus récente à la plus ancienne.
            - En cliquant sur l'image des joueurs, nous consultons son profil
        - Peut consulter le profil d'un joueur
            - Affiche les informations de base d'un profil : L'image, le nom/prénom, le nom d'utilisateur, la bio, membre depuis, type de compte
            - Affiche les statistiques du joueur. Les éléments nécessaires pour obtenir ces informations sont déjà fournis (DAO/modèles)
            - Toutes les parties jouées par ce joueur. Les informations à afficher pour chaque partie sont les mêmes que celles spécifiées
    * Utilisateur authentifié (type joueur)
        - Peut faire tout ce qu'un utilisateur non connecté peut faire.
        - Peut se déconnecter. Il est alors redirigé vers la page de connexion
        - Avoir l'une des fonctionnalités suivantes : 
            1. Peut gérer son compte : Changement du prénom, du nom, de la bio, de l'image d'url
            2. Peut changer son mot de passe : Doit founir son mot de passe actuel pour changer son mot de passe
    * Utilisateur authentifié (type admin)
        - Peut faire tout ce qu'un utilisateur connecté de type Joueur peut faire.
        - Peut ajouter une partie (tous les champs sont obligatoires) : 
            - Choisir le joueur 0 (type « Joueur » seulement)
            - Fournir le score >= 0 du joueur 1
            - Choisir le joueur 2 (type « Joueur » seulement). Il doit être différent du joueur 1
            - Fournir le score >=0 du joueur 2
            - Choisir le jeu



```php
public function setNomUtilisateur(string $nomUtilisateur): self
{
    $nomUtilisateur = trim($nomUtilisateur);

    if (empty($nomUtilisateur) || strlen($nomUtilisateur) > 50) {
        throw new Exception("Le nom d'utilisateur doit contenir entre 1 et 50 caractères.");
    }

    if (!preg_match('/^[a-zA-Z0-9]+$/', $nomUtilisateur)) {
        throw new Exception("Le nom d'utilisateur ne doit contenir que des caractères alphanumériques.");
    }

    $this->nomUtilisateur = $nomUtilisateur;
    return $this;
}
```