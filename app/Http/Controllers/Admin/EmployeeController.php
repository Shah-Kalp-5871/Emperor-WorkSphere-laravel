<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmployeeRequest;
use App\Http\Requests\Admin\UpdateEmployeeRequest;
use App\Services\Admin\EmployeeService;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    public function __construct(
        protected EmployeeService $employeeService
    ) {}

    public function index(): JsonResponse
    {
        $employees = $this->employeeService->listEmployees();
        return response()->json($employees);
    }

    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $employee = $this->employeeService->createEmployee($request->validated());
        return response()->json(['message' => 'Employee created successfully', 'data' => $employee], 201);
    }

    public function show(int $id): JsonResponse
    {
        $employee = $this->employeeService->getEmployeeById($id);
        return response()->json($employee);
    }

    public function update(UpdateEmployeeRequest $request, int $id): JsonResponse
    {
        $employee = $this->employeeService->updateEmployee($id, $request->validated());
        return response()->json(['message' => 'Employee updated successfully', 'data' => $employee]);
    }

    public function destroy(int $id): JsonResponse
    {
        $this->employeeService->totalDeleteEmployee($id);
        return response()->json(['message' => 'Employee and all associated data deleted successfully']);
    }

    public function archived(): JsonResponse
    {
        $employees = $this->employeeService->getArchivedEmployees();
        return response()->json([
            'success' => true,
            'data' => $employees,
        ]);
    }

    public function restore(int $id): JsonResponse
    {
        $employee = $this->employeeService->restoreEmployee($id);
        return response()->json([
            'success' => true,
            'message' => 'Employee restored successfully',
            'data' => $employee
        ]);
    }
}
