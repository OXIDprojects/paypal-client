<?php

namespace OxidSolutionCatalysts\PayPalApi\Service;

use OxidSolutionCatalysts\PayPalApi\Exception\ApiException;
use OxidSolutionCatalysts\PayPalApi\Model\TransactionSearch\BalancesResponse;
use OxidSolutionCatalysts\PayPalApi\Model\TransactionSearch\PartnerPartnerSearchResponse;
use OxidSolutionCatalysts\PayPalApi\Model\TransactionSearch\SearchResponse;

class TransactionSearch extends BaseService
{
    protected string $basePath = '/v1/reporting';

    /**
     * Lists transactions. Specify one or more query parameters to filter the transaction that appear in the
     * response.<blockquote><strong>Notes:</strong> <ul><li>If you specify one or more optional query parameters, the
     * <code>ending_balance</code> response field is empty.</li><li>It takes a maximum of three hours for executed
     * transactions to appear in the list transactions call.</li><li>This call lists transaction for the previous
     * three years.</li></ul></blockquote>
     *
     * @param $transactionId string Filters the transactions in the response by a PayPal transaction ID. A valid
     * transaction ID is 17 characters long, except for an <a href="/docs/api/payments/v1/#definition-order">order
     * ID</a>, which is 19 characters long.<blockquote><strong>Note:</strong> A transaction ID is not unique in the
     * reporting system. The response can list two transactions with the same ID. One transaction can be balance
     * affecting while the other is non-balance affecting.</blockquote>
     *
     * @param $transactionType string Filters the transactions in the response by a PayPal transaction event code.
     * See [Transaction event codes](/docs/integration/direct/transaction-search/transaction-event-codes/).
     *
     * @param $transactionStatus string Filters the transactions in the response by a PayPal transaction status code.
     * Value
     * is:<table><thead><tr><th>Status&nbsp;code</th><th>Description</th></tr></thead><tbody><tr><td><code>D</code></td><td>PayPal
     * or merchant rules denied the transaction.</td></tr><tr><td><code>P</code></td><td>The transaction is pending.
     * The transaction was created but waits for another payment process to complete, such as an ACH transaction,
     * before the status changes to <code>S</code>.</td></tr><tr><td><code>S</code></td><td>The transaction
     * successfully completed without a denial and after any pending
     * statuses.</td></tr><tr><td><code>V</code></td><td>A successful transaction was reversed and funds were
     * refunded to the original sender.</td></tr></tbody></table>
     *
     * @param $transactionAmount string Filters the transactions in the response by a gross transaction amount range.
     * Specify the range as `<start-range> TO <end-range>`, where `<start-range>` is the lower limit of the gross
     * PayPal transaction amount and `<end-range>` is the upper limit of the gross transaction amount. Specify the
     * amounts in lower denominations. For example, to search for transactions from $5.00 to $10.05, specify `[500 TO
     * 1005]`.<blockquote><strong>Note:</strong>The values must be URL encoded.</blockquote>
     *
     * @param $transactionCurrency string Filters the transactions in the response by a [three-character ISO-4217
     * currency code](/docs/api/reference/currency-codes/) for the PayPal transaction currency.
     *
     * @param $transactionDate string Deprecated. Filters the transactions in the response by a PayPal transaction
     * date, in [Internet date and time format](https://tools.ietf.org/html/rfc3339#section-5.6), with the from and
     * to dates in ISO, URL-encoded format. For example: `[2012-12-15T17:55:29+0530 TO 2013-01-15T17:55:29+0530]`.
     * The maximum supported range is 31 days. The maximum number of records in a single request is 10,000. If the
     * account has more than 10,000 records for a specified date range, shorten the date range. The format of a date
     * and time object
     * is:<pre><var>YYYY</var>-<var>MM</var>-<var>DD</var>T<var>HH</var>:<var>MM</var>:<var>SS</var><var>OFFSET</var></pre>The
     * maximum length is 25 characters. For
     * example:<pre>2016-10-15T13:07:00+0000</pre>Where:<ul><li><code><var>YYYY</var></code>. The
     * year.</li><li><code><var>MM</var></code>. The month.</li><li><code><var>DD</var></code>. The
     * date.</li><li><code>T</code>. The actual character, <code>T</code>.</li><li><code><var>HH</var></code>. The
     * hour in 24-hour format.</li><li><code><var>MM</var></code>. The minutes.</li><li><code><var>SS</var></code>
     * The seconds.</li><li><code><var>OFFSET</var></code>. The five-character signed offset from GMT. For example,
     * <code>+0800</code>.</li></ul>
     *
     * @param $startDate string Filters the transactions in the response by a start date and time, in [Internet date
     * and time format](https://tools.ietf.org/html/rfc3339#section-5.6). Seconds are required. Fractional seconds
     * are optional.
     *
     * @param $endDate string Filters the transactions in the response by an end date and time, in [Internet date and
     * time format](https://tools.ietf.org/html/rfc3339#section-5.6). Seconds are required. Fractional seconds are
     * optional. The maximum supported range is 31 days.
     *
     * @param $paymentInstrumentType string Filters the transactions in the response by a payment instrument type.
     * Value is either:<ul><li><code>CREDITCARD</code>. Returns a direct credit card transaction with a corresponding
     * value.</li><li><code>DEBITCARD</code>. Returns a debit card transaction with a corresponding
     * value.</li></ul>If you omit this parameter, the API does not apply this filter.
     *
     * @param $storeId string Filters the transactions in the response by a store ID.
     *
     * @param $terminalId string Filters the transactions in the response by a terminal ID.
     *
     * @param $page integer The zero-relative start index of the entire list of items that are returned in the
     * response. So, the combination of `page=1` and `page_size=20` returns the first 20 items.
     *
     * @param $pageSize integer The number of items to return in the response. So, the combination of `page=1` and
     * `page_size=20` returns the first 20 items. The combination of `page=2` and `page_size=20` returns the next 20
     * items.
     *
     * @param $balanceAffectingRecordsOnly string Indicates whether the response includes only balance-impacting
     * transactions or all transactions. Value is either:<ul><li><code>Y</code>. The default. The response includes
     * only balance transactions.</li><li><code>N</code>. The response includes all transactions.</li></ul>
     *
     * @param $fields string Indicates which fields appear in the response. Value is a single field or a
     * comma-separated list of fields. The <code>transaction_info</code> value returns only the transaction details
     * in the response. To include all fields in the response, specify <code>fields=all</code>. Valid fields
     * are:<ul><li><a
     * href="/docs/api/transaction-search/v1/#definition-transaction_info"><code>transaction_info</code></a>. The
     * transaction information. Includes the ID of the PayPal account of the payee, the PayPal-generated transaction
     * ID, the PayPal-generated base ID, the PayPal reference ID type, the transaction event code, the date and time
     * when the transaction was initiated and was last updated, the transaction amounts including the PayPal fee, any
     * discounts, insurance, the transaction status, and other information about the transaction.</li><li><a
     * href="/docs/api/transaction-search/v1/#definition-payer_info"><code>payer_info</code></a>. The payer
     * information. Includes the PayPal customer account ID and the payer's email address, primary phone number,
     * name, country code, address, and whether the payer is verified or unverified.</li><li><a
     * href="/docs/api/transaction-search/v1/#definition-shipping_info"><code>shipping_info</code></a>. The shipping
     * information. Includes the recipient's name, the shipping method for this order, the shipping address for this
     * order, and the secondary address associated with this order.</li><li><a
     * href="/docs/api/transaction-search/v1/#definition-auction_info"><code>auction_info</code></a>. The auction
     * information. Includes the name of the auction site, the auction site URL, the ID of the customer who makes the
     * purchase in the auction, and the date and time when the auction closes.</li><li><a
     * href="/docs/api/transaction-search/v1/#definition-cart_info"><code>cart_info</code></a>. The cart information.
     * Includes an array of item details, whether the item amount or the shipping amount already includes tax, and
     * the ID of the invoice for PayPal-generated invoices.</li><li><a
     * href="/docs/api/transaction-search/v1/#definition-incentive_info"><code>incentive_info</code></a>. An array of
     * incentive detail objects. Each object includes the incentive, such as a special offer or coupon, the incentive
     * amount, and the incentive program code that identifies a merchant loyalty or incentive program.</li><li><a
     * href="/docs/api/transaction-search/v1/#definition-store_info"><code>store_info</code></a>. The store
     * information. Includes the ID of the merchant store and the terminal ID for the checkout stand in the merchant
     * store.</li></ul>
     *
     * @throws ApiException
     * @return SearchResponse
     */
    public function listTransactions(
        $transactionId,
        $transactionType,
        $transactionStatus,
        $transactionAmount,
        $transactionCurrency,
        $transactionDate,
        $startDate,
        $endDate,
        $paymentInstrumentType,
        $storeId,
        $terminalId,
        $page = 1,
        $pageSize = 100,
        $balanceAffectingRecordsOnly = 'Y',
        $fields = 'transaction_info'
    ): SearchResponse {
        $path = "/transactions";


        $params = [];
        $params['transaction_id'] = $transactionId;
        $params['transaction_type'] = $transactionType;
        $params['transaction_status'] = $transactionStatus;
        $params['transaction_amount'] = $transactionAmount;
        $params['transaction_currency'] = $transactionCurrency;
        $params['transaction_date'] = $transactionDate;
        $params['start_date'] = $startDate;
        $params['end_date'] = $endDate;
        $params['payment_instrument_type'] = $paymentInstrumentType;
        $params['store_id'] = $storeId;
        $params['terminal_id'] = $terminalId;
        $params['page'] = $page;
        $params['page_size'] = $pageSize;
        $params['balance_affecting_records_only'] = $balanceAffectingRecordsOnly;
        $params['fields'] = $fields;

        $body = null;
        $response = $this->send('GET', $path, $params, [], $body);
        $jsonData = json_decode($response->getBody(), true);
        return new SearchResponse($jsonData);
    }

    /**
     * List all balances. Specify date time to list balances for that time that appear in the
     * response.<blockquote><strong>Notes:</strong> <ul><li>It takes a maximum of three hours for balances to appear
     * in the list balances call.</li><li>This call lists balances upto the previous three
     * years.</li></ul></blockquote>
     *
     * @param $asOfTime string List balances in the response at the date time provided, will return the last
     * refreshed balance in the system when not provided.
     *
     * @param $currencyCode string Filters the transactions in the response by a [three-character ISO-4217 currency
     * code](/docs/api/reference/currency-codes/) for the PayPal transaction currency.
     *
     * @param $includeCryptoCurrencies boolean Indicates whether the response list balances including crypto
     * transactions. Value is either:<ul><li><code>false</code>. The default. The response doesn't include crypto
     * transactions.</li><li><code>true</code>. The response also includes crypto transactions.</li></ul>
     *
     * @throws ApiException
     * @return BalancesResponse
     */
    public function listAllBalances($asOfTime, $currencyCode, $includeCryptoCurrencies = 'false'): BalancesResponse
    {
        $path = "/balances";


        $params = [];
        $params['as_of_time'] = $asOfTime;
        $params['currency_code'] = $currencyCode;
        $params['include_crypto_currencies'] = var_export($includeCryptoCurrencies, true);

        $body = null;
        $response = $this->send('GET', $path, $params, [], $body);
        $jsonData = json_decode($response->getBody(), true);
        return new BalancesResponse($jsonData);
    }

    /**
     * Lists partner transactions. Specify one or more query parameters to filter the transaction that appear in the
     * response.<blockquote><strong>Notes:</strong> <ul><li>It takes a maximum of six hours for executed transactions
     * to appear in the list partner transactions call.</li></ul></blockquote>
     *
     * @param $integrationId string Filter the transactions in the response for integration id (BNCODE). Value is a
     * single integration id or a comma-separated list of integration ids.
     *
     * @param $accountId string Filter the transactions in the response for Merchant payer ID.
     *
     * @param $startTime string Filters the transactions in the response by a start date and time, in [Internet date
     * and time format](https://tools.ietf.org/html/rfc3339#section-5.6). Seconds are required. Fractional seconds
     * are optional. The maximum supported date range between start_time and end_time is 31 days.
     *
     * @param $endTime string Filters the transactions in the response by an end date and time, in [Internet date and
     * time format](https://tools.ietf.org/html/rfc3339#section-5.6). Seconds are required. Fractional seconds are
     * optional. The maximum supported date range between start_time and end_time is 31 days.
     *
     * @param $page integer The zero-relative start index of the entire list of items that are returned in the
     * response. So, the combination of `page=1` and `page_size=20` returns the first 20 items.
     *
     * @param $pageSize integer The number of items to return in the response. So, the combination of `page=1` and
     * `page_size=20` returns the first 20 items. The combination of `page=2` and `page_size=20` returns the next 20
     * items.
     *
     * @throws ApiException
     * @return PartnerPartnerSearchResponse
     */
    public function listPartnerTransactions($integrationId, $accountId, $startTime, $endTime, $page = 1, $pageSize = 100): PartnerPartnerSearchResponse
    {
        $path = "/partner-transactions";


        $params = [];
        $params['integration_id'] = $integrationId;
        $params['account_id'] = $accountId;
        $params['start_time'] = $startTime;
        $params['end_time'] = $endTime;
        $params['page'] = $page;
        $params['page_size'] = $pageSize;

        $body = null;
        $response = $this->send('GET', $path, $params, [], $body);
        $jsonData = json_decode($response->getBody(), true);
        return new PartnerPartnerSearchResponse($jsonData);
    }
}
