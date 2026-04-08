<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Guide;
use App\Models\GuideCategory as GuideCategoryModel;
use App\Models\User;
use Illuminate\Database\Seeder;

class PhpGuideSeeder extends Seeder
{
    /**
     * Создает практические PHP-гайды для middle-разработчика.
     */
    public function run(): void
    {
        $admin = User::query()
            ->where('role', UserRole::Admin)
            ->firstOrFail();

        $phpCategoryId = GuideCategoryModel::query()
            ->where('name', 'PHP')
            ->value('id');

        $guides = [
            [
                'title' => 'PHP: типы данных и строгая типизация',
                'description' => 'Как использовать строгие типы в PHP 8+ чтобы код был надёжнее и понятнее.',
                'tags' => ['types', 'strict', 'php8', 'declare'],
                'steps' => [
                    'Включить строгую типизацию в начале каждого файла: declare(strict_types=1);',
                    'Типизировать аргументы функции: function getUser(int $id): User',
                    'Типизировать возвращаемое значение: function getName(): string',
                    'Nullable тип — если может быть null: function find(int $id): ?User',
                    'Union типы (PHP 8.0+): function parse(int|string $value): string',
                    'Тип mixed — когда тип неизвестен (использовать редко): function handle(mixed $data): void',
                    'Readonly свойства (PHP 8.1+): public readonly string $name;',
                    "Enum (PHP 8.1+) вместо констант: enum Status: string { case Active = 'active'; case Inactive = 'inactive'; }",
                ],
            ],
            [
                'title' => 'PHP: массивы — основные функции которые нужны каждый день',
                'description' => 'Самые полезные функции для работы с массивами в PHP. Без них никуда.',
                'tags' => ['array', 'map', 'filter', 'reduce'],
                'steps' => [
                    'Фильтровать массив по условию: $adults = array_filter($users, fn($u) => $u->age >= 18);',
                    'Преобразовать каждый элемент: $names = array_map(fn($u) => $u->name, $users);',
                    'Свернуть массив в одно значение: $sum = array_reduce($nums, fn($carry, $n) => $carry + $n, 0);',
                    'Найти элемент: $user = array_find($users, fn($u) => $u->id === 5); // PHP 8.4+',
                    'Объединить массивы: $merged = array_merge($arr1, $arr2);',
                    'Уникальные значения: $unique = array_unique($arr);',
                    'Сортировка с сохранением ключей: usort($users, fn($a, $b) => $a->age <=> $b->age);',
                    'Проверить что хотя бы один элемент подходит: $hasAdmin = in_array(\'admin\', $roles);',
                    'Разбить строку в массив и обратно: $parts = explode(\',\', $str); $str = implode(\', \', $parts);',
                ],
            ],
            [
                'title' => 'PHP: работа со строками — нужные функции',
                'description' => 'Частые операции со строками которые встречаются в любом проекте.',
                'tags' => ['string', 'str', 'regex', 'format'],
                'steps' => [
                    'Проверить начало строки (PHP 8.0+): str_starts_with($url, \'https\')',
                    'Проверить конец строки: str_ends_with($file, \'.php\')',
                    'Найти подстроку: str_contains($text, \'error\')',
                    'Заменить часть строки: str_replace(\'old\', \'new\', $text)',
                    'Обрезать пробелы: trim($str) — с обеих сторон, ltrim/rtrim — с одной',
                    "Форматировать число: number_format(1234567.89, 2, '.', ',') → 1,234,567.89",
                    'Разбить на части по длине: str_split($str, 3)',
                    'Регулярки — найти все совпадения: preg_match_all(\'/\d+/\', $str, $matches);',
                    'Регулярки — заменить: $clean = preg_replace(\'/\s+/\', \' \', $str);',
                    'Sprintf для форматирования: sprintf(\'Привет, %s! Тебе %d лет.\', $name, $age)',
                ],
            ],
            [
                'title' => 'PHP: ООП — интерфейсы, абстрактные классы, трейты',
                'description' => 'Когда использовать interface, abstract class и trait. Разница и примеры.',
                'tags' => ['oop', 'interface', 'abstract', 'trait'],
                'steps' => [
                    'Interface — контракт: все методы публичные и без реализации. Класс может реализовать несколько интерфейсов',
                    'Пример: interface Sendable { public function send(string $to): bool; }',
                    'Abstract class — частичная реализация: может иметь реализованные методы и свойства. Нельзя создать экземпляр',
                    'Пример абстрактного метода: abstract protected function format(): string;',
                    'Trait — переиспользование кода между несвязанными классами: trait HasTimestamps { public function createdAt(): Carbon {...} }',
                    'Подключить trait в класс: use HasTimestamps;',
                    'Когда что использовать: Interface = что делает (контракт), Abstract = основа иерархии, Trait = общий код без иерархии',
                    'Constructor property promotion (PHP 8.0+): public function __construct(private string $name, private int $age) {}',
                ],
            ],
            [
                'title' => 'PHP: исключения — правильная обработка ошибок',
                'description' => 'Как бросать, ловить и создавать свои исключения. Не использовать die() и exit().',
                'tags' => ['exception', 'try', 'catch', 'error'],
                'steps' => [
                    'Базовый try-catch: try { $result = divide($a, $b); } catch (DivisionByZeroError $e) { log($e->getMessage()); }',
                    'Поймать несколько типов: catch (NotFoundException|ValidationException $e)',
                    'Finally — выполнится всегда (даже при исключении): finally { $connection->close(); }',
                    'Создать своё исключение: class UserNotFoundException extends RuntimeException {}',
                    'Бросить исключение: throw new UserNotFoundException(\'User \' . $id . \' not found\');',
                    'Получить сообщение: $e->getMessage(), код: $e->getCode(), стек: $e->getTraceAsString()',
                    'Не глотать исключения молча — хотя бы логировать: catch (Exception $e) { Log::error($e); throw $e; }',
                    'Иерархия: Error (фатальные PHP ошибки) и Exception (логика приложения) — оба наследуют Throwable',
                ],
            ],
            [
                'title' => 'PHP: работа с датами через Carbon',
                'description' => 'Carbon — стандарт для работы с датами в PHP и Laravel. Основные операции.',
                'tags' => ['carbon', 'date', 'datetime', 'time'],
                'steps' => [
                    'Текущее время: Carbon::now() — с таймзоной: Carbon::now(\'Europe/Moscow\')',
                    'Создать из строки: Carbon::parse(\'2024-01-15 10:30:00\')',
                    'Добавить время: $date->addDays(7), addMonths(1), addHours(3)',
                    'Вычесть время: $date->subDays(1), subWeeks(2)',
                    'Форматировать: $date->format(\'d.m.Y H:i\') → 15.01.2024 10:30',
                    'Человекочитаемый формат: $date->diffForHumans() → 3 days ago',
                    'Сравнение: $date->isBefore(Carbon::now()), isAfter(), isSameDay($other)',
                    'Начало/конец периода: $date->startOfDay(), endOfMonth(), startOfWeek()',
                    'Разница между датами: $date->diffInDays($other), diffInHours($other)',
                ],
            ],
            [
                'title' => 'PHP: производительность — что замедляет код',
                'description' => 'Частые ошибки которые делают PHP код медленным. N+1 запросы, лишние операции.',
                'tags' => ['performance', 'optimization', 'cache', 'n+1'],
                'steps' => [
                    'N+1 проблема — самая частая: цикл где каждая итерация делает запрос в БД',
                    'Пример N+1: foreach ($users as $user) { echo $user->profile->avatar; } — запрос на каждого',
                    'Решение: загружать заранее через eager loading (в Laravel: User::with(\'profile\')->get())',
                    'Кешировать дорогие операции: $result = cache()->remember(\'key\', 3600, fn() => heavyQuery());',
                    'Не использовать array_push в цикле — использовать $arr[] = $value;',
                    'isset() быстрее чем array_key_exists() для проверки ключа',
                    'Считать strlen() один раз до цикла, не в условии: $len = strlen($str); for ($i=0; $i<$len; $i++)',
                    'Использовать генераторы для больших наборов данных: function readLines(): Generator { yield $line; }',
                    'Профилировать код перед оптимизацией — xdebug или Telescope в Laravel',
                ],
            ],
            [
                'title' => 'PHP: безопасность — основные уязвимости и защита',
                'description' => 'SQL-инъекции, XSS, CSRF — как они работают и как защититься.',
                'tags' => ['security', 'sql-injection', 'xss', 'csrf'],
                'steps' => [
                    'SQL-инъекция — никогда не вставлять пользовательские данные в запрос напрямую',
                    "Уязвимый код: query('SELECT * FROM users WHERE id = ' . \$_GET['id'])",
                    "Защита — prepared statements: \$stmt = \$pdo->prepare('SELECT * FROM users WHERE id = ?'); \$stmt->execute([\$id]);",
                    "XSS — не выводить пользовательский ввод без экранирования: htmlspecialchars(\$data, ENT_QUOTES, 'UTF-8')",
                    "В Laravel Blade автоматически экранирует: {{ \$data }} — безопасно, {!! \$data !!} — опасно",
                    'CSRF — использовать токены для всех форм. В Laravel: @csrf в каждой форме',
                    "Хранить пароли только через password_hash(): \$hash = password_hash(\$password, PASSWORD_BCRYPT);",
                    'Проверять пароль: password_verify($input, $hash) — не сравнивать строки напрямую',
                    'Валидировать и санировать весь пользовательский ввод до использования',
                ],
            ],
        ];

        foreach ($guides as $guide) {
            Guide::create([
                'user_id' => $admin->id,
                'author_name' => $admin->name,
                'title' => $guide['title'],
                'guide_category_id' => $phpCategoryId,
                'description' => $guide['description'],
                'tags' => $guide['tags'],
                'steps' => $guide['steps'],
            ]);
        }
    }
}
