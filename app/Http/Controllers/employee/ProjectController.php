<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\Employee\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService
    ) {}

    /**
     * GET /api/employee/projects
     */
    public function index(): JsonResponse
    {
        $projects = $this->projectService->getMyProjects();
        return response()->json([
            'success' => true,
            'message' => 'Employee projects retrieved successfully.',
            'data' => $projects
        ]);
    }

    /**
     * GET /api/employee/projects/{id}
     */
    public function show(int $id): JsonResponse
    {
        try {
            $project = $this->projectService->getMyProjectDetails($id);
            return response()->json([
                'success' => true,
                'message' => 'Project details retrieved successfully.',
                'data' => $project
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }
}
