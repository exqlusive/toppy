<?php

namespace Tests\Unit;

use Tests\TestCase;

class PostTest extends TestCase
{
    public function test_get_all_posts()
    {
        $response = $this->json('GET', '/api/posts');

        $response->assertStatus(200);
    }

    public function test_get_single_post()
    {
        $response = $this->json('GET', '/api/posts/2');

        $response->assertStatus(200);
    }

    public function test_create_post()
    {
        $response = $this->json('POST', '/api/posts', [
            'slug' => 'slug',
            'title' => 'Hello World',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis efficitur semper urna. Aenean laoreet, est sed condimentum tristique, turpis tellus vulputate dui, at suscipit risus nisi quis purus. Suspendisse id lorem accumsan turpis scelerisque rutrum vel ac mi. Mauris faucibus nunc sed sem ornare sollicitudin. Integer sollicitudin orci in malesuada lacinia. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus id mi eget erat luctus egestas sit amet sed tortor. Suspendisse cursus lacus sed laoreet accumsan.',
            'enabled' => 1
        ]);

        $response->assertStatus(201);
    }

    public function test_update_post()
    {
        $response = $this->json('PUT', '/api/posts/2', [
            'slug' => 'slug',
            'title' => 'Hello World',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis efficitur semper urna. Aenean laoreet, est sed condimentum tristique, turpis tellus vulputate dui, at suscipit risus nisi quis purus. Suspendisse id lorem accumsan turpis scelerisque rutrum vel ac mi. Mauris faucibus nunc sed sem ornare sollicitudin. Integer sollicitudin orci in malesuada lacinia. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus id mi eget erat luctus egestas sit amet sed tortor. Suspendisse cursus lacus sed laoreet accumsan.',
            'enabled' => 1
        ]);

        $response->assertStatus(200);
    }

    public function test_enable_post()
    {
        $response = $this->json('PATCH', '/api/posts/2/enable', [
            'enabled' => 1
        ]);

        $response->assertStatus(200);
    }

    public function test_disable_post()
    {
        $response = $this->json('PATCH', '/api/posts/2/disable', [
            'enabled' => 0
        ]);

        $response->assertStatus(200);
    }

    public function test_delete_post()
    {
        $response = $this->json('DELETE', '/api/posts/2');

        $response->assertStatus(200);
    }
}
