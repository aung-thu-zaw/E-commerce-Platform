<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Admin\Categories\CreateCategoryAction;
use App\Actions\Admin\Categories\PermanentlyDeleteAllTrashCategoryAction;
use App\Actions\Admin\Categories\UpdateCategoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Traits\HandlesQueryStringParameters;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\ResponseFactory;

class AdminCategoryController extends Controller
{
    use HandlesQueryStringParameters;

    public function __construct()
    {
        $this->middleware('permission:categories.view', ['only' => ['index']]);
        $this->middleware('permission:categories.create', ['only' => ['create', 'store']]);
        $this->middleware('permission:categories.edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:categories.delete', ['only' => ['destroy', 'destroySelected']]);
        $this->middleware('permission:categories.view.trash', ['only' => ['trashed']]);
        $this->middleware('permission:categories.restore', ['only' => ['restore', 'restoreSelected']]);
        $this->middleware('permission:categories.force.delete', ['only' => ['forceDelete', 'forceDeleteSelected', 'forceDeleteAll']]);
    }

    public function index(): Response|ResponseFactory
    {
        $categories = Category::search(request('search'))
                              ->query(function (Builder $builder) {
                                  $builder->with('children');
                              })
                              ->orderBy(request('sort', 'id'), request('direction', 'desc'))
                              ->paginate(request('per_page', 10))
                              ->appends(request()->all());

        return inertia('Admin/Categories/Index', compact('categories'));
    }

    public function create(): Response|ResponseFactory
    {
        $categories = Category::select('id', 'parent_id', 'name')->get();

        return inertia('Admin/Categories/Create', compact('categories'));
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        (new CreateCategoryAction())->handle($request->validated());

        return to_route('admin.categories.index', $this->getQueryStringParams($request))->with('success', ':label has been successfully created.');
    }

    public function edit(Request $request, Category $category): Response|ResponseFactory
    {
        $categories = Category::select('id', 'parent_id', 'name')->get();

        return inertia('Admin/Categories/Edit', compact('category', 'categories'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        (new UpdateCategoryAction())->handle($request->validated(), $category);

        return to_route('admin.categories.index', $this->getQueryStringParams($request))->with('success', ':label has been successfully updated.');
    }

    public function destroy(Request $request, Category $category): RedirectResponse
    {
        $category->delete();

        return to_route('admin.categories.index', $this->getQueryStringParams($request))->with('success', ':label has been successfully deleted.');
    }

    public function destroySelected(Request $request): RedirectResponse
    {
        if (! empty($request->selectedItems)) {
            Category::whereIn('id', $request->selectedItems)->delete();
        }

        return to_route('admin.categories.index', $this->getQueryStringParams($request))->with('success', 'Selected :label have been successfully deleted.');
    }

    public function trashed(): Response|ResponseFactory
    {
        $trashedCategories = Category::search(request('search'))
                                   ->onlyTrashed()
                                   ->orderBy(request('sort', 'id'), request('direction', 'desc'))
                                   ->paginate(request('per_page', 10))
                                   ->appends(request()->all());

        return inertia('Admin/Categories/Trash', compact('trashedCategories'));
    }

    public function restore(Request $request, int $trashCategoryId): RedirectResponse
    {
        $trashCategory = Category::onlyTrashed()->findOrFail($trashCategoryId);

        $trashCategory->restore();

        return to_route('admin.categories.trashed', $this->getQueryStringParams($request))->with('success', ':label has been successfully restored.');
    }

    public function restoreSelected(Request $request): RedirectResponse
    {
        if (! empty($request->selectedItems)) {
            Category::onlyTrashed()->whereIn('id', $request->selectedItems)->restore();
        }

        return to_route('admin.categories.trashed', $this->getQueryStringParams($request))->with('success', 'Selected :label have been successfully restored.');
    }

    public function forceDelete(Request $request, int $trashCategoryId): RedirectResponse
    {
        $trashCategory = Category::onlyTrashed()->findOrFail($trashCategoryId);

        Category::deleteImage($trashCategory->image);

        $trashCategory->forceDelete();

        return to_route('admin.categories.trashed', $this->getQueryStringParams($request))->with('success', 'The :label has been permanently deleted.');
    }

    public function forceDeleteSelected(Request $request): RedirectResponse
    {
        if (! empty($request->selectedItems)) {
            Category::onlyTrashed()->whereIn('id', $request->selectedItems)->forceDelete();
        }

        return to_route('admin.categories.trashed', $this->getQueryStringParams($request))->with('success', 'Selected :label have been permanently deleted.');
    }

    public function forceDeleteAll(Request $request): RedirectResponse
    {
        $trashCategories = Category::onlyTrashed()->get();

        (new PermanentlyDeleteAllTrashCategoryAction())->handle($trashCategories);

        return to_route('admin.categories.trashed', $this->getQueryStringParams($request))->with('success', 'All :label have been permanently deleted.');
    }
}
