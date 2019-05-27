<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssociatedTxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('associated_txes', function (Blueprint $table) {
          
          $table->engine = 'MyISAM';
          $table->charset = 'utf8';
          $table->collation = 'utf8_unicode_ci';
        
          $table->bigIncrements('id');

          $table->nullableTimestamps();

          $table->string('tx', 64)->collation('ascii_general_ci')->nullable()->unique();
          $table->unsignedMediumInteger('height')->nullable();
          $table->unsignedMediumInteger('wallet_height')->nullable();
          $table->unsignedBigInteger('invoice_id')->nullable();
          $table->unsignedBigInteger('amount')->default(0);
          
          $table->index(['height', 'created_at']);
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('associated_txes');
    }
}
