<?php

namespace App\Api\Feature\Webhook;

use Symfony\Component\Serializer\Annotation\SerializedName;

final readonly class ResultResponse
{
    public function __construct(
        #[SerializedName('FileName')]
        public string $fileName,

        #[SerializedName('Status')]
        public string $status,

        #[SerializedName('ErrorMessage')]
        public ?string $errorMessage,
    ) {
    }
}