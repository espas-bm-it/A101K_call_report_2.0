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
        // Define the path to the TicketCollector.xml file
        $ticketCollectorPath = 'P:\\API Projekte\\A101K Telefonservice Report\\02 Projektdateien\\04 Archiv\\TicketCollector.xml';

        // Insert a record into the configs table with the path
        Config::create([
            'path' => $ticketCollectorPath
        ]);
    }
}
