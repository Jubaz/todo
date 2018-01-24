<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Http\Requests\ItemCreateRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Item;
use Extra\Transformers\ItemTransformer;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;

class ItemController extends ApiController
{

    protected $itemTransformer;

    public function __construct(ItemTransformer $itemTransformer)
    {
        $this->itemTransformer = $itemTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @param null $categoryId
     * @return \Illuminate\Http\Response
     */
    public function showAll($categoryId = null)
    {
        // get category items OR all items
        $items = $categoryId ? Category::findOrFail($categoryId)->items : Item::all();

        // Lazy Eager Loading
        $items->load(['owner' , 'category']);

        // check if items empty or not
        if (empty($items[0])) {
            return $this->setStatusCode(HttpResponse::HTTP_NOT_FOUND)
                ->responseWithError('there is no data to show :(');
        }

        // transform item data
        $responseData = $this->itemTransformer->transformCollection($items->toArray());

        // set http status to 200 then response with data
        return $this->setStatusCode(HttpResponse::HTTP_OK)->responseWithData($responseData);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param $categoryID
     * @param ItemCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store($categoryID, ItemCreateRequest $request)
    {
        //check given id is exist in database or not
        if (empty(Category::find($categoryID))) {
            return $this->setStatusCode(HttpResponse::HTTP_NOT_FOUND)
                ->responseWithError('invalid List ID');
        }

        // create new item
        $item = Item::create([
            'user_id' => Auth::user()->id,
            'category_id' => $categoryID,
            'title' => $request->post('title'),
            'description' => $request->post('description')
        ]);

        // Lazy Eager Loading
        $item->load(['owner' , 'category']);

        // transform item data
        $responseData = $this->itemTransformer->transform($item);

        // set HTTP to 200 , response with success
        return $this->setStatusCode(HttpResponse::HTTP_CREATED)
            ->responseWithData($responseData, 'item created successfully <3');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param $categoryID
     * @param $itemID
     * @param ItemUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updateById($categoryID, $itemID, ItemUpdateRequest $request)
    {
        // check if item exist or not
        $item = Item::findOrFail($itemID);

        //check given categoryID is exist or not
        if (empty(Category::find($categoryID)) || $item->category_id != $categoryID) {
            return $this->setStatusCode(HttpResponse::HTTP_NOT_FOUND)
                ->responseWithError('invalid list ID');
        }

        // check if you are the owner or NOT
        if ($item->user_id != Auth::user()->id) {
            return $this->setStatusCode(HttpResponse::HTTP_FORBIDDEN)
                ->responseWithError("you must be the owner");
        }

        // check if there is a change in updated info
        if ($request->post('title') == $item->title && $request->post('description') == $item->description) {
            return $this->setStatusCode(HttpResponse::HTTP_OK)
                ->responseWithError("nothing changed please change your title or description");
        }

        // update
        $item->title = $request->post('title');
        $item->description = $request->post('description');
        $item->save();

        // Lazy Eager Loading
        $item->load(['owner' , 'category']);

        // transform item data
        $responseData = $this->itemTransformer->transform($item);

        // set HTTP code to 200 , response with success message
        return $this->setStatusCode(HttpResponse::HTTP_OK)
            ->responseWithData($responseData, " updated successfully ");
    }

    /**
     * Remove the specified resource from storage.
     * @param $categoryID
     * @param $itemId
     * @return \Illuminate\Http\Response
     */
    public function deleteById($categoryID, $itemId)
    {
        // check if item exist or not
        $item = Item::findOrFail($itemId);

        //check given categoryID is exist or not
        if (empty(Category::find($categoryID)) || $item->category_id != $categoryID) {
            return $this->setStatusCode(HttpResponse::HTTP_NOT_FOUND)
                ->responseWithError('invalid listID or itemId');
        }

        // check if you are the owner or NOT
        if ($item->user_id != Auth::user()->id) {
            return $this->setStatusCode(HttpResponse::HTTP_FORBIDDEN)
                ->responseWithError("you must be the owner");
        }

        // every thing is OK you can delete
        $item->forceDelete();

        // response with success message
        return $this->setStatusCode(HttpResponse::HTTP_OK)
            ->response(['message' => 'deleted successfully']);
    }
}
