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
        // Check if any Config records exist
        $existingConfigsCount = Config::count();

        if ($existingConfigsCount === 0) {
            // Insert default Ticket Collector Path (ID 1)
            $ticketCollectorPath = '\\zh-hq-sv023\logs\TicketCollector.xml';
            Config::create(['path' => $ticketCollectorPath]);

            $this->command->info('Ticket Collector Path inserted into configs table: ' . $ticketCollectorPath);

            // Insert default Archive Path (ID 2)
            $archivePath = '\\zh-hq-sv023\archiv';
            Config::create(['path' => $archivePath]);

            $this->command->info('Archive Path inserted into configs table: ' . $archivePath);
        } else {
            $this->command->info('Config records already exist. Skipping seeding.');
        }
    }
}
