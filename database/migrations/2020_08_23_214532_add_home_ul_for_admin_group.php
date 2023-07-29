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
        Schema::table('admin_groups', function (Blueprint $table) {
            $table->text('home_url')->nullable()->after('name');
            $table->integer('url_index')->nullable()->after('home_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_groups', function (Blueprint $table) {
            $table->dropColumn('home_url');
            $table->dropColumn('url_index');
        });
    }
};
