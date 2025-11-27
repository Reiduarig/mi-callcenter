<?php

namespace App\DataTransferObjects;

use App\Enums\AbsenceStatus;
use App\Enums\AbsenceType;
use Carbon\Carbon;

readonly class CreateAbsenceData
{
    /**
     * Create a new CreateAbsenceData instance.
     */
    public function __construct(
        public int $userId,
        public AbsenceType $type,
        public Carbon $startDate,
        public Carbon $endDate,
        public AbsenceStatus $status = AbsenceStatus::Pending,
    ) {}

    /**
     * Create from array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            type: is_string($data['type']) ? AbsenceType::from($data['type']) : $data['type'],
            startDate: Carbon::parse($data['start_date']),
            endDate: Carbon::parse($data['end_date']),
            status: isset($data['status'])
                ? (is_string($data['status']) ? AbsenceStatus::from($data['status']) : $data['status'])
                : AbsenceStatus::Pending,
        );
    }

    /**
     * Convert to array for model creation.
     */
    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'type' => $this->type,
            'start_date' => $this->startDate->format('Y-m-d'),
            'end_date' => $this->endDate->format('Y-m-d'),
            'status' => $this->status,
        ];
    }
}
