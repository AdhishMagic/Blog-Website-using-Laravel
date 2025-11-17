<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('title');
            $table->text('content');
            $table->string('status', 255)->default('Draft');

            $table->timestamps();
            $table->softDeletes();

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();

        });

        // Add a database-level check constraint for status values on supported databases
        $driver = Schema::getConnection()->getDriverName();
        if (in_array($driver, ['pgsql', 'mysql'])) {
            DB::statement("ALTER TABLE posts ADD CONSTRAINT posts_status_check CHECK (status IN ('Draft','Published','Archived'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
