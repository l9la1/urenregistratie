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
        Schema::create('free', function (Blueprint $table) {
            $table->id();
            $table->integer("userid");
            $table->date("startDay");
            $table->date("endDay");
            $table->integer("state");
            $table->time("startTime")->nullable();
            $table->time("endTime")->nullable();
            $table->text("reason");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
