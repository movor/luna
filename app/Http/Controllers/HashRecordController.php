<?php

namespace App\Http\Controllers;

use App\Models\HashRecord;

class HashRecordController extends Controller
{
    // TODO: Name changes, removing additional call to database
    function canonicalView($hash)
    {
        $hashRecord = HashRecord::where('hash', $hash)->firstOrFail();

        $controllerClass = 'App\Http\Controllers\\' . $hashRecord->getModelName() . 'Controller';

        return (new $controllerClass)->view($hashRecord->getRecord()->slug);
    }
}

