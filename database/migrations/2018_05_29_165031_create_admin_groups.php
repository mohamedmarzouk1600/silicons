<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('name');
            $table->tinyInteger('user_group');
            $table->enum('status',\MaxDev\Enums\Status::getValues())->default(\MaxDev\Enums\Status::ACTIVE);
            $table->uuid('question_category_id')->nullable();
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
        Schema::drop('admin_groups');
    }
};
