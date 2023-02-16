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
        Schema::create('twilio_result_logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('from')->nullable();
            $table->bigInteger('to')->nullable();
            $table->string('body')->nullable();
            $table->string('status')->nullable();
            $table-> dateTime('sent_date')->nullable();
            $table->string('err_code')->nullable();
            $table->string('direction')->nullable();
            $table->double('price', 6,4)->nullable();
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
        Schema::dropIfExists('twilio_result_logs');
    }
};
