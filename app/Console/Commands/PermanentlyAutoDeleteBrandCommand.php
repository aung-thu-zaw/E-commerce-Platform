<?php

namespace App\Console\Commands;

use App\Actions\Admin\Brands\PermanentlyDeleteAllTrashBrandAction;
use App\Models\Brand;
use Carbon\Carbon;
use Illuminate\Console\Command;

class PermanentlyAutoDeleteBrandCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'brand:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Brands in the trash will be automatically deleted after 60 days';

    public function handle(): void
    {
        $cutoffDate = Carbon::now()->subDays(60);

        $brands = Brand::onlyTrashed()->where('deleted_at', '<=', $cutoffDate)->get();

        (new PermanentlyDeleteAllTrashBrandAction())->handle($brands);
    }
}
