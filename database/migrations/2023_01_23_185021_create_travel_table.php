<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travel', function (Blueprint $table) {
            
            $table->id();

            // @ Text
            $table->string('department')->nullable(true); 
            $table->string('project_name')->nullable(true); 
            $table->string('project_manager')->nullable(true); 
            $table->string('purpose')->nullable(true); 
            $table->string('departure_destination')->nullable(true);
            $table->timestamp('date_from')->nullable(true);
            $table->timestamp('date_to')->nullable(true);
            $table->string('start_visa')->nullable(true);
            $table->string('requirements')->nullable(true);
            $table->string('pocket_money')->nullable(true);
            $table->boolean('is_active')->default(true); 
            $table->timestamps();

            $table->unsignedBigInteger('traveler_id')->nullable(true);
            $table->unsignedBigInteger('reporting_user_id')->nullable(true);
            $table->unsignedBigInteger('approval_user_id')->nullable(true);
            $table->unsignedBigInteger('client_id')->nullable(true);
            $table->unsignedBigInteger('departure_branch_id')->nullable(true);
            $table->unsignedBigInteger('destination_branch_id')->nullable(true);

            // @ REL
            $table->foreign('traveler_id')->references('id')->on('users');
            $table->foreign('reporting_user_id')->references('id')->on('users');
            $table->foreign('approval_user_id')->references('id')->on('users');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('departure_branch_id')->references('id')->on('branches');
            $table->foreign('destination_branch_id')->references('id')->on('branches');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travel');
    }
}
