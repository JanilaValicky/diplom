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
        Schema::create('question_type_header_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_question_id');
            $table->string('html_id');
            $table->string('name');
            $table->string('value');
            $table->tinyInteger('size'); //todo min value is 1, max value is 6
            $table->timestamps();
            $table->foreign('form_question_id')->references('id')->on('form_questions')->onDelete('cascade');
            $table->index('form_question_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_type_header_options');
    }
};
