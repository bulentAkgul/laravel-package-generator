# Laravel Package Generator

This package aims to generate packages to increase productivity. It offers a flexible package structure tanks to settings that can be found in the **structures** array on *config/packagify.php*. The detailed explanations can be found in that array's comment block.

#### DISCLAIMER

It should be production-ready but hasn't been tested enough. You should use it carefully since this package will manipulate your files and folders. Always use a version-control, and make sure you have [**File History**](https://github.com/bulentAkgul/file-history) to be able to roll back the changes.

### Installation

If you installed [**Packagified Laravel**](https://github.com/bulentAkgul/packagified-laravel), you should have this package already. So skip installation.
```
composer require bakgul/laravel-package-generator
```

Next, you need to publish the settings by executing the following command. By doing so, you will have a new file named *config/packagify.php* in the config folder. If you check the "**files**" array, you can see the file types that can be created. Quite deep explanations are provided in the comment block of the files array.

```
sail artisan packagify:publish-config
```

### Signature
```
create:package {package} {root} {--d|dev}
```
### Arguments

-   **package**: It's required and must be unique.

-   **root**: It's required and must be one of the predefined roots in the "**roots**" array on *config/packagify.php.* The roots' keys or folders can be used here.

### Options

-   **dev**: If you create a dev-dependency, add "**-d**" or "**--dev**" to the command.

## Packagified Laravel

The main package that includes this one can be found here: [**Packagified Laravel**](https://github.com/bulentAkgul/packagified-laravel)

## The Packages That Will Be Installed By This Package

-   [**Command Evaluator**](https://github.com/bulentAkgul/command-evaluator)
-   [**File Content**](https://github.com/bulentAkgul/file-content)
-   [**File History**](https://github.com/bulentAkgul/file-history)
-   [**Kernel**](https://github.com/bulentAkgul/kernel)