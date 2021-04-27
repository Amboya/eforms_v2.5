<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigEform extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_eform', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('description');
            $table->string('test_url');
            $table->string('icon');
            $table->string('production_url');
            $table->integer('category_id');
            $table->integer('status_id');

            $table->integer('total_new')->nullable();
            $table->integer('total_pending')->nullable();
            $table->integer('total_closed')->nullable();
            $table->integer('total_rejected')->nullable();

            $table->string('created_by');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_eform');
    }
}
