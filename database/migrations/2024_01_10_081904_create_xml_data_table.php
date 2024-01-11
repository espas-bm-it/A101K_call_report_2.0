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
            $table->string('SubscriberName')->nullable();
            $table->string('DialledNumber')->nullable();
            $table->date('Date')->nullable();
            $table->time('Time')->nullable();
            $table->time('RingingDuration')->nullable();
            $table->time('CallDuration')->nullable();
            $table->string('CallStatus')->nullable();
            $table->string('CommunicationType')->nullable();
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
