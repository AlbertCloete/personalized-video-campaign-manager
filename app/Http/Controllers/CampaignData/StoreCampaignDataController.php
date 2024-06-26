<?php

namespace App\Http\Controllers\CampaignData;

use App\Dtos\CampaignDataDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCampaignDataRequest;
use App\Jobs\StoreCampaignDataJob;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;

class StoreCampaignDataController extends Controller
{
    public function __invoke(StoreCampaignDataRequest $request, $campaignId): JsonResponse
    {
        $campaign = Campaign::whereId($campaignId)->first();
        if ($campaign === null) {
            return new JsonResponse(['message' => "Campaign with ID {$campaignId} not found."], 404);
        }

        foreach ($request->data as $data) {
            StoreCampaignDataJob::dispatch(new CampaignDataDto(
                $campaignId,
                $data['user_id'],
                $data['video_url'],
                $data['custom_fields'],
            ));
        }

        return new JsonResponse(['message' => 'Request Accepted'], 202);
    }
}
