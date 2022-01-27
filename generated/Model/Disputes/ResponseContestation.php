<?php

namespace OxidSolutionCatalysts\PayPalApi\Model\Disputes;

use JsonSerializable;
use OxidSolutionCatalysts\PayPalApi\Model\BaseModel;
use Webmozart\Assert\Assert;

/**
 * The contestation generated by PayPal and shared with the processor.
 *
 * generated from: response-contestation.json
 */
class ResponseContestation implements JsonSerializable
{
    use BaseModel;

    /** The contestation has been generated and shared with the processor. The processor must update the status with `ACCEPTED` or `DENIED` after processing the contestation. */
    public const STATUS_CONTESTED = 'CONTESTED';

    /** The processor has accepted the contestation. */
    public const STATUS_ACCEPTED = 'ACCEPTED';

    /** The processor has rejected the contestation. */
    public const STATUS_DENIED = 'DENIED';

    /**
     * An array of contestation documents.
     *
     * @var ResponseContestationDocument[]
     * maxItems: 1
     * maxItems: 100
     */
    public $documents;

    /**
     * The status of the contestation.
     *
     * use one of constants defined in this class to set the value:
     * @see STATUS_CONTESTED
     * @see STATUS_ACCEPTED
     * @see STATUS_DENIED
     * @var string | null
     * minLength: 1
     * maxLength: 255
     */
    public $status;

    /**
     * The date and time, in [Internet date and time format](https://tools.ietf.org/html/rfc3339#section-5.6).
     * Seconds are required while fractional seconds are optional.<blockquote><strong>Note:</strong> The regular
     * expression provides guidance but does not reject all invalid dates.</blockquote>
     *
     * @var string | null
     * minLength: 20
     * maxLength: 64
     */
    public $create_time;

    /**
     * The date and time, in [Internet date and time format](https://tools.ietf.org/html/rfc3339#section-5.6).
     * Seconds are required while fractional seconds are optional.<blockquote><strong>Note:</strong> The regular
     * expression provides guidance but does not reject all invalid dates.</blockquote>
     *
     * @var string | null
     * minLength: 20
     * maxLength: 64
     */
    public $update_time;

    public function validate($from = null)
    {
        $within = isset($from) ? "within $from" : "";
        Assert::notNull($this->documents, "documents in ResponseContestation must not be NULL $within");
        Assert::minCount(
            $this->documents,
            1,
            "documents in ResponseContestation must have min. count of 1 $within"
        );
        Assert::maxCount(
            $this->documents,
            100,
            "documents in ResponseContestation must have max. count of 100 $within"
        );
        Assert::isArray(
            $this->documents,
            "documents in ResponseContestation must be array $within"
        );
        if (isset($this->documents)) {
            foreach ($this->documents as $item) {
                $item->validate(ResponseContestation::class);
            }
        }
        !isset($this->status) || Assert::minLength(
            $this->status,
            1,
            "status in ResponseContestation must have minlength of 1 $within"
        );
        !isset($this->status) || Assert::maxLength(
            $this->status,
            255,
            "status in ResponseContestation must have maxlength of 255 $within"
        );
        !isset($this->create_time) || Assert::minLength(
            $this->create_time,
            20,
            "create_time in ResponseContestation must have minlength of 20 $within"
        );
        !isset($this->create_time) || Assert::maxLength(
            $this->create_time,
            64,
            "create_time in ResponseContestation must have maxlength of 64 $within"
        );
        !isset($this->update_time) || Assert::minLength(
            $this->update_time,
            20,
            "update_time in ResponseContestation must have minlength of 20 $within"
        );
        !isset($this->update_time) || Assert::maxLength(
            $this->update_time,
            64,
            "update_time in ResponseContestation must have maxlength of 64 $within"
        );
    }

    private function map(array $data)
    {
        if (isset($data['documents'])) {
            $this->documents = [];
            foreach ($data['documents'] as $item) {
                $this->documents[] = new ResponseContestationDocument($item);
            }
        }
        if (isset($data['status'])) {
            $this->status = $data['status'];
        }
        if (isset($data['create_time'])) {
            $this->create_time = $data['create_time'];
        }
        if (isset($data['update_time'])) {
            $this->update_time = $data['update_time'];
        }
    }

    public function __construct(array $data = null)
    {
        $this->documents = [];
        if (isset($data)) {
            $this->map($data);
        }
    }
}
