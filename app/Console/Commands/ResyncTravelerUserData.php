<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Traveler;
use Illuminate\Support\Facades\DB;

class ResyncTravelerUserData extends Command
{
    protected $signature = 'users:resync-travelers
                            {--force : Overwrite non-null users.phone_number and users.date_of_birth}
                            {--dry-run : Show what would change without writing}';

    protected $description = 'Sync phone_number and date_of_birth from travelers table into users table';

    public function handle(): int
    {
        $force  = (bool) $this->option('force');
        $dryRun = (bool) $this->option('dry-run');

        $this->info('Starting re-sync (force=' . ($force ? 'yes' : 'no') . ', dry-run=' . ($dryRun ? 'yes' : 'no') . ')');

        $scanned = 0;
        $updated = 0;
        $skipped = 0;

        Traveler::query()
            ->with('user:id,phone_number,date_of_birth')
            ->select(['id','user_id','phone_number','date_of_birth'])
            ->whereNotNull('user_id')
            ->chunkById(200, function ($travelers) use ($force, $dryRun, &$scanned, &$updated, &$skipped) {
                foreach ($travelers as $t) {
                    $scanned++;

                    $userId = $t->user_id;
                    if (! $userId) {
                        $skipped++;
                        continue;
                    }

                    $u = $t->user;
                    if (! $u) {
                        $skipped++;
                        continue;
                    }

                    $newPhone = $t->phone_number;
                    $newDob   = $t->date_of_birth;

                    // Determine whether to write
                    $setPhone = $newPhone !== null && ($force || $u->phone_number === null);
                    $setDob   = $newDob !== null   && ($force || $u->date_of_birth === null);

                    if (! $setPhone && ! $setDob) {
                        $skipped++;
                        continue;
                    }

                    if ($dryRun) {
                        $this->line("Would update user #{$u->id}: "
                            . ($setPhone ? "phone='{$newPhone}' " : '')
                            . ($setDob   ? "dob='{$newDob}'" : '')
                        );
                        $updated++;
                        continue;
                    }

                    DB::table('users')->where('id', $u->id)->update(array_filter([
                        'phone_number'  => $setPhone ? $newPhone : null,
                        'date_of_birth' => $setDob   ? $newDob   : null,
                    ], fn ($v) => $v !== null));

                    $updated++;
                }
            });

        $this->info("Re-sync complete. Scanned: {$scanned}, Updated: {$updated}, Skipped: {$skipped}");

        return self::SUCCESS;
    }
}
