<?php

namespace Illuminate\Http;

interface Request
{
    /**
     * @return \App\Models\dept_officers_master|null
     */
    public function user($guard = null);
}