<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('maintenance_logs', static function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->owned(); // from luchavez/starter-kit
            $table->boolean('is_down');
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('secret')->nullable();
            $table->unsignedTinyInteger('status')->default(0);
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_logs');
    }
};
