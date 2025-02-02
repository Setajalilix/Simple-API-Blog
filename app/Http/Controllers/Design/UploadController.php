<?php

namespace App\Http\Controllers\Design;

use App\Http\Controllers\Controller;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use File;


class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $image = $request->file('image');
        $imagePath = $image->getPathname();
        $imageName = time() . '_' . preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));


        $design = auth()->user()->Designs()->create([
            'image' => $imageName,
            'disk' => config('site.Upload_disk')
        ]);

        $Original_image = $image->storeAs('Uploads/Designs/Original', $imageName, 'tmp');
        //$path= $image->move(public_path('Uploads/Designs/Original'), $imageName);

        $Original_image_path = storage_path() . '/Uploads/Designs/Original/' . $imageName;
        $manager = new ImageManager(Driver::Class);
        $manager->read($Original_image_path)->cover(800, 600)->save($large = storage_path('Uploads/Designs/Large/' . $imageName));

        $manager->read($Original_image_path)->cover(200, 150)->save($thumbnail = storage_path('Uploads/Designs/Thumbnail/' . $imageName));


        if (Storage::disk($design->disk)->put('/Uploads/Designs/Original/' . $imageName, fopen($Original_image_path, 'r+'))) {
            File::delete($Original_image_path);
        }
        if (Storage::disk($design->disk)->put('/Uploads/Designs/Large/' . $imageName, fopen($large, 'r+'))) {
            File::delete($large);
        }
        if (Storage::disk($design->disk)->put('/Uploads/Designs/Thumbnail/' . $imageName, fopen($thumbnail, 'r+'))) {
            File::delete($thumbnail);
        }

        auth()->user()->designs()->update(
            [
                'upload_successful' => true,
            ]
        );


        return response()->json([$design], 200);
    }
}
