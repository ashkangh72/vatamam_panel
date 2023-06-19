<?php

namespace App\Http\Controllers\Back;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\MarketingCampaign;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\{Foundation\Application, Routing\ResponseFactory};
use Illuminate\Contracts\View\{Factory, View};
use Illuminate\Http\{Request, Response};
use Illuminate\Validation\ValidationException;

class MarketingCampaignController extends Controller
{
    /**
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function index()
    {
        $this->authorize('users.marketing-campaigns');

        $marketingCampaigns = MarketingCampaign::latest()->paginate(10);

        return view('back.marketing-campaigns.index', compact('marketingCampaigns'));
    }

    /**
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function create()
    {
        $this->authorize('users.marketing-campaigns.create');

        return view('back.marketing-campaigns.create');
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'                              => 'required|string',
            'start_at'                          => 'required',
            'end_at'                            => 'required',
            'tariffs'                           => 'required|array',
            'tariffs.*.minimum_purchase'                       => 'required|numeric|min:0',
            'tariffs.*.products_commission_percent'            => 'required|numeric|min:0|max:100',
            'tariffs.*.discounted_products_commission_percent' => 'required|numeric|min:0|max:100',
            'tariffs.*.discounted_orders_commission_percent'   => 'required|numeric|min:0|max:100',
        ]);

        $marketingCampaign = MarketingCampaign::create([
            'name'      => $request->name,
            'start_at'  => Verta::parse($request->start_at)->formatGregorian('Y-m-d H:i:s'),
            'end_at'    => Verta::parse($request->end_at)->formatGregorian('Y-m-d H:i:s')
        ]);

        foreach ($request->tariffs as $tariff) {
            $marketingCampaign->tariffs()->insert([
                'marketing_campaign_id'                  => $marketingCampaign->id,
                'minimum_purchase'                       => $tariff['minimum_purchase'],
                'products_commission_percent'            => $tariff['products_commission_percent'],
                'discounted_products_commission_percent' => $tariff['discounted_products_commission_percent'],
                'discounted_orders_commission_percent'   => $tariff['discounted_orders_commission_percent'],
                'created_at'                             => Carbon::now(),
                'updated_at'                             => Carbon::now()
            ]);
        }

        toastr()->success('کمپین با موفقیت ایجاد شد.');

        return response("success", 200);
    }

    /**
     * @param MarketingCampaign $marketingCampaign
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function edit(MarketingCampaign $marketingCampaign)
    {
        $this->authorize('users.marketing-campaigns.update');

        return view('back.marketing-campaigns.edit', compact('marketingCampaign'));
    }

    /**
     * @param Request $request
     * @param MarketingCampaign $marketingCampaign
     * @return Application|ResponseFactory|Response
     * @throws ValidationException
     */
    public function update(Request $request, MarketingCampaign $marketingCampaign)
    {
        $this->validate($request, [
            'name'                              => 'required|string',
            'start_at'                          => 'required',
            'end_at'                            => 'required',
            'tariffs'                           => 'required|array',
            'tariffs.*.minimum_purchase'                       => 'required|numeric|min:0',
            'tariffs.*.products_commission_percent'            => 'required|numeric|min:0|max:100',
            'tariffs.*.discounted_products_commission_percent' => 'required|numeric|min:0|max:100',
            'tariffs.*.discounted_orders_commission_percent'   => 'required|numeric|min:0|max:100',
        ]);

        $marketingCampaign->update([
            'name'      => $request->name,
            'start_at'  => Verta::parse($request->start_at)->formatGregorian('Y-m-d H:i:s'),
            'end_at'    => Verta::parse($request->end_at)->formatGregorian('Y-m-d H:i:s')
        ]);

        $minimumPurchases = [];
        foreach ($request->tariffs as $tariff) {
            $minimumPurchases[] = $tariff['minimum_purchase'];

            $marketingCampaignTariff = $marketingCampaign->tariffs()->where('minimum_purchase', $tariff['minimum_purchase']);
            if ($marketingCampaignTariff->exists()) {
                $marketingCampaignTariff->update([
                    'products_commission_percent'            => $tariff['products_commission_percent'],
                    'discounted_products_commission_percent' => $tariff['discounted_products_commission_percent'],
                    'discounted_orders_commission_percent'   => $tariff['discounted_orders_commission_percent'],
                    'updated_at'                             => Carbon::now()
                ]);
            } else {
                $marketingCampaign->tariffs()->create([
                    'marketing_campaign_id'                  => $marketingCampaign->id,
                    'minimum_purchase'                       => $tariff['minimum_purchase'],
                    'products_commission_percent'            => $tariff['products_commission_percent'],
                    'discounted_products_commission_percent' => $tariff['discounted_products_commission_percent'],
                    'discounted_orders_commission_percent'   => $tariff['discounted_orders_commission_percent'],
                    'created_at'                             => Carbon::now(),
                    'updated_at'                             => Carbon::now()
                ]);
            }
        }
        $marketingCampaign->tariffs()->whereNotIn('minimum_purchase', $minimumPurchases)->delete();

        toastr()->success('کمپین با موفقیت ویرایش شد.');

        return response("success", 200);
    }
}
