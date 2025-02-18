<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    /**
     * Get all tasks resources
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Find a Task resource by UUID
     *
     * @param string $uuid
     * @return Task|null
     */
    public function find(string $uuid): ?Task;

    /**
     * Create a Task resource
     *
     * @param array $data
     * @return Task
     */
    public function create(array $data): Task;

    /**
     * Update a Task resource
     *
     * @param Task $task
     * @param array $data
     * @return Task
     */
    public function update(Task $task, array $data): Task;

    /**
     * Delete a Task resource
     *
     * @param Task $task
     * @return void
     */
    public function delete(Task $task): void;
}
