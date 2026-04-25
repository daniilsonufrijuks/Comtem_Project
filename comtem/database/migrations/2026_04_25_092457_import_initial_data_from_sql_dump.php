<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sql = File::get(database_path('/comex (2) - Copy.sql'));
        DB::unprepared($sql);
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
