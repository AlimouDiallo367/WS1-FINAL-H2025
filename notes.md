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
                - [x] Nom utilisateur : Seuls les caractères alphanumériques sont acceptés.
                Entre 1 et 50 caractères.
                - [x] Mot de passe : 5 caractères minimum.
                - [ ] La confirmation du mot de passe. Le mot de passe et la confirmation
                doivent correspondre.
                - [x] Le prénom de l’utilisateur. Entre 1 et 50 caractères.
                - [x] Le nom de l’utilisateur. Entre 1 et 50 caractères.
                - [x] Bio : Entre 1 et 255 caractères.
                - [x] Url avatar/image profil : L’URL doit être de format valide.
                - [ ] Le type du compte est systématiquement « Joueur ».
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
            - [] Choisir le joueur 1 (type « Joueur » seulement)
            - [x]Fournir le score >= 0 du joueur 1
            - Choisir le joueur 2 (type « Joueur » seulement). Il doit être différent du joueur 1
            - [x]Fournir le score >=0 du joueur 2
            - Choisir le jeu

            - **IMPORTANT : Demander à Fred si la validation des id joueurs et jeu doivent être >= 0**


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

### Code des DAO 
1. Création de PartieDao.php, puis complétion de UtilisateurDao.php
2. Complétion de UtilisateurDao.php 
    - Ce qu'il manque : 
        - inserer (Add utilisateur/Création nouveau compte)
        - update (Update profil)
        - changerMotDePasse (Update mot de passe)
        - selectTout (Afficher tout les utilisateur*)
        - supprimer (pas besoin dans le cas du projet)
3. Complétion de PartieDao.php
> Dans le contexte de la méthode selectAll() du DAO PartieDao, on pourrait techniquement construire une grosse requête SQL avec plusieurs jointures (JOIN) entre les tables partie, utilisateur (pour j1 et j2), et jeu. Cela permettrait de récupérer toutes les données liées en une seule requête. Cependant, cette approche va à l’encontre du principe de responsabilité unique propre aux DAO. Chaque DAO est censé gérer uniquement l’accès aux données de son entité. En appelant plutôt les méthodes select() de UtilisateurDao et JeuDao, on garde un code plus modulaire, plus facile à lire et surtout plus maintenable. Cela permet également de centraliser toute la logique de construction des objets (Utilisateur, Jeu) dans leur propre DAO, au lieu de dupliquer cette logique à chaque endroit où ils sont utilisés. Ce découplage respecte aussi les bonnes pratiques apprises en programmation orientée objet (POO).

> selectAll : 
```php
public function selectAll(int $limite = 0): array
{
    $connexion = $this->getConnexion();

    // Base de la requête
    $sql = "SELECT * FROM partie ORDER BY date_creation DESC";
    if ($limite > 0) {
        $sql .= " LIMIT :limite";
    }

    $requete = $connexion->prepare($sql);

    if ($limite > 0) {
        $requete->bindValue(":limite", $limite, PDO::PARAM_INT);
    }

    $requete->execute();

    $parties = [];

    while ($enregistrement = $requete->fetch(PDO::FETCH_ASSOC)) {
        $partie = new Partie(
            new DateTime($enregistrement['date_creation']),
            $enregistrement['j1_id'],
            $enregistrement['j2_id'],
            $enregistrement['j1_score'],
            $enregistrement['j2_score'],
            $enregistrement['jeu_id'],
            $enregistrement['id']
        );

        // Associer les objets liés (optionnel mais utile pour les vues)
        $partie->setJoueur1($this->utilisateurDao->select($partie->getJoueur1Id()));
        $partie->setJoueur2($this->utilisateurDao->select($partie->getJoueur2Id()));
        $partie->setJeu($this->jeuDao->select($partie->getJeuId()));

        $parties[] = $partie;
    }

    return $parties;
}
```