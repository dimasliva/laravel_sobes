<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEquipmentRequest;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        $paginate = 5;
        $equipment = DB::table('equipment');
        if($req->input('type') !== null) {
            $query = $req->input('type');
            $equipment->where(
                    "type",'like',"%$query%",
                )->paginate($paginate);
        } if ($req->input('mask') !== null) {
        $query = $req->input('mask');
        $equipment->where(
                "mask",'like',"%$query%",
            )->paginate($paginate);
        } if ($req->input('id') !== null) {
        $query = $req->input('id');
        $equipment->where(
                "id",'like',"%$query%",
            )->paginate($paginate);
        }
        return response(["status" => 200, "value" => $equipment->paginate($paginate)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreEquipmentRequest $request)
    {
        $validateData = $request->validated();
        $equipmentsReqData = $validateData['equipments'];
        $equipmentsData = [];
        $dateTimeNow = now();
        foreach ($equipmentsReqData as $data) {
            $equipment = [];
            $equipment['type'] = $data['type'];
            $equipment['mask'] = $data['mask'];
            $equipment['created_at'] = $dateTimeNow;
            $equipment['updated_at'] = $dateTimeNow;
            $equipmentsData[] = $equipment;
//            $equipment = Equipment::create([
//                'type' => $data->type,
//                'mask' => $data->mask,
//            ]);
        }
        $intValue = Equipment::insert($equipmentsData);
//        $request->validate([
//            'type' => 'required|unique:equipment',
//            'mask' => array(
//                'min:10',
//                'regex: (([A-Z0-9]{2}[A-Z]{5}[A-Z0-9][A-Z]{2})
//                |([0-9][A-Z0-9]{2}[A-Z]{2}[A-Z0-9][-_,@][A-Z0-9][a-z]{2})
//                |([0-9][A-Z]{4}[A-Z0-9][-_,@][A-Z0-9]{3}))',
//            )
//        ]);
//        $equipment = Equipment::create([
//            'type' => $request->type,
//            'mask' => $request->mask,
//        ]);
//        $validator = $request->validate([
//            'type' => 'required|unique:equipment',
//            'mask' => array(
//                'min:10',
//                'regex: (([A-Z0-9]{2}[A-Z]{5}[A-Z0-9][A-Z]{2})
//                |([0-9][A-Z0-9]{2}[A-Z]{2}[A-Z0-9][-_,@][A-Z0-9][a-z]{2})
//                |([0-9][A-Z]{4}[A-Z0-9][-_,@][A-Z0-9]{3}))',
//                )
//        ]);

        return response()->json($equipment, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $equipment = Equipment::find($id);
        if(!$equipment) {
            return response()->json([
                'status' => false,
                'message' => 'Equipment not found'
            ])->setStatusCode(404);
        }
        return $equipment;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $equipment = Equipment::find($id);
        $equipment->update($request->all());
        return ["value" => $equipment];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $equipment = Equipment::find($id);
        $equipment->delete();
        return [
            "status" => 200,
            "value" => $id
        ];
    }
}
