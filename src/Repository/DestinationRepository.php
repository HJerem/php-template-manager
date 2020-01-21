<?php

namespace Repository;

use Entity\Destination;
use Faker\Factory;
use Helper\SingletonTrait;

class DestinationRepository implements Repository
{
    use SingletonTrait;

    /**
     * @param int $id
     *
     * @return Destination
     */
    public function getById(int $id)
    {
        // DO NOT MODIFY THIS METHOD

        $faker = Factory::create();
        $faker->seed($id);

        return new Destination(
            $id,
            $faker->country,
            'en',
            $faker->slug()
        );
    }
}
