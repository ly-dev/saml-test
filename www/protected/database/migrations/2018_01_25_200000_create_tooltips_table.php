<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateTooltipsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tooltips', function (Blueprint $table) {
            $table->string('page_id');
            $table->string('tooltip_id');
            $table->timestamps();

            $table->string('title');
            $table->text('description');

            $table->primary([
                'page_id',
                'tooltip_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tooltips');
    }
}
