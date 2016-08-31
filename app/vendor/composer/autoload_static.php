<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7550dad7bea3eb7d310375e3cd67e32c
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SoftnCMS\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SoftnCMS\\' => 
        array (
            0 => __DIR__ . '/../../..' . '/app',
        ),
    );

    public static $classMap = array (
        'SoftnCMS\\controllers\\BaseController' => __DIR__ . '/../../..' . '/app/controllers/BaseController.php',
        'SoftnCMS\\controllers\\Config' => __DIR__ . '/../../..' . '/app/controllers/config/Config.php',
        'SoftnCMS\\controllers\\ConfigCreate' => __DIR__ . '/../../..' . '/app/controllers/config/ConfigCreate.php',
        'SoftnCMS\\controllers\\Controller' => __DIR__ . '/../../..' . '/app/controllers/Controller.php',
        'SoftnCMS\\controllers\\DBController' => __DIR__ . '/../../..' . '/app/controllers/DBController.php',
        'SoftnCMS\\controllers\\LoginController' => __DIR__ . '/../../..' . '/app/controllers/LoginController.php',
        'SoftnCMS\\controllers\\LogoutController' => __DIR__ . '/../../..' . '/app/controllers/LogoutController.php',
        'SoftnCMS\\controllers\\Messages' => __DIR__ . '/../../..' . '/app/controllers/Messages.php',
        'SoftnCMS\\controllers\\RegisterController' => __DIR__ . '/../../..' . '/app/controllers/RegisterController.php',
        'SoftnCMS\\controllers\\Request' => __DIR__ . '/../../..' . '/app/controllers/Request.php',
        'SoftnCMS\\controllers\\Router' => __DIR__ . '/../../..' . '/app/controllers/Router.php',
        'SoftnCMS\\controllers\\ViewController' => __DIR__ . '/../../..' . '/app/controllers/ViewController.php',
        'SoftnCMS\\controllers\\admin\\CategoryController' => __DIR__ . '/../../..' . '/app/controllers/admin/CategoryController.php',
        'SoftnCMS\\controllers\\admin\\CommentController' => __DIR__ . '/../../..' . '/app/controllers/admin/CommentController.php',
        'SoftnCMS\\controllers\\admin\\IndexController' => __DIR__ . '/../../..' . '/app/controllers/admin/IndexController.php',
        'SoftnCMS\\controllers\\admin\\OptionController' => __DIR__ . '/../../..' . '/app/controllers/admin/OptionController.php',
        'SoftnCMS\\controllers\\admin\\PostController' => __DIR__ . '/../../..' . '/app/controllers/admin/PostController.php',
        'SoftnCMS\\controllers\\admin\\TermController' => __DIR__ . '/../../..' . '/app/controllers/admin/TermController.php',
        'SoftnCMS\\controllers\\admin\\UserController' => __DIR__ . '/../../..' . '/app/controllers/admin/UserController.php',
        'SoftnCMS\\controllers\\exceptions\\ExceptionBase' => __DIR__ . '/../../..' . '/app/controllers/exceptions/ExceptionBase.php',
        'SoftnCMS\\controllers\\themes\\IndexController' => __DIR__ . '/../../..' . '/app/controllers/themes/IndexController.php',
        'SoftnCMS\\models\\Login' => __DIR__ . '/../../..' . '/app/models/Login.php',
        'SoftnCMS\\models\\MySql' => __DIR__ . '/../../..' . '/app/models/mysql.php',
        'SoftnCMS\\models\\Register' => __DIR__ . '/../../..' . '/app/models/Register.php',
        'SoftnCMS\\models\\admin\\Categories' => __DIR__ . '/../../..' . '/app/models/admin/Categories.php',
        'SoftnCMS\\models\\admin\\Category' => __DIR__ . '/../../..' . '/app/models/admin/Category.php',
        'SoftnCMS\\models\\admin\\CategoryDelete' => __DIR__ . '/../../..' . '/app/models/admin/CategoryDelete.php',
        'SoftnCMS\\models\\admin\\CategoryInsert' => __DIR__ . '/../../..' . '/app/models/admin/CategoryInsert.php',
        'SoftnCMS\\models\\admin\\CategoryUpdate' => __DIR__ . '/../../..' . '/app/models/admin/CategoryUpdate.php',
        'SoftnCMS\\models\\admin\\Comment' => __DIR__ . '/../../..' . '/app/models/admin/Comment.php',
        'SoftnCMS\\models\\admin\\CommentDelete' => __DIR__ . '/../../..' . '/app/models/admin/CommentDelete.php',
        'SoftnCMS\\models\\admin\\CommentInsert' => __DIR__ . '/../../..' . '/app/models/admin/CommentInsert.php',
        'SoftnCMS\\models\\admin\\CommentUpdate' => __DIR__ . '/../../..' . '/app/models/admin/CommentUpdate.php',
        'SoftnCMS\\models\\admin\\Comments' => __DIR__ . '/../../..' . '/app/models/admin/Comments.php',
        'SoftnCMS\\models\\admin\\Option' => __DIR__ . '/../../..' . '/app/models/admin/Option.php',
        'SoftnCMS\\models\\admin\\OptionUpdate' => __DIR__ . '/../../..' . '/app/models/admin/OptionUpdate.php',
        'SoftnCMS\\models\\admin\\Options' => __DIR__ . '/../../..' . '/app/models/admin/Options.php',
        'SoftnCMS\\models\\admin\\Post' => __DIR__ . '/../../..' . '/app/models/admin/Post.php',
        'SoftnCMS\\models\\admin\\PostCategory' => __DIR__ . '/../../..' . '/app/models/admin/PostCategory.php',
        'SoftnCMS\\models\\admin\\PostCategoryDelete' => __DIR__ . '/../../..' . '/app/models/admin/PostCategoryDelete.php',
        'SoftnCMS\\models\\admin\\PostCategoryInsert' => __DIR__ . '/../../..' . '/app/models/admin/PostCategoryInsert.php',
        'SoftnCMS\\models\\admin\\PostDelete' => __DIR__ . '/../../..' . '/app/models/admin/PostDelete.php',
        'SoftnCMS\\models\\admin\\PostInsert' => __DIR__ . '/../../..' . '/app/models/admin/PostInsert.php',
        'SoftnCMS\\models\\admin\\PostTerm' => __DIR__ . '/../../..' . '/app/models/admin/PostTerm.php',
        'SoftnCMS\\models\\admin\\PostTermDelete' => __DIR__ . '/../../..' . '/app/models/admin/PostTermDelete.php',
        'SoftnCMS\\models\\admin\\PostTermInsert' => __DIR__ . '/../../..' . '/app/models/admin/PostTermInsert.php',
        'SoftnCMS\\models\\admin\\PostUpdate' => __DIR__ . '/../../..' . '/app/models/admin/PostUpdate.php',
        'SoftnCMS\\models\\admin\\Posts' => __DIR__ . '/../../..' . '/app/models/admin/Posts.php',
        'SoftnCMS\\models\\admin\\PostsCategories' => __DIR__ . '/../../..' . '/app/models/admin/PostsCategories.php',
        'SoftnCMS\\models\\admin\\PostsTerms' => __DIR__ . '/../../..' . '/app/models/admin/PostsTerms.php',
        'SoftnCMS\\models\\admin\\Term' => __DIR__ . '/../../..' . '/app/models/admin/Term.php',
        'SoftnCMS\\models\\admin\\TermDelete' => __DIR__ . '/../../..' . '/app/models/admin/TermDelete.php',
        'SoftnCMS\\models\\admin\\TermInsert' => __DIR__ . '/../../..' . '/app/models/admin/TermInsert.php',
        'SoftnCMS\\models\\admin\\TermUpdate' => __DIR__ . '/../../..' . '/app/models/admin/TermUpdate.php',
        'SoftnCMS\\models\\admin\\Terms' => __DIR__ . '/../../..' . '/app/models/admin/Terms.php',
        'SoftnCMS\\models\\admin\\User' => __DIR__ . '/../../..' . '/app/models/admin/User.php',
        'SoftnCMS\\models\\admin\\UserDelete' => __DIR__ . '/../../..' . '/app/models/admin/UserDelete.php',
        'SoftnCMS\\models\\admin\\UserInsert' => __DIR__ . '/../../..' . '/app/models/admin/UserInsert.php',
        'SoftnCMS\\models\\admin\\UserUpdate' => __DIR__ . '/../../..' . '/app/models/admin/UserUpdate.php',
        'SoftnCMS\\models\\admin\\Users' => __DIR__ . '/../../..' . '/app/models/admin/Users.php',
        'SoftnCMS\\models\\admin\\base\\BaseModels' => __DIR__ . '/../../..' . '/app/models/admin/base/BaseModels.php',
        'SoftnCMS\\models\\admin\\base\\IModel' => __DIR__ . '/../../..' . '/app/models/admin/base/IModel.php',
        'SoftnCMS\\models\\admin\\base\\IModels' => __DIR__ . '/../../..' . '/app/models/admin/base/IModels.php',
        'SoftnCMS\\models\\admin\\base\\Model' => __DIR__ . '/../../..' . '/app/models/admin/base/Model.php',
        'SoftnCMS\\models\\admin\\base\\ModelDelete' => __DIR__ . '/../../..' . '/app/models/admin/base/ModelDelete.php',
        'SoftnCMS\\models\\admin\\base\\ModelInsert' => __DIR__ . '/../../..' . '/app/models/admin/base/ModelInsert.php',
        'SoftnCMS\\models\\admin\\base\\ModelUpdate' => __DIR__ . '/../../..' . '/app/models/admin/base/ModelUpdate.php',
        'SoftnCMS\\models\\admin\\base\\Models' => __DIR__ . '/../../..' . '/app/models/admin/base/Models.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7550dad7bea3eb7d310375e3cd67e32c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7550dad7bea3eb7d310375e3cd67e32c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7550dad7bea3eb7d310375e3cd67e32c::$classMap;

        }, null, ClassLoader::class);
    }
}