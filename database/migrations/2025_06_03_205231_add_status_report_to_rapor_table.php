<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('rapor', function (Blueprint $table) {
        $table->enum('status_report', ['Belum Ada', 'Sudah Ada'])->after('file_path')->nullable();
    });
}

public function down()
{
    Schema::table('rapor', function (Blueprint $table) {
        $table->dropColumn('status_report');
    });
}

};
