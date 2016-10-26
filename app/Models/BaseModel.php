<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

/**
 * Description of BaseModel
 *
 * @author francisco
 */
class BaseModel
        extends Model {

    protected $messages = [];
    protected $fillable = [];
    protected static $rules = [];
    protected $errors;

    public function __construct(array $attributes = []) {
        parent::__construct($attributes);
    }

    /**
     * Every time before a model is being saved or updated, It must be
     * validated with the respective rules
     */
    protected static function boot() {
        parent::boot();

        static::saving(function($model) {
            return $model->validate();
        });

        static::updating(function($model) {
            return $model->validate(true);
        });
    }

    /**
     * Validates current attributes against rules
     */
    public function validate() {

        $replace = ($this->getKey() > 0) ? $this->getKey() : 'NULL';
        $rules = static::$rules;

        foreach ($rules as $key => $rule) {
            $rules[$key] = str_replace(':ID', $replace, $rule);
        }

        $validator = Validator::make($this->attributes, $rules, $this->messages);

        $passed = $validator->passes();

        if (!$passed) {
            $this->setErrors($validator->messages());
        }

        return $passed;
    }

    /**
     * Set error message bag
     *
     * @var Illuminate\Support\MessageBag
     */
    protected function setErrors($errors) {
        $this->errors = $errors;
    }

    /**
     * Retrieve error message bag
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Inverse of wasSaved
     */
    public function hasErrors() {
        return !empty($this->errors);
    }

}
