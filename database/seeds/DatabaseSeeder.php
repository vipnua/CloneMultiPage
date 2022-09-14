<?php

use Illuminate\Database\Seeder;
use App\Model\Functions;
use App\Model\Admin;
use Carbon\Carbon;
use App\Model\RoleSecond;
use Spatie\Permission\Models\Permission;
use App\Model\Groupermission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        //create user
        $user = Admin::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => '123123',
        ]);
        $user->infor()->create([
            'first_name' => 'Super',
            'last_name'=> 'Admin',
            'birthday' => Carbon::now(),
            'address' => '36',
            'gender' => rand(1,2),
        ]);

        //create role
        $role = RoleSecond::create([
            'name' => 'Employee',
            'guard_name' => 'admin',
        ]);
       $role = RoleSecond::create([
           'name' => 'Owner',
           'guard_name' => 'admin',
       ]);

        $user->assignRole('Owner');

        $group_permission = Groupermission::create([
            'name' => 'Customer manager',
            'description' => '',
            'key' => 'user',
        ]);

        $group_permission->permissions()->createMany(
            [
                [
                  "name" => "user_view",
                  "guard_name" => "admin"
                ],
                [
                  "name" => "user_create",
                  "guard_name" => "admin"
                ],
                [
                  "name" => "user_edit",
                  "guard_name" => "admin"
                ],
                [
                  "name" => "user_delete",
                  "guard_name" => "admin"
                ],
                [
                  "name" => "user_forcedelete",
                  "guard_name" => "admin"
                ],
                [
                  "name" => "user_viewprofile",
                  "guard_name" => "admin"
                ]
            ]
        );

       //create Function
       Functions::create([
           'parent_id' => 0,
           'name' => 'Dashboard',
           'route' => 'dashboard',
           'controller' => 'DashboardController',
           'icon' => "<span class='svg-icon svg-icon-2'> 												<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'> 													<rect x='2' y='2' width='9' height='9' rx='2' fill='black'></rect> 													<rect opacity='0.3' x='13' y='2' width='9' height='9' rx='2' fill='black'></rect> 													<rect opacity='0.3' x='13' y='13' width='9' height='9' rx='2' fill='black'></rect> 													<rect opacity='0.3' x='2' y='13' width='9' height='9' rx='2' fill='black'></rect> 												</svg> 											</span>",
           'status' => '1',
           'ordering' => '100',
           'description' => '',
       ]);

       Functions::create([
           'parent_id' => 0,
           'name' => 'Manager',
           'route' => '',
           'controller' => '',
           'icon' => "",
           'status' => '1',
           'ordering' => '90',
           'description' => 'Manager group',
       ]);

       Functions::create([
           'parent_id' => 2,
           'name' => 'Employee',
           'route' => 'admin',
           'controller' => 'AdminController',
           'icon' => "<span class='svg-icon svg-icon-2'> 												<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'> 													<path opacity='0.3' d='M22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM12 7C10.3 7 9 8.3 9 10C9 11.7 10.3 13 12 13C13.7 13 15 11.7 15 10C15 8.3 13.7 7 12 7Z' fill='black'></path> 													<path d='M12 22C14.6 22 17 21 18.7 19.4C17.9 16.9 15.2 15 12 15C8.8 15 6.09999 16.9 5.29999 19.4C6.99999 21 9.4 22 12 22Z' fill='black'></path> 												</svg> 											</span>",
           'status' => '1',
           'ordering' => '100',
           'description' => '',
       ]);

       Functions::create([
           'parent_id' => 2,
           'name' => 'Customer',
           'route' => 'user',
           'controller' => 'UserController',
           'icon' => "<span class='svg-icon svg-icon-muted svg-icon-2'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'> <path d='M16.0173 9H15.3945C14.2833 9 13.263 9.61425 12.7431 10.5963L12.154 11.7091C12.0645 11.8781 12.1072 12.0868 12.2559 12.2071L12.6402 12.5183C13.2631 13.0225 13.7556 13.6691 14.0764 14.4035L14.2321 14.7601C14.2957 14.9058 14.4396 15 14.5987 15H18.6747C19.7297 15 20.4057 13.8774 19.912 12.945L18.6686 10.5963C18.1487 9.61425 17.1285 9 16.0173 9Z' fill='black'/> <rect opacity='0.3' x='14' y='4' width='4' height='4' rx='2' fill='black'/> <path d='M4.65486 14.8559C5.40389 13.1224 7.11161 12 9 12C10.8884 12 12.5961 13.1224 13.3451 14.8559L14.793 18.2067C15.3636 19.5271 14.3955 21 12.9571 21H5.04292C3.60453 21 2.63644 19.5271 3.20698 18.2067L4.65486 14.8559Z' fill='black'/> <rect opacity='0.3' x='6' y='5' width='6' height='6' rx='3' fill='black'/> </svg></span>",
           'status' => '1',
           'ordering' => '80',
           'description' => '',
       ]);

       Functions::create([
           'parent_id' => 0,
           'name' => 'System',
           'route' => '',
           'controller' => '',
           'icon' => "",
           'status' => '1',
           'ordering' => '0',
           'description' => 'System group',
       ]);

       Functions::create([
           'parent_id' => 5,
           'name' => 'Function',
           'route' => 'function',
           'controller' => 'FunctionController',
           'icon' => "<span class='svg-icon svg-icon-muted svg-icon-2'><svg xmlns='http://www.w3.org/2000/svg' width='16' height='19' viewBox='0 0 16 19' fill='none'>                                                 <path d='M12 0.400024H1C0.4 0.400024 0 0.800024 0 1.40002V2.40002C0 3.00002 0.4 3.40002 1 3.40002H12C12.6 3.40002 13 3.00002 13 2.40002V1.40002C13 0.800024 12.6 0.400024 12 0.400024Z' fill='black'></path>                                                 <path opacity='0.3' d='M15 8.40002H1C0.4 8.40002 0 8.00002 0 7.40002C0 6.80002 0.4 6.40002 1 6.40002H15C15.6 6.40002 16 6.80002 16 7.40002C16 8.00002 15.6 8.40002 15 8.40002ZM16 12.4C16 11.8 15.6 11.4 15 11.4H1C0.4 11.4 0 11.8 0 12.4C0 13 0.4 13.4 1 13.4H15C15.6 13.4 16 13 16 12.4ZM12 17.4C12 16.8 11.6 16.4 11 16.4H1C0.4 16.4 0 16.8 0 17.4C0 18 0.4 18.4 1 18.4H11C11.6 18.4 12 18 12 17.4Z' fill='black'></path>                                             </svg>                                         </span>",
           'status' => '1',
           'ordering' => '100',
           'description' => '',
       ]);

       Functions::create([
           'parent_id' => 5,
           'name' => 'Role',
           'route' => 'role',
           'controller' => 'RoleController',
           'icon' => "<span class='svg-icon svg-icon-2'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'> <path opacity='0.3' d='M19 22H5C4.4 22 4 21.6 4 21V3C4 2.4 4.4 2 5 2H14L20 8V21C20 21.6 19.6 22 19 22ZM15 17C15 16.4 14.6 16 14 16H8C7.4 16 7 16.4 7 17C7 17.6 7.4 18 8 18H14C14.6 18 15 17.6 15 17ZM17 12C17 11.4 16.6 11 16 11H8C7.4 11 7 11.4 7 12C7 12.6 7.4 13 8 13H16C16.6 13 17 12.6 17 12ZM17 7C17 6.4 16.6 6 16 6H8C7.4 6 7 6.4 7 7C7 7.6 7.4 8 8 8H16C16.6 8 17 7.6 17 7Z' fill='black'/> <path d='M15 8H20L14 2V7C14 7.6 14.4 8 15 8Z' fill='black'/> </svg></span>",
           'status' => '1',
           'ordering' => '90',
           'description' => '',
       ]);

       Functions::create([
           'parent_id' => 5,
           'name' => 'Permission',
           'route' => 'permission',
           'controller' => 'PermissionController',
           'icon' => "<span class='svg-icon svg-icon-2'><svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'> <path opacity='0.3' d='M18 22C19.7 22 21 20.7 21 19C21 18.5 20.9 18.1 20.7 17.7L15.3 6.30005C15.1 5.90005 15 5.5 15 5C15 3.3 16.3 2 18 2H6C4.3 2 3 3.3 3 5C3 5.5 3.1 5.90005 3.3 6.30005L8.7 17.7C8.9 18.1 9 18.5 9 19C9 20.7 7.7 22 6 22H18Z' fill='black'/> <path d='M18 2C19.7 2 21 3.3 21 5H9C9 3.3 7.7 2 6 2H18Z' fill='black'/> <path d='M9 19C9 20.7 7.7 22 6 22C4.3 22 3 20.7 3 19H9Z' fill='black'/> </svg></span>",
           'status' => '1',
           'ordering' => '80',
           'description' => '',
       ]);
    }
}
