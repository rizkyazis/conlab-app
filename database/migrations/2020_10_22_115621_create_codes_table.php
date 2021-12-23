<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lesson_id');
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('contributor_id')->nullable();
            $table->text('raw_code');
            $table->text('output')->nullable();
            $table->text('feedback')->nullable();
            $table->double('score')->nullable();
            $table->boolean('is_reviewed')->default(false);
            $table->timestamps();

            $table->foreign('lesson_id')->references('id')->on('section_lessons')->onDelete('cascade');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('contributor_id')->references('id')->on('contributors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('codes');
    }
}
