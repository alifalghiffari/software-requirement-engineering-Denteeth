<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_home_page_displays_denteeth(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<h1>Denteeth</h1>', false);
    }
}
