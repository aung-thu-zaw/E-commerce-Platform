<?php

namespace App\Services;

use App\Models\BlogPost;
use Illuminate\Http\UploadedFile;

class BlogPostImageUploadService
{
    public function createImage(UploadedFile $image): string
    {
        $originalName=$image->getClientOriginalName();

        $finalName=time()."-".$originalName;

        $image->move(storage_path("app/public/blog-posts/"), $finalName);

        return $finalName;
    }

    public function updateImage(UploadedFile $image, string $blogPostImage): string
    {
        if(is_string($blogPostImage)) {

            BlogPost::deleteImage($blogPostImage);
        }

        $originalName=$image->getClientOriginalName();

        $finalName=time()."-".$originalName;

        $image->move(storage_path("app/public/blog-posts/"), $finalName);

        return $finalName;
    }
}
