<?php

namespace Up\Core\Model;

class ResponseModel
{
    private int $code;
    private string $message;
    private $response;

    /**
     * @param int $code
     * @param string $message
     * @param mixed $response
     */
    public function __construct(int $code, string $message, $response = '')
    {
        $this->code = $code;
        $this->message = $message;
        $this->response = $response;
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->isError() ? 'ERROR' : 'OK';
    }

    /**
     * @return bool
     */
    public function isError(): bool
    {
        return $this->code >= 400;
    }

    /**
     * @return string
     */
    public function getResponse(): string
    {
        if ($this->isError() && !empty($this->message)) {
            $this->response = (object)['message' => $this->message];
        }

        return json_encode($this->response);
    }
}
