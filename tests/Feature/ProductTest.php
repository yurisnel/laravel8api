<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Model\Product;

use Tests\TestCase;

class ProductTest extends TestCase
{
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
        'attributes' => [
            'color' => [
                'blue' => 5,
                'red' => 7,
                'black' => 1,
            ],
            'size' => [
                'large' => 11,
                'small' => 2,
            ]
        ],
    ];

    public function testsProductSimpleAreCreatedCorrectly()
    {

        $this->json('POST', '/api/products', $this->dataNewProductSimple)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonStructure([
                'data' => ['id']
            ]);
    }

    public function testsArticlesAreUpdatedCorrectly()
    {
        $product = Product::factory()->create();

        $dataSetProduct = [
            'name' => 'Producto name set',
            'description' => 'description set',
        ];

        $this->json('PUT', '/api/products/' . $product->id, $dataSetProduct)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'name' => 'Producto name set',
                    'description' => 'description set'
                ]
            ]);
    }


    public function testsArtilcesAreDeletedCorrectly()
    {
        $product = Product::factory()->create();

        $this->json('DELETE', '/api/products/' . $product->id)
            ->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);
    }

    public function testArticlesAreListedCorrectly()
    {
        $this->json('GET', '/api/products', [])
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['name', 'description']
                ]
            ]);
    }



    public function testsProductVariationsAreCreatedCorrectly()
    {
        $this->json('POST', '/api/products', $this->dataNewProductWithVariations)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'variations' => ['*' => ['price', 'option']]
                ]
            ]);
    }


    public function testsArticlesAreFilterByNameCorrectly()
    {
        $product = \App\Model\Product::create($this->dataNewProductWithVariations);

        $filter = [
            'name' => $this->dataNewProductWithVariations['name'],
            // 'description' => 'description set',
            //  'price' => '5-10',
            // 'stock' => true,
            // 'attributes' => 'small,blue'
        ];

        //filter[name]=Producto name set&filter[description]=description set&filter[price]=5-9&filter[attributes]=small,blue

        $query = http_build_query(array('filter' => $filter));

        $this->json('GET', '/api/products/filter?' . $query)
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
            //'name' => $this->dataNewProductWithVariations['name'],
            //'description' => 'description set',
            // 'price' => '5-10',
            // 'stock' => true,
            'attributes' => 'small,blue'
        ];

        //filter[name]=Producto name set&filter[description]=description set&filter[price]=5-9&filter[attributes]=small,blue

        $query = http_build_query(array('filter' => $filter));

        $this->json('GET', '/api/products/filter?' . $query)
            ->assertStatus(200)
            ->assertJsonFragment([
                'attribute' => 'size',
                'option' => 'small'
            ]);
    }
}
