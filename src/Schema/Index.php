<?php

namespace AviationCode\Elasticsearch\Schema;

use AviationCode\Elasticsearch\Exceptions\BaseElasticsearchException;
use Exception;

class Index extends Schema
{
    /**
     * Check if the given index exists.
     *
     * @param string|null $index
     * @return bool
     */
    public function exists($index = null): bool
    {
        return $this->elasticsearch->getClient()->indices()->exists([
            'index' => $this->getIndex($index),
        ]);
    }

    /**
     * @param null|string $index
     * @return array
     *
     * @throws \Throwable
     */
    public function info($index = null)
    {
        try {
            $response = $this->elasticsearch->getClient()->indices()->get(['index' => $this->getIndex($index)]);

            return $response[$this->getIndex($index)];
        } catch (Exception $exception) {
            throw $this->handleException($exception);
        }
    }

    /**
     * Create an index.
     *
     * @param null $index
     * @return bool
     *
     * @throws BaseElasticsearchException
     * @throws \Throwable
     */
    public function create($index = null)
    {
        try {
            $this->elasticsearch->getClient()->indices()->create(['index' => $this->getIndex($index)]);
        } catch (Exception $exception) {
            throw $this->handleException($exception);
        }

        if ($this->elasticsearch->model) {
            $this->putMapping($this->elasticsearch->model->getSearchMapping(), $index);
        }

        return true;
    }

    /**
     * Delete an index and it's data.
     *
     * @param null $index
     * @return void
     *
     * @throws BaseElasticsearchException
     * @throws \Throwable
     */
    public function delete($index = null): void
    {
        try {
            $this->elasticsearch->getClient()->indices()->delete(['index' => $this->getIndex($index)]);
        } catch (Exception $exception) {
            throw $this->handleException($exception);
        }
    }

    /**
     * Create or update index mapping.
     *
     * @param array $mappings
     * @param null $index
     * @return void
     * @throws BaseElasticsearchException
     * @throws \Throwable
     */
    public function putMapping(?array $mappings = null, $index = null): void
    {
        try {
            $this->elasticsearch->getClient()->indices()->putMapping([
                'index' => $this->getIndex($index),
                'body' => [
                    'properties' => $mappings ?? $this->elasticsearch->model->getSearchMapping(),
                ],
            ]);
        } catch (Exception $exception) {
            throw $this->handleException($exception);
        }
    }
}
