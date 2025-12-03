<?php

declare(strict_types=1);

namespace App\Macros;

class BlueprintMixin
{
    public function uniqueSoftDeleteBy()
    {
        return function ($columns, $name = null, $algorithm = null, string $deletedAtColumnName = 'deleted_at'): void {
            $deletedAtUnix = "{$name}_deleted_at_unix";

            if (is_array($columns)) {
                $columns[] = $deletedAtUnix;
            }

            $this->integer($deletedAtUnix)
                ->always()
                ->storedAs("if({$deletedAtColumnName} is null, 0,TIMESTAMPDIFF(SECOND, from_unixtime(0), {$deletedAtColumnName}))");

            $this->unique($columns, $name, $algorithm);
        };
    }

    public function dropUniqueSoftDeleteBy()
    {
        return function ($columns, $name = null): void {
            $deletedAtUnix = 'deleted_at_unix';

            if (is_array($columns)) {
                $columns[] = $deletedAtUnix;
            }

            if ($name) {
                $this->dropUnique($name);
            } else {
                $this->dropUnique($columns);
            }

            $this->dropColumn($deletedAtUnix);
        };
    }
}
