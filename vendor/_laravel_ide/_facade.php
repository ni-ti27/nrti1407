<?php

namespace Illuminate\Support\Facades;

interface Auth
{
    /**
     * @return \App\Models\dept_officers_master|false
     */
    public static function loginUsingId(mixed $id, bool $remember = false);

    /**
     * @return \App\Models\dept_officers_master|false
     */
    public static function onceUsingId(mixed $id);

    /**
     * @return \App\Models\dept_officers_master|null
     */
    public static function getUser();

    /**
     * @return \App\Models\dept_officers_master
     */
    public static function authenticate();

    /**
     * @return \App\Models\dept_officers_master|null
     */
    public static function user();

    /**
     * @return \App\Models\dept_officers_master|null
     */
    public static function logoutOtherDevices(string $password);

    /**
     * @return \App\Models\dept_officers_master
     */
    public static function getLastAttempted();
}