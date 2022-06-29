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
        Schema::create('scheduled_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('scheduled_group_id');
            $table->integer('schedule_id');
            $table->integer('group_id');
            $table->integer('account_id');
            $table->string('account_allowed_sides', 50);
            $table->string('account_password')->nullable();
            $table->string('account_position', 50);
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
        Schema::dropIfExists('scheduled_accounts');
    }
};
