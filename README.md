# courier-paxy

## Methods

### Init

```php
    $courier = CourierFactory::create('Paxy', [
        'token'     => 'mytoken',
        'key'  => 'myorganizationid',
        'sandbox'   => true,
        'dispatch_point_id' => '1234567890',
        'cod' => true,
        'insurance' => false
    ]);
```

### CreateShipment

```php
    /**
     * Init Courier
     */
    $sender = $courier->makeSender();
    $sender->setFullName('Nazwa Firmy/Nadawca')
        ->setStreet('Ulica')
        ->setHouseNumber('2a')
        ->setApartmentNumber('1')
        ->setCity('Miasto')
        ->setZipCode('66-100')
        ->setCountry('Poland')
        ->setCountryCode('PL')
        ->setContactPerson('Jan Kowalski')
        ->setEmail('login@email.com')
        ->setPhone('48500600700');

    $receiver = $courier->makeReceiver();
    $receiver->setFirstName('Jan')
        ->setSurname('Nowak')
        ->setStreet('Ulica')
        ->setHouseNumber('15')
        ->setApartmentNumber('1896')
        ->setCity('Miasto')
        ->setZipCode('70-200')
        ->setCountry('Poland')
        ->setCountryCode('PL')
        ->setContactPerson('Jan Kowalski')
        ->setEmail('login@email.com')
        ->setPhone('48500600700');

    $parcel = $courier->makeParcel();
    $parcel->setWeight(2)
        ->setLength(8)
        ->setWidth(36)
        ->setHeight(64);

    $shipment = $courier->makeShipment();
    $shipment->setSender($sender)
        ->setReceiver($receiver)
        ->setParcel($parcel)
        ->setContent('Zawartość przesyłki');

    try {
        $response = $courier->createShipment($shipment);
        if ($response->hasErrors()) {
            var_dump($response->getFirstError()->getMessage());
        } else {
            var_dump($response->referenceId);
            var_dump($response->shipmentId); 
            var_dump($response->trackingId); 
        }
    } catch (\Exception $e) {
        var_dump($e->getMessage());
    }
```

### PostShipment

```php
    /**
     * Init Courier
     */
    $booking = $courier->makeBooking();
    $booking->setShipmentId('123456');
    $booking->setTrackingId('987654');
    try {
        $response = $courier->postShipment($booking);
        if($response->hasErrors()) {
            var_dump($response->getFirstError()->getMessage());
        } else {
            var_dump($response->shipmentId); 
            var_dump($response->trackingId); 
        }
    } catch (\Exception $e) {
        var_dump($e->getMessage());
    }
```

### GetStatus

```php
    /**
     * Init Courier
     */
    try {
        $response = $courier->getStatus('1234567890');
        if($response->hasErrors()) {
            var_dump($response->getFirstError()->getMessage());
        } else {
            var_dump((string) $response);
        }
    } catch (\Exception $e) {
        var_dump($e->getMessage());
    }
```


### GetLabel

```php
    /**
     * Init Courier
     */
    try {
        $response = $courier->getLabel('123456');
        if($response->hasErrors()) {
            var_dump($response->getFirstError()->getMessage());
        } else {
            var_dump((string) $response);
        }
    } catch (\Exception $e) {
        var_dump($e->getMessage());
    }
```


## Komendy

| KOMENDA | OPIS |
| ------ | ------ |
| composer tests | Testy |
| composer phpstan |  PHPStan |
| composer coverage | PHPUnit Coverage |
| composer coverage-html | PHPUnit Coverage HTML (DIR: ./coverage/) |
