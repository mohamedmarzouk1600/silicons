<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @Copyright Maximum Develop
 * @FileCreated 9/11/20 2:44 AM
 * @Contact https://www.linkedin.com/in/mohamed-marzouk-138158125
 */

namespace Tests\Feature;

class BasicTest extends BaseCase
{
    /** @test */
    public function home_page_test(){
        $response = $this->get('/');

        $response->assertStatus(200);
    }

}
