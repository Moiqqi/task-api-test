<?php

namespace Tests\Unit\Services;

use App\Repositories\TaskRepositoryInterface;
use App\Services\TaskService;
use App\Models\Task;
use Tests\TestCase;
use Mockery;

class TaskServiceTest extends TestCase
{
    protected $taskRepository;
    protected TaskService $taskService;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock the TaskRepositoryInterface
        $this->taskRepository = Mockery::mock(TaskRepositoryInterface::class);

        // Init the TaskService with mocked task repository
        $this->taskService = new TaskService($this->taskRepository);
    }

    protected function tearDown(): void
    {
        // Clean up Mock
        Mockery::close();
        parent::tearDown();
    }

    /**
     * Test fetching all tasks.
     *
     * @return void
     */
    public function testFetchAllTasks(): void
    {
        // Mock the repository's all() method
        $this->taskRepository
            ->shouldReceive('all')
            ->once()
            ->andReturn(collect([new Task(), new Task()]));

        // Fetch all tasks
        $tasks = $this->taskService->getAllTasks();

        // Assert the correct number of tasks are returned
        $this->assertCount(2, $tasks);
    }

    /**
     * Test fetching a single task by UUID.
     *
     * @return void
     */
    public function testFetchSingleTask(): void
    {
        // Create a mock task
        $task = new Task();
        $task->uuid = '550e8400-e29b-41d4-a716-446655440000';

        // Mock the repository's find() method
        $this->taskRepository
            ->shouldReceive('find')
            ->with($task->uuid)
            ->once()
            ->andReturn($task);

        // Fetch the task by UUID
        $foundTask = $this->taskService->getTask($task->uuid);

        // Assert the correct task is returned
        $this->assertNotNull($foundTask);
        $this->assertEquals($task->uuid, $foundTask->uuid);
    }

    /**
     * Test creating a new task.
     *
     * @return void
     */
    public function testCreateTask(): void
    {
        // Create task data
        $data = [
            'title' => 'New Task',
            'description' => 'This is a new task.',
            'status' => 'pending',
        ];

        // Create a mock task
        $task = new Task();
        $task->uuid = '550e8400-e29b-41d4-a716-446655440000';
        $task->title = $data['title'];
        $task->description = $data['description'];
        $task->status = $data['status'];

        // Mock the repository's create() method
        $this->taskRepository
            ->shouldReceive('create')
            ->with($data)
            ->once()
            ->andReturn($task);

        // Create the task
        $createdTask = $this->taskService->createTask($data);

        // Assert the task was created successfully
        $this->assertEquals($data['title'], $createdTask->title);
    }

    /**
     * Test updating an existing task.
     *
     * @return void
     */
    public function testUpdateTask(): void
    {
        // Create a mock task
        $task = new Task();
        $task->uuid = '550e8400-e29b-41d4-a716-446655440000';
        $task->title = 'Old Task Title';

        // Update data
        $data = [
            'title' => 'Updated Task Title',
        ];

        //  Task object after update
        $updatedTask = new Task();
        $updatedTask->uuid = '550e8400-e29b-41d4-a716-446655440000';
        $updatedTask->title = 'Updated Task Title';


        // Mock the repository's update() method
        $this->taskRepository
            ->shouldReceive('update')
            ->with($task, $data)
            ->once()
            ->andReturn($updatedTask);

        // Update the task
        $updatedTask = $this->taskService->updateTask($task, $data);

        // Assert the task was updated successfully
        $this->assertEquals($data['title'], $updatedTask->title);
    }

    /**
     * Test deleting a task.
     *
     * @return void
     */
    public function testDeleteTask(): void
    {
        // Create a mock task
        $task = new Task();
        $task->uuid = '550e8400-e29b-41d4-a716-446655440000';

        // Mock the repository's delete() method
        $this->taskRepository
            ->shouldReceive('delete')
            ->with($task)
            ->once();

        // Delete the task
        $this->taskService->deleteTask($task);

        // We just need to verify the method was called, no assertion needed
        $this->assertTrue(true);
    }
}
