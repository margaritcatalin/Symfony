<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Post;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        for($i = 0; $i < 10; $i++)
        {
          $post = new Post();
          $post->setText('Test post data ' . rand(0, 100));
          $post->setTime(new \DateTime('2019-10-27'));
          $manager->persist($post);
        }
        $manager->flush();
    }
}
