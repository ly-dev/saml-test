<?php
namespace Fixture;

class Tooltip
{

    public function make($data = [])
    {
        $hash = substr(md5(microtime() . mt_rand()), 0, 8);
        $data = $data + [
            'page_id' => "_page_id_$hash",
            'tooltip_id' => "_tooltip_id_$hash",
            'title' => "_title_$hash",
            'description' => "_description_$hash"
        ];

        return new \App\Modules\Tooltip\Models\Tooltip($data);
    }

    public function create($data = [])
    {
        $tooltip = $this->make($data);

        $tooltip->save();

        return $tooltip;
    }
}