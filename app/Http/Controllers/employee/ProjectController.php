<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Services\Employee\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Http\Resources\ProjectResource;
use App\Http\Requests\Employee\Projects\StoreProjectRequest;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService
    ) {}

    /**
     * GET /api/employee/projects
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = (int) $request->get('per_page', 15);
        $projects = $this->projectService->getMyProjects($perPage);
        
        return response()->json([
            'success' => true,
            'message' => 'Employee projects retrieved successfully.',
            'data' => ProjectResource::collection($projects)->response()->getData(true)
        ]);
    }

    /**
     * POST /api/employee/projects
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        try {
            $project = $this->projectService->createProject($request->validated());
            return response()->json([
                'success' => true,
                'message' => 'Project created successfully.',
                'data' => new ProjectResource($project)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
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
                'data' => new ProjectResource($project)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }

    /**
     * POST /api/employee/projects/{id}/members
     */
    public function addMember(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id'
        ]);

        try {
            $project = $this->projectService->addMember($id, $request->employee_id);
            return response()->json([
                'success' => true,
                'message' => 'Member added successfully.',
                'data' => new ProjectResource($project)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 403);
        }
    }
}
