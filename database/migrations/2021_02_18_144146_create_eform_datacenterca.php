<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEformDatacenterca extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('eform_datacenterca', function (Blueprint $table) {
            $table->id();
            $table->string('asset_name')->nullable();
            $table->string('code')->nullable();
            $table->string('asset_category')->nullable();
            $table->string('rack_location')->nullable();
            $table->string('criticality')->nullable();
            $table->string('physical_location')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('profile')->nullable();
            $table->string('staff_name')->nullable();
            $table->string('staff_no')->nullable();
            $table->string('submitted_date')->nullable();
            $table->string('created_by');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    // "asset_name" varchar2(50 byte),
    //	"asset_category" varchar2(50 byte),
    //	"rack_location" varchar2(100 byte),
    //	"criticality" varchar2(20 byte),
    //	"physical_location" varchar2(100 byte),

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('eform_datacenterca');
    }
}
