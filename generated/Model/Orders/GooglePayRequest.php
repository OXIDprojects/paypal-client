<?php

namespace OxidSolutionCatalysts\PayPalApi\Model\Orders;

use JsonSerializable;
use OxidSolutionCatalysts\PayPalApi\Model\BaseModel;
use Webmozart\Assert\Assert;

/**
 * Information needed to pay using GooglePay.
 *
 * generated from: MerchantsCommonComponentsSpecification-v1-schema-apple_pay_request.json
 */
class GooglePayRequest implements JsonSerializable
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
     * @var string
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
     * @var Phone3 | null
     */
    public $phone_number;

    /**
     * The shipping details.
     *
     * @var ShippingDetail | null
     */
    public $shipping;

    /**
     * The payment card to use to fund a payment. Can be a credit or debit card.
     *
     * @var Card3 | null
     */
    public $card;

    public function validate($from = null)
    {
        $within = isset($from) ? "within $from" : "";
        !isset($this->id) || Assert::minLength(
            $this->id,
            1,
            "id in GooglePayRequest must have minlength of 1 $within"
        );
        !isset($this->id) || Assert::maxLength(
            $this->id,
            250,
            "id in GooglePayRequest must have maxlength of 250 $within"
        );
        Assert::notNull($this->token, "token in GooglePayRequest must not be NULL $within");
        Assert::minLength(
            $this->token,
            1,
            "token in GooglePayRequest must have minlength of 1 $within"
        );
        Assert::maxLength(
            $this->token,
            10000,
            "token in GooglePayRequest must have maxlength of 10000 $within"
        );
        !isset($this->name) || Assert::minLength(
            $this->name,
            3,
            "name in GooglePayRequest must have minlength of 3 $within"
        );
        !isset($this->name) || Assert::maxLength(
            $this->name,
            300,
            "name in GooglePayRequest must have maxlength of 300 $within"
        );
        !isset($this->email_address) || Assert::minLength(
            $this->email_address,
            3,
            "email_address in GooglePayRequest must have minlength of 3 $within"
        );
        !isset($this->email_address) || Assert::maxLength(
            $this->email_address,
            254,
            "email_address in GooglePayRequest must have maxlength of 254 $within"
        );
        !isset($this->phone_number) || Assert::isInstanceOf(
            $this->phone_number,
            Phone3::class,
            "phone_number in GooglePayRequest must be instance of Phone3 $within"
        );
        !isset($this->phone_number) ||  $this->phone_number->validate(GooglePayRequest::class);
        !isset($this->shipping) || Assert::isInstanceOf(
            $this->shipping,
            ShippingDetail::class,
            "shipping in GooglePayRequest must be instance of ShippingDetail $within"
        );
        !isset($this->shipping) ||  $this->shipping->validate(GooglePayRequest::class);
        !isset($this->card) || Assert::isInstanceOf(
            $this->card,
            Card3::class,
            "card in GooglePayRequest must be instance of Card3 $within"
        );
        !isset($this->card) ||  $this->card->validate(GooglePayRequest::class);
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
            $this->phone_number = new Phone3($data['phone_number']);
        }
        if (isset($data['shipping'])) {
            $this->shipping = new ShippingDetail($data['shipping']);
        }
        if (isset($data['card'])) {
            $this->card = new Card3($data['card']);
        }
    }

    public function __construct(array $data = null)
    {
        if (isset($data)) {
            $this->map($data);
        }
    }

    public function initPhoneNumber(): Phone3
    {
        return $this->phone_number = new Phone3();
    }

    public function initShipping(): ShippingDetail
    {
        return $this->shipping = new ShippingDetail();
    }

    public function initCard(): Card3
    {
        return $this->card = new Card3();
    }
}
