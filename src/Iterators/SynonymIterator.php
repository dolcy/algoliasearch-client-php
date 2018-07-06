<?php

namespace Algolia\AlgoliaSearch\Iterators;

class SynonymIterator extends AlgoliaIterator
{
    protected function formatHit(array $hit)
    {
        unset($hit['_highlightResult']);

        return $hit;
    }

    protected function fetchCurrentPageResults()
    {
        $this->response = $this->api->read(
            'POST',
            api_path('/1/indexes/%s/synonyms/search', $this->indexName),
            array_merge(
                $this->requestOptions,
                array('page' => $this->getCurrentPage())
            )
        );
    }
}