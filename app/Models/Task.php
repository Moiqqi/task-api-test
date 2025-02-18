<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Enums\TaskStatus;

class Task extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var string
     */
    protected $keyType = 'int';

    /**
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'title',
        'description',
        'status',
    ];

    /**
     * @var array<string, class-string>
     */
    protected $casts = [
        // Cast status to TaskStatus enum
        'status' => TaskStatus::class,
    ];

    /**
     * Get the route key name for task model
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        // Use 'uuid' for route binding
        return 'uuid';
    }

    /**
     * Register creating model event listener and perform some actions
     *
     * @return void
     */
    protected static function booted(): void
    {
        // Set automatically uuid when creating a new task
        // And set a default status value if not specified
        static::creating(function ($task) {
            $task->uuid = (string) Str::uuid();
            $task->status = $task->status ?? TaskStatus::PENDING;
        });
    }

    /**
     * Accessor for title attribute
     *
     * @param string $value
     *
     * @return string
     */
    public function getTitleAttribute(string $value): string
    {
        return $value;
    }

    /**
     * Mutator for title attribute
     *
     * @param  string  $value
     *
     * @return void
     */
    public function setTitleAttribute(string $value): void
    {
        // Trim title value before saving
        $this->attributes['title'] = trim($value);
    }
}
