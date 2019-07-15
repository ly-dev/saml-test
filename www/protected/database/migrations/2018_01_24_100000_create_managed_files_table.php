<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateManagedFilesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('managed_files', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('file_name');
            $table->string('description');
            $table->string('mime_type');
            $table->integer('file_size')->unsigned();
            $table->binary('data_blob');
            $table->string('file_uri');
        });

        DB::statement('ALTER TABLE managed_files CHANGE COLUMN data_blob data_blob LONGBLOB NOT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('managed_files');
    }
}