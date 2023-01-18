<?php

namespace Conekta;

use Conekta\{Conekta, ConektaResource, Exceptions, Lang};

class DiscountLine extends ConektaResource
{
    public $code = '';
    public $amount = '';
    public $type = '';
    public $parentId = '';
    public $apiVersion = '';

    public function __get(string $property): ?string
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }
        return null;
    }

    public function __isset($property): bool
    {
        return isset($this->{$property});
    }

    public function instanceUrl(): string
    {
        $this->apiVersion = Conekta::$apiVersion;
        $id = $this->id;
        parent::idValidator($id);
        $class = get_class($this);
        $base = $this->classUrl($class);
        $extn = urlencode($id);
        $orderUrl = $this->order->instanceUrl();

        return $orderUrl . $base . "/{$extn}";
    }

    public function update($params = null): ConektaResource
    {
        return parent::_update($params);
    }

    public function delete(): ConektaResource
    {
        return parent::_delete('order', 'discount_lines');
    }
}
