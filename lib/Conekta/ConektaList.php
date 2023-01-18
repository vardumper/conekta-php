<?php

namespace Conekta;

use Conekta\{Conekta, ConektaResource, Exceptions, Requestor, Util};

class ConektaList extends ConektaObject
{
    public const LIMIT = 5;

    public $elements_type;
    public array $params;
    public int $total;
    public bool $has_more;

    public function __construct($elements_type, $params = [])
    {
        parent::__construct();
        $this->elements_type = $elements_type;
        $this->params = $params;
        $this->total = 0;
    }

    public function addElement($element): self
    {
        $element = Util::convertToConektaObject($element);
        $array_length = count($this->_values);
        $this[$array_length] = $element;
        $this->_values[$array_length] = $element;
        $this->total = $this->total + 1;

        return $this;
    }

    public function loadFromArray(array $values = []): void
    {
        if (!empty($values)) {
            $this->has_more = $values['has_more'];
            $this->total = $values['total'];

            foreach ($this as $key => $value) {
                $this->_unsetKey($key);
            }
        }

        if (isset($values['data'])) {
            parent::loadFromArray($values['data']);
        }
    }

    public function next(array $options = ['limit' => self::LIMIT]): void
    {
        if (sizeOf($this) > 0) {
            $array = (array) $this;
            $this->params['next'] = end($array)->id;
        }

        $this->params['previous'] = null;
        $this->_moveCursor($options['limit']);
    }

    public function previous(array $options = ['limit' => self::LIMIT]): void
    {
        if (sizeOf($this) > 0) {
            $this->params['previous'] = $this[0]->id;
        }

        $this->params['next'] = null;

        $this->_moveCursor($options['limit']);
    }

    protected function _moveCursor($limit)
    {
        if (isset($limit)) {
            $this->params['limit'] = $limit;
        }

        $class = Util::$types[strtolower($this->elements_type)];
        $url = ConektaResource::classUrl($class);
        $requestor = new Requestor();
        $response = $requestor->request('get', $url, $this->params);

        return $this->loadFromArray($response);
    }
}
