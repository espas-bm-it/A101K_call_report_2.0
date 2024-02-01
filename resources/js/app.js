// Importe hinzufügen, falls noch nicht vorhanden
import './bootstrap';
import 'laravel-datatables-vite';
require('bootstrap');
require('daterangepicker');

$(function () {
    // Date Range Picker konfigurieren
    $('#logCal').daterangepicker({
        drops: 'down',
        autoApply: true,
        locale: {
            format: 'YYYY-MM-DD',
        },
    });

    // Event-Handler für die Auswahl des Datumsbereichs
    $('#logCal').on('apply.daterangepicker', function (ev, picker) {
        // AJAX-Anfrage, um die Daten basierend auf dem ausgewählten Datumsbereich zu aktualisieren
        $.ajax({
            url: '/updateXmlData', // Passe die URL entsprechend deiner Route an
            type: 'POST',
            data: {
                start_date: picker.startDate.format('YYYY-MM-DD'),
                end_date: picker.endDate.format('YYYY-MM-DD'),
                _token: $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (data) {
                // Aktualisiere die Tabelle mit den neuen Daten
                $('#xmlDataContainer').html(data);
            },
            error: function (error) {
                console.log(error);
            },
        });
    });
});
