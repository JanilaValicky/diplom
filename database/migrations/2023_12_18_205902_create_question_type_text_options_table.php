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
        Schema::create('question_type_text_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_question_id');
            $table->boolean('required');
            $table->string('html_id');
            $table->string('name');
            $table->string('value')->nullable();
            $table->integer('minlength')->nullable();
            $table->integer('maxlength')->nullable();
            $table->string('pattern')->nullable();
            $table->string('placeholder')->nullable();
            $table->integer('size')->nullable();
            $table->boolean('readonly')->default(false);
            $table->boolean('spellcheck')->default(true);
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
        Schema::dropIfExists('question_type_text_options');
    }
};
