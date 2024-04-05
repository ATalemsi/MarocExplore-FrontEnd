<?php
/**
 * @OA\Info(
 *     title="User API",
 *     version="1.0.0",
 *     description="API for managing users",
 *     @OA\Contact(
 *         email="mohamadtalemsi@gmail.com"
 *     ),
 *     @OA\License(
 *         name="abdellah talemsi",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 */

namespace App\Http\Controllers;

use App\Http\Requests\UpdateDestinationRequest;
use App\Models\Destination;
use App\Models\Itinerary;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store destinations for an itinerary.
     *
     * @OA\Post(
     *     path="/api/itineraries/{itineraryId}/destinations",
     *     summary="Store destinations for an itinerary",
     *     tags={"Itineraries"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="itineraryId",
     *         in="path",
     *         required=true,
     *         description="ID of the itinerary to which destinations will be added",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"destinations"},
     *                 @OA\Property(
     *                     property="destinations",
     *                     type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="name", type="string"),
     *                         @OA\Property(property="accommodation", type="string"),
     *                         @OA\Property(
     *                             property="places_to_visit",
     *                             type="array",
     *                             @OA\Items(type="string")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Destinations added successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */

    public function store(Request $request, $itineraryId)
    {
        try {
            // Validate the incoming data
            $request->validate([
                'destinations' => 'required|array',
                'destinations.*.name' => 'required|string',
                'destinations.*.accommodation' => 'required|string',
                // Update field name
            ]);

            // Retrieve the destinations data from the request
            $destinationsData = $request->input('destinations');

            // Create an array to store validated destinations
            $destinations = [];

            // Iterate over each destination data and validate
            foreach ($destinationsData as $destinationData) {
                // Validate placesToVisit field
                if (!isset($destinationData['placesToVisit']) || !is_array($destinationData['placesToVisit'])) {
                    throw new \Exception('Invalid placesToVisit data for destination.');
                }

                // Add the destination to the array
                $destinations[] = [
                    'user_id' => auth()->id(), // Assuming user ID is required
                    'itiniraire_id' => $itineraryId, // Assuming the itinerary ID is required
                    'name' => $destinationData['name'],
                    'accommodation' => $destinationData['accommodation'],

                ];
            }

            // Create and attach the destinations to the itinerary
            Destination::insert($destinations);

            return response()->json(['message' => 'Destinations added successfully'], 200);
        } catch (\Exception $e) {
            // Return a meaningful error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Destination $destination)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Destination $destination)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDestinationRequest $request, Destination $destination)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Destination $destination)
    {
        //
    }
}
