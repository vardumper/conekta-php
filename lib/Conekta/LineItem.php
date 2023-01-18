<?php

namespace Conekta;

use Conekta\{Conekta, ConektaResource, Exceptions, Lang};

class LineItem extends ConektaResource
{
    public $name = '';
    public $description = '';
    public $unitPrice = '';
    public $quantity = '';
    public $sku = '';
    public $shippable = '';
    public $tags = '';
    public $brand = '';
    public $type = '';
    public $parentId = '';

    protected string $apiVersion;

    public function __get(string $property): ?string
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }
        return null;
    }

    public function __isset(string $property): bool
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

    public function update($params = null)
    {
        return parent::_update($params);
    }

    public function delete()
    {
        return parent::_delete('order', 'line_items');
    }
}
