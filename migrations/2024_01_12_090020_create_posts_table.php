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
        // $table->softDeletes($column = 'deleted_at', $precision = 0);
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('caption')->nullable();
            $table->string('photo');
            $table->foreignId('user_id');//->constrained();//->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
