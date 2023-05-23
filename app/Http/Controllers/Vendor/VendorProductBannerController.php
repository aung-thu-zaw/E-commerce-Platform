<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\VendorProductBannerRequest;
use App\Models\VendorProductBanner;
use App\Services\ProductBannerImageUploadService;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Inertia\ResponseFactory;

class VendorProductBannerController extends Controller
{
    public function index(): Response|ResponseFactory
    {
        $vendorProductBanners=VendorProductBanner::search(request("search"))
                                                 ->where("user_id", auth()->user()->id)
                                                 ->orderBy(request("sort", "id"), request("direction", "desc"))
                                                 ->paginate(request("per_page", 10))
                                                 ->appends(request()->all());

        return inertia("Vendor/Product-Banners/Index", compact("vendorProductBanners"));
    }

    public function create(): Response|ResponseFactory
    {
        $per_page=request("per_page");

        return inertia("Vendor/Product-Banners/Create", compact("per_page"));
    }

    public function store(VendorProductBannerRequest $request, ProductBannerImageUploadService $productBannerImageUploadService): RedirectResponse
    {
        VendorProductBanner::create($request->validated()+["image"=>$productBannerImageUploadService->createImage($request),"status"=>"hide"]);

        return to_route("vendor.product-banners.index", "per_page=$request->per_page")->with("success", "Product Banner has been successfully created.");
    }

    public function edit(VendorProductBanner $vendorProductBanner): Response|ResponseFactory
    {
        $paginate=[ "page"=>request("page"),"per_page"=>request("per_page")];

        return inertia("Vendor/Product-Banners/Edit", compact("vendorProductBanner", "paginate"));
    }


    public function update(VendorProductBannerRequest $request, VendorProductBanner $vendorProductBanner, ProductBannerImageUploadService $productBannerImageUploadService): RedirectResponse
    {
        $vendorProductBanner->update($request->validated()+["image"=>$productBannerImageUploadService->updateImageForVendor($request, $vendorProductBanner),"status"=>$vendorProductBanner->status]);

        return to_route("vendor.product-banners.index", "page=$request->page&per_page=$request->per_page")->with("success", "Product Banner has been successfully updated.");
    }

    public function destroy(Request $request, VendorProductBanner $vendorProductBanner): RedirectResponse
    {
        $vendorProductBanner->delete();

        return to_route("vendor.product-banners.index", "page=$request->page&per_page=$request->per_page")->with("success", "Product Banner has been successfully deleted.");
    }

    public function trash(): Response|ResponseFactory
    {
        $trashVendorProductBanners=VendorProductBanner::search(request("search"))
                                            ->onlyTrashed()
                                            ->where("user_id", auth()->user()->id)
                                            ->orderBy(request("sort", "id"), request("direction", "desc"))
                                            ->paginate(request("per_page", 10))
                                            ->appends(request()->all());

        return inertia("Vendor/Product-Banners/Trash", compact("trashVendorProductBanners"));
    }

    public function restore(Request $request, int $id): RedirectResponse
    {
        $vendorProductBanner = VendorProductBanner::onlyTrashed()->where("id", $id)->first();

        $vendorProductBanner->restore();

        return to_route('vendor.product-banners.trash', "page=$request->page&per_page=$request->per_page")->with("success", "Product Banner has been successfully restored.");
    }

    public function forceDelete(Request $request, int $id): RedirectResponse
    {
        $vendorProductBanner = VendorProductBanner::onlyTrashed()->where("id", $id)->first();

        VendorProductBanner::deleteImage($vendorProductBanner);

        $vendorProductBanner->forceDelete();

        return to_route('vendor.product-banners.trash', "page=$request->page&per_page=$request->per_page")->with("success", "Product Banner has been permanently deleted.");
    }

    public function handleShow(Request $request, int $id): RedirectResponse
    {
        $countVendorProductBanners=VendorProductBanner::where([["user_id", auth()->user()->id ],["status", "show"]])->count();

        if ($countVendorProductBanners >= 6) {
            return to_route('vendor.product-banners.index', "page=$request->page&per_page=$request->per_page")->with("error", "You can't display the product banner. Only 6 product banners are allowed.");
        }

        $vendorProductBanner = VendorProductBanner::where([["id", $id],["status","hide"]])->first();

        $vendorProductBanner->update(["status"=>"show"]);

        return to_route('vendor.product-banners.index', "page=$request->page&per_page=$request->per_page")->with("success", "Product Banner has been successfully displayed.");
    }

    public function handleHide(Request $request, int $id): RedirectResponse
    {
        $vendorProductBanner = VendorProductBanner::where([["id", $id],["status","show"]])->first();

        $vendorProductBanner->update(["status"=>"hide"]);

        return to_route('vendor.product-banners.index', "page=$request->page&per_page=$request->per_page")->with("success", "Product Banner has been successfully hidden.");
    }


    public function permanentlyDelete(Request $request): RedirectResponse
    {
        $vendorProductBanners = VendorProductBanner::onlyTrashed()->where("user_id", auth()->user()->id)->get();

        $vendorProductBanners->each(function ($vendorProductBanner) {
            VendorProductBanner::deleteImage($vendorProductBanner);
            $vendorProductBanner->forceDelete();
        });

        return to_route('vendor.product-banners.trash', "page=$request->page&per_page=$request->per_page")->with("success", "Product Banners have been successfully deleted.");
    }
}