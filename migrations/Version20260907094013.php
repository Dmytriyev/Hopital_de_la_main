<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260907094013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chambre (id INT AUTO_INCREMENT NOT NULL, numero_chambre INT NOT NULL, type_chambre VARCHAR(50) NOT NULL, alimentation VARCHAR(255) DEFAULT NULL, duree_occupation TIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE consultation (id INT AUTO_INCREMENT NOT NULL, matricule INT NOT NULL, compte_rendu LONGTEXT NOT NULL, created_at DATETIME NOT NULL, mes_consultation_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, INDEX IDX_964685A6412F3F4A (mes_consultation_id), INDEX IDX_964685A66B899279 (patient_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE dossier_patient (id INT AUTO_INCREMENT NOT NULL, sexe VARCHAR(50) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, adresse LONGTEXT DEFAULT NULL, code_postale INT DEFAULT NULL, telephone VARCHAR(255) DEFAULT NULL, numero_secu VARCHAR(50) NOT NULL, nom_confiance VARCHAR(255) DEFAULT NULL, prenom_confiance VARCHAR(255) DEFAULT NULL, telephone_confiance VARCHAR(255) DEFAULT NULL, details LONGTEXT DEFAULT NULL, numero_dossier INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, nom_medecin_traitant_id INT DEFAULT NULL, nouveau_patient_id INT DEFAULT NULL, secretaire_id INT DEFAULT NULL, INDEX IDX_58803ED3B946BB9 (nom_medecin_traitant_id), INDEX IDX_58803ED3A9B4D688 (nouveau_patient_id), INDEX IDX_58803ED3A90F02B2 (secretaire_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) DEFAULT NULL, image_file VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, image_patient_id INT DEFAULT NULL, INDEX IDX_C53D045F757854F (image_patient_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE observation (id INT AUTO_INCREMENT NOT NULL, matricule INT NOT NULL, observation LONGTEXT NOT NULL, created_at DATETIME NOT NULL, mes_observation_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, INDEX IDX_C576DBE01D3DF77F (mes_observation_id), INDEX IDX_C576DBE06B899279 (patient_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE prescription (id INT AUTO_INCREMENT NOT NULL, traitement LONGTEXT NOT NULL, duree_traitement VARCHAR(255) NOT NULL, details LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, mes_prescriptions_id INT DEFAULT NULL, INDEX IDX_1FBFB8D93FCC9DED (mes_prescriptions_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE rdv (id INT AUTO_INCREMENT NOT NULL, date_rdv DATE NOT NULL, heur_rdv TIME NOT NULL, created_at DATETIME NOT NULL, secretaire_id INT DEFAULT NULL, medecin_id INT DEFAULT NULL, patient_id INT NOT NULL, INDEX IDX_10C31F86A90F02B2 (secretaire_id), INDEX IDX_10C31F864F31A84 (medecin_id), INDEX IDX_10C31F866B899279 (patient_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE rdv_operatoire (id INT AUTO_INCREMENT NOT NULL, date_rdv_operatoire DATE NOT NULL, heur_rdv_operatoire TIME NOT NULL, details LONGTEXT DEFAULT NULL, chambre_id INT DEFAULT NULL, salle_operatoire_id INT DEFAULT NULL, secretaire_id INT DEFAULT NULL, patient_id INT DEFAULT NULL, INDEX IDX_CC1A434D9B177F54 (chambre_id), INDEX IDX_CC1A434D88AA54BD (salle_operatoire_id), INDEX IDX_CC1A434DA90F02B2 (secretaire_id), INDEX IDX_CC1A434D6B899279 (patient_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE rdv_operatoire_user (rdv_operatoire_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_8F8A739D38353ED3 (rdv_operatoire_id), INDEX IDX_8F8A739DA76ED395 (user_id), PRIMARY KEY (rdv_operatoire_id, user_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE salle_operatoire (id INT AUTO_INCREMENT NOT NULL, numero_salle INT NOT NULL, heur_operation TIME NOT NULL, duree_operation TIME NOT NULL, details LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE specialite (id INT AUTO_INCREMENT NOT NULL, specialisation VARCHAR(255) NOT NULL, date_delivrance DATE NOT NULL, details LONGTEXT DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, sexe VARCHAR(50) DEFAULT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, adresse LONGTEXT NOT NULL, code_postale INT NOT NULL, telephone VARCHAR(255) DEFAULT NULL, matricule INT DEFAULT NULL, post VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_specialite (user_id INT NOT NULL, specialite_id INT NOT NULL, INDEX IDX_40B13A2DA76ED395 (user_id), INDEX IDX_40B13A2D2195E0F0 (specialite_id), PRIMARY KEY (user_id, specialite_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_dossier_patient (user_id INT NOT NULL, dossier_patient_id INT NOT NULL, INDEX IDX_4A5C525CA76ED395 (user_id), INDEX IDX_4A5C525C45F3D65C (dossier_patient_id), PRIMARY KEY (user_id, dossier_patient_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A6412F3F4A FOREIGN KEY (mes_consultation_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A66B899279 FOREIGN KEY (patient_id) REFERENCES dossier_patient (id)');
        $this->addSql('ALTER TABLE dossier_patient ADD CONSTRAINT FK_58803ED3B946BB9 FOREIGN KEY (nom_medecin_traitant_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE dossier_patient ADD CONSTRAINT FK_58803ED3A9B4D688 FOREIGN KEY (nouveau_patient_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE dossier_patient ADD CONSTRAINT FK_58803ED3A90F02B2 FOREIGN KEY (secretaire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F757854F FOREIGN KEY (image_patient_id) REFERENCES dossier_patient (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE01D3DF77F FOREIGN KEY (mes_observation_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE observation ADD CONSTRAINT FK_C576DBE06B899279 FOREIGN KEY (patient_id) REFERENCES dossier_patient (id)');
        $this->addSql('ALTER TABLE prescription ADD CONSTRAINT FK_1FBFB8D93FCC9DED FOREIGN KEY (mes_prescriptions_id) REFERENCES consultation (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F86A90F02B2 FOREIGN KEY (secretaire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F864F31A84 FOREIGN KEY (medecin_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rdv ADD CONSTRAINT FK_10C31F866B899279 FOREIGN KEY (patient_id) REFERENCES dossier_patient (id)');
        $this->addSql('ALTER TABLE rdv_operatoire ADD CONSTRAINT FK_CC1A434D9B177F54 FOREIGN KEY (chambre_id) REFERENCES chambre (id)');
        $this->addSql('ALTER TABLE rdv_operatoire ADD CONSTRAINT FK_CC1A434D88AA54BD FOREIGN KEY (salle_operatoire_id) REFERENCES salle_operatoire (id)');
        $this->addSql('ALTER TABLE rdv_operatoire ADD CONSTRAINT FK_CC1A434DA90F02B2 FOREIGN KEY (secretaire_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rdv_operatoire ADD CONSTRAINT FK_CC1A434D6B899279 FOREIGN KEY (patient_id) REFERENCES dossier_patient (id)');
        $this->addSql('ALTER TABLE rdv_operatoire_user ADD CONSTRAINT FK_8F8A739D38353ED3 FOREIGN KEY (rdv_operatoire_id) REFERENCES rdv_operatoire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rdv_operatoire_user ADD CONSTRAINT FK_8F8A739DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_specialite ADD CONSTRAINT FK_40B13A2DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_specialite ADD CONSTRAINT FK_40B13A2D2195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_dossier_patient ADD CONSTRAINT FK_4A5C525CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_dossier_patient ADD CONSTRAINT FK_4A5C525C45F3D65C FOREIGN KEY (dossier_patient_id) REFERENCES dossier_patient (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A6412F3F4A');
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A66B899279');
        $this->addSql('ALTER TABLE dossier_patient DROP FOREIGN KEY FK_58803ED3B946BB9');
        $this->addSql('ALTER TABLE dossier_patient DROP FOREIGN KEY FK_58803ED3A9B4D688');
        $this->addSql('ALTER TABLE dossier_patient DROP FOREIGN KEY FK_58803ED3A90F02B2');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F757854F');
        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE01D3DF77F');
        $this->addSql('ALTER TABLE observation DROP FOREIGN KEY FK_C576DBE06B899279');
        $this->addSql('ALTER TABLE prescription DROP FOREIGN KEY FK_1FBFB8D93FCC9DED');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F86A90F02B2');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F864F31A84');
        $this->addSql('ALTER TABLE rdv DROP FOREIGN KEY FK_10C31F866B899279');
        $this->addSql('ALTER TABLE rdv_operatoire DROP FOREIGN KEY FK_CC1A434D9B177F54');
        $this->addSql('ALTER TABLE rdv_operatoire DROP FOREIGN KEY FK_CC1A434D88AA54BD');
        $this->addSql('ALTER TABLE rdv_operatoire DROP FOREIGN KEY FK_CC1A434DA90F02B2');
        $this->addSql('ALTER TABLE rdv_operatoire DROP FOREIGN KEY FK_CC1A434D6B899279');
        $this->addSql('ALTER TABLE rdv_operatoire_user DROP FOREIGN KEY FK_8F8A739D38353ED3');
        $this->addSql('ALTER TABLE rdv_operatoire_user DROP FOREIGN KEY FK_8F8A739DA76ED395');
        $this->addSql('ALTER TABLE user_specialite DROP FOREIGN KEY FK_40B13A2DA76ED395');
        $this->addSql('ALTER TABLE user_specialite DROP FOREIGN KEY FK_40B13A2D2195E0F0');
        $this->addSql('ALTER TABLE user_dossier_patient DROP FOREIGN KEY FK_4A5C525CA76ED395');
        $this->addSql('ALTER TABLE user_dossier_patient DROP FOREIGN KEY FK_4A5C525C45F3D65C');
        $this->addSql('DROP TABLE chambre');
        $this->addSql('DROP TABLE consultation');
        $this->addSql('DROP TABLE dossier_patient');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE observation');
        $this->addSql('DROP TABLE prescription');
        $this->addSql('DROP TABLE rdv');
        $this->addSql('DROP TABLE rdv_operatoire');
        $this->addSql('DROP TABLE rdv_operatoire_user');
        $this->addSql('DROP TABLE salle_operatoire');
        $this->addSql('DROP TABLE specialite');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_specialite');
        $this->addSql('DROP TABLE user_dossier_patient');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
