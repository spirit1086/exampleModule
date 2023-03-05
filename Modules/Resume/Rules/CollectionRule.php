<?php

namespace App\Modules\Resume\Rules;

use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;

class CollectionRule implements InvokableRule,DataAwareRule,ValidatorAwareRule
{
    protected $data = [];
    protected $validator;
    protected $required_fields = [];
    protected $validateMessages = [];
    protected $index;

    public function __construct(array $required_fields, array $validateMessages, string $index)
    {
        $this->required_fields = $required_fields;
        $this->validateMessages = $validateMessages;
        $this->index = $index;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setValidator($validator)
    {
        $this->validator = $validator;
        return $this;
    }
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $collection = $this->data[$this->index] ?? null;
        $pos = strpos($attribute,'.');
        $currentIndexCollection = substr($attribute,$pos+1,1);
        $reqFields = $this->required_fields[$this->index];
        for ($i = 0; $i < count($reqFields); $i++) {
            $required_field = $reqFields[$i];
            $validate_field = $attribute . '.' . $required_field;
            if (!array_key_exists($required_field, $collection[$currentIndexCollection])) {
                     $this->validator->getMessageBag()->add($validate_field, $this->validateMessages[$this->index][$required_field]);
            }
        }
    }
}
