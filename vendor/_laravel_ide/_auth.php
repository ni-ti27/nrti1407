<?php

namespace Illuminate\Contracts\Auth;

interface Guard
{
    /**
     * @return \App\Models\dept_officers_master|null
     */
    public function user();
}