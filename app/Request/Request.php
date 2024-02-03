<?php

namespace Request;

class Request
{
    private array $body;
    private array $errors;

    public function __construct(array $body)
    {
        $this->body = $body;
        $this->validate();

    }
    private function validate()
    {
        $this->errors = [];

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
    public function getOneByKey($key)
    {
        return $this->has($key)? $this->body[$key] : null;

    }
    public function has($key)
    {
        return isset($this->body[$key]);
    }
    public function getErrors()
    {
        return $this->errors;
    }
    public function getAll()
    {
        return $this->body;
    }

}