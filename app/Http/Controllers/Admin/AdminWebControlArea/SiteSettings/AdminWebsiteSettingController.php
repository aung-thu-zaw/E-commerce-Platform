<?php

namespace App\Http\Controllers\Admin\AdminWebControlArea\SiteSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebsiteSettingRequest;
use App\Models\WebsiteSetting;
use App\Services\WebsiteSettingImageUploadService;
use Inertia\Response;
use Inertia\ResponseFactory;
use Illuminate\Http\RedirectResponse;

class AdminWebsiteSettingController extends Controller
{
    public function edit(): Response|ResponseFactory
    {
        $websiteSetting=WebsiteSetting::findOrFail(1);

        return inertia("Admin/AdminWebControlArea/Settings/WebsiteSettings/Edit", compact("websiteSetting"));
    }

    public function update(WebsiteSettingRequest $request, WebsiteSetting $websiteSetting, WebsiteSettingImageUploadService $websiteSettingImageUploadService): RedirectResponse
    {
        $websiteSetting->update($request->validated()+[
            "logo"=>$websiteSettingImageUploadService->uploadLogo($request, $websiteSetting),
            "favicon"=>$websiteSettingImageUploadService->uploadFavicon($request, $websiteSetting)
        ]);

        return back()->with("success", "Website Setting has been successfully updated.");
    }

}
