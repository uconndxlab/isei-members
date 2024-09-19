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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('degree')->nullable();
            $table->string('position')->nullable();
            $table->string('organization')->nullable();
            $table->string('email');
            $table->string('country')->nullable();
            $table->string('gen_int1')->nullable();
            $table->string('gen_int2')->nullable();
            $table->string('gen_int3')->nullable();
            $table->date('entry_date')->nullable();
            $table->string('member_id')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
