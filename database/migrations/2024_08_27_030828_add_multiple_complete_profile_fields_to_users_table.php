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
        Schema::table('users', function (Blueprint $table) {
            $table->string('type_of_baccalaureate')->nullable();
            $table->string('specialities')->nullable();
            $table->string('european_section')->nullable()->comment('No : 0, Yes : 1');
            $table->string('options')->nullable();
            $table->integer('general_mean')->nullable();

            $table->unsignedBigInteger('subject_id')->nullable();
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');

            $table->string('learning_a_language')->nullable()->comment('No : 0, Yes : 1');;
            $table->string('language')->nullable();
            $table->string('international_experience')->nullable()->comment('No : 0, Yes : 1');;
            $table->string('traveling_to_a_peculiar_region')->nullable()->comment('No : 0, Yes : 1');;
            $table->string('region')->nullable();
            $table->string('prefer_school')->nullable();
            $table->string('study')->nullable();

            $table->string('minimum_monthly_cost')->nullable();
            $table->string('pay_for_your_studies')->nullable();
            $table->string('professionalizing_formation')->nullable();
            $table->string('study_online')->nullable()->comment('No : 0, Yes : 1');
            $table->string('iapprentissage')->nullable()->comment('No : 0, Yes : 1');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('type_of_baccalaureate');
            $table->dropColumn('specialities');
            $table->dropColumn('european_section');
            $table->dropColumn('options');
            $table->dropColumn('filliere_de_formation');
            $table->dropColumn('general_mean');
            $table->dropColumn('subject_id');
            $table->dropColumn('learning_a_language');
            $table->dropColumn('language');
            $table->dropColumn('international_experience');
            $table->dropColumn('traveling_to_a_peculiar_region');
            $table->dropColumn('region');
            $table->dropColumn('prefer_school');
            $table->dropColumn('study');
            $table->dropColumn('minimum_monthly_cost');
            $table->dropColumn('pay_for_your_studies');
            $table->dropColumn('professionalizing_formation');
            $table->dropColumn('study_online');
            $table->dropColumn('iapprentissage');

        });
    }
};
