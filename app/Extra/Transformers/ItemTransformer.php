<?php

namespace Extra\Transformers;

class ItemTransformer extends Transformer
{
    public function transform($item)
    {

        return [
            'id' => $item['id'],
            'listID' => $item['category']['id'],
            'listTitle' => $item['category']['title'],
            'ownerID' => $item['owner']['id'],
            'ownerName' => $item['owner']['name'],
            'itemTitle' => $item['title'],
            'itemDescription' => $item['description']
        ];
    }
}