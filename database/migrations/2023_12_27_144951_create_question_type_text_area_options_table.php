<?php

use App\Enums\HtmlWritingDirection;
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
        Schema::create('question_type_text_area_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_question_id');
            $table->boolean('required');
            $table->string('html_id');
            $table->string('name');
            $table->string('value');
            $table->string('label');
            $table->boolean('autocapitalize')->default(false);
            $table->boolean('autocorrect')->default(false);
            $table->boolean('autofocus')->default(false);
            $table->boolean('disabled')->default(false);
            $table->integer('cols')->default(20);
            $table->enum('dirname', HtmlWritingDirection::getAllValues())->default(HtmlWritingDirection::LeftToRight);
            $table->integer('maxlength');
            $table->integer('minlength');
            $table->string('placeholder')->nullable();
            $table->boolean('readonly')->default(false);
            $table->integer('rows')->default(2);
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
        Schema::dropIfExists('question_type_text_area_options');
    }
};
