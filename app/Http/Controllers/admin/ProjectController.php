<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignEmployeesRequest;
use App\Http\Requests\Admin\Projects\StoreProjectRequest;
use App\Http\Requests\Admin\Projects\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Services\Admin\ProjectService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService
    ) {}

    /**
     * GET /api/admin/projects
     * List all active (non-archived) projects with optional status filter.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status']);
        $perPage = (int) $request->get('per_page', 15);

        $paginator = $this->projectService->listProjects($filters, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Projects retrieved successfully.',
            'data'    => ProjectResource::collection($paginator)->response()->getData(true),
        ]);
    }

    /**
     * POST /api/admin/projects
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $result = $this->projectService->createProject($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully.',
            'data'    => new ProjectResource($result['project']),
        ], 201);
    }

    /**
     * GET /api/admin/projects/{id}
     */
    public function show(int $id): JsonResponse
    {
        $project = $this->projectService->getProjectById($id);

        return response()->json([
            'success' => true,
            'message' => 'Project detail retrieved.',
            'data'    => new ProjectResource($project),
        ]);
    }

    /**
     * PUT /api/admin/projects/{id}
     */
    public function update(UpdateProjectRequest $request, int $id): JsonResponse
    {
        $result = $this->projectService->updateProject($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully.',
            'data'    => new ProjectResource($result['project']),
        ]);
    }

    /**
     * DELETE /api/admin/projects/{id}  — Soft delete (archive).
     */
    public function destroy(int $id): JsonResponse
    {
        $this->projectService->archiveProject($id);

        return response()->json([
            'success' => true,
            'message' => 'Project archived successfully.',
            'data'    => null,
        ]);
    }

    /**
     * GET /api/admin/projects/archived
     */
    public function archived(Request $request): JsonResponse
    {
        $perPage  = (int) $request->get('per_page', 15);
        $paginator = $this->projectService->getArchivedProjects($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Archived projects retrieved.',
            'data'    => ProjectResource::collection($paginator)->response()->getData(true),
        ]);
    }

    /**
     * POST /api/admin/projects/{id}/restore
     */
    public function restore(int $id): JsonResponse
    {
        $project = $this->projectService->restoreProject($id);

        return response()->json([
            'success' => true,
            'message' => 'Project restored successfully.',
            'data'    => new ProjectResource($project),
        ]);
    }

    /**
     * POST /api/admin/projects/{id}/assign-employees
     */
    public function assignEmployees(AssignEmployeesRequest $request, int $id): JsonResponse
    {
        $project = $this->projectService->assignEmployees($id, $request->validated('employee_ids'));

        return response()->json([
            'success' => true,
            'message' => 'Employees assigned to project successfully.',
            'data'    => new ProjectResource($project),
        ]);
    }
}
