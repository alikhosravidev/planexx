<?php

declare(strict_types=1);

namespace App\Core\Notify\Services\SmsServiceProvider\DTOs;

use App\Utilities\StringUtility;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Carbon;

class ReceivedSmsDTO implements Arrayable
{
    public Carbon $receivedAt;

    public function __construct(
        public string $message,
        public string $sender,
        public string $receptor,
        float|string $date,
    ) {
        if (mb_strlen($this->message, 'UTF-8') > 256) {
            throw new \InvalidArgumentException('Message too long');
        }
        $this->sender     = StringUtility::transformMobile($this->sender);
        $this->receivedAt = Carbon::createFromTimestamp($date);
    }

    public function toArray(): array
    {
        return [
            'message'     => $this->message,
            'sender'      => $this->sender,
            'received_at' => $this->receivedAt,
            'receptor'    => $this->receptor ?? '',
        ];
    }
}
