<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212152326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71E2C64CC30');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71E3C08B56B');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71E4DAEE365');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71E5D470B44');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71EEEA1DBC');
        $this->addSql('DROP INDEX IDX_756B71EEEA1DBC ON membre_etablissement');
        $this->addSql('DROP INDEX IDX_756B71E4DAEE365 ON membre_etablissement');
        $this->addSql('DROP INDEX IDX_756B71E2C64CC30 ON membre_etablissement');
        $this->addSql('DROP INDEX IDX_756B71E3C08B56B ON membre_etablissement');
        $this->addSql('DROP INDEX IDX_756B71E5D470B44 ON membre_etablissement');
        $this->addSql('ALTER TABLE membre_etablissement ADD photo_id INT DEFAULT NULL, ADD cni_id INT DEFAULT NULL, ADD diplome_file_id INT DEFAULT NULL, ADD cv_id INT DEFAULT NULL, ADD dfe_id INT DEFAULT NULL, ADD ordre_national_id INT DEFAULT NULL, ADD type_entreprise VARCHAR(255) DEFAULT NULL, ADD gps_entreprise VARCHAR(255) DEFAULT NULL, ADD niveau_entreprise VARCHAR(255) DEFAULT NULL, ADD contact_entreprise VARCHAR(255) DEFAULT NULL, ADD email_entreprise VARCHAR(255) DEFAULT NULL, ADD space_entreprise VARCHAR(255) DEFAULT NULL, ADD nom_complet_promoteur VARCHAR(255) DEFAULT NULL, ADD email_pro VARCHAR(255) DEFAULT NULL, ADD profession VARCHAR(255) DEFAULT NULL, ADD contacts_promoteur VARCHAR(255) DEFAULT NULL, ADD lieu_residence VARCHAR(255) DEFAULT NULL, ADD numero_cni VARCHAR(255) DEFAULT NULL, ADD nom_complet_technique VARCHAR(255) DEFAULT NULL, ADD email_pro_technique VARCHAR(255) DEFAULT NULL, ADD profession_technique VARCHAR(255) DEFAULT NULL, ADD contact_pro_technique VARCHAR(255) DEFAULT NULL, ADD lieu_residence_technique VARCHAR(255) DEFAULT NULL, ADD numero_ordre_technique VARCHAR(255) DEFAULT NULL, DROP photo_physique_id, DROP cni_physique_id, DROP diplome_file_physique_id, DROP cv_physique_id, DROP dfe_physique_id');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71E7E9E4C8C FOREIGN KEY (photo_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71EC68EB1D0 FOREIGN KEY (cni_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71ED9D660AE FOREIGN KEY (diplome_file_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71ECFE419E2 FOREIGN KEY (cv_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71EA12D7511 FOREIGN KEY (dfe_id) REFERENCES param_fichier (id)');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71EE7F05B3F FOREIGN KEY (ordre_national_id) REFERENCES param_fichier (id)');
        $this->addSql('CREATE INDEX IDX_756B71E7E9E4C8C ON membre_etablissement (photo_id)');
        $this->addSql('CREATE INDEX IDX_756B71EC68EB1D0 ON membre_etablissement (cni_id)');
        $this->addSql('CREATE INDEX IDX_756B71ED9D660AE ON membre_etablissement (diplome_file_id)');
        $this->addSql('CREATE INDEX IDX_756B71ECFE419E2 ON membre_etablissement (cv_id)');
        $this->addSql('CREATE INDEX IDX_756B71EA12D7511 ON membre_etablissement (dfe_id)');
        $this->addSql('CREATE INDEX IDX_756B71EE7F05B3F ON membre_etablissement (ordre_national_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71E7E9E4C8C');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71EC68EB1D0');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71ED9D660AE');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71ECFE419E2');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71EA12D7511');
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71EE7F05B3F');
        $this->addSql('DROP INDEX IDX_756B71E7E9E4C8C ON membre_etablissement');
        $this->addSql('DROP INDEX IDX_756B71EC68EB1D0 ON membre_etablissement');
        $this->addSql('DROP INDEX IDX_756B71ED9D660AE ON membre_etablissement');
        $this->addSql('DROP INDEX IDX_756B71ECFE419E2 ON membre_etablissement');
        $this->addSql('DROP INDEX IDX_756B71EA12D7511 ON membre_etablissement');
        $this->addSql('DROP INDEX IDX_756B71EE7F05B3F ON membre_etablissement');
        $this->addSql('ALTER TABLE membre_etablissement ADD photo_physique_id INT DEFAULT NULL, ADD cni_physique_id INT DEFAULT NULL, ADD diplome_file_physique_id INT DEFAULT NULL, ADD cv_physique_id INT DEFAULT NULL, ADD dfe_physique_id INT DEFAULT NULL, DROP photo_id, DROP cni_id, DROP diplome_file_id, DROP cv_id, DROP dfe_id, DROP ordre_national_id, DROP type_entreprise, DROP gps_entreprise, DROP niveau_entreprise, DROP contact_entreprise, DROP email_entreprise, DROP space_entreprise, DROP nom_complet_promoteur, DROP email_pro, DROP profession, DROP contacts_promoteur, DROP lieu_residence, DROP numero_cni, DROP nom_complet_technique, DROP email_pro_technique, DROP profession_technique, DROP contact_pro_technique, DROP lieu_residence_technique, DROP numero_ordre_technique');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71E2C64CC30 FOREIGN KEY (diplome_file_physique_id) REFERENCES param_fichier (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71E3C08B56B FOREIGN KEY (cni_physique_id) REFERENCES param_fichier (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71E4DAEE365 FOREIGN KEY (cv_physique_id) REFERENCES param_fichier (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71E5D470B44 FOREIGN KEY (photo_physique_id) REFERENCES param_fichier (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71EEEA1DBC FOREIGN KEY (dfe_physique_id) REFERENCES param_fichier (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_756B71EEEA1DBC ON membre_etablissement (dfe_physique_id)');
        $this->addSql('CREATE INDEX IDX_756B71E4DAEE365 ON membre_etablissement (cv_physique_id)');
        $this->addSql('CREATE INDEX IDX_756B71E2C64CC30 ON membre_etablissement (diplome_file_physique_id)');
        $this->addSql('CREATE INDEX IDX_756B71E3C08B56B ON membre_etablissement (cni_physique_id)');
        $this->addSql('CREATE INDEX IDX_756B71E5D470B44 ON membre_etablissement (photo_physique_id)');
    }
}
