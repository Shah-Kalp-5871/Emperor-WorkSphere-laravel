<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignEmployeesRequest;
use App\Http\Requests\Admin\Projects\StoreProjectRequest;
use App\Http\Requests\Admin\Projects\UpdateProjectRequest;
use App\Services\Admin\ProjectService;
use Illuminate\Http\JsonResponse;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectService $projectService
    ) {}

    public function index()
    {
        return \App\Http\Resources\ProjectResource::collection($this->projectService->listProjects());
    }

    public function store(StoreProjectRequest $request)
    {
        $project = $this->projectService->createProject($request->validated());
        return new \App\Http\Resources\ProjectResource($project);
    }

    public function show(int $id)
    {
        return new \App\Http\Resources\ProjectResource($this->projectService->getProjectById($id));
    }

    public function update(UpdateProjectRequest $request, int $id): JsonResponse
    {
        $project = $this->projectService->updateProject($id, $request->validated());
        return response()->json(['message' => 'Project updated successfully', 'data' => $project]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->projectService->deleteProject($id);
        return response()->json(['message' => 'Project deleted successfully']);
    }

    public function assignEmployees(AssignEmployeesRequest $request, int $id): JsonResponse
    {
        $project = $this->projectService->assignEmployees($id, $request->validated('employee_ids'));
        return response()->json(['message' => 'Employees assigned to project successfully', 'data' => $project]);
    }
}
