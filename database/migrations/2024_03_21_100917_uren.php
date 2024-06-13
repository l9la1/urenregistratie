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
        Schema::create('uren', function (Blueprint $table) {
            $table->id();
            $table->integer('userid');
            $table->time('startTime');
            $table->time('endTime');
            $table->time('pause');
            $table->date('day');
            $table->string('reason');
            $table->integer('category');
            $table->integer('state')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('uren');
    }
};
