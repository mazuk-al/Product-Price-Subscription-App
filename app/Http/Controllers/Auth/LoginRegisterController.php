<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Foundation\Application as ApplicationContracts;

/**
 * Class LoginRegisterController
 * @package App\Http\Controllers\Auth
 */
class LoginRegisterController extends Controller
{
    /**
     * Instantiate a new LoginRegisterController instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout', 'products'
        ]);
    }

    /**
     * Display a registration form.
     *
     * @return ApplicationContracts|Factory|View|Application|Response
     */
    public function register(): Application|View|Factory|Response|ApplicationContracts
    {
        return view('auth.register');
    }

    /**
     * Store a new user.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('products')
            ->withSuccess('You have successfully registered & logged in!');
    }

    /**
     * Display a login form.
     *
     * @return ApplicationContracts|Factory|View|Application|Response
     */
    public function login(): Application|View|Factory|Response|ApplicationContracts
    {
        return view('auth.login');
    }

    /**
     * Authenticate the user.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('products.index')
                ->withSuccess('You have successfully logged in!');
        }

        return back()->withErrors([
            'email' => 'Your provided credentials do not match in our records.',
        ])->onlyInput('email');

    }

    /**
     * Display a products to authenticated users.
     *
     * @return ApplicationContracts|Factory|View|Application|RedirectResponse|Response
     */
    public function products(): Application|View|Factory|Response|RedirectResponse|ApplicationContracts
    {
        if (Auth::check()) {
            return view('products.index');
        }

        return $this->redirectToLogin();
    }

    /**
     * Log out the user from application.
     *
     * @param Request $request
     * @return Response
     */
    public function logout(Request $request): Response
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');
    }
}
