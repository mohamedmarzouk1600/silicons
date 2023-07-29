## For new feature, use this flow
- Create your branch from `develop` via `git checkout -b feature/20220306_{featureName}`

## To create crud
- `php artisan make:model ModelName -m`
- Update your migration file, with needed columns
- `php artisan make:crud ModelName ModelName`
- `composer du` to autoload factories
- `php artisan test` to test your crud
- Update controller/service to match needed design/UI and language phrases

## To sync all used `__('language phrase'')` to ar.json/en.json
- use `php artisan language:sync ar/en --nofurther`
