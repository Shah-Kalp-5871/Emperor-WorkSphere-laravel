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

    /**
     * GET /api/employee/team/{id}
     */
    public function show($id): JsonResponse
    {
        try {
            $member = $this->employeeRepository->findById($id);
            if (!$member) {
                return response()->json([
                    'success' => false,
                    'message' => 'Team member not found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Team member retrieved successfully.',
                'data' => $member
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving team member.'
            ], 500);
        }
    }
}
