<?php

namespace Illuminate\Support\Facades;

interface Auth
{
    /**
     * @return \App\Models\Pengguna|false
     */
    public static function loginUsingId(mixed $id, bool $remember = false);

    /**
     * @return \App\Models\Pengguna|false
     */
    public static function onceUsingId(mixed $id);

    /**
     * @return \App\Models\Pengguna|null
     */
    public static function getUser();

    /**
     * @return \App\Models\Pengguna
     */
    public static function authenticate();

    /**
     * @return \App\Models\Pengguna|null
     */
    public static function user();

    /**
     * @return \App\Models\Pengguna|null
     */
    public static function logoutOtherDevices(string $password);

    /**
     * @return \App\Models\Pengguna
     */
    public static function getLastAttempted();
}