<?php

namespace App\Http\Controllers\Authenticate;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class AdminRegisterController extends Controller {
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required'
        ]);

        $admin = User::create($validated);

        return $this->respond('یک ادمین جدید ایجاد شد', new UserResource($admin));
    }
}
