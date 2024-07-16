<?php

namespace OxidSolutionCatalysts\PayPalApi\Service;

use OxidSolutionCatalysts\PayPalApi\Exception\ApiException;
use OxidSolutionCatalysts\PayPalApi\Model\Subscriptions\Plan;
use OxidSolutionCatalysts\PayPalApi\Model\Subscriptions\PlanCollection;
use OxidSolutionCatalysts\PayPalApi\Model\Subscriptions\PlanRequestPOST;
use OxidSolutionCatalysts\PayPalApi\Model\Subscriptions\Subscription;
use OxidSolutionCatalysts\PayPalApi\Model\Subscriptions\SubscriptionActivateRequest;
use OxidSolutionCatalysts\PayPalApi\Model\Subscriptions\SubscriptionCancelRequest;
use OxidSolutionCatalysts\PayPalApi\Model\Subscriptions\SubscriptionCaptureRequest;
use OxidSolutionCatalysts\PayPalApi\Model\Subscriptions\SubscriptionCollection;
use OxidSolutionCatalysts\PayPalApi\Model\Subscriptions\SubscriptionRequestPost;
use OxidSolutionCatalysts\PayPalApi\Model\Subscriptions\SubscriptionReviseRequest;
use OxidSolutionCatalysts\PayPalApi\Model\Subscriptions\SubscriptionReviseResponse;
use OxidSolutionCatalysts\PayPalApi\Model\Subscriptions\SubscriptionSaveRequest;
use OxidSolutionCatalysts\PayPalApi\Model\Subscriptions\SubscriptionSuspendRequest;
use OxidSolutionCatalysts\PayPalApi\Model\Subscriptions\TransactionsList;
use OxidSolutionCatalysts\PayPalApi\Model\Subscriptions\UpdatePricingSchemesListRequest;

class Subscriptions extends BaseService
{
    protected string $basePath = '/v1/billing';

    /**
     * Creates a plan that defines pricing and billing cycle details for subscriptions.
     *
     * @param $planRequest mixed
     *
     * @param $prefer string The preferred server response upon successful completion of the request. Value
     * is:<ul><li><code>return=minimal</code>. The server returns a minimal response to optimize communication
     * between the API caller and the server. A minimal response includes the <code>id</code>, <code>status</code>
     * and HATEOAS links.</li><li><code>return=representation</code>. The server returns a complete resource
     * representation, including the current state of the resource.</li></ul>
     *
     * @throws ApiException
     * @return Plan
     */
    public function createPlan(PlanRequestPOST $planRequest, $prefer = 'return=minimal'): Plan
    {
        $path = "/plans";

        $headers = [];
        $headers['Content-Type'] = 'application/json';
        $headers['Prefer'] = $prefer;


        $body = json_encode($planRequest, true);
        $response = $this->send('POST', $path, [], $headers, $body);
        $jsonData = json_decode($response->getBody(), true);
        return new Plan($jsonData);
    }

    /**
     * Lists billing plans.
     *
     * @param $payPalSubjectAccount string An internal only header to pass a target account number to API. The value
     * of the header is a CSV (aka Comma separated values).
     *
     * @param $productId string Filters the response by a Product ID.
     *
     * @param $planIds string Filters the response by list of plan IDs. Filter supports upto 10 plan IDs.
     *
     * @param $sortOrder string Sort order of the plans that are returned in the response. Valid values are asc and
     * desc.
     *
     * @param $statuses string Filters the response by list of plan statuses.
     *
     * @param $sortBy string Sort the plans result. Valid values are create_time and status. If not set, the result
     * will be sorted by create_time.
     *
     * @param $totalRequired boolean Indicates whether to show the total count in the response.
     *
     * @param $page integer A non-zero integer which is the start index of the entire list of items to return in the
     * response. The combination of `page=1` and `page_size=20` returns the first 20 items. The combination of
     * `page=2` and `page_size=20` returns the next 20 items.
     *
     * @param $pageSize integer The number of items to return in the response.
     *
     * @param $prefer string The preferred server response upon successful completion of the request. Value
     * is:<ul><li><code>return=minimal</code>. The server returns a minimal response to optimize communication
     * between the API caller and the server. A minimal response includes the <code>id</code>, <code>name</code>,
     * <code>description</code> and HATEOAS links.</li><li><code>return=representation</code>. The server returns a
     * complete resource representation, including the current state of the resource.</li></ul>
     *
     * @throws ApiException
     * @return PlanCollection
     */
    public function listPlans(
        $payPalSubjectAccount,
        $productId,
        $planIds,
        $sortOrder,
        $statuses,
        $sortBy = 'create_time',
        $totalRequired = false,
        $page = 1,
        $pageSize = 10,
        $prefer = 'return=minimal'
    ): PlanCollection {
        $path = "/plans";

        $headers = [];
        $headers['PayPal-Subject-Account'] = $payPalSubjectAccount;
        $headers['Prefer'] = $prefer;

        $params = [];
        $params['product_id'] = $productId;
        $params['plan_ids'] = $planIds;
        $params['sort_order'] = $sortOrder;
        $params['statuses'] = $statuses;
        $params['sort_by'] = $sortBy;
        $params['total_required'] = var_export($totalRequired, true);
        $params['page'] = $page;
        $params['page_size'] = $pageSize;

        $body = null;
        $response = $this->send('GET', $path, $params, $headers, $body);
        $jsonData = json_decode($response->getBody(), true);
        return new PlanCollection($jsonData);
    }

    /**
     * Shows details for a plan, by ID.
     *
     * @param $payPalSubjectAccount string An internal only header to pass a target account number to API. The value
     * of the header is a CSV (aka Comma separated values).
     *
     * @param $id string The ID of the plan.
     *
     * @param $version integer The version of the plan.
     *
     * @param $fields string List of fields that are to be returned in the response. Possible value for field is
     * <code>Product</code>.
     *
     * @throws ApiException
     * @return Plan
     */
    public function showPlanDetails($payPalSubjectAccount, $id, $version, $fields): Plan
    {
        $path = "/plans/{$id}";

        $headers = [];
        $headers['PayPal-Subject-Account'] = $payPalSubjectAccount;

        $params = [];
        $params['version'] = $version;
        $params['fields'] = $fields;

        $body = null;
        $response = $this->send('GET', $path, $params, $headers, $body);
        $jsonData = json_decode($response->getBody(), true);
        return new Plan($jsonData);
    }

    /**
     * Updates a plan with the `CREATED` or `ACTIVE` status. For an `INACTIVE` plan, you can make only status
     * updates.<br/>You can patch these attributes and objects:<table><thead><tr><th>Attribute or
     * object</th><th>Operations</th></tr></thead><tbody><tr><td><code>description</code></td><td>replace</td></tr><tr><td><code>payment_preferences.auto_bill_outstanding</code></td><td>replace</td></tr><tr><td><code>taxes.percentage</code></td><td>replace</td></tr><tr><td><code>payment_preferences.payment_failure_threshold</code></td><td>replace</td></tr><tr><td><code>payment_preferences.setup_fee</code></td><td>replace</td></tr><tr><td><code>payment_preferences.setup_fee_failure_action</code></td><td>replace</td></tr><tr><td><code>name</code></td><td>replace</td></tr></tbody></table>
     *
     * @param $id string The ID of the plan.
     *
     * @param $patchRequest mixed
     *
     * @throws ApiException
     * @return void
     */
    public function updatePlan($id, array $patchRequest): void
    {
        $path = "/plans/{$id}";

        $headers = [];
        $headers['Content-Type'] = 'application/json';


        $body = json_encode($patchRequest, true);
        $response = $this->send('PATCH', $path, [], $headers, $body);
    }

    /**
     * Replaces an entire plan with the `CREATED` status.
     *
     * @param $id string The ID of the plan.
     *
     * @param $plan mixed
     *
     * @throws ApiException
     * @return void
     */
    public function replacePlan($id, Plan $plan): void
    {
        $path = "/plans/{$id}";

        $headers = [];
        $headers['Content-Type'] = 'application/json';


        $body = json_encode($plan, true);
        $response = $this->send('PUT', $path, [], $headers, $body);
    }

    /**
     * Activates a plan, by ID.
     *
     * @param $id string The ID of the plan.
     *
     * @throws ApiException
     * @return void
     */
    public function activatePlan($id): void
    {
        $path = "/plans/{$id}/activate";



        $body = null;
        $response = $this->send('POST', $path, [], [], $body);
    }

    /**
     * Deactivates a plan, by ID.
     *
     * @param $id string The ID of the plan.
     *
     * @throws ApiException
     * @return void
     */
    public function deactivatePlan($id): void
    {
        $path = "/plans/{$id}/deactivate";



        $body = null;
        $response = $this->send('POST', $path, [], [], $body);
    }

    /**
     * Updates pricing for a plan. For example, you can update a regular billing cycle from $5 per month to $7 per
     * month.
     *
     * @param $id string The ID for the plan.
     *
     * @param $updatePricingSchemesListRequest mixed
     *
     * @throws ApiException
     * @return void
     */
    public function updatePricing($id, UpdatePricingSchemesListRequest $updatePricingSchemesListRequest): void
    {
        $path = "/plans/{$id}/update-pricing-schemes";

        $headers = [];
        $headers['Content-Type'] = 'application/json';


        $body = json_encode($updatePricingSchemesListRequest, true);
        $response = $this->send('POST', $path, [], $headers, $body);
    }

    /**
     * Creates a subscription.
     *
     * @param $subscriptionRequest mixed
     *
     * @param $prefer string The preferred server response upon successful completion of the request. Value
     * is:<ul><li><code>return=minimal</code>. The server returns a minimal response to optimize communication
     * between the API caller and the server. A minimal response includes the <code>id</code>, <code>status</code>
     * and HATEOAS links.</li><li><code>return=representation</code>. The server returns a complete resource
     * representation, including the current state of the resource.</li></ul>
     *
     * @throws ApiException
     * @return Subscription
     */
    public function createSubscription(SubscriptionRequestPost $subscriptionRequest, $prefer = 'return=minimal'): Subscription
    {
        $path = "/subscriptions";

        $headers = [];
        $headers['Content-Type'] = 'application/json';
        $headers['Prefer'] = $prefer;


        $body = json_encode($subscriptionRequest, true);
        $response = $this->send('POST', $path, [], $headers, $body);
        $jsonData = json_decode($response->getBody(), true);
        return new Subscription($jsonData);
    }

    /**
     * List all subscriptions for merchant account.
     *
     * @param $planIds string Filters the response by list of plan IDs. Filter supports upto <code>70</code> plan
     * IDs. URLs should not exceed a length of <code>2000</code> characters.
     *
     * @param $statuses string Filters the response by list of subscription statuses.
     *
     * @param $createdAfter string Filters the response by subscription creation start time for a range of
     * subscriptions.
     *
     * @param $createdBefore string Filters the response by subscription creation end time for a range of
     * subscriptions.
     *
     * @param $statusUpdatedBefore string Filters the response by status update start time for a range of
     * subscriptions.
     *
     * @param $statusUpdatedAfter string Filters the response by status update end time for a range of subscriptions.
     *
     * @param $filter string Filter the response using complex expressions that could use comparison operators like
     * ge, gt, le, lt and logical operators such as 'and' and 'or'.
     *
     * @param $payerIds string Filters the response by list of payer IDs.
     *
     * @param $sortOrder string Sort order of the subscriptions that are returned in the response. Valid values are
     * asc and desc.
     *
     * @param $sortBy string Sort the subscriptions result. Valid or default value is create_time. If not set, the
     * result will be sorted by create_time.
     *
     * @param $totalRequired boolean Indicates whether to show the total count in the response.
     *
     * @param $page integer A non-zero integer which is the start index of the entire list of items to return in the
     * response. The combination of `page=1` and `page_size=20` returns the first 20 items. The combination of
     * `page=2` and `page_size=20` returns the next 20 items.
     *
     * @param $pageSize integer The number of items to return in the response.
     *
     * @throws ApiException
     * @return SubscriptionCollection
     */
    public function listSubscriptions(
        $planIds,
        $statuses,
        $createdAfter,
        $createdBefore,
        $statusUpdatedBefore,
        $statusUpdatedAfter,
        $filter,
        $payerIds,
        $sortOrder,
        $sortBy = 'create_time',
        $totalRequired = false,
        $page = 1,
        $pageSize = 10
    ): SubscriptionCollection {
        $path = "/subscriptions";


        $params = [];
        $params['plan_ids'] = $planIds;
        $params['statuses'] = $statuses;
        $params['created_after'] = $createdAfter;
        $params['created_before'] = $createdBefore;
        $params['status_updated_before'] = $statusUpdatedBefore;
        $params['status_updated_after'] = $statusUpdatedAfter;
        $params['filter'] = $filter;
        $params['payer_ids'] = $payerIds;
        $params['sort_order'] = $sortOrder;
        $params['sort_by'] = $sortBy;
        $params['total_required'] = var_export($totalRequired, true);
        $params['page'] = $page;
        $params['page_size'] = $pageSize;

        $body = null;
        $response = $this->send('GET', $path, $params, [], $body);
        $jsonData = json_decode($response->getBody(), true);
        return new SubscriptionCollection($jsonData);
    }

    /**
     * Shows details for a subscription, by ID.
     *
     * @param $id string The ID of the subscription.
     *
     * @param $fields string List of fields that are to be returned in the response. Possible value for fields are
     * last_failed_payment and plan.
     *
     * @throws ApiException
     * @return Subscription
     */
    public function showSubscriptionDetails($id, $fields): Subscription
    {
        $path = "/subscriptions/{$id}";


        $params = [];
//        $params['fields'] = $fields;

        $body = null;
        $response = $this->send('GET', $path, $params, [], $body);
        $jsonData = json_decode($response->getBody(), true);
        return new Subscription($jsonData);
    }

    /**
     * Updates a subscription which could be in <code>ACTIVE</code> or <code>SUSPENDED</code> status. You can
     * override plan level default attributes by providing customised values for plan path in the patch request.<br
     * /> <ul> <li>You cannot update attributes that have already completed (Example - trial cycles can’t be
     * updated if completed).</li> <li>Once overridden, changes to plan resource will not impact subscription.</li>
     * <li>Any price update will not impact billing cycles within next 10 days (Applicable only for subscriptions
     * funded by PayPal account).</li> </ul> Following are the fields eligible for
     * patch.<table><thead><tr><th>Attribute or
     * object</th><th>Operations</th></tr></thead><tbody><tr><td><code>billing_info.outstanding_balance</code></td><td>replace</td></tr><tr><td><code>custom_id</code></td><td>add,replace</td></tr><tr><td><code>plan.billing_cycles[@sequence==n].<br/>pricing_scheme.fixed_price</code></td><td>add,replace</td></tr><tr><td><code>plan.billing_cycles[@sequence==n].<br/>pricing_scheme.tiers</code></td><td>replace</td></tr><tr><td><code>plan.billing_cycles[@sequence==n].<br/>total_cycles</code></td><td>replace</td></tr><tr><td><code>plan.payment_preferences.<br/>auto_bill_outstanding</code></td><td>replace</td></tr><tr><td><code>plan.payment_preferences.<br/>payment_failure_threshold</code></td><td>replace</td></tr><tr><td><code>plan.taxes.inclusive</code></td><td>add,replace</td></tr><tr><td><code>plan.taxes.percentage</code></td><td>add,replace</td></tr><tr><td><code>shipping_amount</code></td><td>add,replace</td></tr><tr><td><code>start_time</code></td><td>replace</td></tr><tr><td><code>subscriber.shipping_address</code></td><td>add,replace</td></tr><tr><td><code>subscriber.payment_source
     * (for subscriptions funded<br/>by card payments)</code></td><td>replace</td></tr></tbody></table>
     *
     * @param $id string The ID for the subscription.
     *
     * @param $patchRequest mixed
     *
     * @throws ApiException
     * @return void
     */
    public function updateSubscription($id, array $patchRequest): void
    {
        $path = "/subscriptions/{$id}";

        $headers = [];
        $headers['Content-Type'] = 'application/json';


        $body = json_encode($patchRequest, true);
        $response = $this->send('PATCH', $path, [], $headers, $body);
    }

    /**
     * Updates the quantity of the product or service in a subscription. You can also use this method to switch the
     * plan and update the `shipping_amount`, `shipping_address` values for the subscription. This type of update
     * requires the buyer's consent.
     *
     * @param $id string The ID of the subscription.
     *
     * @param $subscriptionReviseRequest mixed
     *
     * @throws ApiException
     * @return SubscriptionReviseResponse
     */
    public function revisePlanOrQuantityOfSubscription($id, SubscriptionReviseRequest $subscriptionReviseRequest): SubscriptionReviseResponse
    {
        $path = "/subscriptions/{$id}/revise";

        $headers = [];
        $headers['Content-Type'] = 'application/json';


        $body = json_encode($subscriptionReviseRequest, true);
        $response = $this->send('POST', $path, [], $headers, $body);
        $jsonData = json_decode($response->getBody(), true);
        return new SubscriptionReviseResponse($jsonData);
    }

    /**
     * Internal API to be consumed by PayPal applications and not by merchants. Used to commit the changes to SOR
     * after the buyer approves the changes made to the subscription.
     *
     * @param $id string The ID of the subscription.
     *
     * @param $subscriptionSaveRequest mixed
     *
     * @throws ApiException
     * @return void
     */
    public function saveSubscription($id, SubscriptionSaveRequest $subscriptionSaveRequest): void
    {
        $path = "/subscriptions/{$id}/save";

        $headers = [];
        $headers['Content-Type'] = 'application/json';


        $body = json_encode($subscriptionSaveRequest, true);
        $response = $this->send('POST', $path, [], $headers, $body);
    }

    /**
     * Suspends the subscription.
     *
     * @param $id string The ID of the subscription.
     *
     * @param $subscriptionSuspendRequest mixed
     *
     * @throws ApiException
     * @return void
     */
    public function suspendSubscription($id, SubscriptionSuspendRequest $subscriptionSuspendRequest): void
    {
        $path = "/subscriptions/{$id}/suspend";

        $headers = [];
        $headers['Content-Type'] = 'application/json';


        $body = json_encode($subscriptionSuspendRequest, true);
        $response = $this->send('POST', $path, [], $headers, $body);
    }

    /**
     * Cancels the subscription.
     *
     * @param $id string The ID of the subscription.
     *
     * @param $subscriptionCancelRequest mixed
     *
     * @throws ApiException
     * @return void
     */
    public function cancelSubscription($id, SubscriptionCancelRequest $subscriptionCancelRequest): void
    {
        $path = "/subscriptions/{$id}/cancel";

        $headers = [];
        $headers['Content-Type'] = 'application/json';


        $body = json_encode($subscriptionCancelRequest, true);
        $response = $this->send('POST', $path, [], $headers, $body);
    }

    /**
     * Activates the subscription.
     *
     * @param $id string The ID of the subscription.
     *
     * @param $subscriptionActivateRequest mixed
     *
     * @throws ApiException
     * @return void
     */
    public function activateSubscription($id, SubscriptionActivateRequest $subscriptionActivateRequest): void
    {
        $path = "/subscriptions/{$id}/activate";

        $headers = [];
        $headers['Content-Type'] = 'application/json';


        $body = json_encode($subscriptionActivateRequest, true);
        $response = $this->send('POST', $path, [], $headers, $body);
    }

    /**
     * Captures an authorized payment from the subscriber on the subscription.
     *
     * @param $id string The ID of the subscription.
     *
     * @param $subscriptionCaptureRequest mixed
     *
     * @throws ApiException
     * @return void
     */
    public function captureAuthorizedPaymentOnSubscription($id, SubscriptionCaptureRequest $subscriptionCaptureRequest): void
    {
        $path = "/subscriptions/{$id}/capture";

        $headers = [];
        $headers['Content-Type'] = 'application/json';


        $body = json_encode($subscriptionCaptureRequest, true);
        $response = $this->send('POST', $path, [], $headers, $body);
    }

    /**
     * Lists transactions for a subscription.
     *
     * @param $id string The ID of the subscription.
     *
     * @param $startTime string The start time of the range of transactions to list.
     *
     * @param $endTime string The end time of the range of transactions to list.
     *
     * @throws ApiException
     * @return TransactionsList
     */
    public function listTransactionsForSubscription($id, $startTime, $endTime): TransactionsList
    {
        $path = "/subscriptions/{$id}/transactions";


        $params = [];
        $params['start_time'] = $startTime;
        $params['end_time'] = $endTime;

        $body = null;
        $response = $this->send('GET', $path, $params, [], $body);
        $jsonData = json_decode($response->getBody(), true);
        return new TransactionsList($jsonData);
    }
}
