<?php

class PartieDao extends BaseDao
{
    private UtilisateurDao $utilisateurDao;
    private JeuDao $jeuDao;

    public function __construct(ConfigDao $config)
    {
        parent::__construct($config);
        $this->utilisateurDao = new UtilisateurDao($config);
        $this->jeuDao = new JeuDao($config);
    }

    public function selectAll(int $limite = 0): array
    {
        $connexion = $this->getConnexion();

        $requeteDeBase = "SELECT * FROM partie ORDER BY date_creation DESC";

        if ($limite > 0) {
            $requeteDeBase .= " LIMIT :limite";
        }

        $requete = $connexion->prepare($requeteDeBase);
        if ($limite > 0)
            $requete->bindValue(":limite", $limite, PDO::PARAM_INT); // commentaire expliquant PDO...

        $requete->execute();

        $parties = []; // equivaut a $parties = array() old syntax
        while($enregistrement = $requete->fetch())
        {
            $partie = $this->construirePartie($enregistrement);

            // $partie->setJoueur1($this->utilisateurDao->select($partie->getJoueur1Id()));
            // $partie->setJoueur2($this->utilisateurDao->select($partie->getJoueur2Id()));
            // $partie->setJeu($this->jeuDao->select($partie->getJeuId()));

            $parties[] = $partie;
        }

        return $parties;
    }

    public function selectParJoueur(int $joueurId): array
    {
        $connexion = $this->getConnexion();

        $requete = $connexion->prepare("SELECT * FROM partie WHERE j1_id=:joueur_id OR j2_id=:joueur_id ORDER BY date_creation DESC");
        $requete->bindValue(":joueur_id", $joueurId);
        $requete->execute();
        
        $parties = [];
        while ($enregistrement = $requete->fetch())
        {
            $partie = $this->construirePartie($enregistrement);

            // $partie->setJoueur1($this->utilisateurDao->select($partie->getJoueur1Id()));
            // $partie->setJoueur2($this->utilisateurDao->select($partie->getJoueur2Id()));
            // $partie->setJeu($this->jeuDao->select($partie->getJeuId()));

            $parties[] = $partie;
        }

        return $parties;
    }

    public function insert(Partie $partie): void
    {
        $connexion = $this->getConnexion();
        try
        {
            $connexion->beginTransaction();

            $requete = $connexion->prepare("INSERT INTO partie(date_creation, j1_id, j2_id, j1_score, j2_score, jeu_id) VALUES(NOW(), :j1_id, :j2_id, :j1_score, :j2_score, :jeu_id)");
            $requete->bindValue(":j1_id", $partie->getJoueur1Id());
            $requete->bindValue(":j2_id", $partie->getJoueur2Id());
            $requete->bindValue(":j1_score", $partie->getScoreJoueur1());
            $requete->bindValue(":j2_score", $partie->getScoreJoueur2());
            $requete->bindValue(":jeu_id", $partie->getJeuId());
            $requete->execute();

            $id = $connexion->lastInsertId();
            $partie->setId($id);

            $connexion->commit();
        }
        catch (PDOException $e)
        {
            $connexion->rollBack();
            throw $e;
        }
    }

    public function delete(int $id): void
    {
        $connexion = $this->getConnexion();
        try
        {
            $connexion->beginTransaction();

            $requete = $connexion->prepare("DELETE FROM partie WHERE id=:id");
            $requete->bindValue(":id", $id);
            $requete->execute();

            $connexion->commit();
        }
        catch (PDOException $e)
        {
            $connexion->rollBack();
            throw $e;
        }
    }

    private function construirePartie($enregistrement): ?Partie
    {
        return new Partie(
            new DateTime($enregistrement['date_creation']),
            $enregistrement['j1_id'],
            $enregistrement['j2_id'],
            $enregistrement['j1_score'],
            $enregistrement['j2_score'],
            $enregistrement['jeu_id'],
            $enregistrement['id']
        );
    }
}