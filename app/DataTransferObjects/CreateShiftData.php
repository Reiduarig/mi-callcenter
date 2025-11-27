<?php

namespace App\DataTransferObjects;

use Carbon\Carbon;

readonly class CreateShiftData
{
    /**
     * Create a new CreateShiftData instance.
     */
    public function __construct(
        public int $userId,
        public Carbon $date,
        public string $startTime,
        public string $endTime,
        public ?int $shiftTemplateId = null,
        public bool $isCustom = false,
    ) {}

    /**
     * Create from array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            date: Carbon::parse($data['date']),
            startTime: $data['start_time'],
            endTime: $data['end_time'],
            shiftTemplateId: $data['shift_template_id'] ?? null,
            isCustom: $data['is_custom'] ?? false,
        );
    }

    /**
     * Convert to array for model creation.
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'date' => $this->date->format('Y-m-d'),
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
            'shift_template_id' => $this->shiftTemplateId,
            'is_custom' => $this->isCustom,
        ];
    }
}
