<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Guide;
use App\Models\GuideCategory as GuideCategoryModel;
use App\Models\User;
use Illuminate\Database\Seeder;

class GitGuideSeeder extends Seeder
{
    /**
     * Создает практические Git-гайды и привязывает их к администратору.
     */
    public function run(): void
    {
        $admin = User::query()
            ->where('role', UserRole::Admin)
            ->firstOrFail();

        $guides = [
            [
                'title' => 'Git: откат изменений — reset, revert, restore',
                'description' => 'Три способа отменить изменения в зависимости от ситуации. Самые частые команды которые нужны каждый день.',
                'tags' => ['reset', 'revert', 'restore', 'undo'],
                'steps' => [
                    'Отменить изменения в файле (ещё не в stage): git restore имя_файла',
                    'Убрать файл из stage (после git add): git restore --staged имя_файла',
                    'Откатить последний коммит, сохранив изменения в файлах: git reset --soft HEAD~1',
                    'Откатить последний коммит и удалить изменения полностью: git reset --hard HEAD~1',
                    'Безопасно отменить коммит который уже запушен (создаёт новый коммит-отмену): git revert HEAD',
                    'Откатить конкретный коммит по хешу: git revert abc1234',
                    'Посмотреть хеши коммитов: git log --oneline',
                ],
            ],
            [
                'title' => 'Git: работа с ветками — создание, слияние, удаление',
                'description' => 'Полный цикл работы с ветками: создал, поработал, смержил, удалил.',
                'tags' => ['branch', 'merge', 'checkout', 'switch'],
                'steps' => [
                    'Посмотреть все ветки: git branch -a',
                    'Создать новую ветку и сразу перейти в неё: git switch -c feature/my-feature',
                    'Переключиться на существующую ветку: git switch main',
                    'Слить ветку в текущую (fast-forward если возможно): git merge feature/my-feature',
                    'Слить ветку с созданием merge-коммита всегда: git merge --no-ff feature/my-feature',
                    'Удалить ветку после слияния локально: git branch -d feature/my-feature',
                    'Удалить ветку на remote: git push origin --delete feature/my-feature',
                    'Переименовать текущую ветку: git branch -m новое-имя',
                ],
            ],
            [
                'title' => 'Git: rebase — чистая история без лишних merge-коммитов',
                'description' => 'Когда и как использовать rebase чтобы история была линейной и читаемой.',
                'tags' => ['rebase', 'interactive', 'squash', 'history'],
                'steps' => [
                    'Переместить свою ветку на вершину main (вместо merge): git rebase main',
                    'Если конфликт при rebase — исправить файл, затем: git add . && git rebase --continue',
                    'Отменить rebase если что-то пошло не так: git rebase --abort',
                    'Интерактивный rebase — сжать последние 3 коммита в один: git rebase -i HEAD~3',
                    'В открывшемся редакторе: первый коммит оставить pick, остальные заменить на squash',
                    'После squash написать один общий commit message и сохранить',
                    'Запушить после rebase (force нужен потому что история изменилась): git push --force-with-lease',
                    'force-with-lease безопаснее чем --force: не перезапишет если кто-то успел запушить',
                ],
            ],
            [
                'title' => 'Git: stash — спрятать незаконченную работу',
                'description' => 'Нужно срочно переключиться на другую ветку но изменения не готовы к коммиту — используй stash.',
                'tags' => ['stash', 'switch', 'wip'],
                'steps' => [
                    'Спрятать все текущие изменения: git stash',
                    "Спрятать с понятным названием: git stash push -m 'правки формы авторизации'",
                    'Посмотреть список всех stash: git stash list',
                    'Применить последний stash и удалить его из списка: git stash pop',
                    'Применить конкретный stash не удаляя его: git stash apply stash@{2}',
                    'Удалить конкретный stash: git stash drop stash@{0}',
                    'Удалить все stash сразу: git stash clear',
                    'Stash включая untracked файлы (новые файлы): git stash push -u',
                ],
            ],
            [
                'title' => 'Git: разрешение конфликтов при merge и rebase',
                'description' => 'Конфликты неизбежны в командной работе. Как быстро и правильно их решать.',
                'tags' => ['conflict', 'merge', 'rebase', 'resolve'],
                'steps' => [
                    'Конфликт возникает когда два человека изменили одну строку — Git не знает какую оставить',
                    'Открыть файл с конфликтом — найти маркеры: <<<<<<< HEAD — это твои изменения',
                    '======= — разделитель, >>>>>>> branch-name — изменения из другой ветки',
                    'Вручную отредактировать файл: оставить нужный код, удалить все маркеры <<<<, ====, >>>>',
                    'После исправления добавить файл: git add имя_файла',
                    'Продолжить merge: git commit (или git rebase --continue если был rebase)',
                    'Использовать VSCode или PhpStorm — там конфликты видны визуально с кнопками Accept Current/Incoming',
                    'Проверить что конфликтов не осталось: git status — должно быть nothing to commit',
                ],
            ],
            [
                'title' => 'Git: cherry-pick — перенести конкретный коммит в другую ветку',
                'description' => 'Нужно взять только один коммит из другой ветки не делая полный merge.',
                'tags' => ['cherry-pick', 'commit', 'branch'],
                'steps' => [
                    'Посмотреть хеш нужного коммита в другой ветке: git log другая-ветка --oneline',
                    'Перейти в ветку куда нужно перенести: git switch main',
                    'Применить конкретный коммит: git cherry-pick abc1234',
                    'Перенести несколько коммитов подряд: git cherry-pick abc1234..def5678',
                    'Если конфликт — исправить, затем: git add . && git cherry-pick --continue',
                    'Отменить cherry-pick: git cherry-pick --abort',
                    'Cherry-pick без автоматического коммита (чтобы внести правки): git cherry-pick -n abc1234',
                ],
            ],
            [
                'title' => 'Git: теги — маркировать релизы',
                'description' => 'Теги используются для обозначения версий релизов. Стандарт: semantic versioning.',
                'tags' => ['tag', 'release', 'version', 'semver'],
                'steps' => [
                    "Создать annotated тег на текущем коммите: git tag -a v1.0.0 -m 'Первый релиз'",
                    "Создать тег на конкретном коммите: git tag -a v1.0.1 abc1234 -m 'Хотфикс'",
                    'Посмотреть все теги: git tag',
                    'Запушить конкретный тег на remote: git push origin v1.0.0',
                    'Запушить все теги сразу: git push origin --tags',
                    'Удалить тег локально: git tag -d v1.0.0',
                    'Удалить тег на remote: git push origin --delete v1.0.0',
                    'Посмотреть информацию о теге: git show v1.0.0',
                ],
            ],
            [
                'title' => 'Git: aliases — сократить длинные команды',
                'description' => 'Настрой псевдонимы для команд которые пишешь каждый день. Экономит время.',
                'tags' => ['alias', 'config', 'productivity'],
                'steps' => [
                    'Добавить alias глобально: git config --global alias.st status',
                    'Теперь git st работает как git status',
                    "Короткий log с графом: git config --global alias.lg 'log --oneline --graph --decorate --all'",
                    "Быстрый коммит: git config --global alias.cm 'commit -m'",
                    "Посмотреть последний коммит: git config --global alias.last 'log -1 HEAD --stat'",
                    "Отменить все локальные изменения: git config --global alias.nah 'reset --hard && git clean -fd'",
                    'Посмотреть все свои aliases: git config --global --list | grep alias',
                    'Все aliases хранятся в файле: ~/.gitconfig',
                ],
            ],
        ];

        $gitCategoryId = GuideCategoryModel::query()
            ->where('name', 'Git')
            ->value('id');

        foreach ($guides as $guide) {
            Guide::create([
                'user_id' => $admin->id,
                'author_name' => $admin->name,
                'title' => $guide['title'],
                'guide_category_id' => $gitCategoryId,
                'description' => $guide['description'],
                'tags' => $guide['tags'],
                'steps' => $guide['steps'],
            ]);
        }
    }
}
