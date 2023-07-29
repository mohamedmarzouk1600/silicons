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
        Schema::create('contact_emails', function (Blueprint $table) {
            $table->id();


            $table->string('qr')->unique();
            $table->tinyInteger('send_email')->default(1);
            $table->tinyInteger('send_message')->default(1);
            $table->tinyInteger('scan_qr')->default(0);
            $table->unsignedBigInteger('email_model_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();

            $table->timestamps(); 

            $table->foreign('email_model_id')->references('id')->on('email_models')->onUpdate('no action')->onDelete('no action');

            $table->foreign('contact_id')->references('id')->on('contacts')->onUpdate('no action')->onDelete('no action');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contact_emails');
    }
};
