<?php

namespace App\Repositories\Contracts;
interface IDesign
{
    public function applyTags($id, $tags);

    public function addComment($designId, array $data);
}
