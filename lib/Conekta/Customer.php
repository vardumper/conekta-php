<?php

namespace Conekta;

class Customer extends ConektaResource
{
    public string $livemode = '';
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $defaultShippingContactId = '';
    public string $defaultPaymentSourceId = '';
    public string $referrer = '';
    public string $accountAge = '';
    public string $paidTransactions = '';
    public string $firstPaidAt = '';
    public string $corporate = '';

    protected const SUBMODELS_V2 = [
        'payment_sources',
        'shipping_contacts'
    ];
    protected const SUBMODELS_V1 = [
        'cards',
    ];

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

    public function loadFromArray(array $values = []): void
    {
        if (!empty($values)) {
            parent::loadFromArray($values);
        }

        if (Conekta::$apiVersion == '2.0.0') {
            foreach (self::SUBMODELS_V2 as $submodel) {
                if (isset($values[$submodel])) {
                    $submodel_list = new ConektaList($submodel, $values[$submodel]);
                    $submodel_list->loadFromArray($values[$submodel]);
                    $this->{$submodel}->_values = $submodel_list;
                } else {
                    $submodel_list = new ConektaList($submodel, []);
                }
                $this->{$submodel} = $submodel_list;

                foreach ($this->{$submodel} as $object => $val) {
                    $val->customer = $this;
                }
            }
        } else {
            foreach (self::SUBMODELS_V1 as $submodel) {
                if (isset($this->{$submodel})) {
                    $submodel_list = $this->{$submodel};

                    foreach ($submodel_list as $object => $val) {
                        if (isset($val->deleted) != true) {
                            $val->customer = $this;
                            $this->{$submodel}->_setVal($object, $val);
                        }
                    }
                }
            }
        }

        if (isset($this->subscription)) {
            $this->subscription->customer = $this;
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

    public static function create(array $params = []): object
    {
        $class = get_called_class();

        return parent::_scpCreate($class, $params);
    }

    public function delete(): ConektaResource
    {
        return parent::_delete();
    }

    public function update($params = null): ConektaResource
    {
        return parent::_update($params);
    }

    public function createPaymentSource(array $params = []): PaymentSource
    {
        return parent::_createMemberWithRelation('payment_sources', $params, $this);
    }

    public function createOfflineRecurrentReference(array $params = []): PaymentSource
    {
        return self::createPaymentSource($params);
    }

    public function deletePaymentSourceById($paymentSourceId): void
    {
        if (Conekta::$apiVersion == '2.0.0') {
            $currentCustomer = $this;
            $paymentSources = $currentCustomer->payment_sources;
            $index = 0;
            foreach ($paymentSources as $paymentSource) {
                if ($paymentSource->id == $paymentSourceId) {
                    $currentCustomer->payment_sources[$index]->delete();
                } else {
                    $index += 1;
                }
            }
        }
    }

    public function createCard($params = null)
    {
        return parent::_createMemberWithRelation('cards', $params, $this);
    }

    public function createSubscription($params = null)
    {
        return parent::_createMember('subscription', $params);
    }

    public function createShippingContact($params = null)
    {
        return parent::_createMemberWithRelation('shipping_contacts', $params, $this);
    }

    /**
     * @deprecated
     */
    public static function retrieve($id)
    {
        $class = get_called_class();

        return parent::_scpFind($class, $id);
    }

    public static function all($params = null)
    {
        $class = get_called_class();

        return parent::_scpWhere($class, $params);
    }
}
