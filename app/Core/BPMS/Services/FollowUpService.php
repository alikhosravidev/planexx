<?php

declare(strict_types=1);

namespace App\Core\BPMS\Services;

use App\Core\BPMS\DTOs\FollowUpDTO;
use App\Core\BPMS\Entities\FollowUp;
use App\Core\BPMS\Repositories\FollowUpRepository;
use App\Core\FileManager\DTOs\FileUploadDTO;
use App\Core\FileManager\Entities\File;
use App\Core\FileManager\Enums\FileCollectionEnum;
use App\Core\FileManager\Services\FileService;
use Illuminate\Http\UploadedFile;

readonly class FollowUpService
{
    public function __construct(
        private FollowUpRepository $followUpRepository,
        private FileService        $fileService,
    ) {
    }

    public function create(
        FollowUpDTO    $dto,
        ?int           $previousAssigneeId = null,
        ?int           $previousStateId = null,
        ?UploadedFile  $attachment = null
    ): FollowUp {
        $data = $dto->toArray();

        $data['previous_assignee_id'] = $previousAssigneeId;
        $data['previous_state_id']    = $previousStateId;
        $data['created_at']           = now();

        /** @var FollowUp $followUp */
        $followUp = $this->followUpRepository->create($data);

        if ($attachment) {
            $this->uploadAttachment($followUp, $attachment);
        }

        return $followUp;
    }

    // TODO: handle this action using events.
    private function uploadAttachment(FollowUp $followUp, UploadedFile $attachment): File
    {
        $uploadDto = new FileUploadDTO(
            file      : $attachment,
            moduleName: $followUp->getModuleName(),
            title     : 'Follow-up Attachment',
            entityType: $followUp->getMorphClass(),
            entityId  : $followUp->id,
            collection: FileCollectionEnum::ATTACHMENT,
            isPublic  : false,
            uploadedBy: $followUp->created_by,
        );

        return $this->fileService->upload($uploadDto);
    }
}
