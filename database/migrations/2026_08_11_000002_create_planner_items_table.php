<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlannerItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planner_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('planner_id');
            $table->string('month'); // April, May, June, etc.
            $table->text('chapters'); // Chapter names/numbers
            $table->text('topics')->nullable(); // Additional topics detail
            $table->text('teaching_methods')->nullable();
            $table->text('assessment')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('is_highlighted')->default(false); // Admin highlighted issue
            $table->text('highlight_comment')->nullable(); // Admin's comment on issue
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('planner_items', function (Blueprint $table) {
            $table->foreign('planner_id')->references('id')->on('planners')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('planner_items');
    }
}
