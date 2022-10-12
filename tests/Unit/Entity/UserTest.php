<?php

namespace App\Tests\Unit\Entity;

use App\Entity\BlogPost;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    protected function setUp() : void
    {
        $this->blogPost = $this->createMock(BlogPost::class);

        $this->user = new User();
    }

    /**
     * Tests entity creation.
     */
    public function testEntityCreation()
    {
        $this->user->setUsername('foo');
        $this->user->setRoles(['ROLE_USER', 'ROLE_USER']);
        $this->user->setPassword('foopass');
        $this->user->setFullname('foo fullname');

        $this->assertEquals('foo', $this->user->getUsername());

        // Tests that the duplicated values are gone. 
        $this->assertEquals([ 'ROLE_USER' ], $this->user->getRoles());
        $this->assertEquals('foopass', $this->user->getPassword());
        $this->assertEquals('foo fullname', $this->user->getFullname());
        $this->assertEquals('foo', $this->user->getUserIdentifier());
    }

    /**
     * Tests that entity adds posts correctly.
     */
    public function testEntityAddPost()
    {
        $result = [ $this->blogPost ];

        $this->user->addPost($this->blogPost);

        $this->assertEquals($result, $this->user->getPosts()->toArray());
    }

    /**
     * Tests that the entity remove posts correctly.
     */
    public function testEntityRemovePost()
    {
        $this->user->addPost($this->blogPost);

        $this->blogPost->expects($this->once())
            ->method('getAuthor')
            ->willReturn($this->user);

        $this->user->removePost($this->blogPost);

        $this->assertEquals([], $this->user->getPosts()->toArray());
    }
}