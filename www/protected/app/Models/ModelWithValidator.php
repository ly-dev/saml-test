<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

abstract class ModelWithValidator extends Model
{
    protected $scenarios = [];
    protected $rules = [];

    public function validate($data = null, $scenario = null)
    {
        if (!is_array($data))
        {
            $data = $data instanceof Model ? $data->getAttributes() : $this->getAttributes();
        }

        $rules = count($this->scenarios) > 0 ? $this->rules[$scenario] : $this->rules;

        if (empty($rules))
        {
            throw new \Exception('Rules not found');
        }

        return Validator::make($data, $rules);
    }
}