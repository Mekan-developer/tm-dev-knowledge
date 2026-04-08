<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Guide;
use App\Models\GuideCategory as GuideCategoryModel;
use App\Models\User;
use Illuminate\Database\Seeder;

class LaravelGuideSeeder extends Seeder
{
    /**
     * Создает практические Laravel-гайды для middle-разработчика.
     */
    public function run(): void
    {
        $admin = User::query()
            ->where('role', UserRole::Admin)
            ->firstOrFail();

        $laravelCategoryId = GuideCategoryModel::query()
            ->where('name', 'Laravel')
            ->value('id');

        $guides = [
            [
                'title' => 'Laravel: Eloquent — отношения и жадная загрузка',
                'description' => 'Как правильно настраивать отношения между моделями и избежать N+1 проблемы.',
                'tags' => ['eloquent', 'relations', 'eager-loading', 'n+1'],
                'steps' => [
                    'hasOne — один к одному: в модели User: public function profile(): HasOne { return $this->hasOne(Profile::class); }',
                    'hasMany — один ко многим: public function posts(): HasMany { return $this->hasMany(Post::class); }',
                    'belongsTo — обратная связь: в модели Post: public function user(): BelongsTo { return $this->belongsTo(User::class); }',
                    'belongsToMany — многие ко многим: public function roles(): BelongsToMany { return $this->belongsToMany(Role::class); }',
                    'N+1 проблема: foreach ($posts as $post) { echo $post->user->name; } — запрос на каждый пост',
                    'Решение — eager loading: Post::with(\'user\')->get() — два запроса вместо N+1',
                    'Загрузить несколько отношений: Post::with([\'user\', \'comments\', \'tags\'])->get()',
                    'Вложенные отношения: Post::with(\'comments.user\')->get()',
                    'Условная загрузка: Post::with([\'comments\' => fn($q) => $q->latest()->limit(3)])->get()',
                    'Проверить количество без загрузки: Post::withCount(\'comments\')->get() → $post->comments_count',
                ],
            ],
            [
                'title' => 'Laravel: Query Builder и Eloquent — сложные запросы',
                'description' => 'Сложные выборки, подзапросы, joins — когда простого find() недостаточно.',
                'tags' => ['query-builder', 'eloquent', 'join', 'subquery'],
                'steps' => [
                    'Фильтрация: User::where(\'active\', true)->where(\'age\', \'>=\', 18)->get()',
                    'OR условие: ->orWhere(\'role\', \'admin\')',
                    'Группировка условий: ->where(fn($q) => $q->where(\'a\', 1)->orWhere(\'b\', 2))',
                    'whereIn — несколько значений: User::whereIn(\'id\', [1,2,3])->get()',
                    'whereHas — по отношению: User::whereHas(\'posts\', fn($q) => $q->where(\'published\', true))->get()',
                    'Join: DB::table(\'users\')->join(\'profiles\', \'users.id\', \'=\', \'profiles.user_id\')->select(\'users.*\')->get()',
                    'Подзапрос в select: User::selectSub(Post::selectRaw(\'count(*)\')->whereColumn(\'user_id\',\'users.id\'), \'posts_count\')->get()',
                    'Сортировка: ->orderBy(\'created_at\', \'desc\') или ->latest()',
                    'Пагинация: ->paginate(15) → передать в Inertia, во Vue использовать props.guides.links',
                    'Raw выражения когда нужен чистый SQL: ->whereRaw(\'DATE(created_at) = ?\', [today()])',
                ],
            ],
            [
                'title' => 'Laravel: миграции — правила и частые ошибки',
                'description' => 'Как правильно писать миграции чтобы не сломать production базу данных.',
                'tags' => ['migration', 'database', 'schema', 'rollback'],
                'steps' => [
                    'Создать миграцию: php artisan make:migration create_posts_table',
                    'Добавить колонку в существующую таблицу: php artisan make:migration add_status_to_posts_table',
                    'Никогда не редактировать уже запущенную миграцию — создавать новую',
                    'nullable() для колонок которые могут быть NULL: $table->string(\'avatar\')->nullable();',
                    'default() значение по умолчанию: $table->boolean(\'active\')->default(true);',
                    'Индексы для колонок по которым фильтруешь: $table->index(\'status\') или $table->index([\'user_id\', \'status\'])',
                    'Foreign key: $table->foreignId(\'user_id\')->constrained()->cascadeOnDelete();',
                    'После изменения — запустить: php artisan migrate',
                    'Откатить последнюю миграцию: php artisan migrate:rollback',
                    'Пересоздать всё с нуля (только dev!): php artisan migrate:fresh --seed',
                ],
            ],
            [
                'title' => 'Laravel: Queues — фоновые задачи и очереди',
                'description' => 'Как вынести тяжёлые операции в фон чтобы пользователь не ждал.',
                'tags' => ['queue', 'job', 'worker', 'background'],
                'steps' => [
                    'Создать Job: php artisan make:job SendWelcomeEmail',
                    'В методе handle() — вся логика задачи: public function handle(): void { Mail::to($this->user)->send(new WelcomeMail()); }',
                    'Передать данные в Job через конструктор: public function __construct(private User $user) {}',
                    'Отправить в очередь: SendWelcomeEmail::dispatch($user)',
                    'Отложить выполнение: SendWelcomeEmail::dispatch($user)->delay(now()->addMinutes(5))',
                    'Настроить в .env: QUEUE_CONNECTION=database (или redis для production)',
                    'Создать таблицу для очереди: php artisan queue:table && php artisan migrate',
                    'Запустить воркер: php artisan queue:work',
                    'Воркер с автоперезапуском при ошибке: php artisan queue:work --tries=3 --backoff=5',
                    'На production использовать Supervisor чтобы воркер не падал',
                ],
            ],
            [
                'title' => 'Laravel: кеширование — Redis и Cache фасад',
                'description' => 'Как кешировать данные чтобы не делать лишних запросов к базе данных.',
                'tags' => ['cache', 'redis', 'performance', 'remember'],
                'steps' => [
                    'Простое кеширование: Cache::put(\'key\', $value, now()->addHours(1))',
                    'Получить из кеша: Cache::get(\'key\') — вернёт null если нет',
                    'Получить или сохранить: Cache::remember(\'users\', 3600, fn() => User::all())',
                    'remember() — идеальный паттерн: если в кеше есть — вернёт, нет — выполнит запрос и сохранит',
                    'Удалить из кеша: Cache::forget(\'key\')',
                    'Проверить наличие: Cache::has(\'key\')',
                    'Кешировать навсегда: Cache::rememberForever(\'settings\', fn() => Setting::all())',
                    'Инвалидировать при изменении данных: после update → Cache::forget(\'users\')',
                    'Настроить Redis: CACHE_DRIVER=redis в .env, установить predis: composer require predis/predis',
                    'Теги для группового сброса: Cache::tags([\'posts\', \'users\'])->flush() — только с Redis',
                ],
            ],
            [
                'title' => 'Laravel: Events и Listeners — событийная архитектура',
                'description' => 'Как разделить логику через события чтобы код не превращался в спагетти.',
                'tags' => ['events', 'listeners', 'observer', 'decoupling'],
                'steps' => [
                    'Создать событие: php artisan make:event UserRegistered',
                    'Создать слушатель: php artisan make:listener SendWelcomeEmail --event=UserRegistered',
                    'В событии хранить данные: public function __construct(public readonly User $user) {}',
                    'В слушателе — реакция на событие: public function handle(UserRegistered $event): void { Mail::to($event->user)... }',
                    'Зарегистрировать в EventServiceProvider: protected $listen = [UserRegistered::class => [SendWelcomeEmail::class]]',
                    'Выбросить событие: event(new UserRegistered($user)) или UserRegistered::dispatch($user)',
                    'Асинхронный слушатель — реализовать ShouldQueue: class SendWelcomeEmail implements ShouldQueue',
                    'Observer — для событий модели: php artisan make:observer UserObserver --model=User',
                    'В Observer методы: created(), updated(), deleted(), restored()',
                    'Зарегистрировать Observer: User::observe(UserObserver::class) — в AppServiceProvider',
                ],
            ],
            [
                'title' => 'Laravel: Policies и Gates — авторизация действий',
                'description' => 'Как контролировать кто что может делать. Policy для моделей, Gate для общих правил.',
                'tags' => ['policy', 'gate', 'authorization', 'middleware'],
                'steps' => [
                    'Создать Policy: php artisan make:policy PostPolicy --model=Post',
                    'Методы Policy: viewAny, view, create, update, delete, restore, forceDelete',
                    'Логика в методе update: public function update(User $user, Post $post): bool { return $user->id === $post->user_id; }',
                    'Использовать в контроллере: $this->authorize(\'update\', $post) — кинет 403 если нет права',
                    'Проверить без исключения: $user->can(\'update\', $post) — вернёт bool',
                    'В Blade: @can(\'update\', $post) ... @endcan',
                    'Gate для общих правил (без модели): Gate::define(\'access-admin\', fn(User $u) => $u->isAdmin())',
                    'Проверить Gate: Gate::allows(\'access-admin\') или Gate::denies()',
                    'Middleware для маршрутов: Route::middleware(\'can:access-admin\')->group(...)',
                    'Policy автоматически регистрируется если следует соглашению об именах',
                ],
            ],
            [
                'title' => 'Laravel: Artisan команды — создание своих команд',
                'description' => 'Как создать свою консольную команду для автоматизации задач.',
                'tags' => ['artisan', 'command', 'console', 'automation', 'cron'],
                'steps' => [
                    'Создать команду: php artisan make:command CleanOldGuides',
                    'Задать имя команды: protected $signature = \'guides:clean {--days=30 : Удалить старше N дней}\';',
                    'Описание: protected $description = \'Удаляет неопубликованные гайды старше N дней\';',
                    'Логика в handle(): public function handle(): int { $days = $this->option(\'days\'); ... return Command::SUCCESS; }',
                    'Вывод в консоль: $this->info(\'Готово!\'), $this->error(\'Ошибка!\'), $this->warn(\'Предупреждение\')',
                    'Прогресс-бар: $bar = $this->output->createProgressBar(count($items)); $bar->advance(); $bar->finish();',
                    'Запустить вручную: php artisan guides:clean --days=60',
                    'Запустить из кода: Artisan::call(\'guides:clean\', [\'--days\' => 60])',
                    'Расписание в Scheduler (routes/console.php): Schedule::command(\'guides:clean\')->daily();',
                    'Добавить cron на сервере одной строкой: * * * * * php /path/to/artisan schedule:run',
                ],
            ],
        ];

        foreach ($guides as $guide) {
            Guide::create([
                'user_id' => $admin->id,
                'author_name' => $admin->name,
                'title' => $guide['title'],
                'guide_category_id' => $laravelCategoryId,
                'description' => $guide['description'],
                'tags' => $guide['tags'],
                'steps' => $guide['steps'],
            ]);
        }
    }
}
