<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Collection;
use App\Repositories\TaskRepositoryInterface;

class TaskService
{
    /**
     * @var TaskRepositoryInterface $taskRepository
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Get all tasks
     *
     * @return Collection
     */
    public function getAllTasks(): Collection
    {
        return $this->taskRepository->all();
    }

    /**
     * Get a specified task
     *
     * @param string $uuid
     *
     * @return Task|null
     */
    public function getTask(string $uuid): ?Task
    {
        return $this->taskRepository->find($uuid);
    }

    /**
     * Create a new task
     *
     * @param array $data
     *
     * @return Task
     */
    public function createTask(array $data): Task
    {
        return $this->taskRepository->create($data);
    }

    /**
     * Update the specified task
     *
     * @param Task $task
     * @param array $data
     *
     * @return Task
     */
    public function updateTask(Task $task, array $data): Task
    {
        return $this->taskRepository->update($task, $data);
    }

    /**
     * Delete the specified task
     *
     * @param Task $task
     *
     * @return void
     */
    public function deleteTask(Task $task): void
    {
        $this->taskRepository->delete($task);
    }
}
