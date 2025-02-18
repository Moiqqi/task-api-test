<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * Get all tasks resources
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Task::all();
    }

    /**
     * Find a Task resource by UUID
     *
     * @param string $uuid
     *
     * @return Task|null
     */
    public function find(string $uuid): ?Task
    {
        return Task::where('uuid', $uuid)->first();
    }

    /**
     * Create a Task resource
     *
     * @param array $data
     *
     * @return Task
     */
    public function create(array $data): Task
    {
        return Task::create($data);
    }

    /**
     * Update a Task resource
     *
     * @param Task $task
     * @param array $data
     *
     * @return Task
     */
    public function update(Task $task, array $data): Task
    {
        $task->update($data);

        return $task;
    }

    /**
     * Delete a Task resource
     *
     * @param Task $task
     *
     * @return void
     */
    public function delete(Task $task): void
    {
        $task->delete();
    }
}
