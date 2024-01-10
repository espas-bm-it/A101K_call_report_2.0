<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('xml_data', function (Blueprint $table) {
            $table->id();
            $table->string('SubscriberName');
            $table->string('DialledNumber');
            $table->date('Date');
            $table->time('Time');
            $table->time('RingingDuration');
            $table->time('CallDuration');
            $table->string('Type');
            $table->string('CallType');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xml_data');
    }
};
