<?php

namespace Gateway\Api;

class Response
{

    /**
     * @var array|\stdClass|string
     */
    protected $body;

    /**
     * Response constructor.
     * @param array|\stdClass|string $body
     */
    public function __construct($body)
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        \header('X-Hostname: ' . $_SERVER['HOSTNAME']);

        if (\is_array($this->body) || \is_object($this->body)) {
            return \json_encode($this->body, JSON_UNESCAPED_UNICODE);
        }

        return (string)$this->body;
    }

}
