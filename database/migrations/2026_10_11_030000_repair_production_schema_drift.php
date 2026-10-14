<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Repairs production schema drift.
 *
 * Several already-run migrations were later edited in place (new columns
 * added, kasbons table file renamed, users.role enum widened) instead of
 * adding new migrations. Fresh databases built from the edited files are
 * fine, but databases migrated before those edits silently miss the
 * columns — surfacing as SQLSTATE[42S22] "Unknown column" at runtime.
 *
 * Every step below is guarded (hasColumn / hasTable / driver check), so
 * this migration is a no-op on already-correct databases and can never
 * duplicate or destroy anything. It also unblocks `migrate --force` on
 * databases stuck behind the renamed kasbons migration (see guarded
 * 2026_08_16 create_kasbons file).
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. keuangan_proyeks.kasbon_id — nullable FK to kasbons.
        if (! Schema::hasColumn('keuangan_proyeks', 'kasbon_id')) {
            Schema::table('keuangan_proyeks', function (Blueprint $table) {
                $table->foreignId('kasbon_id')
                    ->nullable()
                    ->after('proyek_id')
                    ->constrained('kasbons')
                    ->nullOnDelete();
            });
        }

        // 2. laporan_harians.progres_snapshot — nullable JSON, written on
        // every absensi submit.
        if (! Schema::hasColumn('laporan_harians', 'progres_snapshot')) {
            Schema::table('laporan_harians', function (Blueprint $table) {
                $table->json('progres_snapshot')->nullable()->after('kegiatan');
            });
        }

        // 3. kasbons.sisa_nominal — outstanding balance. New kasbons start
        // at full nominal (see KasbonController), so pre-existing rows are
        // backfilled the same way; already-settled rows stay at zero.
        if (! Schema::hasColumn('kasbons', 'sisa_nominal')) {
            Schema::table('kasbons', function (Blueprint $table) {
                $table->decimal('sisa_nominal', 15, 2)->default(0)->after('nominal');
            });

            DB::table('kasbons')
                ->where('status', 'Belum Lunas')
                ->update(['sisa_nominal' => DB::raw('nominal')]);
        }

        // 4. users.role enum — widened in place to include super_admin.
        // MySQL enums need an explicit MODIFY; other drivers (e.g. local
        // sqlite) accept any value, so only MySQL needs the repair.
        // Re-applying the same definition is a harmless no-op.
        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE users MODIFY COLUMN role ENUM('super_admin','admin','pengawas') DEFAULT 'pengawas'"
            );
        }

        // 5-6. Belt-and-suspenders for the 2026_09_22 columns. They are
        // proper new files and normally run before this one, but databases
        // whose migrate was stuck behind the renamed kasbons file may be
        // in any state — guarded copies converge them either way.
        if (! Schema::hasColumn('jabatans', 'can_login')) {
            Schema::table('jabatans', function (Blueprint $table) {
                $table->boolean('can_login')->default(false)->after('gaji_harian');
            });
        }

        if (! Schema::hasColumn('pegawais', 'status')) {
            Schema::table('pegawais', function (Blueprint $table) {
                $table->string('status')->default('aktif')->after('jabatan_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('keuangan_proyeks', 'kasbon_id')) {
            Schema::table('keuangan_proyeks', function (Blueprint $table) {
                $table->dropForeign(['kasbon_id']);
                $table->dropColumn('kasbon_id');
            });
        }

        if (Schema::hasColumn('laporan_harians', 'progres_snapshot')) {
            Schema::table('laporan_harians', function (Blueprint $table) {
                $table->dropColumn('progres_snapshot');
            });
        }

        if (Schema::hasColumn('kasbons', 'sisa_nominal')) {
            Schema::table('kasbons', function (Blueprint $table) {
                $table->dropColumn('sisa_nominal');
            });
        }

        // users.role enum intentionally NOT narrowed on rollback:
        // widening is backward compatible, narrowing could strand rows.
    }
};
