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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('formation_id')->nullable();
            $table->string('name_of_the_formation')->nullable();
            $table->string('link_to_its_webpage')->nullable();
            $table->string('region')->nullable();
            $table->string('schooling_cost')->nullable();
            $table->string('length_of_the_formation')->nullable();
            $table->string('status')->nullable()->comment('private or public');
            $table->integer('access_rate')->nullable();
            $table->string('type_of_formation')->nullable();
            $table->string('town')->nullable();
            $table->string('schooling_modalities')->nullable();
            $table->string('schooling_pursuit')->nullable();
            $table->string('description_of_the_formation')->nullable();
            $table->integer('number_of_students')->nullable();
            $table->string('keywords_option')->nullable();
            $table->string('keywords_secondary')->nullable();
            $table->string('keywords_main')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
