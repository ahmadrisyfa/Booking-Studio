<?php

use App\Models\services;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatebookingpaketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookingpakets', function (Blueprint $table) {
            $table->id();
            $table->string('kode');
            $table->foreignIdFor(services::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->datetime('time_from');
            $table->datetime('time_to');
            $table->tinyInteger('status')->default(0);
            $table->integer('grand_total')->nullable();
            $table->string('bukti_bayar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
