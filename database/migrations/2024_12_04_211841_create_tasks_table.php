<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->ulid('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status');
            $table->foreignUlid('user_id')->nullable()->references('id')->on('users')->onDelete('no action');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
