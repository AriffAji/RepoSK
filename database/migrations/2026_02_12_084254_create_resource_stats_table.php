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
       Schema::create('resource_stats', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('disk_total');
            $table->bigInteger('disk_used');
            $table->float('cpu_usage')->nullable();
            $table->bigInteger('ram_used')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_stats');
    }
};
