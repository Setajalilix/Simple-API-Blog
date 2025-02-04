<?php

namespace App\Http\Controllers\Design;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Models\Design;
use App\Repositories\Contracts\IDesign;
use App\Repositories\Eloquent\Criteria\ForUser;
use App\Repositories\Eloquent\Criteria\IsLive;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class DesignController extends Controller
{
    protected $design;

    public function __construct(IDesign $design)
    {
        $this->design = $design;
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $designs = $this->design->withCriteria([
//            new ForUser(21 )
        ])->all();
        return DesignResource::collection($designs);
    }


    /**
     * @throws AuthorizationException
     */
    public function update($id, Request $request): DesignResource
    {

        $design = $this->design->find($id);
        $this->authorize('update', $design);

        $request->validate([
            'title' => 'required|max:255|unique:designs,title,' . $id,
            'description' => 'required|max:400|min:20',
            'tags' => 'required',
        ]);


        $this->design->update($id,[
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'is_live' => !$design->upload_successful ? false : $request->is_live,
        ]);
        $design->attachTags($request->tags);
        return new DesignResource($design);


    }

    /**
     * @throws AuthorizationException
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $design = $this->design->find($id);
        $this->authorize('delete', $design);


        foreach (['Thumbnail', 'Original', 'Large'] as $size) {
            if (Storage::disk($design->disk)->exists("Uploads/Designs/$size/" . $design->image)) {
                Storage::disk($design->disk)->delete("Uploads/Designs/$size/" . $design->image);

            }
        }
        $this->design->delete($id);
        return response()->json(['message' => 'Design deleted'],200);
    }

    public function like($designId): \Illuminate\Http\JsonResponse
    {
      $countOfLike = $this->design->like($designId);
      return response()->json(['message' => $countOfLike],200);
    }

    public function isLikedByUser($designId): \Illuminate\Http\JsonResponse
    {
        $isliked = $this->design->isLikeByUser($designId);
        return response()->json(['message' => $isliked],200);
    }
}
