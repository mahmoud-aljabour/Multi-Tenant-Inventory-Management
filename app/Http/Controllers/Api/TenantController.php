<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenantRequest;
use App\Http\Resources\TenantResource;
use App\Models\Tenant;

class TenantController extends Controller
{
    public function store(StoreTenantRequest $request)
    {
        $validatedData = $request->validated();

        $tenant = Tenant::create($validatedData);

        return new TenantResource($tenant);

    }
}
