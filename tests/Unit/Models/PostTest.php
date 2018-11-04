<?php

namespace Tests\Unit\Models;

use App\Models\Post;
use Tests\TestCase;

class PostTest extends TestCase
{
    /** @test */
    public function it_can_determine_the_promotional_url()
    {
        $post = factory(Post::class)->create([
            'slug' => 'test'
        ]);
        $this->assertEquals('http://murze.be.test/test', $post->promotional_url);

        $post = factory(Post::class)->create([
            'slug' => 'test',
            'external_url' => 'https://external-blog.com/page'
        ]);
        $this->assertEquals('https://external-blog.com/page', $post->promotional_url);
    }

    /** @test */
    public function it_can_get_scheduled_posts()
    {
        $this->assertCount(0, Post::scheduled()->get());

        factory(Post::class)->create([
            'publish_date' => now()->subMinute(),
            'published' => false,
        ]);
        $this->assertCount(1, Post::scheduled()->get());

        factory(Post::class)->create([
            'publish_date' => now()->subMinute(),
            'published' => true,
        ]);
        $this->assertCount(1, Post::scheduled()->get());

        factory(Post::class)->create([
            'publish_date' => now()->addMinute(),
            'published' => false,
        ]);
        $this->assertCount(2, Post::scheduled()->get());

        factory(Post::class)->create([
            'publish_date' => null,
            'published' => false,
        ]);
        $this->assertCount(2, Post::scheduled()->get());
    }
}
