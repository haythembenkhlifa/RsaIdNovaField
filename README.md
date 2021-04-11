# RSA Id Nova Field

[![Packagist](https://img.shields.io/packagist/v/haythem/rsa-id-nova-field.svg)](https://packagist.org/packages/haythem/rsa-id-nova-field)
[![Packagist](https://img.shields.io/packagist/l/haythem/rsa-id-nova-field.svg)](https://packagist.org/packages/haythem/rsa-id-nova-field)

Package Description: A Laravel Nova field for RSA IdNumber Validation



![](animation.PNG)

## Installation

Install via composer
```bash
composer require haythem/rsa-id-nova-field
```

## Usage

```php
    use Haythem\RsaIdNovaField\RsaIdNovaField;
    
    public function fields(Request $request)
    {
     return [
       RsaIdNovaField::make("RSA ID")
            ->acceptFullIdOnly(false)
            ->acceptValidIdOnly(true) // add an extra validation rule
            ->showValidationIcon(true)
            ->showBirthDay(true)
            ->showGenderIcon(true)
            ->showCitizenship(true)
            ->showAge(true)
            ->storeAge("age")
            ->storeGender("gender")
    ];
    }

```
