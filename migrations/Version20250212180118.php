<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250212180118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE membre_etablissement DROP FOREIGN KEY FK_756B71EE9020683');
        $this->addSql('DROP INDEX IDX_756B71EE9020683 ON membre_etablissement');
        $this->addSql('ALTER TABLE membre_etablissement ADD type_personne VARCHAR(255) DEFAULT NULL, DROP type_personne_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE membre_etablissement ADD type_personne_id INT DEFAULT NULL, DROP type_personne');
        $this->addSql('ALTER TABLE membre_etablissement ADD CONSTRAINT FK_756B71EE9020683 FOREIGN KEY (type_personne_id) REFERENCES type_personne (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_756B71EE9020683 ON membre_etablissement (type_personne_id)');
    }
}
