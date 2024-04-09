# Change Log for PayPal Checkout for OXID

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [2.0.13] -2024-04-09

- PayPal-Request-Id based on serialized body, no extra PayPal-Request-Id necessary anymore

## [2.0.12] -2024-03-19

- introduce central log functionality

## [2.0.11] -2024-03-15

- remove 500-redirect if some API Calls not work correctly
- introduce PayPal-Request-Id for api-calls

## [2.0.10] -2024-01-26

- add Vaulting to API-Calls
- add Debug-Logging for send-Requests

## [2.0.9] -2023-11-17

- add PartnerAttributionId to API-Calls

## [2.0.8] -2023-09-08

- Avoid PHP warning on non existing cache file

## [2.0.7] - 2023-01-02

- split Version to be compatible PHP >= 7.1
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
