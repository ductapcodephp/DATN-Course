<?php
// === FILE: database/migrations/2024_01_01_000032_create_quizzes_table.php ===

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained('lessons')->onDelete('cascade');
            $table->string('title');
            $table->integer('trigger_seconds')->comment('Timestamp in video to trigger quiz');
            $table->boolean('is_required')->default(false)->comment('Must complete to continue');
            $table->timestamps();
            
            // Indexes
            $table->index('lesson_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
