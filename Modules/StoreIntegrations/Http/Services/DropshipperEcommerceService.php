<?php

namespace Modules\StoreIntegrations\Http\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Basic\Service\BasicService;
use Modules\Store\Repositories\DropshipperEcommerceRepository;
use Modules\Acl\Http\Resources\Dropshipper\DropshipperResource;

class DropshipperEcommerceService extends BasicService
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * return void
     */
    public function __construct(DropshipperEcommerceRepository $repository)
    {
        $this->repo = $repository;
    }

    public function store($user)
    {
        $request = new Request([
            'name' => $user->getName(),
            'owner_id' => $user->getID(),
            'dropshipper_id' => Auth::guard('dropshipper')->user()->id,
            'email' => $user->getEmail(),
            'store_id' => $user->getStoreID(),
            'store_type' => 'salla',
            'phone' => $user->getMobile(),
            'role' => $user->getRole(),
            'username' => $user->getStoreUsername(),
            'avatar' => $user->getStoreAvatar()
        ]);
        $data = $this->repo->save($request);

        return true;
    }

    public function storeEasyMode($user, $dropshipper)
    {
        $request = new Request([
            'name' => $user->getName(),
            'owner_id' => $user->getID(),
            'dropshipper_id' => $dropshipper->id,
            'email' => $user->getEmail(),
            'store_id' => $user->getStoreID(),
            'store_type' => 'salla',
            'phone' => $user->getMobile(),
            'role' => $user->getRole(),
            'username' => $user->getStoreUsername(),
            'avatar' => $user->getStoreAvatar()
        ]);
        
        $this->repo->save($request);

        return true;
    }


    /**
     * It deletes the user's account
     *
     * param Request request The request object
     *
     * return The data is being returned.
     */
    public function destroy(Request $request, $id = null)
    {
        $data = $this->repo->delete(user()->id);
        if ($data) {
            return $data;
        }
        return false;
    }


    public function update(Request $request, $id)
    {
        if ($request->amount) {
            $request->merge([
                'walletBalance' => user()->walletBalance - $request->amount,
            ]);
        }
        $data = $this->repo->save($request, user()->id);
        if ($data) {
            return new DropshipperResource($data);
        }
        return false;
    }

    public function userNameMerchant($user)
    {
        $requestnew = new Request([
            'dropshipper_id' => Auth::guard('dropshipper')->user()->id,
            'store_type' => 'salla',
            'username' => $user->getStoreUsername()
        ]);

        $data = $this->repo->userNameMerchant($requestnew);

        if ($data) {
            return true;
        }
        return false;
    }

    public function userNameMerchantEasyMode($user)
    {
        $requestnew=new Request([
            'store_id' => $user->getStoreID(),
            'owner_id' => $user->getID(),
            'store_type' => 'salla',
            'username' => $user->getStoreUsername()]);

        $data = $this->repo->userNameMerchantEasyMode($requestnew);

        if ($data) {
            return true;
        }
        return false;
    }
}
