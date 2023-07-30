<?php

namespace App\Actions\Admin\BlogManagements\BlogPosts;

use App\Models\BlogPost;
use App\Services\BlogPostImageUploadService;

class CreateBlogPostAction
{
    /**
     * @param array<mixed> $data
     */
    public function handle(array $data): void
    {
        $image = isset($data["image"]) ? (new BlogPostImageUploadService())->createImage($data["image"]) : null;

        BlogPost::create([
            "blog_category_id"=>$data["blog_category_id"],
            "author_id"=>$data["author_id"],
            "title"=>$data["title"],
            "description"=>$data["description"],
            "image"=>$image
        ]);
    }
}
