<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id('id_transaksi');

            $table->foreignId('id_user')->constrained('users')->cascadeOnDelete();
            $table->foreignId('id_tiket')->constrained('tickets')->cascadeOnDelete();

            $table->integer('jumlah');
            $table->decimal('total_harga', 15, 2);

            $table->dateTime('tanggal_transaksi');

            $table->string('metode_pembayaran')->nullable();
            $table->string('status_pembayaran')->default('pending');
            $table->string('payment_reference')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};