<?php

namespace App\Console\Commands;

use App\Actions\Admin\BlogManagements\BlogPosts\PermanentlyDeleteAllTrashBlogPostAction;
use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PermanentlyAutoDeleteBlogPostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'blog_post:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Blog posts in the trash will be automatically deleted after 60 days';

    public function handle(): void
    {
        $cutoffDate = Carbon::now()->subDays(60);

        $blogPosts = BlogPost::onlyTrashed()->where('deleted_at', '<=', $cutoffDate)->get();

        (new PermanentlyDeleteAllTrashBlogPostAction())->handle($blogPosts);
    }
}
