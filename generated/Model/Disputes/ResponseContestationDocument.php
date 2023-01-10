<?php

namespace OxidSolutionCatalysts\PayPalApi\Model\Disputes;

use JsonSerializable;
use OxidSolutionCatalysts\PayPalApi\Model\BaseModel;
use Webmozart\Assert\Assert;

/**
 * A document that supports the contestation.
 *
 * generated from: response-contestation_document.json
 */
class ResponseContestationDocument implements JsonSerializable
{
    use BaseModel;

    /** The document is generated by `PayPal` as part of the contestation. */
    const SOURCE_PAYPAL = 'PAYPAL';

    /** The document is submitted by `SELLER` as evidence and is considered by PayPal for contestation. */
    const SOURCE_SELLER = 'SELLER';

    /**
     * The id which uniquely identifies a document.
     *
     * @var string | null
     * minLength: 1
     * maxLength: 255
     */
    public $id;

    /**
     * The contestation document name.
     *
     * @var string | null
     * minLength: 1
     * maxLength: 2000
     */
    public $name;

    /**
     * The contestation document URI.
     *
     * @var string | null
     */
    public $url;

    /**
     * The source from which the document was generated or submitted.
     *
     * use one of constants defined in this class to set the value:
     * @see SOURCE_PAYPAL
     * @see SOURCE_SELLER
     * @var string | null
     * minLength: 1
     * maxLength: 255
     */
    public $source;

    public function validate($from = null)
    {
        $within = isset($from) ? "within $from" : "";
        !isset($this->id) || Assert::minLength(
            $this->id,
            1,
            "id in ResponseContestationDocument must have minlength of 1 $within"
        );
        !isset($this->id) || Assert::maxLength(
            $this->id,
            255,
            "id in ResponseContestationDocument must have maxlength of 255 $within"
        );
        !isset($this->name) || Assert::minLength(
            $this->name,
            1,
            "name in ResponseContestationDocument must have minlength of 1 $within"
        );
        !isset($this->name) || Assert::maxLength(
            $this->name,
            2000,
            "name in ResponseContestationDocument must have maxlength of 2000 $within"
        );
        !isset($this->source) || Assert::minLength(
            $this->source,
            1,
            "source in ResponseContestationDocument must have minlength of 1 $within"
        );
        !isset($this->source) || Assert::maxLength(
            $this->source,
            255,
            "source in ResponseContestationDocument must have maxlength of 255 $within"
        );
    }

    private function map(array $data)
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['name'])) {
            $this->name = $data['name'];
        }
        if (isset($data['url'])) {
            $this->url = $data['url'];
        }
        if (isset($data['source'])) {
            $this->source = $data['source'];
        }
    }

    public function __construct(array $data = null)
    {
        if (isset($data)) {
            $this->map($data);
        }
    }
}
