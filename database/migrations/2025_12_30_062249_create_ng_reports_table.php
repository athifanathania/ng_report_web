<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ng_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->foreignId('part_id')->constrained()->cascadeOnDelete();

            $table->string('ng_category');
            $table->text('ng_detail');

            // multi photo, like DMC
            $table->json('photos')->nullable();

            $table->date('input_date');

            $table->string('status')->default('DRAFT');
            $table->timestamp('email_sent_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ng_reports');
    }
};
