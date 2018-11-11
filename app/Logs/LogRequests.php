<?php
namespace App\Logs;

use Illuminate\Http\Request;
use Spatie\HttpLogger\LogProfile;

class LogRequests implements LogProfile
{
    public function shouldLogRequest(Request $request): bool
    {
        return in_array(strtolower($request->method()), ['post', 'put', 'patch', 'get', 'delete']);
    }
}
