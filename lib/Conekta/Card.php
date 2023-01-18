<?php

namespace Conekta;

use Conekta\{Conekta, ConektaResource, Exceptions, Lang};

class Card extends ConektaResource
{
    public string $createdAt = '';
    public string $last4 = '';
    public string $bin = '';
    public string $name = '';
    public string $expMonth = '';
    public string $expYear = '';
    public string $brand = '';
    public string $parentId = '';
    public string $default = '';
    public string $apiVersion;

    public function __get($property): ?string
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
        $customerUrl = $this->customer->instanceUrl();

        return $customerUrl . $base . "/{$extn}";
    }

    public function update($params = null): self
    {
        return parent::_update($params);
    }

    public function delete(): self
    {
        return parent::_delete('customer', 'cards');
    }
}
