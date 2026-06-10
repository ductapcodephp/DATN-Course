<?php
// === FILE: database/migrations/2024_01_01_000034_create_quiz_questions_table.php ===

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quizzes')->onDelete('cascade');
            $table->text('question');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            // Indexes
            $table->index('quiz_id');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
