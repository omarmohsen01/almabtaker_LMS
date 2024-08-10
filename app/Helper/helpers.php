<?php

use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

function upload_image($data,$folder_name)
{
    $path = null;
    if ($data->hasFile('primary_image')) {
        $newImage = $data->file('primary_image');
        $newName = rand() . '.' . $newImage->getClientOriginalExtension();
        $path = $newImage->storeAs($folder_name.'-images', $newName, 'public');
    }
    return $path;
}

function update_image($admin,$data,$folder_name)
{
    $oldPhotoPath = $admin->image;
    $path = $oldPhotoPath;
    if ($data->hasFile('primary_image')) {
        // Delete the old photo
        if ($oldPhotoPath) {
            Storage::disk('public')->delete($oldPhotoPath);
        }
        // Store the new photo
        $newImage = $data->file('primary_image');
        $newName = rand() . '.' . $newImage->getClientOriginalExtension();
        $path = $newImage->storeAs($folder_name . '-images', $newName, 'public');
    }
    return $path;
}

function other_upload_image($name,$data,$folder_name)
{
    $path = null;
    if ($data->hasFile($name)) {
        $newImage = $data->file($name);
        $newName = rand() . '.' . $newImage->getClientOriginalExtension();
        $path = $newImage->storeAs($folder_name.'-images', $newName, 'public');
    }
    return $path;
}

function upload_multi_images($files, $folder_name)
{
    $paths = [];

    foreach ($files as $file) {
        if ($file instanceof \Illuminate\Http\UploadedFile) {
            $newName = rand() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($folder_name . '-images', $newName, 'public');
            $paths[] = $path;
        }
    }

    return $paths;
}
