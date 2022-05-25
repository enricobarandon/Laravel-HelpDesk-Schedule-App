<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->char('uuid', 36)->nullable();
            $table->integer('group_id');
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('username', 50);
            $table->string('password')->nullable();
            $table->string('contact', 50);
            $table->boolean('is_active');
            $table->string('position', 50);
            $table->string('allowed_sides', 50);
            $table->text('remarks')->nullable();
            $table->string('site')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};
