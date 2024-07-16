<?php

namespace OxidSolutionCatalysts\PayPalApi\Service;

use OxidSolutionCatalysts\PayPalApi\Exception\ApiException;
use OxidSolutionCatalysts\PayPalApi\Model\Catalog\Product;
use OxidSolutionCatalysts\PayPalApi\Model\Catalog\ProductCollection;
use OxidSolutionCatalysts\PayPalApi\Model\Catalog\ProductRequestPOST;

class Catalog extends BaseService
{
    protected string $basePath = '/v1/catalogs';

    /**
     * Creates a product.
     *
     * @param $productRequest mixed
     *
     * @param $prefer string The preferred server response upon successful completion of the request. Value
     * is:<ul><li><code>return=minimal</code>. The server returns a minimal response to optimize communication
     * between the API caller and the server. A minimal response includes the <code>id</code>, <code>status</code>
     * and HATEOAS links.</li><li><code>return=representation</code>. The server returns a complete resource
     * representation, including the current state of the resource.</li></ul>
     *
     * @throws ApiException
     * @return Product
     */
    public function createProduct(ProductRequestPOST $productRequest, $prefer = 'return=minimal'): Product
    {
        $path = "/products";

        $headers = [];
        $headers['Content-Type'] = 'application/json';
        $headers['Prefer'] = $prefer;


        $body = json_encode($productRequest, true);
        $response = $this->send('POST', $path, [], $headers, $body);
        $jsonData = json_decode($response->getBody(), true);
        return new Product($jsonData);
    }

    /**
     * Lists products.
     *
     * @param $totalRequired boolean Indicates whether to show the total items and total pages in the response.
     *
     * @param $page integer A non-zero integer which is the start index of the entire list of items that are returned
     * in the response. So, the combination of `page=1` and `page_size=20` returns the first 20 items. The
     * combination of `page=2` and `page_size=20` returns the next 20 items.
     *
     * @param $pageSize integer The number of items to return in the response.
     *
     * @throws ApiException
     * @return ProductCollection
     */
    public function listProducts($totalRequired = false, $page = 1, $pageSize = 10): ProductCollection
    {
        $path = "/products";


        $params = [];
        $params['total_required'] = var_export($totalRequired, true);
        $params['page'] = $page;
        $params['page_size'] = $pageSize;

        $body = null;
        $response = $this->send('GET', $path, $params, [], $body);
        $jsonData = json_decode($response->getBody(), true);
        return new ProductCollection($jsonData);
    }

    /**
     * Shows details for a product, by ID.
     *
     * @param $productId string The product ID.
     *
     * @throws ApiException
     * @return Product
     */
    public function showProductDetails($productId): Product
    {
        $path = "/products/{$productId}";



        $body = null;
        $response = $this->send('GET', $path, [], [], $body);
        $jsonData = json_decode($response->getBody(), true);
        return new Product($jsonData);
    }

    /**
     * Updates a product, by ID. You can patch these attributes and objects:<table><thead><tr><th>Attribute or
     * object</th><th>Operations</th></tr></thead><tbody><tr><td><code>description</code></td><td>add, replace,
     * remove</td></tr><tr><td><code>category</code></td><td>add, replace,
     * remove</td></tr><tr><td><code>image_url</code></td><td>add, replace,
     * remove</td></tr><tr><td><code>home_url</code></td><td>add, replace, remove</td></tr></tbody></table>
     *
     * @param $productId string The product ID.
     *
     * @param $patchRequest mixed
     *
     * @throws ApiException
     * @return void
     */
    public function updateProduct($productId, array $patchRequest): void
    {
        $path = "/products/{$productId}";

        $headers = [];
        $headers['Content-Type'] = 'application/json';


        $body = json_encode($patchRequest, true);
        $response = $this->send('PATCH', $path, [], $headers, $body);
    }
}
