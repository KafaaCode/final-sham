<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nursing_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nursing_care_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('nurse_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->text('action');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nursing_actions');
    }
};
