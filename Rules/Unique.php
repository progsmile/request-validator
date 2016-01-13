<?php
namespace Progsmile\Validator\Rules;

class Unique implements RulesInterface
{
    private $params;

    public function fire()
    {
        $class = $this->params[1];

        $object = new $class;

        // if ( !$object instanceof IUniqueness ) {
        //     throw new \Exception('Model with unique validator should implement IUniqueness interface');
        // }

        $result = forward_static_call_array(
            $function = [
                $object,
                'count'
            ],
            $parameters = [
                [
                    'conditions' => $object->getUniqueFieldName() . ' = ?0',
                    'bind'       => [
                        $this->params[0]
                    ],
                ],
            ]
        );

        return $result == 0;
    }

    public function setParams($params)
    {
        $this->params = $params;
    }
}
