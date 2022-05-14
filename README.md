# Laravel Package Generator

The purpose of this package is to generate packages in order to increase productivity.

### Installation
If you installed **[Packagified Laravel](https://github.com/bulentAkgul/packagified-laravel)**, you should have this package already. So skip installation.
```
sail composer require bakgul/laravel-package-generator
```
Next, you need to publish the settings through executing the following command. By doing so, you will have a new file named **packagify.php** in the config folder. If you check the "**files**" array, you can see the file types that can be created. A quite deep explanations are provided in the comment block of the files array.
```
sail artisan packagify:publish-config
```

### Signature
```
create:package {package} {root} {--d|dev}
```

### Expected Inputs
+ **Package**: It's required and must be unique.

+ **Root**: It's required and must be one of the defined roots in the "**roots**" array on *config/packagify.php*

+ **Dev**: If you create a dev-dependency, add "**-d**" or "**--dev**" to the command. 

## Packagified Laravel

The main package that includes this one can be found here: **[Packagified Laravel](https://github.com/bulentAkgul/packagified-laravel)**

## The Packages That Will Be Installed By This Package
+ **[Command Evaluator](https://github.com/bulentAkgul/command-evaluator)**
+ **[File Content](https://github.com/bulentAkgul/file-content)**
+ **[File History](https://github.com/bulentAkgul/file-history)**
+ **[Kernel](https://github.com/bulentAkgul/kernel)**