<?php

namespace Docs\Docs\Controller\Concerns;

use Docs\Support\Markdown;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationRuleParser;
use ReflectionMethod;
use ReflectionParameter;

trait DescribesRequest
{
    /**
     * Describe request.
     *
     * @return array
     */
    public function describeRequest()
    {
        return [
            $this->describeAuthorization(),
            $this->describeRules(),
        ];
    }

    /**
     * Describe rules.
     *
     * @return array|null
     */
    public function describeRules()
    {
        if (! $method = $this->reflectRules()) {
            return;
        }

        if (! $this->getRules()) {
            return;
        }

        return [
            $this->subTitle('Rules'),
            //$this->getSummary($method),
            $this->rulesTable(),
        ];
    }

    protected function rulesTable()
    {
        $rows = [];
        foreach ($this->getRules() as $attribute => $rules) {
            foreach ($rules as $rule) {
                $rows[] = [
                    Markdown::code($attribute),
                    Markdown::code($this->getRuleName($rule)),
                    $this->getRuleParameters($rule)->map(fn ($param) => Markdown::code($param)->toMarkdown())->implode(','),
                    $this->getRuleDescription($attribute, $rule),
                ];
            }
        }

        return Markdown::table([
            'Attribute', 'Rules', 'Parameters', 'Message',
        ], $rows);
    }

    public function getRuleName($rule)
    {
        return ValidationRuleParser::parse($rule)[0];
    }

    public function getRuleParameters($rule)
    {
        return collect(last(ValidationRuleParser::parse($rule)));
    }

    public function getRuleDescription($attribute, $rule)
    {
        $validator = validator()->make([], []);

        $validator->addFailure(
            $attribute,
            ...ValidationRuleParser::parse($rule)
        );

        return collect($validator->messages()->get($attribute))->first();
    }

    /**
     * Describe database.
     *
     * @return array
     */
    public function describeAuthorization()
    {
        if (! $method = $this->reflectAuthorization()) {
            return;
        }

        return [
            $this->subTitle('Authorization'),
            $this->getSummary($method),
        ];
    }

    /**
     * Get request parameter.
     *
     * @return ReflectionParameter|null
     */
    public function getRequestParameter()
    {
        return $this->getParameters()->first(function (ReflectionParameter $param) {
            if (! $type = $param->getType()) {
                return false;
            }

            return instance_of($type->getName(), FormRequest::class);
        });
    }

    /**
     * Get request authorization reflection.
     *
     * @return ReflectionMethod|null
     */
    public function reflectAuthorization()
    {
        if (! $param = $this->getRequestParameter()) {
            return;
        }

        return $this->reflectClassMethod(
            $this->reflectParameterClass($param),
            'authorize'
        );
    }

    /**
     * Get request rules reflection.
     *
     * @return ReflectionMethod|null
     */
    public function reflectRules()
    {
        if (! $param = $this->getRequestParameter()) {
            return;
        }

        return $this->reflectClassMethod(
            $this->reflectParameterClass($param),
            'rules'
        );
    }

    /**
     * Get request rules.
     *
     * @return array
     */
    public function getRules()
    {
        if (! $param = $this->getRequestParameter()) {
            return;
        }

        if (! $class = $this->paramTypeName($param)) {
            return;
        }

        return (new $class)->rules();
    }
}
