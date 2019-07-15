<?php
namespace Fixture;

class Variable
{

    public function make($data = [])
    {
        $hash = substr(md5(microtime() . mt_rand()), 0, 8);
        $data = $data + [
            'name' => "_variable_name_$hash",
            'sort_order' => 0,
            'value' => "_variable_value_$hash",
        ];

        return new \App\Modules\Taxonomy\Models\Variable($data);
    }

    public function create($data = [])
    {
        $variable = $this->make($data);

        $variable->save();

        return $variable;
    }
}