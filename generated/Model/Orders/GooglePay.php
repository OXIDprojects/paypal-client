<?php

namespace OxidSolutionCatalysts\PayPalApi\Model\Orders;

use JsonSerializable;
use OxidSolutionCatalysts\PayPalApi\Model\BaseModel;
use Webmozart\Assert\Assert;

/**
 * Information needed to pay using GooglePay.
 *
 * generated from: MerchantsCommonComponentsSpecification-v1-schema-apple_pay.json
 */
class GooglePay implements JsonSerializable
{
    use BaseModel;

    /**
     * GooglePay transaction identifier, this will be the unique identifier for this transaction provided by Apple.
     *
     * @var string | null
     * minLength: 1
     * maxLength: 250
     */
    public $id;

    /**
     * Encrypted GooglePay token, containing card information. This token would be base64encoded.
     *
     * @var string | null
     * minLength: 1
     * maxLength: 10000
     */
    public $token;

    /**
     * The full name representation like Mr J Smith.
     *
     * @var string | null
     * minLength: 3
     * maxLength: 300
     */
    public $name;

    /**
     * The internationalized email address.<blockquote><strong>Note:</strong> Up to 64 characters are allowed before
     * and 255 characters are allowed after the <code>@</code> sign. However, the generally accepted maximum length
     * for an email address is 254 characters. The pattern verifies that an unquoted <code>@</code> sign
     * exists.</blockquote>
     *
     * @var string | null
     * minLength: 3
     * maxLength: 254
     */
    public $email_address;

    /**
     * The phone number, in its canonical international [E.164 numbering plan
     * format](https://www.itu.int/rec/T-REC-E.164/en).
     *
     * @var Phone2 | null
     */
    public $phone_number;

    /**
     * The payment card to use to fund a payment. Card can be a credit or debit card.
     *
     * @var CardResponse | null
     */
    public $card;

    public function validate($from = null)
    {
        $within = isset($from) ? "within $from" : "";
        !isset($this->id) || Assert::minLength(
            $this->id,
            1,
            "id in GooglePay must have minlength of 1 $within"
        );
        !isset($this->id) || Assert::maxLength(
            $this->id,
            250,
            "id in GooglePay must have maxlength of 250 $within"
        );
        !isset($this->token) || Assert::minLength(
            $this->token,
            1,
            "token in GooglePay must have minlength of 1 $within"
        );
        !isset($this->token) || Assert::maxLength(
            $this->token,
            10000,
            "token in GooglePay must have maxlength of 10000 $within"
        );
        !isset($this->name) || Assert::minLength(
            $this->name,
            3,
            "name in GooglePay must have minlength of 3 $within"
        );
        !isset($this->name) || Assert::maxLength(
            $this->name,
            300,
            "name in GooglePay must have maxlength of 300 $within"
        );
        !isset($this->email_address) || Assert::minLength(
            $this->email_address,
            3,
            "email_address in GooglePay must have minlength of 3 $within"
        );
        !isset($this->email_address) || Assert::maxLength(
            $this->email_address,
            254,
            "email_address in GooglePay must have maxlength of 254 $within"
        );
        !isset($this->phone_number) || Assert::isInstanceOf(
            $this->phone_number,
            Phone2::class,
            "phone_number in GooglePay must be instance of Phone2 $within"
        );
        !isset($this->phone_number) ||  $this->phone_number->validate(GooglePay::class);
        !isset($this->card) || Assert::isInstanceOf(
            $this->card,
            CardResponse::class,
            "card in GooglePay must be instance of CardResponse $within"
        );
        !isset($this->card) ||  $this->card->validate(GooglePay::class);
    }

    private function map(array $data)
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['token'])) {
            $this->token = $data['token'];
        }
        if (isset($data['name'])) {
            $this->name = $data['name'];
        }
        if (isset($data['email_address'])) {
            $this->email_address = $data['email_address'];
        }
        if (isset($data['phone_number'])) {
            $this->phone_number = new Phone2($data['phone_number']);
        }
        if (isset($data['card'])) {
            $this->card = new CardResponse($data['card']);
        }
    }

    public function __construct(array $data = null)
    {
        if (isset($data)) {
            $this->map($data);
        }
    }

    public function initPhoneNumber(): Phone2
    {
        return $this->phone_number = new Phone2();
    }

    public function initCard(): CardResponse
    {
        return $this->card = new CardResponse();
    }
}
