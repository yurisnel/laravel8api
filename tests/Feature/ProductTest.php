<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Model\Product;

use Tests\TestCase;

class ProductTest extends TestCase
{
    var $apiPath = "/api/v1";

    var $dataNewProductSimple = [
        'name' => 'Product 01',
        'description' => 'this es product test 01',
        'stock' => '10'
    ];

    // 5,7,1,11,22 son los precios de las variaciones del producto
    var $dataNewProductWithVariations = [
        'name' => 'Product variations 01',
        'description' => 'this product have variations',
        'stock' => '10',
        'variations' => [
            [
                'attribute' => 'color',
                'option' => 'white',
                'price' => 40.30,
            ], [
                'attribute' => 'size',
                'option' => 'small',
                'price' => 10.10,
            ], [
                'attribute' => 'size',
                'option' => 'large',
                'price' => 50.50,
            ]
        ],
    ];

    public function testsProductSimpleAreCreatedCorrectly()
    {

        $this->json('POST', $this->apiPath . '/products', $this->dataNewProductSimple)
            ->assertStatus(200)
            ->assertJson([
                'name' => 'Product 01'
            ]);
    }

    public function testsArticlesAreUpdatedCorrectly()
    {
        $product = Product::factory()->create();

        $dataSetProduct = [
            'name' => 'Producto name set',
            'description' => 'description set',
        ];

        $this->json('PUT', $this->apiPath . '/products/' . $product->id, $dataSetProduct)
            ->assertStatus(200)
            ->assertJson([
                'name' => 'Producto name set',
                'description' => 'description set'
            ]);
    }


    public function testsArtilcesAreDeletedCorrectly()
    {
        $product = Product::factory()->create();

        $this->json('DELETE', $this->apiPath . '/products/' . $product->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function testArticlesAreListedCorrectly()
    {
        $this->json('GET', $this->apiPath . '/products', [])
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => ['name', 'description']
            ]);
    }



    public function testsProductVariationsAreCreatedCorrectly()
    {
        $this->json('POST', $this->apiPath . '/products', $this->dataNewProductWithVariations)
            ->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'variations' => [
                    '*' => ['price', 'option', 'attribute']
                ]
            ]);
    }


    public function testsArticlesAreFilterByNameCorrectly()
    {
        $product = \App\Model\Product::create($this->dataNewProductWithVariations);

        $filter = [
            'name' => $this->dataNewProductWithVariations['name'],
            // 'description' => 'description set',
            // 'price' => '5-10',
            // 'stock' => true,
            // 'variations' => 'small,blue'
        ];

        //name=Producto name set&description=description set&price=5-9&variations=small,blue

        $query = http_build_query($filter);

        $this->json('GET', $this->apiPath . '/products/filter?' . $query)
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => $this->dataNewProductWithVariations['name'],
                'description' => $this->dataNewProductWithVariations['description']
            ]);
    }

    public function testsArticlesAreFilterByAttributeCorrectly()
    {
        $product = \App\Model\Product::create($this->dataNewProductWithVariations);

        $filter = [
            'variations' => 'small,blue'
        ];

        //filter[name]=Producto name set&filter[description]=description set&filter[price]=5-9&filter[variations]=small,blue

        $query = http_build_query(array('filter' => $filter));

        $this->json('GET', $this->apiPath . '/products/filter?' . $query)
            ->assertStatus(200)
            ->assertJsonFragment([
                'attribute' => 'size',
                'option' => 'small'
            ]);
    }
}
