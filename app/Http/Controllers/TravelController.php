<?php

namespace App\Http\Controllers;

use App\Travel;
use Illuminate\Http\Request;
use App\Http\Resources\TravelResource;
use App\Http\Requests\CreateTravelRequest;

class TravelController extends Controller
{
    public function index()
    {
        $this->middleware('auth:api', ['except' => '']);
    }

    public function get(Travel $travel)
    {
        try {
            new TravelResource($travel);
            return response([
                'data' => new TravelResource($travel),
                'message' => "",
                'errors' => []
            ], 201);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function list(Request $request)
    {
        // @ page: string
        // @ By default paginator will intercept the request and handle page number

        // @ limit: string
        $limit = !empty($request->limit) ? $request->limit : 10;

        // @ filter: string
        // $filter = $request->filter ? [['name', 'like', '%' . $request->filter  . '%']] : [];

        // @ sortBy: string
        $sortBy =  !empty($request->sortBy) ? $request->sortBy : 'project_name';

        // @ descending: Boolean
        $descending =  json_decode($request->descending)  ? "DESC" : 'ASC';

        $records = Travel::orderby($sortBy, $descending)
            // ->where($filter)
            ->where(function ($query) use ($request) {
                // $query->where('name', 'like', '%' . $request->filter  . '%');
            })
            ->paginate($limit);

        return TravelResource::collection($records);
    }

    public function create(CreateTravelRequest $request)
    {
        try {
            $travel = Travel::create($request->all());
            return response([
                'data' => new TravelResource($travel),
                'message' => "Travel created successfully.",
                'errors' => []
            ], 201);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

}
