<?php
namespace Quincalla\Tests\Functional;

use Quincalla\Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchTest extends TestCase
{

    public function testProductSearch()
    {
        $this->visit('/')
            ->type('Gold', 'query')
            ->press('Search');
        $this->seePageIs('/search?query=Gold');
    }

    public function testNotProductsFound()
    {
        $this->visit('/')
            ->type('products-not-found-query', 'query')
            ->press('Search')
            ->see('Not Products Found');
    }

    public function testSearchResultPagination()
    {
        $query = 'a';
        $this->visit('/')
            ->type($query, 'query')
            ->press('Search')
            ->see('Search results for `' . $query . '`')
            ->see('&laquo')
            ->see('&raquo')
            ->click('»')
            ->seePageIs('/search?page=2&query=' . $query)
            ->click('«')
            ->seePageIs('/search?page=1&query=' . $query);
    }
}