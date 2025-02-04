<?php

namespace App\Repositories\Eloquent;

use App\Models\Design;
use App\Repositories\Contracts\IDesign;
use App\Models\Traits;



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

    public function like($designId)
    {

        $design = $this->model->findOrFail($designId);
        if ($design->isLikeByUser(auth()->id())) {
            $design->unlike();
        } else {
            $design->like();
        }
        return $design->likes()->count();
    }

    public function isLikeByUser($designId)
    {
        $design = $this->model->findOrFail($designId);
        return $design->isLikeByUser(auth()->id());
    }
}
