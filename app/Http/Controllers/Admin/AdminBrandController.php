<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\Brands\CreateBrandAction;
use App\Actions\Admin\Brands\PermanentlyDeleteAllTrashBrandAction;
use App\Actions\Admin\Brands\UpdateBrandAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\BrandRequest;
use App\Http\Traits\HandlesQueryStringParameters;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class AdminBrandController extends Controller
{
    use HandlesQueryStringParameters;

    public function __construct()
    {
        $this->middleware('permission:brands.view', ['only' => ['index']]);
        $this->middleware('permission:brands.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:brands.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:brands.delete', ['only' => ['destroy', 'destroySelected']]);
        $this->middleware('permission:brands.view.trash', ['only' => ['trashed']]);
        $this->middleware('permission:brands.restore', ['only' => ['restore', 'restoreSelected']]);
        $this->middleware('permission:brands.force.delete', ['only' => ['forceDelete', 'forceDeleteSelected', 'forceDeleteAll']]);
    }

    public function index(): Response|ResponseFactory
    {
        $brands = Brand::search(request('search'))
                       ->query(function (Builder $builder) {
                           $builder->withCount('products');
                       })
                       ->orderBy(request('sort', 'id'), request('direction', 'desc'))
                       ->paginate(request('per_page', 10))
                       ->appends(request()->all());

        return inertia('Admin/Brands/Index', compact('brands'));
    }

    public function create(): Response|ResponseFactory
    {
        $categories = Category::select('id', 'name')->get();

        return inertia('Admin/Brands/Create', compact('categories'));
    }

    public function store(BrandRequest $request): RedirectResponse
    {
        (new CreateBrandAction())->handle($request->validated());

        return to_route('admin.brands.index', $this->getQueryStringParams($request))->with('success', ':label has been successfully created.');
    }

    public function edit(Request $request, Brand $brand): Response|ResponseFactory
    {
        $categories = Category::select('id', 'name')->get();

        return inertia('Admin/Brands/Edit', compact('brand', 'categories'));
    }

    public function update(BrandRequest $request, Brand $brand): RedirectResponse
    {
        (new UpdateBrandAction())->handle($request->validated(), $brand);

        return to_route('admin.brands.index', $this->getQueryStringParams($request))->with('success', ':label has been successfully updated.');
    }

    public function destroy(Request $request, Brand $brand): RedirectResponse
    {
        $brand->delete();

        return to_route('admin.brands.index', $this->getQueryStringParams($request))->with('success', ':label has been successfully deleted.');
    }

    public function destroySelected(Request $request): RedirectResponse
    {
        if (! empty($request->selectedItems)) {
            Brand::whereIn('id', $request->selectedItems)->delete();
        }

        return to_route('admin.brands.index', $this->getQueryStringParams($request))->with('success', 'Selected :label have been successfully deleted.');
    }

    public function trashed(): Response|ResponseFactory
    {
        $trashedBrands = Brand::search(request('search'))
                              ->onlyTrashed()
                              ->orderBy(request('sort', 'id'), request('direction', 'desc'))
                              ->paginate(request('per_page', 10))
                              ->appends(request()->all());

        return inertia('Admin/Brands/Trash', compact('trashedBrands'));
    }

    public function restore(Request $request, int $trashBrandId): RedirectResponse
    {
        $trashBrand = Brand::onlyTrashed()->findOrFail($trashBrandId);

        $trashBrand->restore();

        return to_route('admin.brands.trashed', $this->getQueryStringParams($request))->with('success', ':label has been successfully restored.');
    }

    public function restoreSelected(Request $request): RedirectResponse
    {
        if (! empty($request->selectedItems)) {
            Brand::onlyTrashed()->whereIn('id', $request->selectedItems)->restore();
        }

        return to_route('admin.brands.trashed', $this->getQueryStringParams($request))->with('success', 'Selected :label have been successfully restored.');
    }

    public function forceDelete(Request $request, int $trashBrandId): RedirectResponse
    {
        $trashBrand = Brand::onlyTrashed()->findOrFail($trashBrandId);

        Brand::deleteImage($trashBrand->image);

        $trashBrand->forceDelete();

        return to_route('admin.brands.trashed', $this->getQueryStringParams($request))->with('success', 'The :label has been permanently deleted.');
    }

    public function forceDeleteSelected(Request $request): RedirectResponse
    {
        if (! empty($request->selectedItems)) {
            Brand::onlyTrashed()->whereIn('id', $request->selectedItems)->forceDelete();
        }

        return to_route('admin.brands.trashed', $this->getQueryStringParams($request))->with('success', 'Selected :label have been permanently deleted.');
    }

    public function forceDeleteAll(Request $request): RedirectResponse
    {
        $trashBrands = Brand::onlyTrashed()->get();

        (new PermanentlyDeleteAllTrashBrandAction())->handle($trashBrands);

        return to_route('admin.brands.trashed', $this->getQueryStringParams($request))->with('success', 'All :label have been permanently deleted.');
    }
}
