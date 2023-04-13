<?php

namespace Tabata\Service;

class WorkoutService {

    private $collectionRandom;
    private $collection;
    
    public function __construct()
    {
        $this->collection = 
        $this->collectionRandom = [];
    }

    public function add($id, $name, $image = '')
    {
        $this->collection[] = [
            'id' => $id,
            'name' => $name,
            'image' => $image,
        ];
    }

    public function get($limit = 0, $collection = [])
    {
        $collectionToUse = $this->collection;
        if (!empty($collection)) {
            $collectionToUse = $collection;
        }

        if ($limit == 0) {
            return $collectionToUse;
        }

        $return = [];
        $i = 0;

        foreach ($collectionToUse as $key => $workout) {
            ++$i;
            if ($i > $limit) {
                break;
            }

            $return[] = $workout;
        }

        return $return;
    }

    public function randomize($limit = 0)
    {
        $this->collectionRandom = $this->collection;
        shuffle($this->collectionRandom);

        return $this->get($limit, $this->collectionRandom);
    }
}