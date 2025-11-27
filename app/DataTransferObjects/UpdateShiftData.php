<?php

namespace App\DataTransferObjects;

use Carbon\Carbon;

readonly class UpdateShiftData
{
    /**
     * Create a new UpdateShiftData instance.
     */
    public function __construct(
        public ?int $userId = null,
        public ?Carbon $date = null,
        public ?string $startTime = null,
        public ?string $endTime = null,
        public ?int $shiftTemplateId = null,
        public ?bool $isCustom = null,
    ) {}

    /**
     * Create from array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'] ?? null,
            date: isset($data['date']) ? Carbon::parse($data['date']) : null,
            startTime: $data['start_time'] ?? null,
            endTime: $data['end_time'] ?? null,
            shiftTemplateId: $data['shift_template_id'] ?? null,
            isCustom: $data['is_custom'] ?? null,
        );
    }

    /**
     * Convert to array for model update (only non-null values).
     */
    public function toArray(): array
    {
        return array_filter([
            'user_id' => $this->userId,
            'date' => $this->date?->format('Y-m-d'),
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
            'shift_template_id' => $this->shiftTemplateId,
            'is_custom' => $this->isCustom,
        ], fn ($value) => $value !== null);
    }
}
