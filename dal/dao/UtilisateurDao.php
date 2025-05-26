<?php

class UtilisateurDao extends BaseDao
{
    private RoleDao $roleDao;

    public function __construct(ConfigDao $config)
    {
        parent::__construct($config);
        $this->roleDao = new RoleDao($config);
    }
    
    public function selectAllParRole(int $roleId): array
    {
        $connexion = $this->getConnexion();

        $requete = $connexion->prepare("SELECT * FROM utilisateur WHERE role_id=:role_id ORDER BY date_creation ASC");
        $requete->bindValue(":role_id", $roleId);
        $requete->execute();

        $utilisateurs = [];
        while ($enregistrement  = $requete->fetch()) {
            $utilisateur = $this->construireUtilisateur($enregistrement);
            $utilisateur->setRole($this->roleDao->select($utilisateur->getRoleId()));
            $utilisateurs[] = $utilisateur;
        }

        return $utilisateurs;
    }

    public function select(int $id): ?Utilisateur
    {
        $connexion = $this->getConnexion();

        $requete = $connexion->prepare("SELECT * FROM utilisateur WHERE id=:id");
        $requete->bindValue(":id", $id);
        $requete->execute();

        $utilisateur = null;
        if ($enregistrement = $requete->fetch())
        {
            $utilisateur = $this->construireUtilisateur($enregistrement);
            $utilisateur->setRole($this->roleDao->select($utilisateur->getRoleId()));
        }

        return $utilisateur;
    }

    public function insert(Utilisateur $utilisateur): void 
    {
        $connexion = $this->getConnexion();
        try {
            $connexion->beginTransaction();

            $requete = $connexion->prepare("INSERT INTO utilisateur(nom_utilisateur, nom, prenom, bio, date_creation, url_avatar, hash, role_id) VALUES(:nom_utilisateur, :nom, :prenom, :bio, NOW(), :url_avatar, :hash, :role_id)");
            $requete->bindValue(":nom_utilisateur", $utilisateur->getNomUtilisateur());
            $requete->bindValue(":nom", $utilisateur->getNom());
            $requete->bindValue(":prenom", $utilisateur->getPrenom());
            $requete->bindValue(":bio", $utilisateur->getBio());
            $requete->bindValue(":url_avatar", $utilisateur->getUrlAvatar());
            $requete->bindValue(":hash", $utilisateur->getHash());
            $requete->bindValue(":role_id", $utilisateur->getRoleId());
            $requete->execute();

            $id = $connexion->lastInsertId();
            $utilisateur->setId($id);
            
            $connexion->commit();
        } 
        catch (PDOException $e) 
        {
            $connexion->rollBack();
            throw $e;
        }
    }

    public function update(Utilisateur $utilisateur): void
    {
        $connexion = $this->getConnexion();
        try
        {
            $connexion->beginTransaction();

            $requete = $connexion->prepare("UPDATE utilisateur SET nom=:nom, prenom=:prenom, bio=:bio, url_avatar=:url_avatar WHERE id=:id");
            $requete->bindValue(":nom", $utilisateur->getNom());
            $requete->bindValue(":prenom", $utilisateur->getPrenom());
            $requete->bindValue(":bio", $utilisateur->getBio());
            $requete->bindValue(":url_avatar", $utilisateur->getUrlAvatar());
            $requete->bindValue(":id", $utilisateur->getId());

            $requete->execute();

            $connexion->commit();
        }
        catch(PDOException $e)
        {
            $connexion->rollBack();
            throw $e;
        }
    }

    // 2ème fonctionnalité à implémenter pour le bonus
    public function changerMotDePasse(int $id, string $nouveauHash): void
    {
        $connexion = $this->getConnexion();

        try {
            $connexion->beginTransaction();

            $requete = $connexion->prepare("
                UPDATE utilisateur 
                SET hash = :hash 
                WHERE id = :id
            ");

            $requete->bindValue(":hash", $nouveauHash);
            $requete->bindValue(":id", $id);
            $requete->execute();

            $connexion->commit();
        } catch (PDOException $e) {
            $connexion->rollBack();
            throw $e;
        }
    }


    public function selectParNomUtilisateur(string $nomUtilisateur): ?Utilisateur
    {
        $connexion = $this->getConnexion();

        $requete = $connexion->prepare("SELECT * FROM utilisateur WHERE nom_utilisateur=:nom_utilisateur");
        $requete->bindValue(":nom_utilisateur", $nomUtilisateur);
        $requete->execute();

        $utilisateur = null;
        if ($enregistrement = $requete->fetch())
        {
            $utilisateur = $this->construireUtilisateur($enregistrement);
            $utilisateur->setRole($this->roleDao->select($utilisateur->getRoleId()));
        }

        return $utilisateur;
    }

    private function construireUtilisateur($enregistrement): ?Utilisateur
    {
        return new Utilisateur(
            $enregistrement['nom_utilisateur'],
            $enregistrement['prenom'],
            $enregistrement['nom'],
            $enregistrement['bio'],
            new DateTime($enregistrement['date_creation']),
            $enregistrement['role_id'],
            $enregistrement['url_avatar'],
            $enregistrement['hash'],
            $enregistrement['id']
        );
    }
}
