<?php

namespace Extra\Transformers;

class CategoryTransformer extends Transformer
{
    public function transform($category)
    {
        return [
            'id'    => $category['id'],
            'title' => $category['title'],
            'ownerID' => $category['owner']['id'],
            'ownerName' => $category['owner']['name']
        ];
    }
}