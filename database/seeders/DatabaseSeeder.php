<?php

namespace Database\Seeders;

use App\Enums\GuideCategory;
use App\Enums\UserRole;
use App\Models\Guide;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Админ, пример контрибьютора и демо-гайды.
     */
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@devknowledge.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => UserRole::Admin,
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'contributor@devknowledge.test'],
            [
                'name' => 'Ahmed',
                'password' => Hash::make('password'),
                'role' => UserRole::Contributor,
            ],
        );

        $samples = [
            [
                'title' => 'Docker multi-stage build for Laravel',
                'category' => GuideCategory::Docker,
                'description' => 'Production image with OPcache, non-root user, and Composer in build stage only.',
                'tags' => ['docker', 'laravel', 'php'],
                'steps' => [
                    'Use `FROM php:8.3-fpm-alpine` as runtime stage.',
                    'Run `docker-php-ext-install pdo pdo_mysql opcache` in a builder stage.',
                    'Copy `vendor` and compiled assets from builder: `COPY --from=builder /app /var/www/html`.',
                    'Healthcheck: `HEALTHCHECK CMD curl -f http://localhost/health || exit 1`.',
                ],
            ],
            [
                'title' => 'Git: safe rebase flow',
                'category' => GuideCategory::Git,
                'description' => 'Rebase onto main without losing history on shared branches.',
                'tags' => ['git', 'rebase'],
                'steps' => [
                    '`git fetch origin` and `git checkout feature/x`.',
                    '`git rebase origin/main` — resolve conflicts, then `git rebase --continue`.',
                    'Push with `git push --force-with-lease` (never bare `--force` on shared remotes).',
                ],
            ],
            [
                'title' => 'Linux: find large files quickly',
                'category' => GuideCategory::Linux,
                'description' => 'One-liners to locate disk hogs before cleanup.',
                'tags' => ['disk', 'find'],
                'steps' => [
                    'Top directories: `du -h --max-depth=1 /var | sort -hr | head`.',
                    'Large files: `find /var -type f -size +100M -printf "%p %k KB\n" 2>/dev/null`.',
                    'Inodes: `df -i` then narrow with `find` + `xargs` if needed.',
                ],
            ],
            [
                'title' => 'MySQL: covering index checklist',
                'category' => GuideCategory::Database,
                'description' => 'When a secondary index avoids table lookups for common queries.',
                'tags' => ['mysql', 'indexes'],
                'steps' => [
                    'Run `EXPLAIN ANALYZE` on slow SELECTs.',
                    'Order index columns by equality predicates first, then range, then includes needed columns.',
                    'Consider `CREATE INDEX ... (user_id, status) INCLUDE (created_at)` pattern where supported.',
                ],
            ],
        ];

        foreach ($samples as $row) {
            Guide::query()->updateOrCreate(
                ['title' => $row['title'], 'user_id' => $admin->id],
                [
                    'author_name' => 'Admin',
                    'category' => $row['category'],
                    'description' => $row['description'],
                    'tags' => $row['tags'],
                    'steps' => $row['steps'],
                ],
            );
        }
    }
}
