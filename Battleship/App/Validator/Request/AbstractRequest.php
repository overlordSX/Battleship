<?php

namespace Battleship\App\Validator\Request;

use Battleship\App\Validator\Validator;

abstract class AbstractRequest
{
    protected Validator $validator;

    abstract protected function prepareParams(array $params): array;
    abstract protected function rules(): array;

    public function validate($params): void
    {
        $this->validator = new Validator();
        $this->validator->make($this->prepareParams($params), $this->rules());
    }

    public function answer(): array
    {
        $errors = $this->validator->isValid() ? [] : $this->validator->getErrors();
        if ($errors) {
            $error['success'] = false;
            $error['message'] = implode("\n", array_values($errors));
            return $error;
        }
        return [];
    }
}