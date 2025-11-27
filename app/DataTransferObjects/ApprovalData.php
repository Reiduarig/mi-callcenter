<?php

namespace App\DataTransferObjects;

readonly class ApprovalData
{
    /**
     * Create a new ApprovalData instance.
     */
    public function __construct(
        public ?string $notes = null,
    ) {}

    /**
     * Create from array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            notes: $data['notes'] ?? $data['approval_notes'] ?? null,
        );
    }

    /**
     * Get notes.
     */
    public function getNotes(): ?string
    {
        return $this->notes;
    }
}
