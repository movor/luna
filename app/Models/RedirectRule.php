<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Vkovic\LaravelDbRedirector\Models\RedirectRule as PackageRedirectRule;

class RedirectRule extends PackageRedirectRule
{
    protected $guarded = ['id'];

    use CrudTrait;
}