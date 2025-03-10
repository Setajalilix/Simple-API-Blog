<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\{
    IUser, IDesign , IComment, ITeam, IInvitation, IChat, IMessage
};
use App\Repositories\Eloquent\{
    UserRepository, DesignRepository , CommentRepository, TeamRepository, InvitationRepository, ChatRepository, MassageRepository
};

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(IDesign::class, DesignRepository::class);
        $this->app->bind(IUser::class, UserRepository::class);
        $this->app->bind(IComment::class, CommentRepository::class);
        $this->app->bind(ITeam::class, TeamRepository::class);
        $this->app->bind(IInvitation::class, InvitationRepository::class);
        $this->app->bind(IChat::class, ChatRepository::class);
        $this->app->bind(IMessage::class, MassageRepository::class);

    }
}
