<?php

namespace Modules\Wms\Services;

use Illuminate\Support\Facades\Http;
//todo change
class WMSService
{
    /* The `private ;` and `private ;` are private properties of the `WMSService` class
    in PHP. */
    private $baseUrl;
    private $apiKey;

    /**
     * The function sets the base URL and API key by retrieving them from the configuration file.
     */
    public function __construct()
    {
        $this->baseUrl = config('wms.config.base_url');
        $this->apiKey = config('wms.config.api_key');
    }

    /**
     * The function fetchMasterCatalog sends a GET request to the '/catalog/master' endpoint and
     * returns the JSON response.
     * 
     * @return the JSON response from the API endpoint '/catalog/master' after making a GET request.
     */
    public function fetchMasterCatalog()
    {
        $response =  $this->getResponse("/catalog/master", 'get');
        return $response->json();
    }

    /**
     * The function fetches a product by its SKU code and returns the response in JSON format.
     * 
     * param skuCode The skuCode parameter is a unique identifier for a product. It is used to fetch
     * the product information from the server.
     * 
     * @return the JSON response from the API call.
     */
    public function fetchProductBySKU($skuCode)
    {
        $response =  $this->getResponse("/products/$skuCode", 'get');
        return $response->json();
    }

    /**
     * The createProduct function sends a POST request to the "/products" endpoint with the product
     * data and returns the response as a JSON object.
     * 
     * param product The parameter `` is the data that will be used to create a new product.
     * It could be an array or an object containing the necessary information for creating the product,
     * such as the product name, price, description, and any other relevant details.
     * 
     * @return the JSON response from the API call.
     */
    public function createProduct($product)
    {
        $data = $this->setUpProductData($product);
        $response =  $this->getResponse("/products", 'post', $data);
        return $response->json();
    }

    /**
     * The function updates a product with the given SKU code using the provided data.
     * 
     * param skuCode The skuCode parameter is a unique identifier for a product. It is used to specify
     * which product to update in the database.
     * param data The  parameter is an array that contains the updated information for the
     * product. It could include fields such as the product name, description, price, quantity, etc.
     * 
     * @return the JSON response from the API after updating the product with the given SKU code and
     * data.
     */
    public function updateProduct($product)
    {
        $data = $this->setUpProductData($product);
        $response =  $this->getResponse("/products/$data->sku", 'put', $data);
        return $response->json();
    }

    /**
     * The function fetchHubInventory fetches the inventory data for a specific hub.
     * 
     * @return the JSON response from the API call to fetch the hub inventory.
     */
    public function fetchHubInventory()
    {
        $response =  $this->getResponse("/inventory/ollkomHub/hub", 'get');
        return $response->json();
    }

    /**
     * The createOrder function sends a POST request to the "/orders" endpoint with the provided data
     * and returns the response as a JSON object.
     * 
     * param data The parameter `` is an array that contains the information needed to create an
     * order. It could include details such as the customer's name, address, items ordered, quantity,
     * price, and any other relevant information for creating an order.
     * 
     * @return the JSON response from the API call.
     */
    public function createOrder($data)
    {
        $response =  $this->getResponse("/orders", 'post', $data);
        return $response->json();
    }    

    /**
     * The function `getResponse` sends an HTTP request to a specified URL using the specified method
     * (GET, POST, etc.) and includes an optional data payload.
     * 
     * param url The URL of the API endpoint you want to send the request to.
     * param method The method parameter is the HTTP method to be used for the request, such as "GET",
     * "POST", "PUT", "DELETE", etc.
     * param data The `data` parameter is an optional parameter that represents the data to be sent in
     * the request. It can be used to send parameters, JSON payload, or any other data that needs to be
     * included in the request. If no data is provided, it will default to `null`.
     * 
     * @return the response from the HTTP request made to the specified URL using the specified method
     * and data.
     */
    public function getResponse($url, $method, $data = null)
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->$method($this->baseUrl . $url, $data);
    }

     /**
     * The fetchProducts function fetches products from an API using the provided API key, pagination
     * parameters, and returns the response in JSON format.
     * 
     * param perPage The "perPage" parameter specifies the number of products to be fetched per page.
     * It determines how many products will be displayed on each page of the product listing.
     * param page The "page" parameter is used to specify the page number of the results you want to
     * fetch. It is typically used in combination with the "per_page" parameter to implement pagination
     * and retrieve a specific subset of results.
     * 
     * @return the JSON response from the API call.
     */
    public function fetchProducts($perPage, $page)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get("$this->baseUrl/products", [
            'per_page' => $perPage,
            'page' => $page,
        ]);
        return $response->json();
    }

    /**
     * The function takes a product array and sets up the data for the product.
     * 
     * param product The parameter "product" is an array that contains various information about a
     * product. Each key in the array represents a specific attribute of the product, such as its name,
     * description, SKU code, attributes, handling type, type, status, unit, SKU images, categories,
     * manufacturer name, brand name
     * 
     * @return an array of product data.
     */
    private function setUpProductData($product)
    {
        $data = [
            'name' => $product->name->value,
            'description' => $product->name->description,
            'sku_code' => $product['sku'],
            'attributes' => 'attributes',
            'handling_type' => 'hot',
            'type' => "simple",
            'status' => "live",
            'unit' => "pcs",
            'sku_images' => 'sku_images',
            'categories' => $product->category_id,
            'manufacturer_name' => $product->category->name->value,
            'brand_name' => 'BrandX',
            'country_of_origin' => 'CountryX',
            'is_weighted' => 'false',
            'dimensions' => 'dimensions',
            'barcodes' => '123456789',
            'cost' => $product['cost'],
            'retail_price' => $product['cost'],
            'selling_price' =>$product['cost'],
            'is_perishable' => 'false',
            'configuration' => 'configuration',
            'custom_attribute' => $product['custom_attribute'],
            'product_url' => 'https://www.google.com/',
        ];
        return $data;
    }
}
