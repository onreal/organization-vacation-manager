<?php

namespace Up\Core\Models;

interface IApiResponse
{
    public function __construct(int $code, string $message, $response = '');
    public function getCode(): int;
    public function getMessage(): string;
    public function isError(): bool;
    public function getResponse(): string;
}
