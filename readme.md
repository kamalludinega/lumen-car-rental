# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/lumen-framework/v/unstable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](http://lumen.laravel.com/docs).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

# Lumen Car Rental Documentation
Lumen Car Rental is example API application of car rental which built by Lumen (micro-framework of laravel)
click for demo https://lumen-car-rental.herokuapp.com/
### Get Client / Car / Rental
API to get client / car / rental data
```
Method      : GET

[CLIENT]
URL         : /api/v1/client
Response    : ['name','gender']

[CAR]
URL         : /api/v1/car
Response    : ['brand','type','year','color','plate']

[RENTAL]
URL         : /api/v1/rental
Response    : ['name','brand','type','plate','date-from','date-to']
```

### Create Client / Car / Rental
API to create client / car / rental data
```
Method      : POST

[CLIENT]
URL         : /api/v1/client
Parameter   : ['name','gender:male,female']
Validation  :
* All field is required
* Gender must be “male” or “female”

[CAR]
URL         : /api/v1/car
Parameter   : ['brand','type','year:2016','color','plate']
Validation  :
* All field is required
* Year cannot be future
* Plate is unique

[RENTAL]
URL         : /api/v1/rental
Parameter   : ['car-id','client-id','date-from:2011-11-11','date-to:2011-11-11']
Validation  :
* car-id and client-id must be exists.
* Client is not rent another car at selected rent date.
* All field is required
* Car is not rented at selected rent date.
* Rented duration max 3 days
* Rent date only between current day + 1 days until current date +7 days.
```

### Update Client / Car / Rental
API to update client / car /rental data
```
Method      : PUT

[CLIENT]
URL         : /api/v1/client/{id}
Parameter   : ['name','gender:male,female']
Validation  :
* All fields are required.
* Gender must be “male” or “female”
* The ID must be exists on the database

[CAR]
URL         : /api/v1/car/{id}
Parameter   : ['brand','type','year','color','plate']
Validation  :
* All field required
* Plate cannot be duplicated.
* Year cannot be future

[RENTAL]
URL         : /api/v1/rental/{id}
Parameter   : ['car-id','client-id','date-from:2011-11-11','date-to:2011-11-11']
Validation  :
* Car id and client id must be exists.
* Client is not rent another car at selected rent date.
* Car is not rented at selected rent date.
* Rented duration max 3 days
* Rent date only between current day + 1 days until current date +7 days.
```

### Delete Client / Car / Rental
API to delete client / car / rental data
```
Method      : DELETE

[CLIENT]
URL         : /api/v1/client/{id}
Validation  : id must be exist in database

[CAR]
URL         : /api/v1/car/{id}
Validation  : id must be exist in database

[RENTAL]
URL         : /api/v1/rental/{id}
Validation  : id must be exist in database
```

### Client / Car Rental Histories
API to get rental histories for specific client and within specified month for car histories
```
Method      : GET

[CLIENT]
URL         : /api/v1/histories/client/{id}
Validation  : id must be exist in database

[CAR]
URL         : /api/v1/histories/car/{id}
Parameter   : ['month:"12-2016"']
Validation  :
* The car ID must be exists on the database
* Month format must be `MM-YYYY`
```

### Rented Car
API to get rented car in specified date
```
Method      : GET
URL         : /api/v1/car/rented
Parameter   : ['month:"22-12-2016"']
Validation  :
* date format must be `DD-MM-YYYY`
```

### Available Car
API to get available car in specified date
```
Method      : GET
URL         : /api/v1/car/free
Parameter   : ['month:"22-12-2016"']
Validation  :
* date format must be `DD-MM-YYYY`
```