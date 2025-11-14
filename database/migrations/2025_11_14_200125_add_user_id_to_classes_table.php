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
        // For fresh installs, delete any orphaned classes
        if (Schema::hasTable('classes')) {
            \DB::table('classes')->delete();
        }

        Schema::table('classes', function (Blueprint $table) {
            if (!Schema::hasColumn('classes', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            }
        });

        // Drop old unique constraint if it exists
        try {
            Schema::table('classes', function (Blueprint $table) {
                $table->dropUnique(['code']);
            });
        } catch (\Exception $e) {
            // Constraint might not exist, continue
        }

        // Add new unique constraint
        Schema::table('classes', function (Blueprint $table) {
            try {
                $table->unique(['user_id', 'code']);
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
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id', 'code']);
            $table->dropColumn('user_id');
            $table->unique('code');
        });
    }
};
