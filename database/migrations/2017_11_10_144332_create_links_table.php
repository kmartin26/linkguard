<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('links', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->mediumText('urls');
            $table->smallInteger('urls_nb');
            $table->ipAddress('client_ip');
            $table->char('delete_code', 8);
            $table->boolean('deleted')->default(0);
            $table->char('deleted_reason', 10)->nullable();
            $table->timestamps();

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('links');
    }
}
