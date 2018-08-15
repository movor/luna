<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Movor\LaravelDbRedirector\Models\RedirectRule as PackageRedirectRule;

class RedirectRule extends PackageRedirectRule
{
    use CrudTrait;
}