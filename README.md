# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/khant-nyar/service-extender.svg?style=flat-square)](https://packagist.org/packages/khant-nyar/service-extender)
[![Total Downloads](https://img.shields.io/packagist/dt/khant-nyar/service-extender.svg?style=flat-square)](https://packagist.org/packages/khant-nyar/service-extender)
![GitHub Actions](https://github.com/khant-nyar/service-extender/actions/workflows/main.yml/badge.svg)

- ✅ Improved Type Safety – Helps with debugging and prevents invalid data types.
- ✅ IDE-Friendly Navigation – Ctrl+Click methods to jump to service class.
- ✅ Automatic Transactions – Prevents database inconsistencies.
- ✅ Logging Enabled – Tracks changes for debugging.
- ✅ Easily Extendable – New services can override methods as needed.

## Installation

You can install the package via composer:

```bash
composer require khant-nyar/service-extender
```

## Usage

```php
/** 
 * Example uses with UserService 
 */

namespace App\Services;

use App\Models\User;
use KhantNyar\ServiceExtender\Services\EloquenceService;

class UserService extends EloquenceService
{
    protected static string $model = User::class;
}
```
it will generate automacally for UserService::all(),find($id),create($data)

```php
/**
 * Example uses in controller
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(UserService::all());
    }

    public function show($id)
    {
        return response()->json(UserService::find((int) $id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6'
        ]);

        return response()->json(UserService::create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|min:6'
        ]);

        return response()->json(UserService::update((int) $id, $data));
    }

    public function destroy($id)
    {
        return response()->json(['success' => UserService::delete((int) $id)]);
    }
}

```


### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email khantnyar.dev@gmail.com instead of using the issue tracker.

## Credits

-   [Khant-Nyar](https://github.com/khant-nyar)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
