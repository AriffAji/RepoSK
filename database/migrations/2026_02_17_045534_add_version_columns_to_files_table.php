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
        Schema::table('files', function (Blueprint $table) {

            if (!Schema::hasColumn('files', 'base_name')) {
                $table->string('base_name')->nullable()->after('filename');
            }

            if (!Schema::hasColumn('files', 'is_latest')) {
                $table->boolean('is_latest')->default(true)->after('version');
            }
        });
    }





    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropColumn(['base_name', 'version', 'is_latest']);
        });
    }
};