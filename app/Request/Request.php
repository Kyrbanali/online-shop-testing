<?php

namespace Request;

class Request
{
    private string $method;
    private string $uri;
    private array $headers;
    private array $body;
    private array $errors;
    public function __construct(string $method, string $uri, array $headers = [], array $body = [])
    {
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = $headers;
        $this->body = $body;
        $this->validate();

    }
    private function validate()
    {
        $this->errors = [];

        $fields = [
            'name' => ['min-length' => 2, 'no-numbers' => true],
            'email' => ['min-length' => 2, 'contains-at' => true],
            'psw' => ['min-length' => 4],
            'psw-repeat' => ['min-length' => 4],
        ];

        foreach ($this->body as $key => $value)
        {
            if (!($this->has($key)))
            {
                $this->errors[$key] = "Field '{$key}' is missing";
                continue;
            }
            switch ($key)
            {
                case 'name':
                    $this->validateField($value, 'name', ['min-length' => 2, 'no-numbers' => true]);
                    break;
                case 'email':
                    $this->validateField($value, 'email', ['min-length' => 2, 'contains-at' => true]);
                    break;
                case 'psw':
                    $this->validateField($value, 'psw', ['min-length' => 4]);
                    break;
                case 'psw-repeat':
                    $this->validateField($value, 'psw-repeat', ['min-length' => 4, 'matches' => $this->body['psw']]);
                    break;
            }
        }
    }

    private function validateField($value, string $fieldName, array $rules) : void
    {
        if (isset($rules['min-length']) && strlen($value) < $rules['min-length'])
        {
            $this->errors[$fieldName] = "{$fieldName} length must be more than {$rules['min-length']}";
        }

        if (isset($rules['no-numbers']) && preg_match("/\d/", $value))
        {
            $this->errors[$fieldName] = "The {$fieldName} should not contain numbers";
        }

        if (isset($rules['contains-at']) && !str_contains($value, '@') )
        {
            $this->errors[$fieldName] = "{$fieldName} doesn't contain '@'";
        }

        if (isset($rules['matches']) && $value !== $rules['matches'] )
        {
            $this->errors[$fieldName] = "{$fieldName} mismatch";
        }
    }
    public function getOneByKey(string $key)
    {
        return $this->has($key)? $this->body[$key] : null;

    }
    public function has(string $key) : bool
    {
        return isset($this->body[$key]);
    }
    public function getErrors() : array
    {
        return $this->errors;
    }
    public function getMethod() : string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody() : array
    {
        return $this->body;
    }

}