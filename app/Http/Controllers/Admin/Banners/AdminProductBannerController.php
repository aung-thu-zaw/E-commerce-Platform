<?php

namespace App\Http\Controllers\Admin\Banners;

use App\Actions\Admin\Banners\ProductBanners\CreateProductBannerAction;
use App\Actions\Admin\Banners\ProductBanners\PermanentlyDeleteAllTrashProductBannerAction;
use App\Actions\Admin\Banners\ProductBanners\UpdateProductBannerAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductBannerRequest;
use App\Models\ProductBanner;
use Illuminate\Http\Request;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Inertia\ResponseFactory;

class AdminProductBannerController extends Controller
{
    public function index(): Response|ResponseFactory
    {
        $productBanners=ProductBanner::search(request("search"))
                                     ->orderBy(request("sort", "id"), request("direction", "desc"))
                                     ->paginate(request("per_page", 10))
                                     ->appends(request()->all());

        return inertia("Admin/Banners/Product-Banners/Index", compact("productBanners"));
    }

    public function create(): Response|ResponseFactory
    {
        $per_page=request("per_page");

        return inertia("Admin/Banners/Product-Banners/Create", compact("per_page"));
    }

    public function store(ProductBannerRequest $request): RedirectResponse
    {
        (new CreateProductBannerAction())->handle($request->validated());

        $queryStringParams=["page"=>"1","per_page"=>$request->per_page,"sort"=>"id","direction"=>"desc"];

        return to_route("admin.product-banners.index", $queryStringParams)->with("success", "PRODUCT_BANNER_HAS_BEEN_SUCCESSFULLY_CREATED");
    }

    public function edit(Request $request, ProductBanner $productBanner): Response|ResponseFactory
    {
        $queryStringParams=["page"=>$request->page,"per_page"=>$request->per_page,"sort"=>$request->sort,"direction"=>$request->direction];

        return inertia("Admin/Banners/Product-Banners/Edit", compact("productBanner", "queryStringParams"));
    }

    public function update(ProductBannerRequest $request, ProductBanner $productBanner): RedirectResponse
    {
        (new UpdateProductBannerAction())->handle($request->validated(), $productBanner);

        $queryStringParams=["page"=>$request->page,"per_page"=>$request->per_page,"sort"=>$request->sort,"direction"=>$request->direction];

        return to_route("admin.product-banners.index", $queryStringParams)->with("success", "PRODUCT_BANNER_HAS_BEEN_SUCCESSFULLY_UPDATED");
    }

    public function destroy(Request $request, ProductBanner $productBanner): RedirectResponse
    {
        $productBanner->delete();

        $queryStringParams=["page"=>$request->page,"per_page"=>$request->per_page,"sort"=>$request->sort,"direction"=>$request->direction];

        return to_route("admin.product-banners.index", $queryStringParams)->with("success", "PRODUCT_BANNER_HAS_BEEN_SUCCESSFULLY_DELETED");
    }

    public function trash(): Response|ResponseFactory
    {
        $trashProductBanners=ProductBanner::search(request("search"))
                                          ->onlyTrashed()
                                          ->orderBy(request("sort", "id"), request("direction", "desc"))
                                          ->paginate(request("per_page", 10))
                                          ->appends(request()->all());

        return inertia("Admin/Banners/Product-Banners/Trash", compact("trashProductBanners"));
    }

    public function restore(Request $request, int $trashProductBannerId): RedirectResponse
    {
        $trashProductBanner = ProductBanner::onlyTrashed()->findOrFail($trashProductBannerId);

        $trashProductBanner->restore();

        $queryStringParams=["page"=>$request->page,"per_page"=>$request->per_page,"sort"=>$request->sort,"direction"=>$request->direction];

        return to_route('admin.product-banners.trash', $queryStringParams)->with("success", "PRODUCT_BANNER_HAS_BEEN_SUCCESSFULLY_RESTORED");
    }

    public function forceDelete(Request $request, int $trashProductBannerId): RedirectResponse
    {
        $trashProductBanner = ProductBanner::onlyTrashed()->findOrFail($trashProductBannerId);

        ProductBanner::deleteImage($trashProductBanner->image);

        $trashProductBanner->forceDelete();

        $queryStringParams=["page"=>$request->page,"per_page"=>$request->per_page,"sort"=>$request->sort,"direction"=>$request->direction];

        return to_route('admin.product-banners.trash', $queryStringParams)->with("success", "THE_PRODUCT_BANNER_HAS_BEEN_PERMANENTLY_DELETED");
    }

    public function permanentlyDelete(Request $request): RedirectResponse
    {
        $trashProductBanners = ProductBanner::onlyTrashed()->get();

        (new PermanentlyDeleteAllTrashProductBannerAction())->handle($trashProductBanners);

        $queryStringParams=["page"=>$request->page,"per_page"=>$request->per_page,"sort"=>$request->sort,"direction"=>$request->direction];

        return to_route('admin.product-banners.trash', $queryStringParams)->with("success", "PRODUCT_BANNERS_HAVE_BEEN_PERMANENTLY_DELETED");
    }

    public function handleShow(Request $request, int $productBannerId): RedirectResponse
    {
        $countProductBanners=ProductBanner::where("status", "show")->count();

        if ($countProductBanners >= 3) {

            $queryStringParams=["page"=>$request->page,"per_page"=>$request->per_page,"sort"=>$request->sort,"direction"=>$request->direction];

            return to_route('admin.product-banners.index', $queryStringParams)->with("error", "YOU_CANT_DISPLAY_THE_PRODUCT_BANNER");

        }

        $productBanner = ProductBanner::where([["id", $productBannerId],["status","hide"]])->first();

        if($productBanner) {

            $productBanner->update(["status"=>"show"]);
        }

        $queryStringParams=["page"=>$request->page,"per_page"=>$request->per_page,"sort"=>$request->sort,"direction"=>$request->direction];

        return to_route('admin.product-banners.index', $queryStringParams)->with("success", "PRODUCT_BANNER_HAS_BEEN_SUCCESSFULLY_DISPLAYED");
    }

    public function handleHide(Request $request, int $productBannerId): RedirectResponse
    {
        $productBanner = ProductBanner::where([["id", $productBannerId],["status","show"]])->first();

        if($productBanner) {

            $productBanner->update(["status"=>"hide"]);
        }

        $queryStringParams=["page"=>$request->page,"per_page"=>$request->per_page,"sort"=>$request->sort,"direction"=>$request->direction];

        return to_route('admin.product-banners.index', $queryStringParams)->with("success", "PRODUCT_BANNER_HAS_BEEN_SUCCESSFULLY_HIDDEN");
    }

}
