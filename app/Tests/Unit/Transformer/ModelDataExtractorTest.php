<?php

declare(strict_types=1);

namespace App\Tests\Unit\Transformer;

use App\Contracts\Model\BaseModel;
use App\Services\Transformer\ModelDataExtractor;
use Tests\UnitTestBase;

/**
 * Test suite for ModelDataExtractor.
 */
class ModelDataExtractorTest extends UnitTestBase
{
    private ModelDataExtractor $extractor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extractor = new ModelDataExtractor();
    }

    public function test_extracts_attributes(): void
    {
        $user = new UserModel();
        $data = $this->extractor->extract($user);

        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertEquals('John', $data['name']);
        $this->assertEquals('john@example.com', $data['email']);
    }

    public function test_extracts_relations(): void
    {
        $author = new AuthorModel();
        $data   = $this->extractor->extract($author);

        $this->assertArrayHasKey('name', $data);
        $this->assertArrayHasKey('posts', $data);
        $this->assertCount(2, $data['posts']);
    }

    public function test_extracts_accessors_when_enabled(): void
    {
        $person = new PersonModel();
        $this->extractor->setIncludeAccessors(true);
        $data = $this->extractor->extract($person);

        $this->assertArrayHasKey('first_name', $data);
        $this->assertArrayHasKey('last_name', $data);
        $this->assertArrayHasKey('full_name', $data);
        $this->assertEquals('John Doe', $data['full_name']);
    }

    public function test_skips_accessors_when_disabled(): void
    {
        $person = new PersonModel();
        $this->extractor->setIncludeAccessors(false);
        $data = $this->extractor->extract($person);

        $this->assertArrayHasKey('first_name', $data);
        $this->assertArrayNotHasKey('full_name', $data);
    }

    public function test_throws_exception_for_non_model(): void
    {
        $this->expectException(\TypeError::class);

        $this->extractor->extract(new \stdClass());
    }
}

class UserModel extends BaseModel
{
    protected $fillable = ['name', 'email'];

    public function __construct()
    {
        parent::__construct([
            'name'  => 'John',
            'email' => 'john@example.com',
        ]);
    }

    public function getAppends(): array
    {
        return [];
    }
}

class AuthorModel extends BaseModel
{
    protected $fillable = ['name'];

    public function __construct()
    {
        parent::__construct(['name' => 'John']);
        $this->setRelation('posts', collect(['post1', 'post2']));
    }

    public function getAppends(): array
    {
        return [];
    }
}

class PersonModel extends BaseModel
{
    protected $fillable = ['first_name', 'last_name'];
    protected $appends  = ['full_name'];

    public function __construct()
    {
        parent::__construct([
            'first_name' => 'John',
            'last_name'  => 'Doe',
        ]);
    }

    public function getAppends(): array
    {
        return ['full_name'];
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
