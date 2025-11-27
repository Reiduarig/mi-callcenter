<?php

namespace App\DataTransferObjects;

use App\Enums\AbsenceStatus;
use App\Enums\AbsenceType;
use Carbon\Carbon;

readonly class UpdateAbsenceData
{
    /**
     * Create a new UpdateAbsenceData instance.
     */
    public function __construct(
        public ?int $userId = null,
        public ?AbsenceType $type = null,
        public ?Carbon $startDate = null,
        public ?Carbon $endDate = null,
        public ?AbsenceStatus $status = null,
        public ?int $approvedBy = null,
        public ?Carbon $approvedAt = null,
        public ?string $approvalNotes = null,
    ) {}

    /**
     * Create from array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'] ?? null,
            type: isset($data['type'])
                ? (is_string($data['type']) ? AbsenceType::from($data['type']) : $data['type'])
                : null,
            startDate: isset($data['start_date']) ? Carbon::parse($data['start_date']) : null,
            endDate: isset($data['end_date']) ? Carbon::parse($data['end_date']) : null,
            status: isset($data['status'])
                ? (is_string($data['status']) ? AbsenceStatus::from($data['status']) : $data['status'])
                : null,
            approvedBy: $data['approved_by'] ?? null,
            approvedAt: isset($data['approved_at']) ? Carbon::parse($data['approved_at']) : null,
            approvalNotes: $data['approval_notes'] ?? null,
        );
    }

    /**
     * Convert to array for model update (only non-null values).
     */
    public function toArray(): array
    {
        return array_filter([
            'user_id' => $this->userId,
            'type' => $this->type,
            'start_date' => $this->startDate?->format('Y-m-d'),
            'end_date' => $this->endDate?->format('Y-m-d'),
            'status' => $this->status,
            'approved_by' => $this->approvedBy,
            'approved_at' => $this->approvedAt,
            'approval_notes' => $this->approvalNotes,
        ], fn ($value) => $value !== null);
    }
}
