<?php

namespace Module\Comment;

use Dnsoft\Acl\Facades\Permission;
use Dnsoft\Core\Events\CoreAdminMenuRegistered;
use Dnsoft\Core\Support\BaseModuleServiceProvider;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Module\Comment\Events\CommentApproved;
use Module\Comment\Events\CommentCreated;
use Module\Comment\Http\Middleware\Cors;
use Module\Comment\Listeners\CommentApprovalListener;
use Module\Comment\Listeners\CommentCreatedListener;
use Module\Comment\Models\Comment;
use Module\Comment\Models\Customer;
use Module\Comment\Models\Page;
use Module\Comment\Repositories\CommentRepositoryInterface;
use Module\Comment\Repositories\CustomerRepositoryInterface;
use Module\Comment\Repositories\Eloquent\CommentRepository;
use Module\Comment\Repositories\Eloquent\CustomerRepository;
use Module\Comment\Repositories\Eloquent\PageRepository;
use Module\Comment\Repositories\PageRepositoryInterface;

class CommentServiceProvider extends BaseModuleServiceProvider
{
    public function getModuleNamespace()
    {
        return 'comment';
    }

    public function boot()
    {
        parent::boot();

        $routeApi = $this->getModuleDirectory().'/routes/api.php';

        Route::prefix(config('comment.api_prefix'))
            ->group($routeApi);

        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/comment'),
        ], 'comment');

        $this->publishes([
            __DIR__.'/../config/comment.php' => config_path('comment.php'),
        ]);

        require_once __DIR__.'/../helpers/helpers.php';

        $this->registerAdminMenu();
    }

    public function register()
    {
        parent::register();

        $this->app->singleton(CommentRepositoryInterface::class, function () {
            return new CommentRepository(new Comment());
        });

        $this->app->singleton(CustomerRepositoryInterface::class, function () {
            return new CustomerRepository(new Customer());
        });

        $this->app->singleton(PageRepositoryInterface::class, function () {
            return new PageRepository(new Page());
        });

        $this->registerMiddleware();

        $this->registerEvent();
    }

    public function registerPermissions()
    {
        Permission::add('comment.admin.comment.index', __('comment::permission.comment.index'));
        Permission::add('comment.admin.comment.create', __('comment::permission.comment.create'));
        Permission::add('comment.admin.comment.edit', __('comment::permission.comment.edit'));
        Permission::add('comment.admin.comment.destroy', __('comment::permission.comment.destroy'));
        Permission::add('comment.admin.comment.publish', __('comment::permission.comment.publish'));
    }

    public function registerAdminMenu()
    {
        Event::listen(CoreAdminMenuRegistered::class, function ($menu) {
            $menu->add('Setting Comment', [
                'id' => 'comment_root',
                'route' => 'comment.admin.comment.index',
                'parent' => $menu->content->id,
            ])->data('order', 2000)->prepend('<i class="far fa-file-alt"></i>');
        });
    }

    protected function registerMiddleware()
    {
        /** @var Route $router */
        $router = $this->app['router'];
        $router->aliasMiddleware('cors', Cors::class);
    }

    public function registerEvent()
    {
        Event::listen(CommentApproved::class, CommentApprovalListener::class);
        Event::listen(CommentCreated::class, CommentCreatedListener::class);
    }
}
