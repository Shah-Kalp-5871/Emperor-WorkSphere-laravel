<?php

namespace App\Jobs;

use App\Models\AnonymousChatSession;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CleanupAnonymousChatSessionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     * Deletes sessions and their messages that have expired.
     */
    public function handle(): void
    {
        $count = AnonymousChatSession::where('expires_at', '<', now())->count();
        
        if ($count > 0) {
            // Due to cascading soft deletes or standard deletes, this will wipe the messages too.
            // Since our migration doesn't specify cascade delete on the 'session_id' foreign key implicitly 
            // (Wait, we should just delete the sessions to wipe the traces).
            AnonymousChatSession::where('expires_at', '<', now())->delete();
            
            // To be thorough, manually delete orphan messages if constrained deletion wasn't set up
            \App\Models\AnonymousChatMessage::whereNotIn('session_id', \App\Models\AnonymousChatSession::pluck('id'))->delete();

            Log::info("Cleaned up {$count} expired anonymous chat sessions and their messages.");
        }
    }
}
