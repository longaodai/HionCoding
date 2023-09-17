<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Symfony\Component\Routing\Exception\InvalidParameterException;

/**
 * Class BaseService
 *
 * @package App\Services
 */
abstract class BaseService
{
    /**
     * @var object $repository
     */
    protected $repository;

    /**
     * @var array $options
     */
    protected $options;

    /**
     * @param $data
     * @param $options
     *
     * @return object
     */
    public function all($data = null, $options = null)
    {
        if ($options) {
            $this->setOption($options);
        }

        return $this->repository->all($this->response($data));
    }

    /**
     * @param $data
     * @param $options
     *
     * @return object
     */
    public function getList($data = null, $options = null)
    {
        if ($options) {
            $this->setOption($options);
        }

        return $this->repository->getList($this->response($data));
    }

    /**
     * @param $data
     * @param $options
     *
     * @return object
     */
    public function show($data = null, $options = null)
    {
        if ($options) {
            $this->setOption($options);
        }

        $data = $this->checkParameter($data);
        $item = $this->repository->find($this->response($data));

        return $item;
    }

    /**
     * @param $data
     * @param $options
     *
     * @return object
     */
    public function getFirstBy($data = null, $options = null)
    {
        if ($options) {
            $this->setOption($options);
        }

        return $this->repository->first($this->response($data));
    }

    /**
     * @param $data
     * @param $options
     *
     * @return object
     *
     * @throws \Exception
     */
    public function store($data = null, $options = null)
    {
        if ($options) {
            $this->setOption($options);
        }

        try {
            $create = $this->repository->create($this->response($data));

            return $create;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param $data
     * @param null $options
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function update($data = null, $options = null)
    {
        try {
            if ($options) {
                $this->setOption($options);
            }

            $update = $this->repository->update($this->response($data));

            return $update;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param $data
     * @param null $options
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function updateOrCreate($data = null, $options = null)
    {
        try {
            if ($options) {
                $this->setOption($options);
            }

            $updateOrCreate = $this->repository->updateOrCreate($this->response($data));

            return $updateOrCreate;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param $data
     * @param $options
     *
     * @return object
     *
     * @throws \Exception
     */
    public function destroy($data = null, $options = null)
    {
        if ($options) {
            $this->setOption($options);
        }

        try {
            $delete = $this->repository->destroy($this->response($data));

            return $delete;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param $options
     *
     * @return $this
     */
    protected function setOption($options)
    {
        $this->options = $this->checkParameter($options);

        return $this;
    }

    /**
     * @param $data
     * @param array $options
     *
     * @return RepositoryResponse
     */
    protected function response($data, $options = [])
    {
        $input = $this->checkParameter($data);

        if (!empty($options)) {
            $this->setOption($options);
        }

        return new RepositoryResponse($input, $this->options ?? []);
    }

    /**
     * @param $param
     *
     * @return array
     */
    private function checkParameter($param)
    {
        switch (true) {
            case $param instanceof Collection:
                $param = $param->toArray();
                break;

            case $param instanceof \stdClass:
                $param = (array) $param;
                break;

            case empty($param):
                $param = [];
                break;

            case is_array($param):
                break;

            default:
                throw new InvalidParameterException('Params invalid !');
                break;
        }

        return $param;
    }


    /**
     * set Model
     *
     * @param null $modelName
     */
    public function setModel($modelName = null)
    {
        $this->repository->setModel($modelName);
    }
}


/**
 * Class RepositoryResponse
 *
 * @package App\Services\Internals
 */
class RepositoryResponse
{
    protected $data;
    protected $options;

    /**
     * @param $data
     * @param $options
     */
    public function __construct($data = [], $options = [])
    {
        $this->data = $data;
        $this->options = $options;
    }

    /**
     * Get data params
     *
     * @param $key
     * @param $default
     *
     * @return null
     */
    public function get($key = null, $default = null)
    {
        if (!empty($key)) {
            return isset($this->data[$key]) ? $this->data[$key] : $default;
        }

        return (!empty($this->data)) ? $this->data : null;
    }

    /**
     * Get params options
     *
     * @param $key
     *
     * @return null
     */
    public function option($key = null)
    {
        if (!empty($key)) {
            return isset($this->options[$key]) ? $this->options[$key] : null;
        }

        return (!empty($this->options)) ? $this->options : null;
    }

    /**
     * Get all params request
     *
     * @return array
     */
    public function all()
    {
        return [
            'data' => $this->data,
            'options' => $this->options
        ];
    }

    /**
     * @param array $keys
     *
     * @return array|null
     */
    public function only($keys = [])
    {
        if (empty($keys)) {
            return $this->get();
        }

        $results = [];
        foreach ($keys as $key) {
            $results = [$key => $this->get($key)];
        }

        return $results;
    }

    /**
     * @param $key
     * @param null $value
     *
     * @return null
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {
            return $this->setData($key);
        }

        $this->data[$key] = $value;

        return $this->get();
    }

    /**
     * @param $data
     *
     * @return array
     */
    private function setData($data = null)
    {
        array_merge($this->data, $data);

        return $this->get();
    }

    /**
     * @param $key
     * @param null $value
     *
     * @return null
     */
    public function setOption($key, $value = null)
    {
        if (is_array($key)) {
            return $this->setOptions($key);
        }

        $this->options[$key] = $value;

        return $this->option();
    }

    /**
     * @param $options
     *
     * @return array
     */
    private function setOptions($options)
    {
        array_merge($this->options, $options);

        return $this->option();
    }
}
