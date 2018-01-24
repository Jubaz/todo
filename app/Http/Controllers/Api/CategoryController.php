<?php

namespace App\Http\Controllers\Api;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Extra\Transformers\CategoryTransformer;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;

class CategoryController extends ApiController
{
    /*
     * @var Extra\Transformers\CategoryTransformer
     */
    protected $CategoryTransformer;

    public function __construct(CategoryTransformer $CategoryTransformer)
    {
        $this->CategoryTransformer = $CategoryTransformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        // get all categories
        $categories = Category::with('owner:id,name,email')->get();


        // check if categories is empty
        if (empty($categories)) {
            return $this->setStatusCode(HttpResponse::HTTP_NOT_FOUND)->responseWithError('there is no data to show :(');
        }

        // transform category data
        $responseData = $this->CategoryTransformer->transformCollection($categories->toArray());

        // set http status to 200 then response with data
        return $this->setStatusCode(HttpResponse::HTTP_OK)->responseWithData($responseData);
    }

    /**
     * Display the specified resource.
     *
     * @param $categoryId
     * @return \Illuminate\Http\Response
     */
    public function showById($categoryId)
    {
        // get category by id or throw not found exception
        $category = Category::with('owner:id,name,email')->findOrFail($categoryId);

        // transform category data
        $responseData = $this->CategoryTransformer->transform($category);

        // set http status to 200 then response with data
        return $this->setStatusCode(HttpResponse::HTTP_OK)->responseWithData($responseData);
    }


    /**
     * Store a newly created resource in storage.
     * @param CategoryCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryCreateRequest $request)
    {
        // create new  category
        $category = Category::create([
            'title' => $request->post('title'),
            'user_id' => Auth::user()->id,
        ]);

        // Lazy Eager Loading
        $category->load(['owner']);

        // transform category data
        $responseData = $this->CategoryTransformer->transform($category);

        // set http status to 200 then response with data
        return $this->setStatusCode(HttpResponse::HTTP_CREATED)
            ->responseWithData($responseData, 'list created successfully <3');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param $categoryId
     * @param  CategoryUpdateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function updateById($categoryId, CategoryUpdateRequest $request)
    {
        // get category by id or throw not found exception
        $category = Category::FindOrFail($categoryId);

        // check if you are the owner or NOT
        if ($category->user_id != Auth::user()->id) {
            return $this->setStatusCode(HttpResponse::HTTP_FORBIDDEN)
                ->responseWithError("you must be the owner");
        }

        // check if there is a change in updated info
        if ($request->post('title') == $category->title) {
            return $this->setStatusCode(HttpResponse::HTTP_OK)
                ->responseWithError("nothing changed please change your title");
        }

        // every thing is OK update now
        $category->title = $request->post('title');
        $category->save();

        // Lazy Eager Loading
        $category->load(['owner']);

        // transform category data
        $responseData = $this->CategoryTransformer->transform($category);

        // response with new data and success message
        return $this->setStatusCode(HttpResponse::HTTP_OK)
            ->responseWithData($responseData, " updated successfully ");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $categoryId
     * @return \Illuminate\Http\Response
     */
    public function deleteById($categoryId)
    {
        // get category by id or throw not found exception
        $category = Category::findOrFail($categoryId);

        // check if you are the owner or NOT
        if ($category->user_id != Auth::user()->id) {
            return $this->setStatusCode(HttpResponse::HTTP_FORBIDDEN)
                ->responseWithError("you must be the owner");
        }

        // every thing is OK you can delete
        $category->forceDelete();

        // response with success message
        return $this->setStatusCode(HttpResponse::HTTP_OK)
            ->response(['message' => 'deleted successfully']);
    }
}
