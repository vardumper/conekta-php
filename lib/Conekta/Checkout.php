<?php

namespace Conekta;

class Checkout extends ConektaResource
{
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

    public function loadFromArray($values = null): void
    {
        if (isset($values)) {
            parent::loadFromArray($values);
        }
    }

    public static function find($id): object
    {
        $class = get_called_class();

        return parent::_scpFind($class, $id);
    }

    public static function where($params = null): ConektaObject
    {
        $class = get_called_class();

        return parent::_scpWhere($class, $params);
    }

    public static function create($params = null): object
    {
        $class = get_called_class();

        return parent::_scpCreate($class, $params);
    }

    public function cancel($params = null): ConektaResource
    {
        return parent::_customAction('put', 'cancel', $params);
    }

    public function sendEmail($params = null): ConektaResource
    {
        return parent::_customAction('post', 'email', $params);
    }

    public function sendSms($params = null): ConektaResource
    {
        return parent::_customAction('post', 'sms', $params);
    }

    public static function all($params = null): ConektaObject
    {
        $class = get_called_class();

        return parent::_scpWhere($class, $params);
    }
}
