<?php
namespace Fixture;

class Basicpage
{

    public function make($data = [])
    {
        $hash = substr(md5(microtime() . mt_rand()), 0, 8);
        $data = $data + [
                'title' => "_basicpage_{$hash}",
                'body' => "_body_{$hash}",
            ];

        return new \App\Modules\Basicpage\Models\Basicpage($data);
    }

    public function create($data = [])
    {
        $basicpage = $this->make($data);

        $basicpage->save();

        return $basicpage;
    }
}