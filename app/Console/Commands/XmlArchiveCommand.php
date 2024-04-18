<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Config;

class XmlArchiveCommand extends Command
{
    protected $signature = 'archive:xml';
    protected $description = 'Archive TicketCollector.xml daily';

    public function handle()
    {
        // Retrieve configuration data (specifically the XML file path)
        $config = Config::first();

        if (!$config) {
            $this->error('Configuration data not found. Make sure the config table is populated.');
            return;
        }

        $xmlFilePath = $config->path;
        $archiveDirectory = 'T:\_TelefonService_Archive';

        // Output the XML file path for debugging
        $this->info('XML file path: ' . $xmlFilePath);

        // Check if the XML file exists at the specified path
        if (!file_exists($xmlFilePath)) {
            $this->error('XML file not found at the specified path: ' . $xmlFilePath);
            return;
        }

        try {
            // Ensure the archive directory exists
            if (!Storage::exists($archiveDirectory)) {
                Storage::makeDirectory($archiveDirectory);
            }

            // Generate a unique archive name based on the current date and time
            $archiveName = 'TicketCollector_' . now()->format('Ymd_His') . '.xml';

            // Copy the XML file to the archive path with the generated archive name
            Storage::copy($xmlFilePath, $archiveDirectory . DIRECTORY_SEPARATOR . $archiveName);

            // Output a success message
            $this->info('TicketCollector.xml archived successfully.');
        } catch (\Exception $e) {
            // Handle any exceptions or errors
            $this->error('Failed to archive TicketCollector.xml: ' . $e->getMessage());
        }
    }
}
