<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepositoryInterface;
use App\Support\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    protected UserRepositoryInterface $users;

    /**
     * Create controller instance.
     */
    public function __construct(UserRepositoryInterface $users)
    {
        $this->users = $users;

        /*
        |--------------------------------------------------------------------------
        | Guest Middleware
        |--------------------------------------------------------------------------
        |
        | These methods are available to guests.
        |
        */
        $this->middleware('guest')->except([
            'logout',

            // Profile
            'profileForm',
            'profileEditForm',
            'passwordForm',
            'dashboard',
            'updateProfile',
            'changePassword',

            // User management
            'assignRole',
            'revokeRole',
            'activate',
            'deactivate',
            'statuses',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Auth Middleware
        |--------------------------------------------------------------------------
        |
        | These methods require an authenticated user.
        |
        */
        $this->middleware('auth')->only([
            'logout',

            // Profile
            'profileForm',
            'profileEditForm',
            'passwordForm',
            'dashboard',
            'updateProfile',
            'changePassword',

            // User management
            'assignRole',
            'revokeRole',
            'activate',
            'deactivate',
            'statuses',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Authentication Pages
    |--------------------------------------------------------------------------
    */

    /**
     * Show login page.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Show registration page.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Show forgot password page.
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    /**
     * Display logged-in user's profile.
     */
    public function profileForm()
    {
        return view('auth.profile', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Display profile edit page.
     */
    public function profileEditForm()
    {
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Display change password page.
     */
    public function passwordForm()
    {
        return view('profile.password', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Display user dashboard.
     */
    public function dashboard()
    {
        return view('auth.dashboard', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update logged-in user's profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
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
        ]);

        $oldValues = [
            'name' => $user->name,
            'email' => $user->email,
        ];

        $this->users->update($user, $data);

        ActivityLogger::log(
            'updated',
            'Profile',
            'Updated own profile',
            $user->id,
            $oldValues,
            $data,
            $user
        );

        return back()->with(
            'success',
            'Profile updated successfully.'
        );
    }

    /**
     * Change logged-in user's password.
     */
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => [
                'required',
                'string',
            ],

            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Verify Current Password
        |--------------------------------------------------------------------------
        */
        if (!Hash::check(
            $validated['current_password'],
            $user->password
        )) {
            return back()
                ->withErrors([
                    'current_password' =>
                        'Current password does not match.',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Update Password
        |--------------------------------------------------------------------------
        */
        $this->users->update($user, [
            'password' => Hash::make(
                $validated['password']
            ),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */
        ActivityLogger::log(
            'password_changed',
            'Profile',
            'Changed own account password',
            $user->id,
            null,
            null,
            $user
        );

        return back()->with(
            'success',
            'Password updated successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Registration
    |--------------------------------------------------------------------------
    */

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
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
        ]);
        //dd($data);
        /*
        |--------------------------------------------------------------------------
        | Create User
        |--------------------------------------------------------------------------
        */
        $user = $this->users->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_active' => true,
            'status' => 'new',
            'is_super_admin' => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Assign Default Role
        |--------------------------------------------------------------------------
        */
        if (!$user->hasRole('User')) {
            $user->assignRole('User');
        }

        /*
        |--------------------------------------------------------------------------
        | Login Registered User
        |--------------------------------------------------------------------------
        */
        Auth::login($user);

        /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */
        ActivityLogger::log(
            'registered',
            'Authentication',
            'New user registered',
            $user->id,
            null,
            [
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
            ],
            $user
        );

        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        |
        | Direct redirect avoids redirect loops involving "/".
        |
        */
        return redirect()
            ->route('profile.dashboard')
            ->with(
                'success',
                'Registration successful.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    /**
     * Authenticate user.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],

            'password' => [
                'required',
                'string',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Attempt Authentication
        |--------------------------------------------------------------------------
        */
        if (!Auth::attempt(
            $credentials,
            $request->boolean('remember')
        )) {
            return back()
                ->withErrors([
                    'email' => 'Invalid email or password.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | Check Account Status
        |--------------------------------------------------------------------------
        */
        if (!$user->is_active) {

            Auth::logout();

            return back()
                ->withErrors([
                    'email' =>
                        'Your account has been deactivated.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Regenerate Session
        |--------------------------------------------------------------------------
        */
        $request->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | Login activity is handled by LogSuccessfulLogin listener.
        | Do not log login here again.
        |
        */

        /*
        |--------------------------------------------------------------------------
        | Super Admin / Active Admin
        |--------------------------------------------------------------------------
        */
        if (
            $user->is_super_admin ||
            $user->status === 'active'
        ) {
            return redirect()
                ->route('admin.dashboard')
                ->with(
                    'success',
                    'Welcome back!'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Normal User
        |--------------------------------------------------------------------------
        |
        | Do NOT use redirect()->intended('/') here.
        | The "/" route may redirect again and create:
        |
        | /login -> / -> /login -> / ...
        |
        */
        return redirect()
            ->route('profile.dashboard')
            ->with(
                'success',
                'Logged in successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    /**
     * Logout authenticated user.
     */
    public function logout(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | LogSuccessfulLogout listener handles logout activity.
        |
        */

        Auth::logout();

        /*
        |--------------------------------------------------------------------------
        | Invalidate Session
        |--------------------------------------------------------------------------
        */
        $request->session()->invalidate();

        /*
        |--------------------------------------------------------------------------
        | Regenerate CSRF Token
        |--------------------------------------------------------------------------
        */
        $request->session()->regenerateToken();

        return redirect()
            ->route('login.form')
            ->with(
                'success',
                'Logged out successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Forgot Password
    |--------------------------------------------------------------------------
    */

    /**
     * Send password reset link.
     */
    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
            ],
        ]);

        $status = Password::sendResetLink([
            'email' => $validated['email'],
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(
                'status',
                __($status)
            );
        }

        return back()->withErrors([
            'email' => __($status),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Administrative User Management
    |--------------------------------------------------------------------------
    */

    /**
     * Assign role to user.
     */
    public function assignRole(Request $request, $id)
    {
        $this->authorizeUserManagement();

        $validated = $request->validate([
            'role' => [
                'required',
                'string',
            ],
        ]);

        $user = $this->users->findById($id);

        if (!$user) {
            return back()->withErrors([
                'user' => 'User not found.',
            ]);
        }

        $role = $validated['role'];

        $user->assignRole($role);

        ActivityLogger::log(
            'role_assigned',
            'Users',
            "Assigned role '{$role}' to user: {$user->name}",
            Auth::id(),
            null,
            [
                'role' => $role,
                'user_id' => $user->id,
            ],
            $user
        );

        return back()->with(
            'success',
            'Role assigned successfully.'
        );
    }

    /**
     * Revoke role from user.
     */
    public function revokeRole(Request $request, $id)
    {
        $this->authorizeUserManagement();

        $validated = $request->validate([
            'role' => [
                'required',
                'string',
            ],
        ]);

        $user = $this->users->findById($id);

        if (!$user) {
            return back()->withErrors([
                'user' => 'User not found.',
            ]);
        }

        $role = $validated['role'];

        $user->removeRole($role);

        ActivityLogger::log(
            'role_revoked',
            'Users',
            "Revoked role '{$role}' from user: {$user->name}",
            Auth::id(),
            [
                'role' => $role,
            ],
            null,
            $user
        );

        return back()->with(
            'success',
            'Role revoked successfully.'
        );
    }

    /**
     * Activate user.
     */
    public function activate(Request $request, $id)
    {
        $this->authorizeUserManagement();

        $user = $this->users->findById($id);

        if (!$user) {
            return back()->withErrors([
                'user' => 'User not found.',
            ]);
        }

        $oldValues = [
            'is_active' => $user->is_active,
            'status' => $user->status,
        ];

        $this->users->update($user, [
            'is_active' => true,
            'updated_by' => Auth::id(),
        ]);

        ActivityLogger::log(
            'activated',
            'Users',
            "Activated user: {$user->name}",
            Auth::id(),
            $oldValues,
            [
                'is_active' => true,
            ],
            $user
        );

        return back()->with(
            'success',
            'User activated successfully.'
        );
    }

    /**
     * Deactivate user.
     */
    public function deactivate(Request $request, $id)
    {
        $this->authorizeUserManagement();

        $user = $this->users->findById($id);

        if (!$user) {
            return back()->withErrors([
                'user' => 'User not found.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Self Deactivation
        |--------------------------------------------------------------------------
        */
        if ((int) $user->id === (int) Auth::id()) {
            return back()->withErrors([
                'user' =>
                    'You cannot deactivate your own account.',
            ]);
        }

        $oldValues = [
            'is_active' => $user->is_active,
            'status' => $user->status,
        ];

        $this->users->update($user, [
            'is_active' => false,
            'updated_by' => Auth::id(),
        ]);

        ActivityLogger::log(
            'deactivated',
            'Users',
            "Deactivated user: {$user->name}",
            Auth::id(),
            $oldValues,
            [
                'is_active' => false,
            ],
            $user
        );

        return back()->with(
            'success',
            'User deactivated successfully.'
        );
    }

    /**
     * Display user statuses.
     */
    public function statuses()
    {
        $this->authorizeUserManagement();

        $statuses = $this->users->allStatuses();

        return view(
            'users.statuses',
            compact('statuses')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Authorization
    |--------------------------------------------------------------------------
    */

    /**
     * Authorize user management actions.
     */
    protected function authorizeUserManagement(): void
    {
        if (!Gate::allows('manage-users')) {
            abort(
                403,
                'User does not have the right permissions.'
            );
        }
    }
}