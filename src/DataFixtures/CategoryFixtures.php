<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const INFO_REFERENCE = 'info-category';
    public const NEWS_REFERENCE = 'news-category';
    public const TECHNICAL_REFERENCE = 'technical-category';
    public const NATURE_REFERENCE = 'nature-category';

    public function load(ObjectManager $manager): void
    {
        $categoryInfo = new Category('Информация');
        $manager->persist($categoryInfo);
        $categoryNews = new Category('Новость');
        $manager->persist($categoryNews);
        $categoryTechnical = new Category('Техника');
        $manager->persist($categoryTechnical);
        $categoryNature = new Category('Природа');
        $manager->persist($categoryNature);

        $this->addReference(self::INFO_REFERENCE, $categoryInfo);
        $this->addReference(self::NEWS_REFERENCE, $categoryNews);
        $this->addReference(self::TECHNICAL_REFERENCE, $categoryTechnical);
        $this->addReference(self::NATURE_REFERENCE, $categoryNature);

        $manager->flush();
    }
}
