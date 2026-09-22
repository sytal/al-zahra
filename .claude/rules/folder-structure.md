# Folder structure (docs/CLAUDE.md Section 4)

Module-based, not type-based at top level. Every feature lives inside its
own module folder under `app/Modules/{Module}/`.

```
app/
  Modules/
    Article/
      Models/Article.php
      Http/Controllers/ArticleController.php
      Http/Requests/StoreArticleRequest.php
      Http/Requests/UpdateArticleRequest.php
      Http/Resources/ArticleResource.php
      Policies/ArticlePolicy.php
      Services/ArticleService.php
      Repositories/ArticleRepository.php
      Repositories/ArticleRepositoryInterface.php
      Livewire/ArticleList.php
      Livewire/ArticleCard.php
      database/migrations/  (or root migrations, see rules for migrations)
    Course/
    Research/
    Resource/
    Consultation/
    User/
    Certificate/
  Support/
    helpers.php
    Traits/HasUuid.php
    Traits/HasActivityLog.php
    Enums/UserRole.php
    Enums/ActivityAction.php
resources/
  views/components/        ← shared Blade components (buttons, inputs, cards)
  views/livewire/          ← only if a livewire view isn't co-located
  lang/en/ , lang/ur/ , lang/hi/ , lang/fa/ , lang/ur-roman/
routes/
  web.php        ← includes route files per module
  modules/articles.php
  modules/courses.php
  modules/research.php
  admin.php
```

Rule: a Controller/Model/Service/Migration/Test ALWAYS lives in its module
folder. Only truly shared, cross-module code goes in `app/Support/`.
