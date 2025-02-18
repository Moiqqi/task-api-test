<?php

namespace Database\Factories;

use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * @var class-string<Task>
     */
    protected $model = Task::class;

    /**
     * Define the model's default state
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid(),
            'title' => $this->faker->words(2, true),
            'description' => $this->faker->paragraph(1),
            'status' => $this->faker->randomElement([
                TaskStatus::PENDING->value,
                TaskStatus::COMPLETED->value,
            ]),
        ];
    }
}
