<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateSocialAccountsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->timestamps();
            $table->smallInteger('provider_type'); // facebook = 1, etc
            $table->string('provider_user_id');
            $table->string('name');
            $table->string('email');
            $table->text('access_token');
            $table->timestamp('expire_at');

            $table->integer('user_id')
                ->unsigned()
                ->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->primary([
                'provider_type',
                'provider_user_id'
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
        Schema::drop('social_accounts');
    }
}
