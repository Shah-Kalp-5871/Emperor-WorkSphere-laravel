<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\EmployeeRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function __construct(
        protected EmployeeRepositoryInterface $employeeRepository
    ) {}

    /**
     * GET /api/employee/team
     */
    public function index(): JsonResponse
    {
        // Get all active employees (teammates)
        // We can just use getAll from repository
        $team = $this->employeeRepository->getAll(100);
        
        return response()->json([
            'success' => true,
            'message' => 'Team members retrieved successfully.',
            'data' => $team
        ]);
    }
}
