<?php

namespace Repository;

use Entity\Site;
use Faker\Factory;
use Helper\SingletonTrait;

class SiteRepository implements Repository
{
    use SingletonTrait;

    /**
     * @param int $id
     *
     * @return Site
     */
    public function getById($id)
    {
        // DO NOT MODIFY THIS METHOD
        $faker = Factory::create();
        $faker->seed($id);
        return new Site($id, $faker->url);
    }
}
