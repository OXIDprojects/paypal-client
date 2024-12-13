# Change Log for PayPal Checkout for OXID

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [1.0.12] - 2024-12-12

### FIX

- add possibility to ignore cached tokens. It helps e.g. for webhook registration
- introduce ActionHash to make the PayPal-Request-ID more unique

## [1.0.11] - 2024-09-09

### FIX

- Provide BN-Codes in showOrderDetails

## [1.0.10] - 2023-11-21

### FIX

- PHP-Error in File

## [1.0.9] -2023-11-17

- add PartnerAttributionId to API-Calls

## [1.0.8] -2023-09-08

- Avoid PHP warning on non existing cache file

## [1.0.7] - 2023-01-02

- Version compatible with PHP 7.0
- cache the token

## [1.0.6] - 2022-06-01

- Adapt PUI response mapping

## [1.0.5] - 2022-06-01

- add getter for PUI Bankdata

## [1.0.4] - 2022-05-24

- now backwardcompatible with PHP7.1

## [1.0.3] - 2022-04-05

- remove wrong PayPal Partner Attr Id

## [1.0.2] - 2022-04-05

- get MerchantInformations during onBoarding

## [1.0.1] - 2022-03-22

- add tax-rate in Order items

## [1.0.0] - 2022-03-10

### Changed
- initial release
