<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class RouteTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_home_page_loads()
    {
        $this->get(route('home'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page->component('Home'));
    }

    public function test_about_page_loads()
    {
        $this->get(route('about'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page->component('About'));
    }

    public function test_contact_page_loads()
    {
        $this->get(route('contact'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page->component('Contact'));
    }

    public function test_market_page_loads()
    {
        $this->get(route('market'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page->component('Market'));
    }

    public function test_login_page_loads()
    {
        $this->get(route('login'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page->component('Auth/Login'));
    }

    public function test_registration_page_loads()
    {
        $this->get(route('registration'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page->component('Auth/Registration'));
    }

    public function test_laptops_page_loads()
    {
        $this->get(route('laptops'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page->component('Laptops'));
    }

    public function test_pcs_page_loads()
    {
        $this->get(route('pcs'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page->component('Pcs'));
    }

    public function test_components_page_loads()
    {
        $this->get(route('components'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page->component('Components'));
    }

    public function test_cart_page_loads()
    {
        $this->get(route('cart'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page->component('Cart'));
    }

    public function test_tutor_page_loads()
    {
        $this->get(route('tutor'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page->component('Tutorials'));
    }

    public function test_auction_page_loads()
    {
        $this->get(route('auction'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page->component('Auction'));
    }

    public function test_quiz_page_loads()
    {
        $this->get(route('quiz'))
            ->assertStatus(200)
            ->assertInertia(fn (Assert $page) => $page->component('Quiz'));
    }
}
