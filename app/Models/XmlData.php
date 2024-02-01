<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class XmlData extends Model
{
    use HasFactory, Sortable;

    protected $table = 'xml_data';

    public $timestamps = false;

    protected $dates = [];

    protected $fillable = ['SubscriberName', 'DialledNumber', 'Date', 'Time', 'RingingDuration', 'CallDuration', 'CallStatus', 'CommunicationType'];

    protected $sortable = ['Date', 'Zeit', 'RingingDuration', 'CallDuration', 'CallStatus', 'CommunicationType'];

    // Funktion um Telefonnummer passend zu formatieren
    public function getFormattedPhoneNumberAttribute()
    {
        $phoneNumber = $this->DialledNumber;

        // Länderkennungen definieren
        $countryCodes = [
            '41' => 'Schweiz',
            '44' => 'Großbritannien',
            // Weitere Ländercodes hier hinzufügen...
        ];

        // Länderkennung aus der Telefonnummer extrahieren
        $countryCode = substr($phoneNumber, 0, 2);

        // Überprüfen, ob die Länderkennung bekannt ist
        if (isset($countryCodes[$countryCode])) {
            $formattedNumber = '+' . $countryCode . ' ';

            // Je nach Länderkennung unterschiedliche Formate anwenden
            switch ($countryCode) {
                case '41': // Schweiz
                    $formattedNumber .= substr($phoneNumber, 2, 2) . ' ' . substr($phoneNumber, 4, 3) . ' ' . substr($phoneNumber, 7, 2) . ' ' . substr($phoneNumber, 9, 2);
                    break;
                case '44': // Großbritannien
                    $formattedNumber .= substr($phoneNumber, 2, 4) . ' ' . substr($phoneNumber, 6, 4) . ' ' . substr($phoneNumber, 10, 2) . ' ' . substr($phoneNumber, 12, 2);
                    break;
                    // Weitere Ländercodes hier hinzufügen...
                default:
                    // Standardformat für bekannte Länder ohne spezielle Regel
                    $formattedNumber .= substr($phoneNumber, 2);
                    break;
            }
        } else {
            // Wenn die Länderkennung unbekannt ist, das Originalformat beibehalten
            if (strlen($phoneNumber) == 10) {
                // Annahme: Länderkennung für Schweiz ist +41
                $formattedNumber = '+41 ' . substr($phoneNumber, 1, 2) . ' ' . substr($phoneNumber, 3, 3) . ' ' . substr($phoneNumber, 6, 2) . ' ' . substr($phoneNumber, 8, 2);
            } elseif (strlen($phoneNumber) == 11) {
                // Annahme: Länderkennung für Schweiz ist +41
                $formattedNumber = '+' . substr($phoneNumber, 0, 2) . ' ' . substr($phoneNumber, 2, 2) . ' ' . substr($phoneNumber, 4, 3) . ' ' . substr($phoneNumber, 7, 2) . ' ' . substr($phoneNumber, 9, 2);
            } else {
                // Wenn die Telefonnummer nicht 10 oder 11 Ziffern hat, unverändert zurückgeben
                $formattedNumber = $phoneNumber;
            }
        }

        return $formattedNumber;
    }

    protected $appends = ['formattedPhoneNumber'];
}
