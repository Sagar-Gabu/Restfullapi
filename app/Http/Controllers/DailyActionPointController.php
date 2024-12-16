<?php

namespace App\Http\Controllers;

use App\Models\DailyActionPoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Throwable;


class DailyActionPointController extends Controller
{

    public function store(Request $request)
    {
        try {
            $validatorcheck = Validator::make($request->all(), [
                'title' => 'required|unique:daily_action_points,title',
            ]);


            if ($validatorcheck->fails()) {
                return response()->json(['status' => false, 'message' => 'something went wrong', 'errors' => $validatorcheck->errors()], 200);
            }
            $actionpoint = new DailyActionPoint;
            $actionpoint->title = $request->title;
            $actionpoint->description = $request->description;
            $actionpoint->date = $request->date;
            $actionpoint->save();

            return response()->json(['status' => true, 'data' =>
            $actionpoint], 200);
        } catch (QueryException $e) {

            // Database-related error
            $errorCode = $e->errorInfo[1];

            if ($errorCode == 1062) {
                // Duplicate entry error (for unique constraint violation)
                return response()->json(['status' => false, 'message' => 'something went wrong', 'errors' => ['duplicate_entry' => ['Duplicate entry.']]], 200);
            }

            if ($e->errorInfo[1] == 1406) {
                // pincode validation
                return response()->json(['status' => false, 'message' => 'something went wrong', 'errors' => ['data_to_long' => 'Provide Data Too long.']], 200);
            }
            // Handle other Connection error occurred. Retry later or contact support for helps as needed
            return response()->json(['status' => false, 'message' => 'something went wrong', 'errors' => ['database_error' => $e->getMessage()]], 200);
        } catch (Throwable $e) {

            return response()->json(['status' => false, 'message' => 'something went wrong', 'errors' => ['something_went_wrong' => ['Something Went Wrong.']]], 200);
        }
    }
    public function allActionPoint()
    {
        try {

            $actionPoints = DailyActionPoint::get();


            return response()->json([
                'status' => true,
                'message' => 'Daily Action Points retrieved successfully',
                'data' => $actionPoints,
            ], 200);
        } catch (Throwable $e) {

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while fetching data',
                'errors' => ['unexpected_error' => $e->getMessage()],
            ], 500);
        }
    }
    public function updateActionPoint(Request $request, $id)
    {
        try {

            $actionPoint = DailyActionPoint::findOrFail($id);


            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:500',
                'date' => 'nullable|date',
            ]);


            $actionPoint->update($validatedData);


            return response()->json([
                'status' => true,
                'message' => 'Daily Action Point updated successfully',
                'data' => $actionPoint,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'status' => false,
                'message' => 'Daily Action Point not found',
            ], 404);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function getActionPointByid($id)
    {
        $actionPoint = DailyActionPoint::find($id);
        if ($actionPoint  != null) {
            return response()->json([
                'Message' => 'Record found',
                'data' => $actionPoint,
                'status' => true,
            ]);
        } else {
            return response()->json([
                'Message' => 'Record  Notfound',
                'data' => [],
                'status' => true,
            ]);
        }
    }
    public function destroy($id)
    {

        $actionPoint = DailyActionPoint::find($id);

        if ($actionPoint  === null) {
            return response()->json([
                'Message' =>  'Record not Found',
                'status' => false,

            ], 404);
        }
        $actionPoint->delete();


        return response()->json([
            'status' => true,
            'message' => 'Daily Action Point Deleted  successfully',
        ], 200);
    }
}
