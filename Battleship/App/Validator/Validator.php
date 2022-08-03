<?php

namespace Battleship\App\Validator;

use Battleship\App\Validator\Rule\IfWasErrorsStop;

class Validator
{
    protected array $fieldsWithErrors = [];

    /**
     * @param array $fieldsData
     * @param array $fieldsRules
     * @return void
     */
    public function make(array $fieldsData, array $fieldsRules): void
    {
        /**
         * @param RuleInterface $rule
         */
        foreach ($fieldsRules as $field => $rules) {
            foreach ($rules as $rule) {
                if (is_a($rule, IfWasErrorsStop::class)) {
                    if ($this->fieldsWithErrors) {
                        break 2;
                    } else {
                        continue;
                    }
                }

                if (!$rule->pass($fieldsData[$field] ?? null)) {

                    $this->fieldsWithErrors[$field] = $rule->message();
                    break;
                }

            }
        }
    }

    public function isValid(): bool
    {
        return empty($this->fieldsWithErrors);
    }

    public function getErrors(): array
    {
        return $this->fieldsWithErrors;
    }
}