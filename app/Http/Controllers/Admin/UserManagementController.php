<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserStatusAudit;
use App\Repositories\UserRepositoryInterface;
use App\Support\ActivityLogger;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    protected UserRepositoryInterface $users;

    /**
     * Create controller instance.
     */
    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;
    }


    /**
     * =========================================================
     * USER LIST
     * =========================================================
     */
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));

        $users = User::query()
            ->when($q !== '', function ($query) use ($q) {

                $search = '%' . $q . '%';

                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('name', 'like', $search)
                        ->orWhere('email', 'like', $search);
                });

            })
            ->with('roles')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact(
            'users',
            'q'
        ));
    }


    /**
     * =========================================================
     * SHOW USER
     * =========================================================
     */
    public function show($id)
    {
        $user = $this->users->findById($id);

        if (!$user) {

            return redirect()
                ->route('admin.users.index')
                ->withErrors([
                    'user' => 'User not found.'
                ]);
        }

        $roles = Role::orderBy('name')->get();

        $audits = UserStatusAudit::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        /*
         * Activity Log
         */
        ActivityLogger::log(
            'viewed',
            'users',
            'Viewed user: ' . $user->name,
            $user
        );

        return view('admin.users.show', compact(
            'user',
            'roles',
            'audits'
        ));
    }


    /**
     * =========================================================
     * CREATE USER FORM
     * =========================================================
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();

        return view(
            'admin.users.create',
            compact('roles')
        );
    }


    /**
     * =========================================================
     * STORE USER
     * =========================================================
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'role' => [
                'nullable',
                'string',
                'exists:roles,name',
            ],

        ]);

        DB::beginTransaction();

        try {

            $data = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'is_active' => $request->boolean('is_active', true),
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ];

            /*
             * Create user
             */
            $user = $this->users->create($data);

            /*
             * Assign role
             */
            if (!empty($validated['role'])) {

                $user->assignRole($validated['role']);

            }

            DB::commit();

            /*
             * Activity Log
             */
            ActivityLogger::log(
                'created',
                'users',
                'Created user: ' . $user->name,
                $user,
                null,
                [
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_active' => $user->is_active,
                    'role' => $validated['role'] ?? null,
                ]
            );

            return redirect()
                ->route('admin.users.index')
                ->with(
                    'success',
                    'User created successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'user' => 'Unable to create user.'
                ]);
        }
    }


    /**
     * =========================================================
     * EDIT USER FORM
     * =========================================================
     */
    public function edit($id)
    {
        $user = $this->users->findById($id);

        if (!$user) {

            return redirect()
                ->route('admin.users.index')
                ->withErrors([
                    'user' => 'User not found.'
                ]);
        }

        $roles = Role::orderBy('name')->get();

        return view(
            'admin.users.edit',
            compact(
                'user',
                'roles'
            )
        );
    }


    /**
     * =========================================================
     * UPDATE USER
     * =========================================================
     */
    public function update(Request $request, $id)
    {
        $user = $this->users->findById($id);

        if (!$user) {

            return redirect()
                ->route('admin.users.index')
                ->withErrors([
                    'user' => 'User not found.'
                ]);
        }

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'role' => [
                'nullable',
                'string',
                'exists:roles,name',
            ],

        ]);

        /*
         * Old values BEFORE update
         */
        $oldValues = $user->only([
            'name',
            'email',
            'is_active',
        ]);

        $oldRoles = $user
            ->getRoleNames()
            ->values()
            ->toArray();

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'is_active' => $request->boolean('is_active'),
            'updated_by' => auth()->id(),
        ];

        /*
         * Update password only when supplied
         */
        if (!empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        DB::beginTransaction();

        try {

            /*
             * Update user
             */
            $this->users->update(
                $user,
                $data
            );

            /*
             * Refresh user
             */
            $user->refresh();

            /*
             * Update role
             */
            if (!empty($validated['role'])) {

                $user->syncRoles([
                    $validated['role']
                ]);

            } else {

                $user->syncRoles([]);

            }

            $newValues = $user->only([
                'name',
                'email',
                'is_active',
            ]);

            $newRoles = $user
                ->getRoleNames()
                ->values()
                ->toArray();

            DB::commit();

            /*
             * Activity Log
             */
            ActivityLogger::log(
                'updated',
                'users',
                'Updated user: ' . $user->name,
                $user,
                array_merge(
                    $oldValues,
                    [
                        'roles' => $oldRoles,
                    ]
                ),
                array_merge(
                    $newValues,
                    [
                        'roles' => $newRoles,
                    ]
                )
            );

            return redirect()
                ->route('admin.users.show', $user->id)
                ->with(
                    'success',
                    'User updated successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->withInput()
                ->withErrors([
                    'user' => 'Unable to update user.'
                ]);
        }
    }


    /**
     * =========================================================
     * ASSIGN ROLE
     * =========================================================
     */
    public function assignRole(Request $request, $id)
    {
        $request->validate([
            'role' => [
                'required',
                'string',
                'exists:roles,name',
            ],
        ]);

        $user = $this->users->findById($id);

        if (!$user) {

            return redirect()
                ->back()
                ->withErrors([
                    'user' => 'User not found.'
                ]);
        }

        $oldRoles = $user
            ->getRoleNames()
            ->values()
            ->toArray();

        $role = $request->role;

        /*
         * Assign role
         */
        $user->assignRole($role);

        $user->refresh();

        $newRoles = $user
            ->getRoleNames()
            ->values()
            ->toArray();

        /*
         * Activity Log
         */
        ActivityLogger::log(
            'role_assigned',
            'users',
            'Assigned role "' . $role . '" to user: ' . $user->name,
            $user,
            [
                'roles' => $oldRoles,
            ],
            [
                'roles' => $newRoles,
                'assigned_role' => $role,
            ]
        );

        return redirect()
            ->back()
            ->with(
                'success',
                'Role assigned successfully.'
            );
    }


    /**
     * =========================================================
     * REVOKE ROLE
     * =========================================================
     */
    public function revokeRole(Request $request, $id)
    {
        $request->validate([
            'role' => [
                'required',
                'string',
                'exists:roles,name',
            ],
        ]);

        $user = $this->users->findById($id);

        if (!$user) {

            return redirect()
                ->back()
                ->withErrors([
                    'user' => 'User not found.'
                ]);
        }

        $role = $request->role;

        $oldRoles = $user
            ->getRoleNames()
            ->values()
            ->toArray();

        /*
         * Remove role
         */
        $user->removeRole($role);

        $user->refresh();

        $newRoles = $user
            ->getRoleNames()
            ->values()
            ->toArray();

        /*
         * Activity Log
         */
        ActivityLogger::log(
            'role_revoked',
            'users',
            'Revoked role "' . $role . '" from user: ' . $user->name,
            $user,
            [
                'roles' => $oldRoles,
            ],
            [
                'roles' => $newRoles,
                'revoked_role' => $role,
            ]
        );

        return redirect()
            ->back()
            ->with(
                'success',
                'Role revoked successfully.'
            );
    }


    /**
     * =========================================================
     * ACTIVATE USER
     * =========================================================
     */
    public function activate($id)
    {
        $user = $this->users->findById($id);

        if (!$user) {

            return redirect()
                ->back()
                ->withErrors([
                    'user' => 'User not found.'
                ]);
        }

        $oldValues = [
            'is_active' => (bool) $user->is_active,
        ];

        /*
         * Update status
         */
        $this->users->update(
            $user,
            [
                'is_active' => true,
                'updated_by' => auth()->id(),
            ]
        );

        $user->refresh();

        $newValues = [
            'is_active' => (bool) $user->is_active,
        ];

        /*
         * Status Audit
         */
        UserStatusAudit::create([
            'user_id' => $user->id,
            'old_status' => $oldValues['is_active'] ? 'active' : 'inactive',
            'new_status' => 'active',
            'changed_by' => auth()->id(),
        ]);

        /*
         * Activity Log
         */
        ActivityLogger::log(
            'activated',
            'users',
            'Activated user: ' . $user->name,
            $user,
            $oldValues,
            $newValues
        );

        return redirect()
            ->back()
            ->with(
                'success',
                'User activated successfully.'
            );
    }


    /**
     * =========================================================
     * DEACTIVATE USER
     * =========================================================
     */
    public function deactivate($id)
    {
        $user = $this->users->findById($id);

        if (!$user) {

            return redirect()
                ->back()
                ->withErrors([
                    'user' => 'User not found.'
                ]);
        }

        $oldValues = [
            'is_active' => (bool) $user->is_active,
        ];

        /*
         * Update status
         */
        $this->users->update(
            $user,
            [
                'is_active' => false,
                'updated_by' => auth()->id(),
            ]
        );

        $user->refresh();

        $newValues = [
            'is_active' => (bool) $user->is_active,
        ];

        /*
         * Status Audit
         */
        UserStatusAudit::create([
            'user_id' => $user->id,
            'old_status' => $oldValues['is_active'] ? 'active' : 'inactive',
            'new_status' => 'inactive',
            'changed_by' => auth()->id(),
        ]);

        /*
         * Activity Log
         */
        ActivityLogger::log(
            'deactivated',
            'users',
            'Deactivated user: ' . $user->name,
            $user,
            $oldValues,
            $newValues
        );

        return redirect()
            ->back()
            ->with(
                'success',
                'User deactivated successfully.'
            );
    }


    /**
     * =========================================================
     * USER STATUS AUDITS
     * =========================================================
     */
    public function audits($id)
        {
           $users = $this->users->findById($id)->get([
                    'id',
                    'name',
                ]);

            if (!$users) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'users' => 'User not found.'
                    ]);
            }

            /*
            * Get Activity Logs for this user
            */
            $activities = ActivityLog::where('user_id', $id)
                ->latest('created_at')
                ->paginate(25);
              $modules = ActivityLog::query()
            ->whereNotNull('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');
               $actions = ActivityLog::query()
            ->whereNotNull('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');
            return view(
                'admin.users.activities',
                compact('users', 'activities', 'modules', 'actions')
            );
    }


    /**
     * =========================================================
     * DELETE USER
     * =========================================================
     */
    public function destroy($id)
    {
        $user = $this->users->findById($id);

        if (!$user) {

            return redirect()
                ->route('admin.users.index')
                ->withErrors([
                    'user' => 'User not found.'
                ]);
        }

        /*
         * Store values before deletion
         */
        $oldValues = $user->only([
            'id',
            'name',
            'email',
            'is_active',
        ]);

        $oldValues['roles'] = $user
            ->getRoleNames()
            ->values()
            ->toArray();

        DB::beginTransaction();

        try {

            /*
             * Delete user through repository
             */
            $this->users->delete($user);

            DB::commit();

            /*
             * Activity Log
             *
             * Pass null as model because the user
             * may no longer exist after deletion.
             */
            ActivityLogger::log(
                'deleted',
                'users',
                'Deleted user: ' . $oldValues['name'],
                null,
                $oldValues,
                null
            );

            return redirect()
                ->route('admin.users.index')
                ->with(
                    'success',
                    'User deleted successfully.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            report($e);

            return redirect()
                ->back()
                ->withErrors([
                    'user' => 'Unable to delete user.'
                ]);
        }
    }
}