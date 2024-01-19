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
        Schema::create('archive', function (Blueprint $table) {
            $table->id();
            $table->integer('bet_count');
            $table->integer('transaction_count');
            $table->string('date_covered',100);
            $table->text('fg_link');
            $table->text('schedule_link');
            $table->string('start',100);
            $table->string('end',100);
            $table->string('duration',100);
            $table->string('requested_by',100);
            $table->string('processed_by',100);
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
        Schema::dropIfExists('archive');
    }
};
