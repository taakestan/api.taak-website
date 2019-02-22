<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase {
    use CreatesApplication;

    /**
     * Set the currently logged in user for the application.
     *
     * @param User|null $user
     * @return TestCase
     */
    public function signIn($user = null)
    {
        $user = $user ?: create(User::class);

        $this->be($user);

        return $this;
    }
}
