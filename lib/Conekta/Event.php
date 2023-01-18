<?php

namespace Conekta;

class Event extends ConektaResource
{
    public $data = '';
    public $livemode = '';
    public $webhookStatus = '';
    public $createdAt = '';
    public $type = '';

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

    public static function where(array $params = []): ConektaObject
    {
        $class = get_called_class();

        return parent::_scpWhere($class, $params);
    }

    /**
     * @deprecated
     */
    public static function all($params = null)
    {
        $class = get_called_class();

        return parent::_scpWhere($class, $params);
    }
}
