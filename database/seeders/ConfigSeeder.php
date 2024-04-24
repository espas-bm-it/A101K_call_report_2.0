<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Config;

class ConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Retrieve the Config record where id = 1
        $config = Config::find(1);

        if (!$config) {
            // Handle the case where the record with id = 1 is not found
            $this->command->error('Config record with id = 1 not found.');
            return;
        }

        // Get the path from the retrieved Config record
        $ticketCollectorPath = $config->path;

        // Insert a record into the configs table with the path
        Config::create([
            'path' => $ticketCollectorPath
        ]);


        $this->command->info('Path inserted into configs table: ' . $ticketCollectorPath);
    }
}
