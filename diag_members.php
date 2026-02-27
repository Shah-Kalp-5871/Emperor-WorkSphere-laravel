<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

use App\Models\Project;

$project = Project::with('members.user')->first();
if ($project) {
    echo "Project: " . $project->name . "\n";
    echo "Members count: " . $project->members->count() . "\n";
    foreach ($project->members as $member) {
        echo " - Member ID: " . $member->id . ", User Name: " . ($member->user->name ?? 'N/A') . "\n";
    }
    
    $projectsArray = Project::with('members.user')->get()->toArray();
    file_put_contents('c:/laravel-projects/Emperor-WorkSphere/project_dump.json', json_encode($projectsArray, JSON_PRETTY_PRINT));
    echo "Full dump saved to c:/laravel-projects/Emperor-WorkSphere/project_dump.json\n";
} else {
    echo "No project found.\n";
}
