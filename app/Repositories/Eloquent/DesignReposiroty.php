<?php

namespace App\Repositories\Eloquent;

use App\Models\Design;
use App\Repositories\Contracts\IDesign;


class DesignReposiroty extends BaseRepository implements IDesign
{
    public function model(): string
    {
        return Design::class;
    }


    public function applyTags($id, $tags)
    {
        $design = $this->find($id);
        return $design->attachTags($tags);
    }

    public function addComment($designId, $data)
    {
        $design = $this->find($designId);
        return $design->comments()->create($data);
    }
}
