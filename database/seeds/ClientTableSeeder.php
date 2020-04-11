<?php

use Elasticsearch\Client;
use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use Ramsey\Uuid\Uuid as RamseyUuid;

class ClientTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        /** @var Client $client */
        $client = app(Client::class);
        $start = microtime(true);
        foreach (range(1, 1000) as $value) {
            $client->create([
                'index' => env('ES_INDEX'),
                'type' => 'clients',
                'id' => (string)RamseyUuid::uuid4(),
                'body' => [
                    'name' => $faker->firstName . ' ' . $faker->lastName,
                    'estado' => $faker->state
                ]
            ]);
        }
        $end = microtime(true);
        $time = $end - $start;
        $this->command->info("Execucao em: $time segundos");
    }
}
