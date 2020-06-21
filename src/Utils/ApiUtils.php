<?php


namespace App\Utils;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiUtils
{

    /**
     * Response names
     */
    protected const CODE = "code";
    /**
     *
     */
    protected const ERROR = "error";
    /**
     *
     */
    protected const MESSAGE = "message";
    /**
     *
     */
    protected const OBJECT = "obj";
    /**
     *
     */
    protected const RESULTS = "results";
    /**
     *
     */
    protected const SUCCESS = "success";

    /**
     * Params name
     */
    protected const TABLE = "table";
    protected const COLUMN = "column";

    /**
     * @var array
     */
    protected $response = [
        self::SUCCESS => "",
        self::MESSAGE => "",
    ];

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var array
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $formErrors = [];

    /**
     * @param Request $request
     */
    public function getContent(Request $request)
    {
        if ($content = $request->getContent()){
            $this->data = json_decode($content,true);
        }
    }

    /**
     * @param Request $request
     * Gets the query string sanitizing it
     */
    public function getRequestParams(Request $request)
    {
        $request->query->has(self::COLUMN) ? $this->parameters[self::COLUMN] = intval($request->query->get(self::COLUMN)):null;
        $request->query->has(self::TABLE) ? $this->parameters[self::TABLE] = $request->query->get(self::TABLE):null;

        $this->parameters = array_filter($this->parameters);
    }


    /**
     * @param array $data
     * @return array
     *
     * Sanitize the request data checking html tags and trimming it
     */
    public function sanitizeData ($data)
    {
        foreach ($data as $key => $item) {
            if (!is_array($item)){
                $data[$key] = trim(strip_tags($item));
            } else{
                // Sanitize array value (ex: array tags)
                foreach ($item as $item_key => $value){
                    $item[$item_key] = trim(strip_tags($value));
                }
            }
        }
        return $data;
    }

    /**
     * @param ValidatorInterface $validator
     * @param $value
     * @return array
     */
    public function validateData (ValidatorInterface $validator, $value)
    {
        $this->formErrors = [];

        $errors = $validator->validate($value);
        if (count($errors) >0){
            for ($idx=0; $idx < count($errors); $idx ++){
                $prop = $errors->get($idx)->getPropertyPath();
                $this->formErrors[$prop] = $errors->get($idx)->getMessage();
            }
        }

        return $this->formErrors;
    }

    /**
     * @param string $message
     * @param array $errors
     * @param null $obj
     * Prepares error response setting the information
     */
    public function errorResponse (string $message, array $errors = [], $obj = null)
    {
        $obj !== null ? $error[self::OBJECT] = $obj:null;
        $this->response[self::SUCCESS] = false;
        $this->response[self::MESSAGE] = $message;
        $this->response[self::ERROR] = $errors;
    }

    /**
     * @param string $message
     */
    public function notFoundResponse (string $message)
    {
        $error[self::MESSAGE] = "Objeto no encontrado";
        $this->response[self::SUCCESS] = false;
        $this->response[self::MESSAGE] = $message;
        $this->response[self::ERROR] = $error;
    }

    /**
     * @param string $message
     * @param null $results
     */
    public function successResponse (string $message, $results = null)
    {
        $this->response[self::SUCCESS] = true;
        $this->response[self::MESSAGE] = $message;
        $this->response[self::ERROR] = [];
        if ($results !== null)
            $this->response[self::RESULTS] = $results;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @param array $response
     */
    public function setResponse(array $response): void
    {
        $this->response = $response;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getFormErrors(): array
    {
        return $this->formErrors;
    }

    /**
     * @param array $formErrors
     */
    public function setFormErrors(array $formErrors): void
    {
        $this->formErrors = $formErrors;
    }


}