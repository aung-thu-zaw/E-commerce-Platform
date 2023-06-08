<?php

namespace App\Http\Controllers\Admin\Managements\AdminManage;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminManageRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\AdminUserAvatarUploadService;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Response;
use Inertia\ResponseFactory;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class AdminManageController extends Controller
{
    public function index(): Response|ResponseFactory
    {
        $admins=User::search(request("search"))
        ->query(function (Builder $builder) {
            $builder->with("roles");
        })
                              ->where("role", "admin")
                              ->where("status", "active")
                              ->orderBy(request("sort", "id"), request("direction", "desc"))
                              ->paginate(request("per_page", 10))
                              ->appends(request()->all());

        return inertia("Admin/Managements/AdminManage/Index", compact("admins"));
    }

    public function create(): Response|ResponseFactory
    {
        $per_page=request("per_page");

        $roles=Role::all();

        return inertia("Admin/Managements/AdminManage/Create", compact("per_page", "roles"));
    }

    public function store(AdminManageRequest $request, AdminUserAvatarUploadService $adminUserAvatarUploadService): RedirectResponse
    {
        $user=User::create($request->validated()+["avatar"=>$adminUserAvatarUploadService->createImage($request)]);

        $user->assignRole($request->assign_role);

        return to_route("admin.admin-manage.index", "per_page=$request->per_page")->with("success", "Admin has been successfully created.");
    }

    public function edit(User $user): Response|ResponseFactory
    {
        $paginate=[ "page"=>request("page"),"per_page"=>request("per_page")];

        $roles=Role::all();

        $user->load("roles");

        return inertia("Admin/Managements/AdminManage/Edit", compact("user", "roles", "paginate"));
    }

    public function update(AdminManageRequest $request, User $user, AdminUserAvatarUploadService $adminUserAvatarUploadService): RedirectResponse
    {
        $user->update($request->validated()+["avatar"=>$adminUserAvatarUploadService->updateImage($request, $user)]);

        if ($request->assign_role) {
            $user->roles()->detach();
            $user->assignRole($request->assign_role);
        }

        return to_route("admin.admin-manage.index", "page=$request->page&per_page=$request->per_page")->with("success", "Admin has been successfully updated.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $user->delete();

        return to_route("admin.admin-manage.index", "page=$request->page&per_page=$request->per_page")->with("success", "Admin has been successfully deleted.");
    }

    public function trash(): Response|ResponseFactory
    {
        $trashAdmins=User::search(request("search"))

        ->query(function (Builder $builder) {
            $builder->with("roles");
        })
                          ->onlyTrashed()
                          ->orderBy(request("sort", "id"), request("direction", "desc"))
                          ->paginate(request("per_page", 10))
                          ->appends(request()->all());

        return inertia("Admin/Managements/AdminManage/Trash", compact("trashAdmins"));
    }

    public function restore(Request $request, int $id): RedirectResponse
    {
        $admin = User::onlyTrashed()->findOrFail($id);

        $admin->restore();

        return to_route('admin.admin-manage.trash', "page=$request->page&per_page=$request->per_page")->with("success", "Admin has been successfully restored.");
    }

    public function forceDelete(Request $request, int $id): RedirectResponse
    {
        $admin = User::onlyTrashed()->findOrFail($id);

        $admin->forceDelete();

        return to_route('admin.admin-manage.trash', "page=$request->page&per_page=$request->per_page")->with("success", "The admin has been permanently deleted");
    }

    public function permanentlyDelete(Request $request): RedirectResponse
    {
        $admins = User::onlyTrashed()->get();

        $admins->each(function ($admin) {
            User::deleteUserAvatar($admin);
            $admin->forceDelete();
        });

        return to_route('admin.admin-manage.trash', "page=$request->page&per_page=$request->per_page")->with("success", "Admins have been successfully deleted.");
    }
}
