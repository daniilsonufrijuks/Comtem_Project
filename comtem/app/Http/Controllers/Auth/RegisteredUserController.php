<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeEmail;
use App\Models\User;
use App\Models\Family;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Registration');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Determine account type based on inputs
        $accountType = 'standalone';
        if ($request->filled('invitation_code')) {
            $accountType = 'join_family';
        } elseif ($request->filled('family_name')) {
            $accountType = 'create_family';
        }

        // Validate based on account type
        $validationRules = [
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'address' => 'required|string|max:255',
        ];

        if ($accountType === 'join_family') {
            $validationRules['invitation_code'] = 'required|string|exists:families,invitation_code';
        } elseif ($accountType === 'create_family') {
            $validationRules['family_name'] = 'required|string|max:255';
        }

        $request->validate($validationRules);

        // Extract the name from the email
        $name = strstr($request->email, '@', true);

        // Handle different account types
        if ($accountType === 'join_family') {
            // Joining existing family
            return $this->joinFamily($request, $name);
        } elseif ($accountType === 'create_family') {
            // Creating new family
            return $this->createFamily($request, $name);
        } else {
            // Standalone account (no family)
            return $this->createStandaloneAccount($request, $name);
        }
    }

    /**
     * Create a standalone account (no family)
     */
    private function createStandaloneAccount(Request $request, string $name): RedirectResponse
    {
        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'standalone', // New role for standalone users
            'is_family_admin' => false,
            'can_use_family_card' => false,
            'family_id' => null,
        ]);

        Auth::login($user);

        $this->sendWelcomeEmail($user);

        return redirect(route('quiz', absolute: false));
    }

    /**
     * Create a new family account
     */
    private function createFamily(Request $request, string $name): RedirectResponse
    {
        // Create the user first
        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'parent',
            'is_family_admin' => true,
            'can_use_family_card' => true,
        ]);

        // Create family
        $family = Family::create([
            'family_name' => $request->family_name,
            'invitation_code' => Family::generateInvitationCode(),
            'parent_id' => $user->id,
            'max_members' => 5, // Default value
            'is_active' => true,
        ]);

        // Update user with family_id
        $user->update(['family_id' => $family->id]);

        Auth::login($user);

        $this->sendWelcomeEmail($user);

        return redirect(route('quiz', absolute: false));
    }

    /**
     * Join an existing family
     */
    private function joinFamily(Request $request, string $name): RedirectResponse
    {
        $family = Family::where('invitation_code', strtoupper($request->invitation_code))->first();

        if (!$family) {
            return back()->withErrors([
                'invitation_code' => 'Invalid invitation code.'
            ]);
        }

        if ($family->getAvailableSlots() <= 0) {
            return back()->withErrors([
                'invitation_code' => 'This family has reached its maximum member limit.'
            ]);
        }

        $user = User::create([
            'name' => $name,
            'email' => $request->email,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'family_id' => $family->id,
            'role' => 'child',
            'is_family_admin' => false,
            'can_use_family_card' => false, // Parent must enable this
        ]);

        Auth::login($user);

        $this->sendWelcomeEmail($user);

        return redirect(route('quiz', absolute: false));
    }

    /**
     * Send welcome email
     */
    private function sendWelcomeEmail(User $user): void
    {
        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
        } catch (\Exception $e) {
            // Log error but don't stop registration
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }
    }
}
