<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Config;

class XmlArchiveCommand extends Command
{
    protected $signature = 'app:xml-archive-command';
    protected $description = 'Archive TicketCollector.xml daily';

    public function handle()
    {
        // Retrieve configuration data (specifically the XML file path)
        $config = Config::getConfigData();

        if ($config) {
            $xmlFilePath = $config->path;
            $archivePath = 'T:\_TelefonService_Archive';

            // Check if the XML file exists at the specified path
            if (file_exists($xmlFilePath)) {
                // Generate a unique archive name based on the current date and time
                $archiveName = 'TicketCollector_' . now()->format('Ymd_His') . '.xml';
                
                try {
                    // Copy the XML file to the archive path with the generated archive name
                    Storage::copy($xmlFilePath, $archivePath . DIRECTORY_SEPARATOR . $archiveName);
                    
                    // Output a success message
                    $this->info('TicketCollector.xml archived successfully.');
                } catch (\Exception $e) {
                    // Handle any exceptions or errors
                    $this->error('Failed to archive TicketCollector.xml: ' . $e->getMessage());
                }
            } else {
                // XML file not found at the specified path
                $this->error('XML file not found at the specified path: ' . $xmlFilePath);
            }
        } else {
            // Configuration data not found
            $this->error('Configuration data not found. Make sure the config table is populated.');
        }
    }
}
