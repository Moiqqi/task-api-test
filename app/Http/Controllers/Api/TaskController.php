<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Http\Request;
use App\Services\TaskService;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    /**
     * @var TaskService $taskService
     */
    protected TaskService $taskService;

    /**
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Get completed list of Task resource
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tasks = $this->taskService->getAllTasks();

        return response()->json($tasks);
    }

    /**
     * Get the specified Task resource.
     *
     * @param string $uuid
     *
     * @return JsonResponse
     */
    public function show(string $uuid): JsonResponse
    {
        $task = $this->taskService->getTask($uuid);

        if (!$task) {
            return response()->json(['message' => 'Not Found Task'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($task);
    }

    /**
     * Create a new Task resource in database.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // Validate the request data
        $validated = $request->validate([
            'title' => 'required|string|max:55',
            'description' => 'nullable|string',
            'status' => ['nullable', Rule::enum(TaskStatus::class)],
        ]);

        // Create the task
        $task = $this->taskService->createTask($validated);

        return response()->json($task, Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in database.
     *
     * @param Request $request
     * @param Task $task
     *
     * @return JsonResponse
     */
    public function update(Request $request, Task $task): JsonResponse
    {
        // Validate the request data
        $validated = $request->validate([
            'title' => 'sometimes|string|max:55',
            'description' => 'nullable|string',
            'status' => ['sometimes', Rule::enum(TaskStatus::class)],
        ]);

        // Update task
        $task = $this->taskService->updateTask($task, $validated);

        return response()->json($task);
    }

    /**
     * Remove the specified Task resource from database
     *
     * @param Task $task
     *
     * @return JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        // Delete task
        $this->taskService->deleteTask($task);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
