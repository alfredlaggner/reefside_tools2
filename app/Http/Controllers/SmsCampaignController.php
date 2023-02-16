<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSmsCampaignRequest;
use App\Http\Requests\UpdateSmsCampaignRequest;
use App\Models\SmsCampaign;
use App\Models\SmsLog;

class SmsCampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campagnes = SmsCampaign::get();
        return view('campaign.show', compact("campagnes"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('campaign.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreSmsCampaignRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSmsCampaignRequest $request)
    {
        SmsCampaign::create([
            'name' => $request->input('name'),
            'message' => $request->input('message'),
            'phone_number_file' => $request->input('phone_number_file'),
            'send_date' => $request->input('send_date')
        ]);
        return back()->with(['success' => "Campagne saved!"]);

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\SmsCampaign $smsCampagne
     * @return \Illuminate\Http\Response
     */
    public function show(SmsCampaign $smsCampagne)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\SmsCampaign $smsCampagne
     * @return \Illuminate\Http\Response
     */
    public function edit(SmsCampaign $smsCampagne)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateSmsCampaignRequest $request
     * @param \App\Models\SmsCampaign $smsCampagne
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSmsCampaignRequest $request, SmsCampaign $smsCampagne)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\SmsCampaign $smsCampagne
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmsCampaign $smsCampagne)
    {
        //
    }
}
