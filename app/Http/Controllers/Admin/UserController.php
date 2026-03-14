<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\SetPasswordNotification;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\PhysicalBorrow;
use App\Models\Penalty;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereNot('role', 'admin');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('student_id', 'like', '%' . $request->search . '%')
                ->orWhere('department', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->role) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'role'        => 'required|in:faculty,student',
            'student_id'  => 'nullable|string',
            'department'  => 'nullable|string',
            'rfid_tag'    => 'nullable|string',
        ]);

        if ($request->rfid_tag) {
            $exists = User::where('rfid_tag', $request->rfid_tag)->exists();
            if ($exists) {
                return back()->withErrors(['rfid_tag' => 'This RFID tag is already assigned to another user.']);
            }
        }

        $token = Str::random(64);

        $user = User::create([
            'name'                          => $request->name,
            'email'                         => $request->email,
            'password'                      => bcrypt(Str::random(16)),
            'role'                          => $request->role,
            'student_id'                    => $request->student_id,
            'department'                    => $request->department,
            'rfid_tag'                      => $request->rfid_tag,
            'is_active'                     => true,
            'password_set'                  => false,
            'set_password_token'            => $token,
            'set_password_token_expires_at' => now()->addHours(48),
        ]);

        $user->assignRole($request->role);

        try {
            $user->notify(new SetPasswordNotification($token));
        } catch (\Exception $e) {
            \Log::error('Mail error: ' . $e->getMessage());
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully! A password setup email has been sent.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->route('admin.users.index')
            ->with('success', 'Users imported successfully. Password setup emails have been sent.');
    }

    public function toggle(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        return back()->with('success', 'User status updated.');
    }

    public function resendSetPassword(User $user)
    {
        $token = Str::random(64);
        $user->update([
            'set_password_token'            => $token,
            'set_password_token_expires_at' => now()->addHours(48),
        ]);

        try {
            $user->notify(new SetPasswordNotification($token));
        } catch (\Exception $e) {
            \Log::error('Mail error: ' . $e->getMessage());
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Password setup email resent to ' . $user->name . '.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'role'       => 'required|in:faculty,student',
            'student_id' => 'nullable|string',
            'department' => 'nullable|string',
            'rfid_tag'   => 'nullable|string',
        ]);

        if ($request->student_id) {
            $exists = User::where('student_id', $request->student_id)
                ->where('id', '!=', $user->id)
                ->exists();
            if ($exists) {
                return back()->withErrors(['student_id' => 'This ID is already assigned to another user.']);
            }
        }

        if ($request->rfid_tag) {
            $exists = User::where('rfid_tag', $request->rfid_tag)
                ->where('id', '!=', $user->id)
                ->exists();
            if ($exists) {
                return back()->withErrors(['rfid_tag' => 'This RFID tag is already assigned to another user.']);
            }
        }

        $user->update([
            'name'       => $request->name,
            'role'       => $request->role,
            'student_id' => $request->student_id,
            'department' => $request->department,
            'rfid_tag'   => $request->rfid_tag,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully!');
    }

    public function destroy(User $user)
    {
        $activeBorrows = PhysicalBorrow::where('user_id', $user->id)
            ->whereIn('status', ['reserved', 'approved', 'claimed'])
            ->count();

        $unpaidPenalties = Penalty::where('user_id', $user->id)
            ->where('is_paid', false)
            ->count();

        if ($activeBorrows > 0) {
            return back()->withErrors(['delete' => 'Cannot delete user. They have ' . $activeBorrows . ' active borrow(s). Please resolve them first.']);
        }

        if ($unpaidPenalties > 0) {
            return back()->withErrors(['delete' => 'Cannot delete user. They have ' . $unpaidPenalties . ' unpaid penalty/penalties. Please settle them first.']);
        }

        $user->delete();
        return back()->with('success', 'User deleted successfully!');
    }

    public function passwordLinks()
    {
        $users = User::where('password_set', false)
            ->whereNotNull('set_password_token')
            ->whereHas('roles', function ($q) {
                $q->whereIn('name', ['student', 'faculty']);
            })
            ->latest()
            ->get();

        return view('admin.users.password-links', compact('users'));
    }
}