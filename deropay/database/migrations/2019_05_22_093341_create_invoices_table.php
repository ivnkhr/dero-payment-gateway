<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
          
            $table->engine = 'MyISAM';
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
          
            $table->bigIncrements('id');
            $table->string('name', 128)->default('');
            $table->string('desc', 512)->default('');
            $table->unsignedBigInteger('price')->default(1000000000000);
            $table->unsignedMediumInteger('ttl')->default(1200);
            $table->nullableTimestamps();
            $table->unsignedTinyInteger('status')->default(0);
            $table->json('webhook_data')->nullable();
            $table->string('payment_id', 64)->collation('ascii_general_ci')->nullable();
            $table->string('integrated_address', 142)->collation('ascii_general_ci')->nullable();
            
            $table->index(['name', 'created_at']);
            $table->unique(['payment_id', 'integrated_address']);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
