<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase; // Reset the database after each test

    /**
     * Test fetching all tasks.
     *
     * @return void
     */
    public function testFetchAllTasks(): void
    {
        // Create some test tasks
        Task::factory()->count(10)->create();

        // Send a GET request to the index endpoint
        $response = $this->getJson('/api/tasks');

        // Assert the response status is 200 OK
        $response->assertStatus(200);

        // Assert the response contains the correct number of tasks
        $response->assertJsonCount(10);
    }

    /**
     * Test fetching a single task.
     *
     * @return void
     */
    public function testFetchSingleTask(): void
    {
        // Create a test task
        /** @var Task $task */
        $task = Task::factory()->create();

        // Send a GET request to the show endpoint
        $response = $this->getJson("/api/tasks/{$task->uuid}");

        // Assert the response status is 200 OK
        $response->assertStatus(200);

        // Assert the response contains the correct task data
        $response->assertJson([
            'uuid' => $task->uuid,
            'title' => $task->title,
            'description' => $task->description,
            'status' => $task->status->value,
        ]);
    }

    /**
     * Test creating a new task.
     *
     * @return void
     */
    public function testCreateTask(): void
    {
        // Send a POST request to the store endpoint
        $response = $this->postJson('/api/tasks', [
            'title' => 'New task',
            'description' => 'This is a new task.',
            'status' => TaskStatus::PENDING->value,
        ]);

        // Assert the response status is 201 Created
        $response->assertStatus(201);

        // Assert the response contains the correct task data
        $response->assertJson([
            'title' => 'New task',
            'description' => 'This is a new task.',
            'status' => TaskStatus::PENDING->value,
        ]);

        // Assert the task was saved in the database
        $this->assertDatabaseHas('tasks', [
            'title' => 'New task',
            'description' => 'This is a new task.',
            'status' => TaskStatus::PENDING->value,
        ]);
    }

    /**
     * Test updating an existing task.
     *
     * @return void
     */
    public function testUpdateTask(): void
    {
        // Create a test task
        /** @var Task $task */
        $task = Task::factory()->create();

        // Send a PUT request to the update endpoint
        $response = $this->putJson("/api/tasks/{$task->uuid}", [
            'title' => ' Updated Task Title ',
            'status' => TaskStatus::COMPLETED->value,
        ]);

        // Assert the response status is 200 OK
        $response->assertStatus(200);

        // Assert the response contains the updated task data
        $response->assertJson([
            'title' => 'Updated Task Title',
            'status' => TaskStatus::COMPLETED->value,
        ]);

        // Assert the task was updated in the database
        $this->assertDatabaseHas('tasks', [
            'uuid' => $task->uuid,
            'title' => 'Updated Task Title',
            'status' => TaskStatus::COMPLETED->value,
        ]);
    }

    /**
     * Test deleting a task.
     *
     * @return void
     */
    public function testDeleteTask(): void
    {
        // Create a test task
        /** @var Task $task */
        $task = Task::factory()->create();

        // Assert the created task exist in the database
        $this->assertDatabaseHas('tasks', [
            'uuid' => $task->uuid,
        ]);

        // Send a DELETE request to the destroy endpoint
        $response = $this->deleteJson("/api/tasks/{$task->uuid}");

        // Assert the response status is 204 No Content
        $response->assertStatus(204);

        // Assert the task was deleted from the database
        $this->assertDatabaseMissing('tasks', [
            'uuid' => $task->uuid,
        ]);
    }

    /**
     * Test creating a task with invalid status.
     *
     * @return void
     */
    public function testCreateTaskInvalidStatus(): void
    {
        // Send a POST request with invalid status value
        $response = $this->postJson('/api/tasks', [
            'title' => 'Task title',
            'description' => 'This is a test task.',
            'status' => 'invalid_status',
        ]);

        // Assert the response status is 422 Unprocessable Entity
        $response->assertStatus(422);

        // Assert the response contains validation errors
        $response->assertJsonValidationErrors(['status']);
    }


    /**
     * Test creating a task with missing title
     *
     * @return void
     */
    public function testCreateTaskMissingTitle(): void
    {
        // Send a POST request with missing title
        $response = $this->postJson('/api/tasks', [
            'description' => 'This is a test task.',
            'status' => 'pending',
        ]);

        // Assert the response status is 422 Unprocessable Entity
        $response->assertStatus(422);

        // Assert the response contains validation errors
        $response->assertJsonValidationErrors(['title']);
    }
    /**
     * Test fetching a non-existent task.
     *
     * @return void
     */
    public function testFetchNonExistentTask(): void
    {
        // Send a GET request with an invalid UUID
        $response = $this->getJson('/api/tasks/invalid-uuid');

        // Assert the response status is 404 Not Found
        $response->assertStatus(404);

        // Assert the response contains the correct error message
        $response->assertJson([
            'message' => 'Not Found Task',
        ]);
    }
}
