<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212121845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alerte (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, destinateur_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, objet VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, lecteur VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_3AE753AA76ED395 (user_id), INDEX IDX_3AE753AC631C63F (destinateur_id), INDEX IDX_3AE753AB03A8386 (created_by_id), INDEX IDX_3AE753A896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, image_id INT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, status INT NOT NULL, text LONGTEXT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_23A0E66A76ED395 (user_id), INDEX IDX_23A0E663DA5256D (image_id), INDEX IDX_23A0E66B03A8386 (created_by_id), INDEX IDX_23A0E66896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE civilite (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2C4C1BD6B03A8386 (created_by_id), INDEX IDX_2C4C1BD6896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE code (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, state INT DEFAULT 0 NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_7715309877153098 (code), INDEX IDX_77153098B03A8386 (created_by_id), INDEX IDX_77153098896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, user_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, commentaire LONGTEXT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_67F068BC7294869C (article_id), INDEX IDX_67F068BCA76ED395 (user_id), INDEX IDX_67F068BCB03A8386 (created_by_id), INDEX IDX_67F068BC896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE destinateur (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_93F0615AB03A8386 (created_by_id), INDEX IDX_93F0615A896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_835033F8B03A8386 (created_by_id), INDEX IDX_835033F8896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membre_entite (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, appartenir_organisation VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', discr VARCHAR(18) NOT NULL, UNIQUE INDEX UNIQ_80208D9EA76ED395 (user_id), INDEX IDX_80208D9EB03A8386 (created_by_id), INDEX IDX_80208D9E896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membre_etablissement (id INT NOT NULL, type_personne_id INT DEFAULT NULL, genre_id INT DEFAULT NULL, photo_physique_id INT DEFAULT NULL, cni_physique_id INT DEFAULT NULL, diplome_file_physique_id INT DEFAULT NULL, cv_physique_id INT DEFAULT NULL, dfe_physique_id INT DEFAULT NULL, nom_entreprise VARCHAR(255) DEFAULT NULL, nature_entreprise VARCHAR(255) DEFAULT NULL, INDEX IDX_756B71EE9020683 (type_personne_id), INDEX IDX_756B71E4296D31F (genre_id), INDEX IDX_756B71E5D470B44 (photo_physique_id), INDEX IDX_756B71E3C08B56B (cni_physique_id), INDEX IDX_756B71E2C64CC30 (diplome_file_physique_id), INDEX IDX_756B71E4DAEE365 (cv_physique_id), INDEX IDX_756B71EEEA1DBC (dfe_physique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membre_professionnel (id INT NOT NULL, civilite_id INT DEFAULT NULL, nationate_id INT DEFAULT NULL, photo_id INT DEFAULT NULL, diplome_file_id INT DEFAULT NULL, cni_id INT DEFAULT NULL, cv_id INT DEFAULT NULL, casier_id INT DEFAULT NULL, certificat_id INT DEFAULT NULL, specialite_id INT DEFAULT NULL, genre_id INT DEFAULT NULL, ville_id INT DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenoms VARCHAR(255) DEFAULT NULL, email_pro VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, professionnel VARCHAR(255) DEFAULT NULL, address_pro VARCHAR(255) DEFAULT NULL, profession VARCHAR(255) DEFAULT NULL, date_naissance DATETIME DEFAULT NULL, lieu_residence VARCHAR(255) DEFAULT NULL, adresse_email VARCHAR(255) DEFAULT NULL, contact_pro VARCHAR(255) DEFAULT NULL, situation VARCHAR(255) DEFAULT NULL, diplome VARCHAR(255) DEFAULT NULL, date_diplome DATETIME DEFAULT NULL, date_emploi DATETIME DEFAULT NULL, situation_pro VARCHAR(255) DEFAULT NULL, status VARCHAR(255) NOT NULL, reason LONGTEXT DEFAULT NULL, lieu_diplome VARCHAR(255) NOT NULL, INDEX IDX_5D832F3D39194ABF (civilite_id), INDEX IDX_5D832F3DACCE26D0 (nationate_id), INDEX IDX_5D832F3D7E9E4C8C (photo_id), INDEX IDX_5D832F3DD9D660AE (diplome_file_id), INDEX IDX_5D832F3DC68EB1D0 (cni_id), INDEX IDX_5D832F3DCFE419E2 (cv_id), INDEX IDX_5D832F3D643911C6 (casier_id), INDEX IDX_5D832F3DFA55BACF (certificat_id), INDEX IDX_5D832F3D2195E0F0 (specialite_id), INDEX IDX_5D832F3D4296D31F (genre_id), INDEX IDX_5D832F3DA73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, receiver_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, message LONGTEXT NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307FCD53EDB6 (receiver_id), INDEX IDX_B6BD307FB03A8386 (created_by_id), INDEX IDX_B6BD307F896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation (id INT AUTO_INCREMENT NOT NULL, entite_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, numero VARCHAR(255) DEFAULT NULL, annee VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E6E132B49BEA957A (entite_id), INDEX IDX_E6E132B4B03A8386 (created_by_id), INDEX IDX_E6E132B4896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE param_fichier (id INT AUTO_INCREMENT NOT NULL, size INT DEFAULT NULL, path VARCHAR(255) DEFAULT NULL, alt VARCHAR(255) DEFAULT NULL, date_creation DATETIME NOT NULL, url VARCHAR(5) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_349F3CAEB03A8386 (created_by_id), INDEX IDX_349F3CAE896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialite (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E7D6FCC1B03A8386 (created_by_id), INDEX IDX_E7D6FCC1896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, montant VARCHAR(255) NOT NULL, reference VARCHAR(255) NOT NULL, reference_channel VARCHAR(255) DEFAULT NULL, channel VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL, state INT NOT NULL, data LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_723705D1A76ED395 (user_id), INDEX IDX_723705D1B03A8386 (created_by_id), INDEX IDX_723705D1896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_personne (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C231EE13B03A8386 (created_by_id), INDEX IDX_C231EE13896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, avatar_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, username VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, type_user VARCHAR(255) DEFAULT NULL, payement VARCHAR(255) NOT NULL, data LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_1D1C63B3F85E0677 (username), INDEX IDX_1D1C63B386383B10 (avatar_id), INDEX IDX_1D1C63B3B03A8386 (created_by_id), INDEX IDX_1D1C63B3896DBBDE (updated_by_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, updated_by_id INT DEFAULT NULL, code VARCHAR(255) NOT NULL, libelle VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_43C3D9C3B03A8386 (created_by_id), INDEX IDX_43C3D9C3896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alerte ADD CONSTRAINT FK_3AE753AA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE alerte ADD CONSTRAINT FK_3AE753AC631C63F FOREIGN KEY (destinateur_id) REFERENCES destinateur (id)');
        $this->addSql('ALTER TABLE alerte ADD CONSTRAINT FK_3AE753AB03A8386 FOREIGN KEY (created_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE alerte ADD CONSTRAINT FK_3AE753A896DBBDE FOREIGN KEY (updated_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E663DA5256D FOREIGN KEY (image_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66B03A8386 FOREIGN KEY (created_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66896DBBDE FOREIGN KEY (updated_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE civilite ADD CONSTRAINT FK_2C4C1BD6B03A8386 FOREIGN KEY (created_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE civilite ADD CONSTRAINT FK_2C4C1BD6896DBBDE FOREIGN KEY (updated_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE code ADD CONSTRAINT FK_77153098B03A8386 FOREIGN KEY (created_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE code ADD CONSTRAINT FK_77153098896DBBDE FOREIGN KEY (updated_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCB03A8386 FOREIGN KEY (created_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC896DBBDE FOREIGN KEY (updated_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE destinateur ADD CONSTRAINT FK_93F0615AB03A8386 FOREIGN KEY (created_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE destinateur ADD CONSTRAINT FK_93F0615A896DBBDE FOREIGN KEY (updated_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE genre ADD CONSTRAINT FK_835033F8B03A8386 FOREIGN KEY (created_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE genre ADD CONSTRAINT FK_835033F8896DBBDE FOREIGN KEY (updated_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE membre_entite ADD CONSTRAINT FK_80208D9EA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE membre_entite ADD CONSTRAINT FK_80208D9EB03A8386 FOREIGN KEY (created_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE membre_entite ADD CONSTRAINT FK_80208D9E896DBBDE FOREIGN KEY (updated_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71EE9020683 FOREIGN KEY (type_personne_id) REFERENCES type_personne (id)');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71E4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71E5D470B44 FOREIGN KEY (photo_physique_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71E3C08B56B FOREIGN KEY (cni_physique_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71E2C64CC30 FOREIGN KEY (diplome_file_physique_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71E4DAEE365 FOREIGN KEY (cv_physique_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71EEEA1DBC FOREIGN KEY (dfe_physique_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71EBF396750 FOREIGN KEY (id) REFERENCES membre_entite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE membre_professionnel ADD CONSTRAINT FK_5D832F3D39194ABF FOREIGN KEY (civilite_id) REFERENCES civilite (id)');
        $this->addSql('ALTER TABLE membre_professionnel ADD CONSTRAINT FK_5D832F3DACCE26D0 FOREIGN KEY (nationate_id) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE membre_professionnel ADD CONSTRAINT FK_5D832F3D7E9E4C8C FOREIGN KEY (photo_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE membre_professionnel ADD CONSTRAINT FK_5D832F3DD9D660AE FOREIGN KEY (diplome_file_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE membre_professionnel ADD CONSTRAINT FK_5D832F3DC68EB1D0 FOREIGN KEY (cni_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE membre_professionnel ADD CONSTRAINT FK_5D832F3DCFE419E2 FOREIGN KEY (cv_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE membre_professionnel ADD CONSTRAINT FK_5D832F3D643911C6 FOREIGN KEY (casier_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE membre_professionnel ADD CONSTRAINT FK_5D832F3DFA55BACF FOREIGN KEY (certificat_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE membre_professionnel ADD CONSTRAINT FK_5D832F3D2195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('ALTER TABLE membre_professionnel ADD CONSTRAINT FK_5D832F3D4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE membre_professionnel ADD CONSTRAINT FK_5D832F3DA73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE membre_professionnel ADD CONSTRAINT FK_5D832F3DBF396750 FOREIGN KEY (id) REFERENCES membre_entite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB03A8386 FOREIGN KEY (created_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F896DBBDE FOREIGN KEY (updated_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE organisation ADD CONSTRAINT FK_E6E132B49BEA957A FOREIGN KEY (entite_id) REFERENCES membre_entite (id)');
        $this->addSql('ALTER TABLE organisation ADD CONSTRAINT FK_E6E132B4B03A8386 FOREIGN KEY (created_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE organisation ADD CONSTRAINT FK_E6E132B4896DBBDE FOREIGN KEY (updated_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE pays ADD CONSTRAINT FK_349F3CAEB03A8386 FOREIGN KEY (created_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE pays ADD CONSTRAINT FK_349F3CAE896DBBDE FOREIGN KEY (updated_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE specialite ADD CONSTRAINT FK_E7D6FCC1B03A8386 FOREIGN KEY (created_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE specialite ADD CONSTRAINT FK_E7D6FCC1896DBBDE FOREIGN KEY (updated_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1B03A8386 FOREIGN KEY (created_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1896DBBDE FOREIGN KEY (updated_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE type_personne ADD CONSTRAINT FK_C231EE13B03A8386 FOREIGN KEY (created_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE type_personne ADD CONSTRAINT FK_C231EE13896DBBDE FOREIGN KEY (updated_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B386383B10 FOREIGN KEY (avatar_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3B03A8386 FOREIGN KEY (created_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE utilisateur ADD CONSTRAINT FK_1D1C63B3896DBBDE FOREIGN KEY (updated_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE ville ADD CONSTRAINT FK_43C3D9C3B03A8386 FOREIGN KEY (created_by_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE ville ADD CONSTRAINT FK_43C3D9C3896DBBDE FOREIGN KEY (updated_by_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alerte DROP FOREIGN KEY FK_3AE753AA76ED395');
        $this->addSql('ALTER TABLE alerte DROP FOREIGN KEY FK_3AE753AC631C63F');
        $this->addSql('ALTER TABLE alerte DROP FOREIGN KEY FK_3AE753AB03A8386');
        $this->addSql('ALTER TABLE alerte DROP FOREIGN KEY FK_3AE753A896DBBDE');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A76ED395');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E663DA5256D');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66B03A8386');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66896DBBDE');
        $this->addSql('ALTER TABLE civilite DROP FOREIGN KEY FK_2C4C1BD6B03A8386');
        $this->addSql('ALTER TABLE civilite DROP FOREIGN KEY FK_2C4C1BD6896DBBDE');
        $this->addSql('ALTER TABLE code DROP FOREIGN KEY FK_77153098B03A8386');
        $this->addSql('ALTER TABLE code DROP FOREIGN KEY FK_77153098896DBBDE');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC7294869C');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCA76ED395');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCB03A8386');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC896DBBDE');
        $this->addSql('ALTER TABLE destinateur DROP FOREIGN KEY FK_93F0615AB03A8386');
        $this->addSql('ALTER TABLE destinateur DROP FOREIGN KEY FK_93F0615A896DBBDE');
        $this->addSql('ALTER TABLE genre DROP FOREIGN KEY FK_835033F8B03A8386');
        $this->addSql('ALTER TABLE genre DROP FOREIGN KEY FK_835033F8896DBBDE');
        $this->addSql('ALTER TABLE membre_entite DROP FOREIGN KEY FK_80208D9EA76ED395');
        $this->addSql('ALTER TABLE membre_entite DROP FOREIGN KEY FK_80208D9EB03A8386');
        $this->addSql('ALTER TABLE membre_entite DROP FOREIGN KEY FK_80208D9E896DBBDE');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71EE9020683');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71E4296D31F');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71E5D470B44');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71E3C08B56B');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71E2C64CC30');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71E4DAEE365');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71EEEA1DBC');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71EBF396750');
        $this->addSql('ALTER TABLE membre_professionnel DROP FOREIGN KEY FK_5D832F3D39194ABF');
        $this->addSql('ALTER TABLE membre_professionnel DROP FOREIGN KEY FK_5D832F3DACCE26D0');
        $this->addSql('ALTER TABLE membre_professionnel DROP FOREIGN KEY FK_5D832F3D7E9E4C8C');
        $this->addSql('ALTER TABLE membre_professionnel DROP FOREIGN KEY FK_5D832F3DD9D660AE');
        $this->addSql('ALTER TABLE membre_professionnel DROP FOREIGN KEY FK_5D832F3DC68EB1D0');
        $this->addSql('ALTER TABLE membre_professionnel DROP FOREIGN KEY FK_5D832F3DCFE419E2');
        $this->addSql('ALTER TABLE membre_professionnel DROP FOREIGN KEY FK_5D832F3D643911C6');
        $this->addSql('ALTER TABLE membre_professionnel DROP FOREIGN KEY FK_5D832F3DFA55BACF');
        $this->addSql('ALTER TABLE membre_professionnel DROP FOREIGN KEY FK_5D832F3D2195E0F0');
        $this->addSql('ALTER TABLE membre_professionnel DROP FOREIGN KEY FK_5D832F3D4296D31F');
        $this->addSql('ALTER TABLE membre_professionnel DROP FOREIGN KEY FK_5D832F3DA73F0036');
        $this->addSql('ALTER TABLE membre_professionnel DROP FOREIGN KEY FK_5D832F3DBF396750');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB03A8386');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F896DBBDE');
        $this->addSql('ALTER TABLE organisation DROP FOREIGN KEY FK_E6E132B49BEA957A');
        $this->addSql('ALTER TABLE organisation DROP FOREIGN KEY FK_E6E132B4B03A8386');
        $this->addSql('ALTER TABLE organisation DROP FOREIGN KEY FK_E6E132B4896DBBDE');
        $this->addSql('ALTER TABLE pays DROP FOREIGN KEY FK_349F3CAEB03A8386');
        $this->addSql('ALTER TABLE pays DROP FOREIGN KEY FK_349F3CAE896DBBDE');
        $this->addSql('ALTER TABLE specialite DROP FOREIGN KEY FK_E7D6FCC1B03A8386');
        $this->addSql('ALTER TABLE specialite DROP FOREIGN KEY FK_E7D6FCC1896DBBDE');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1A76ED395');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1B03A8386');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1896DBBDE');
        $this->addSql('ALTER TABLE type_personne DROP FOREIGN KEY FK_C231EE13B03A8386');
        $this->addSql('ALTER TABLE type_personne DROP FOREIGN KEY FK_C231EE13896DBBDE');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B386383B10');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3B03A8386');
        $this->addSql('ALTER TABLE utilisateur DROP FOREIGN KEY FK_1D1C63B3896DBBDE');
        $this->addSql('ALTER TABLE ville DROP FOREIGN KEY FK_43C3D9C3B03A8386');
        $this->addSql('ALTER TABLE ville DROP FOREIGN KEY FK_43C3D9C3896DBBDE');
        $this->addSql('DROP TABLE alerte');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE civilite');
        $this->addSql('DROP TABLE code');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE destinateur');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE membre_entite');
        $this->addSql('DROP TABLE membre_etablissement');
        $this->addSql('DROP TABLE membre_professionnel');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE organisation');
        $this->addSql('DROP TABLE param_fichier');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP TABLE specialite');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE type_personne');
        $this->addSql('DROP TABLE utilisateur');
        $this->addSql('DROP TABLE ville');
    }
}
