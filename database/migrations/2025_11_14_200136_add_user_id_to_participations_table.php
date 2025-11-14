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
        // For fresh installs, delete any orphaned participations
        if (Schema::hasTable('participations')) {
            \DB::table('participations')->delete();
        }

        Schema::table('participations', function (Blueprint $table) {
            if (!Schema::hasColumn('participations', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            }
        });

        // Drop old unique constraint if it exists
        try {
            Schema::table('participations', function (Blueprint $table) {
                $table->dropUnique(['class_id', 'date']);
            });
        } catch (\Exception $e) {
            // Constraint might not exist, continue
        }

        // Add new unique constraint
        Schema::table('participations', function (Blueprint $table) {
            try {
                $table->unique(['user_id', 'class_id', 'date']);
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
        Schema::table('participations', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropUnique(['user_id', 'class_id', 'date']);
            $table->dropColumn('user_id');
            $table->unique(['class_id', 'date']);
        });
    }
};
