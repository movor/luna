<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiExceptions\ApiBaseException;
use App\Exceptions\ApiExceptions\ApiMethodNotAllowedException;
use App\Exceptions\ApiExceptions\ApiResourceNotFoundException;
use App\Exceptions\ApiExceptions\ApiUnauthorizedException;
use App\Exceptions\ApiExceptions\ApiUnprocessableEntityException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request as SymphonyRequest;
use Request;

abstract class ApiController extends Controller
{
    /**
     * Url segment that corresponds to model name
     *
     * @var int
     */
    protected $urlModelSegment = 2;

    /**
     * Validate the given request with the given rules.
     *
     * @param  SymphonyRequest $request
     * @param  array           $rules
     * @param  array           $messages
     * @param  array           $customAttributes
     *
     * @return void
     *
     * @throws ApiBaseException
     */
    public function validate(SymphonyRequest $request, array $rules, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()->make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            $message = implode(' ', $validator->errors()->all());
            $this->throwUnprocessableEntityException($message);
        }
    }

    /**
     * Get model class name based on request segment
     */
    protected function getModelClassName()
    {
        return 'App\\Models\\' . studly_case(Request::segment($this->urlModelSegment));
    }

    /**
     * Get model based on corresponding request segment
     * and provided id
     *
     * @param int $id
     *
     * @return Model
     */
    protected function getModel($id)
    {
        $modelClassName = $this->getModelClassName();

        /** @var Model $model */
        $model = $modelClassName::find($id);

        if (is_null($model)) {
            $this->throwApiResourceNotFoundException();
        } else {
            return $model;
        }
    }

    /**
     * Return all models
     *
     * @return Collection
     */
    public function index()
    {
        $className = $this->getModelClassName();

        return $className::all();
    }

    /**
     * Return single model
     *
     * @param int $id
     *
     * @return Model
     */
    public function show($id)
    {
        return $this->getModel($id);
    }

    // TODO
    // This is just quick implementation
    public function create()
    {
        $modelName = $this->getModelClassName();
        $model = new $modelName;

        // Do not proceed in case of empty request
        $inputs = Request::all();
        //if (count($inputs) == 0) return $model;

        // Get fillable attributes for current model
        $modelFillable = $model->getFillable();

        foreach ($inputs as $key => $value) {
            if (in_array($key, $modelFillable)) {
                $regularFields[$key] = $value;
            }
        }

        // Save model with regular fields
        if (!empty($regularFields)) {
            $model->fill($regularFields)->save();
            return $model;
        } else {
            return null;
        }
    }

    /**
     * @param int   $id
     * @param array $fieldHandlers
     *
     * @return mixed
     */
    public function update($id, array $fieldHandlers = [])
    {
        $model = $this->getModel($id);

        // Do not proceed in case of empty request
        $inputs = Request::all();
        if (count($inputs) == 0) return $model;

        // Get fillable attributes for current model
        $modelFillable = $model->getFillable();

        // Filter fields - leave:
        // 1) Those defined in Model:$fillable
        // 2) Those provided in field handlers
        $customFields = [];
        $regularFields = [];
        foreach ($inputs as $key => $value) {
            // Handle custom field with callback
            if (array_key_exists($key, $fieldHandlers)) {
                $customFields[$key] = $inputs[$key];
            } // Regular fields (those not present in Model::$fillable will be ignored)
            elseif (in_array($key, $modelFillable)) {
                $regularFields[$key] = $value;
            }
        }

        // Save model with regular fields
        if (!empty($regularFields)) $model->update($regularFields);

        // Process custom fields with callbacks
        $data = [];
        foreach ($customFields as $key => $value) {
            if (is_callable($fieldHandlers[$key])) {
                // Callback with param: value (from request)
                $result = $fieldHandlers[$key]($value);

                // If callback return type is void, than
                // do not add this field to array of data to be saved
                if ($result !== null) $data[$key] = $result;
            } else {
                $data[$key] = $value;
            }
        }

        if (!empty($data)) $model->update($data);

        return $model;
    }

    protected function destroy($id)
    {
        $model = $this->getModel($id);

        $model->delete();
    }

    /**
     * @param string $message
     *
     * @throws ApiBaseException
     */
    protected function throwMethodNotAllowedException($message = '')
    {
        throw new ApiMethodNotAllowedException($message);
    }

    /**
     * @param string $message
     *
     * @throws ApiBaseException
     */
    protected function throwResourceNotFoundException($message = '')
    {
        throw new ApiResourceNotFoundException($message);
    }

    /**
     * @param string $message
     *
     * @throws ApiBaseException
     */
    protected function throwUnauthorizedException($message = '')
    {
        throw new ApiUnauthorizedException($message);
    }

    /**
     * @param string $message
     *
     * @throws ApiBaseException
     */
    protected function throwUnprocessableEntityException($message = '')
    {
        throw new ApiUnprocessableEntityException($message);
    }
}