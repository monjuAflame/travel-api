<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\TourListRequest;
use App\Http\Resources\TourResource;
use App\Models\Travel;

class TourController extends Controller
{
    public function index(Travel $travel, TourListRequest $request)
    {
        $tours = $travel->tours()
                ->when($request->priceForm, function ($query) use ($request) {
                    $query->where('price', '>=', $request->priceForm * 100);
                })
                ->when($request->priceTo, function ($query) use ($request) {
                    $query->where('price', '<=', $request->priceTo * 100);
                })
                ->when($request->dateForm, function ($query) use ($request) {
                    $query->where('starting_date', '>=', $request->dateForm);
                })
                ->when($request->dateTo, function ($query) use ($request) {
                    $query->where('ending_date', '<=', $request->dateTo);
                })
                ->when($request->sortBy && $request->sortOrder, function ($query) use ($request) {
                    $query->orderBy($request->sortBy, $request->sortOrder);
                })
                ->paginate();

        return TourResource::collection($tours);
    }
}
