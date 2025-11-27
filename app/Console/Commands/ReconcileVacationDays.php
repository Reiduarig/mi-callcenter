<?php

namespace App\Console\Commands;

use App\Domains\Staff\Models\Absence;
use App\Models\User;
use Illuminate\Console\Command;

class ReconcileVacationDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vacation:reconcile {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reconcile vacation days consumed with actual approved absences';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 Running in DRY RUN mode - no changes will be made');
        }

        $this->info('Analyzing vacation days for all users...');
        $this->newLine();

        $users = User::all();
        $inconsistencies = 0;
        $totalDaysAdjusted = 0;

        foreach ($users as $user) {
            $recordedDays = $user->used_vacation_days;

            // Calcular días reales de ausencias aprobadas de tipo vacation
            $actualDays = Absence::where('user_id', $user->id)
                ->where('type', 'vacation')
                ->where('status', 'approved')
                ->get()
                ->sum(fn ($absence) => $absence->durationInDays());

            if ($recordedDays !== $actualDays) {
                $inconsistencies++;
                $difference = $recordedDays - $actualDays;
                $totalDaysAdjusted += abs($difference);

                $this->warn("⚠️  {$user->name} (ID: {$user->id})");
                $this->line("   Recorded: {$recordedDays} days | Actual: {$actualDays} days | Difference: {$difference} days");

                if (! $dryRun) {
                    $user->update(['used_vacation_days' => $actualDays]);
                    $this->info("   ✅ Updated to {$actualDays} days");
                } else {
                    $this->info("   📝 Would update to {$actualDays} days");
                }

                $this->newLine();
            }
        }

        $this->newLine();

        if ($inconsistencies === 0) {
            $this->info('✨ No inconsistencies found! All vacation days are accurate.');

            return self::SUCCESS;
        }

        if ($dryRun) {
            $this->info("📊 Found {$inconsistencies} user(s) with inconsistencies ({$totalDaysAdjusted} total days)");
            $this->info('💡 Run without --dry-run to apply changes');
        } else {
            $this->info("✅ Successfully reconciled {$inconsistencies} user(s) ({$totalDaysAdjusted} total days adjusted)");
        }

        return self::SUCCESS;
    }
}
