<?php

declare(strict_types=1);

namespace App\Core\Organization\Http\Transformers\V1\Admin;

use App\Contracts\Transformer\BaseTransformer;
use App\Core\FileManager\Http\Transformers\V1\Admin\FileTransformer;
use App\Core\Organization\Entities\Department;
use App\Services\Transformer\FieldTransformers\EnumTransformer;

class DepartmentTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'parent',
        'manager',
        'children',
        'users',
        'thumbnail',
        'childrenWithUsers',
    ];

    protected array $includeAliases = [
        'childrenWithUsers' => 'children',
    ];

    protected array $fieldTransformers = [
        'type' => EnumTransformer::class,
    ];

    public function includeParent(Department $department)
    {
        return $this->itemRelation(
            model: $department,
            relationName: 'parent',
            transformer: $this,
        );
    }

    public function includeUsers(Department $department)
    {
        return $this->collectionRelation(
            model: $department,
            relationName: 'users',
            transformer: UserTransformer::class,
        );
    }

    public function includeManager(Department $department)
    {
        return $this->itemRelation(
            model: $department,
            relationName: 'manager',
            transformer: UserTransformer::class,
        );
    }

    public function includeThumbnail(Department $department)
    {
        return $this->itemRelation(
            model: $department,
            relationName: 'thumbnail',
            transformer: FileTransformer::class,
        );
    }

    public function includeChildren(Department $department)
    {
        $children = $department->children;

        if ($children->isEmpty()) {
            return $this->null();
        }

        $childTransformer = resolve(self::class);
        $childTransformer->setDefaultIncludes(['children']);

        return $this->collection($children, $childTransformer);
    }

    /**
     * Include children recursively with all relations needed for organization chart.
     * This uses the childrenWithUsers relationship that already eager loads
     * all necessary data including users with their avatars and thumbnails.
     */
    public function includeChildrenWithUsers(Department $department)
    {
        $children = $department->childrenWithUsers;

        if ($children->isEmpty()) {
            return $this->null();
        }

        $childTransformer = resolve(self::class);
        $childTransformer->setDefaultIncludes(
            ['childrenWithUsers', 'users', 'thumbnail']
        );

        return $this->collection($children, $childTransformer);
    }
}
