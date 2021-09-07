<?php

use App\Helpers\Acl;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use App\Models\Permission;

class SetupRolesPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (Acl::roles() as $role) {
            Role::findOrCreate($role);
        }

        $adminRole = Role::findByName(Acl::ROLE_ADMIN);
        $managerRole = Role::findByName(Acl::ROLE_MANAGER);
        $editorRole = Role::findByName(Acl::ROLE_EDITOR);
        $userRole = Role::findByName(Acl::ROLE_USER);
        $visitorRole = Role::findByName(Acl::ROLE_VISITOR);

        foreach (Acl::permissions() as $permission) {
            Permission::findOrCreate($permission, 'api');
        }

        // Setup basic permission
        $adminRole->givePermissionTo(Acl::permissions());
        $managerRole->givePermissionTo(Acl::permissions([Acl::PERMISSION_PERMISSION_MANAGE]));
        $editorRole->givePermissionTo(Acl::menuPermissions());
        $editorRole->givePermissionTo([
            Acl::PERMISSION_ARTICLE_MANAGE,
            Acl::PERMISSION_COMMENT_MANAGE
        ]);
        $userRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_ELEMENT_UI,
            Acl::PERMISSION_VIEW_MENU_PERMISSION,
        ]);
        $visitorRole->givePermissionTo([
            Acl::PERMISSION_VIEW_MENU_ELEMENT_UI,
            Acl::PERMISSION_VIEW_MENU_PERMISSION,
        ]);

        // foreach (Acl::roles() as $role) {
        //     /** @var \App\User[] $users */
        //     $users = User::where('role', $role)->get();
        //     $role = Role::findByName($role);
        //     foreach ($users as $user) {
        //         $user->syncRoles($role);
        //     }
        // }

        // Schema::table('users', function (Blueprint $table) {
        //     $table->dropColumn('role');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // if (!Schema::hasColumn('users', 'role')) {
        //     Schema::table('users', function (Blueprint $table) {
        //         $table->string('role')->default('editor');
        //     });
        // }

        // /** @var \App\User[] $users */
        // $users = User::all();
        // foreach ($users as $user) {
        //     $roles = array_reverse(Acl::roles());
        //     foreach ($roles as $role) {
        //         if ($user->hasRole($role)) {
        //             $user->role = $role;
        //             $user->save();
        //         }
        //     }
        // }
    }
}
