<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use App\Repository\User\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{

    public function __construct(
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function index(): View
    {
        $users = User::query()->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        return view('users.create');
    }

    public function store(UserStoreRequest $userStoreRequest): RedirectResponse
    {
        $user = $this->userRepository->store($userStoreRequest);

        return redirect()
            ->route('users.show', $user)
            ->with('success', 'User created successfully');
    }

    public function show(User $user): View
    {
        return view('users.show', [
            'user' => $user
        ]);
    }

    public function update(
        UserUpdateRequest $userUpdateRequest,
        User              $user
    ): RedirectResponse
    {
        $this->userRepository->update($userUpdateRequest, $user);

        return redirect()->route(
            'users.show',
            $user
        )->with('success', 'User updated successfully');
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->userRepository->destroy($user);

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
