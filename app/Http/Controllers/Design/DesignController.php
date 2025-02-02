<?php

namespace App\Http\Controllers\Design;

use App\Http\Controllers\Controller;
use App\Http\Resources\DesignResource;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class DesignController extends Controller
{
    public function update(Request $request, $id)
    {

        $design = Design::find($id);
        $this->authorize('update', $design);

        $request->validate([
            'title' => 'required|max:255|unique:designs,title,' . $id,
            'description' => 'required|max:400|min:20',
            'tags' => 'required',
        ]);


        $design->update([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'is_live' => !$design->upload_successful ? false : $request->is_live,
        ]);
        $design->attachTags($request->tags);
        return new DesignResource($design);


    }

    public function destroy($id)
    {
        $design = Design::findOrFail($id);
        $this->authorize('delete', $design);



        foreach (['Thumbnail', 'Original', 'Large'] as $size) {
            if (Storage::disk($design->disk)->exists("Uploads/Designs/$size/" . $design->image)) {
                Storage::disk($design->disk)->delete("Uploads/Designs/$size/" . $design->image);

            }
        }
        $design->delete();
        return response()->json(['message' => 'Design deleted']);
    }
}
