[![License](https://poser.pugx.org/tcgunel/omnipay-sodexo/license)](https://packagist.org/packages/tcgunel/omnipay-sodexo)
[![Buy us a tree](https://img.shields.io/badge/Treeware-%F0%9F%8C%B3-lightgreen)](https://plant.treeware.earth/tcgunel/omnipay-sodexo)
[![PHP Composer](https://github.com/tcgunel/omnipay-sodexo/actions/workflows/tests.yml/badge.svg)](https://github.com/tcgunel/omnipay-sodexo/actions/workflows/tests.yml)

# Omnipay Sodexo Gateway
Omnipay gateway for Sodexo. All the methods of Sodexo implemented for easy usage.

## Requirements
| PHP       | Package |
|-----------|---------|
| ^7.4-^8.0 | v1.0.0  |

## Installment

```
composer require tcgunel/omnipay-sodexo
```

## Usage

Please see the [Wiki](https://github.com/tcgunel/omnipay-sodexo/wiki) page for detailed usage of every method.

## Methods
#### Auth Services

* login($options) // [Web servis token alım servisi](https://github.com/tcgunel/omnipay-sodexo/wiki/Login-Servisi)
* createActionToken($options) // [Action token alım servisi](https://github.com/tcgunel/omnipay-sodexo/wiki/Create-Action-Token-Servisi)

#### Make Payment / Purchase Services

* purchase($options) // [Ödeme servisi](https://github.com/tcgunel/omnipay-sodexo/wiki/Purchase---Make-Payment-Servisi)
* voidPayment($options) // [Günsonu öncesi ödeme iptali servisi](https://github.com/tcgunel/omnipay-sodexo/wiki/Void-Servisi)
* reversePayment($options) // [Satışın teknik iptali servisi](https://github.com/tcgunel/omnipay-sodexo/wiki/Reverse-Payment-Servisi)
* reverseOfVoidPayment($options) // [İptalin teknik iptali servisi](https://github.com/tcgunel/omnipay-sodexo/wiki/Reverse-of-Void-Servisi)


#### Refund Services

* refund($options) // [İade servisi](https://github.com/tcgunel/omnipay-sodexo/wiki/Refund-Servisi)


## Tests
```
composer test
```
For windows:
```
vendor\bin\paratest.bat
```

## Treeware

This package is [Treeware](https://treeware.earth). If you use it in production, then we ask that you [**buy the world a tree**](https://plant.treeware.earth/tcgunel/omnipay-sodexo) to thank us for our work. By contributing to the Treeware forest you’ll be creating employment for local families and restoring wildlife habitats.
