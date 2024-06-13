<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddArchivePathToConfigsTable extends Migration
{
    public function up()
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->string('archive_path')->nullable()->after('path'); // Add archive_path column after path
        });
    }

    public function down()
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->dropColumn('archive_path'); // Rollback: drop the archive_path column
        });
    }
}
