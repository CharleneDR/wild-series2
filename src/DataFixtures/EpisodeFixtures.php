<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Episode;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for($i = 1; $i < 6; $i++) {
            for($j = 1; $j < 6; $j++) {
                for($k = 1; $k < 11; $k++) {

                    $episode = new Episode();
                    //Ce Faker va nous permettre d'alimenter l'instance de episode que l'on souhaite ajouter en base
                    $episode->setNumber($k);
                    $episode->setTitle($faker->words(3,true));
                    $episode->setSynopsis($faker->paragraph());
                    $episode->setSeason($this->getReference('program_' . $i . '_season_' . $j));
                    $episode->setDuration($faker->numberBetween(10,60));
                    $slug = $this->slugger->slug($episode->getTitle());
                    $episode->setSlug($slug);
                    $manager->persist($episode);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          SeasonFixtures::class,
        ];
    }
}
