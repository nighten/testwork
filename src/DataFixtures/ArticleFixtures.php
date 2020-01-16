<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $article1 = new Article('План развития');
        $article1->setText('Таким образом, реализация намеченного плана развития позволяет оценить значение дальнейших направлений развитая системы массового участия');
        $article1->addCategory($this->getReference(CategoryFixtures::INFO_REFERENCE));
        $article1->addCategory($this->getReference(CategoryFixtures::TECHNICAL_REFERENCE));
        $manager->persist($article1);

        $article2 = new Article('Задача организации');
        $article2->setText('Задача организации, в особенности же консультация с профессионалами из IT напрямую зависит от модели развития?');
        $article2->addCategory($this->getReference(CategoryFixtures::INFO_REFERENCE));
        $article2->addCategory($this->getReference(CategoryFixtures::NEWS_REFERENCE));
        $manager->persist($article2);

        $article3 = new Article('Формирование позиции');
        $article3->setText('Значимость этих проблем настолько очевидна, что начало повседневной работы по формированию позиции представляет собой интересный эксперимент проверки новых предложений. Разнообразный и богатый опыт новая модель организационной деятельности в значительной степени обуславливает создание системы обучения кадров');
        $manager->persist($article3);

        $article4 = new Article('Интересный эксперимент');
        $article4->setText('Разнообразный и богатый опыт сложившаяся структура организации представляет собой интересный эксперимент проверки системы обучения кадров');
        $article4->addCategory($this->getReference(CategoryFixtures::INFO_REFERENCE));
        $article4->addCategory($this->getReference(CategoryFixtures::NEWS_REFERENCE));
        $article4->addCategory($this->getReference(CategoryFixtures::TECHNICAL_REFERENCE));
        $manager->persist($article4);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
        ];
    }
}
