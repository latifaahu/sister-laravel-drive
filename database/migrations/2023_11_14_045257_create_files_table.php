<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('google_drive_id')->nullable();
            $table->string('name');
            $table->text('description');
            $table->timestamps();
        });

        // Tambahkan pernyataan terpisah untuk memberikan nilai default NULL
        DB::statement('ALTER TABLE files MODIFY google_drive_id VARCHAR(255) NULL DEFAULT NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
