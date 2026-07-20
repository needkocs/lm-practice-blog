<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use App\Models\Post;
use App\Models\PostAccessRequest;
use App\Policies\PostAccessRequestPolicy;
use App\Policies\PostPolicy;
use App\Repositories\Contracts\FollowRepositoryInterface;
use App\Repositories\Contracts\PostAccessRequestRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\EloquentFollowRepository;
use App\Repositories\Eloquent\EloquentPostAccessRequestRepository;
use App\Repositories\Eloquent\EloquentPostRepository;
use App\Repositories\Eloquent\EloquentTagRepository;
use App\Repositories\Eloquent\EloquentUserRepository;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
        $this->app->bind(PostRepositoryInterface::class, EloquentPostRepository::class);
        $this->app->bind(FollowRepositoryInterface::class, EloquentFollowRepository::class);
        $this->app->bind(TagRepositoryInterface::class, EloquentTagRepository::class);
        $this->app->bind(PostAccessRequestRepositoryInterface::class, EloquentPostAccessRequestRepository::class);
        $this->app->bind(StatefulGuard::class, fn () => Auth::guard());
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        Gate::policy(Post::class, PostPolicy::class);
        Gate::policy(PostAccessRequest::class, PostAccessRequestPolicy::class);

        RateLimiter::for('login', fn (Request $request): Limit => Limit::perMinute(5)->by($request->input('email').$request->ip()));
        RateLimiter::for('follows', fn (Request $request): Limit => Limit::perMinute(20)->by($request->user()?->id ?: $request->ip()));
        RateLimiter::for('access-requests', fn (Request $request): Limit => Limit::perMinute(10)->by($request->user()?->id ?: $request->ip()));
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
