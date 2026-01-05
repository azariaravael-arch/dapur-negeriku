<?php

namespace Illuminate\Http;

interface Request
{
    /**
     * @return \App\Models\Pengguna|null
     */
    public function user($guard = null);
}