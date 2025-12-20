<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Complainant;
use App\Models\EtoAction;

class SyncComplaintCurrentState extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-complaint-current-state';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
 

public function handle()
{
    Complainant::chunk(100, function ($complaints) {

        foreach ($complaints as $complaint) {

            $action = EtoAction::where('application_id', $complaint->application_id)
                ->latest('id')
                ->first();

            if (! $action) {
                $complaint->update([
                    'current_owner' => 'ETO',
                    'current_level' => 'ETO',
                    'current_status' => 'fresh',
                    'is_final' => false,
                ]);
                continue;
            }

            $complaint->update([
                'current_owner' => match ($action->current_level) {
                    'ETO' => $action->status === 'pending'
                        ? 'APPLICANT'
                        : 'ETO',
                    'HQ' => 'HQ',
                    'CLOSED' => 'CLOSED',
                    default => 'ETO',
                },

                'current_level' => $action->current_level,
                'current_status' => $action->current_status,
                'is_final' => $action->is_final,
            ]);
        }
    });

    $this->info('Complaint current state synced successfully.');
}

}
