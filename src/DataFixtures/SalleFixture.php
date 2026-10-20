<?php

namespace App\DataFixtures;

use App\Entity\Chambre;
use App\Entity\SalleOperatoire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SalleFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->loadChambres($manager);
        $this->loadSallesOperatoires($manager);

        $manager->flush();
    }

    private function loadChambres(ObjectManager $manager): void
    {
        $chambresData = [
            ['numero' => 1,  'type' => 'Publique', 'alimentation' => 'Standard', 'duree' => '04:00:00'],
            ['numero' => 3,  'type' => 'Publique', 'alimentation' => 'Speciale', 'duree' => '06:30:00'],
            ['numero' => 6,  'type' => 'Privee',   'alimentation' => 'Standard', 'duree' => '08:00:00'],
            ['numero' => 9,  'type' => 'Privee',   'alimentation' => 'Speciale', 'duree' => '03:15:00'],
            ['numero' => 12, 'type' => 'Publique', 'alimentation' => 'Standard', 'duree' => '10:00:00'],
            ['numero' => 15, 'type' => 'Privee',   'alimentation' => 'Standard', 'duree' => '12:00:00'],
            ['numero' => 20, 'type' => 'Publique', 'alimentation' => 'Speciale', 'duree' => '05:45:00'],
            ['numero' => 25, 'type' => 'Privee',   'alimentation' => 'Speciale', 'duree' => '07:30:00'],
            ['numero' => 32, 'type' => 'Publique', 'alimentation' => 'Standard', 'duree' => '09:00:00'],
            ['numero' => 40, 'type' => 'Privee',   'alimentation' => 'Standard', 'duree' => '15:00:00'],
        ];

        foreach ($chambresData as $data) {
            $chambre = new Chambre();
            $chambre->setNumeroChambre($data['numero']);
            $chambre->setTypeChambre($data['type']);
            $chambre->setAlimentation($data['alimentation']);
            $chambre->setDureeOccupation(\DateTime::createFromFormat('H:i:s', $data['duree']));

            $manager->persist($chambre);
        }
    }

    private function loadSallesOperatoires(ObjectManager $manager): void
    {
        $sallesData = [
            [
                'numero'  => 1,
                'heure'   => '08:00:00',
                'duree'   => '02:00:00',
                'details' => 'Salle equipee pour la chirurgie generale et digestive.',
            ],
            [
                'numero'  => 2,
                'heure'   => '09:30:00',
                'duree'   => '03:30:00',
                'details' => 'Salle dediee a la neurochirurgie, equipement de haute precision.',
            ],
            [
                'numero'  => 3,
                'heure'   => '13:00:00',
                'duree'   => '01:45:00',
                'details' => 'Salle reservee aux interventions d urgence de courte duree.',
            ],
            [
                'numero'  => 4,
                'heure'   => '14:30:00',
                'duree'   => '04:15:00',
                'details' => 'Salle polyvalente pour la chirurgie orthopedique et pediatrique.',
            ],
        ];

        foreach ($sallesData as $data) {
            $salle = new SalleOperatoire();
            $salle->setNumeroSalle($data['numero']);
            $salle->setHeurOperation(\DateTime::createFromFormat('H:i:s', $data['heure']));
            $salle->setDureeOperation(\DateTime::createFromFormat('H:i:s', $data['duree']));
            $salle->setDetails($data['details']);

            $manager->persist($salle);
        }
    }
}
