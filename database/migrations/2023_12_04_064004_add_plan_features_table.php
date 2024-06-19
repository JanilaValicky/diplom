<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plan_features', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained();
            $table->foreignId('feature_id')->constrained();
        });

        DB::table('plan_features')->insert([
            ['plan_id' => 1, 'feature_id' => 1],
            ['plan_id' => 2, 'feature_id' => 1],
            ['plan_id' => 2, 'feature_id' => 2],
            ['plan_id' => 3, 'feature_id' => 1],
            ['plan_id' => 3, 'feature_id' => 2],
            ['plan_id' => 3, 'feature_id' => 3],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_features');
    }
};
