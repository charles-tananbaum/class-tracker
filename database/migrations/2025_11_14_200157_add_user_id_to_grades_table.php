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
        // For fresh installs, delete any orphaned grades
        if (Schema::hasTable('grades')) {
            \DB::table('grades')->delete();
        }

        Schema::table('grades', function (Blueprint $table) {
            if (!Schema::hasColumn('grades', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            }
        });

        // Drop old unique constraint if it exists
        try {
            Schema::table('grades', function (Blueprint $table) {
                $table->dropUnique(['class_id']);
            });
        } catch (\Exception $e) {
            // Constraint might not exist, continue
        }

        // Add new unique constraint
        Schema::table('grades', function (Blueprint $table) {
            try {
                $table->unique(['user_id', 'class_id']);
            } catch (\Exception $e) {
                // Might already exist
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id', 'class_id']);
            $table->dropColumn('user_id');
            $table->unique('class_id');
        });
    }
};
