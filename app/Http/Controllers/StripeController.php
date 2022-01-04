<?php

namespace App\Http\Controllers;

use App\repositories\StripeRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StripeController extends Controller
{
    /* @var $stripe StripeRepository*/
    public $stripeRepo;

    public function __construct(StripeRepository $stripeRepository)
    {
        $this->stripeRepo = $stripeRepository;
    }

    /**
     * Sync All plans from
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncPlans(Request $request)
    {
        $this->stripeRepo->syncPlans();
        return api_response(null,null,'Plans Sync Successfully');
    }
}
