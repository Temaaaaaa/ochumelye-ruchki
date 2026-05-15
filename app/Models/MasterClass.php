<?php

namespace App\Models;

use App\Support\MasterClassSchedule;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read string $formatted_date
 * @property-read string $formatted_time_slot
 * @property-read int $seats_left
 * @property-read bool $is_full
 */
class MasterClass extends Model
{
    protected $fillable = [
        'user_id',
        'creativity_type_id',
        'title',
        'description',
        'date',
        'time_slot',
        'max_people',
        'price',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    protected $appends = [
        'formatted_date',
        'formatted_time_slot',
        'seats_left',
        'is_full',
    ];

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function master(): BelongsTo
    {
        return $this->teacher();
    }

    /**
     * @return BelongsTo<CreativityType, $this>
     */
    public function creativityType(): BelongsTo
    {
        return $this->belongsTo(CreativityType::class);
    }

    /**
     * @return HasMany<Enrollment, $this>
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    protected function formattedDate(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->date->locale('ru')->translatedFormat('d F Y'),
        );
    }

    protected function formattedTimeSlot(): Attribute
    {
        return Attribute::make(
            get: fn (): string => MasterClassSchedule::label($this->time_slot),
        );
    }

    protected function seatsLeft(): Attribute
    {
        return Attribute::make(
            get: fn (): int => $this->calculateSeatsLeft(),
        );
    }

    protected function isFull(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => $this->calculateSeatsLeft() <= 0,
        );
    }

    private function calculateSeatsLeft(): int
    {
        $enrollmentsCount = array_key_exists('enrollments_count', $this->attributes)
            ? (int) $this->attributes['enrollments_count']
            : ($this->relationLoaded('enrollments')
                ? $this->enrollments->count()
                : $this->enrollments()->count());

        return max(0, (int) $this->max_people - $enrollmentsCount);
    }
}
