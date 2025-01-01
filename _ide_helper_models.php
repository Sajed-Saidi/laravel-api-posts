<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models {
	/**
	 * 
	 *
	 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Post> $posts
	 * @property-read int|null $posts_count
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newModelQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category newQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category onlyTrashed()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category query()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category withTrashed()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Category withoutTrashed()
	 * @mixin \Eloquent
	 */
	class Category extends \Eloquent
	{
	}
}

namespace App\Models {
	/**
	 * 
	 *
	 * @property-read \App\Models\Post|null $post
	 * @property-read User|null $user
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newModelQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment newQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment onlyTrashed()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment query()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment withTrashed()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Comment withoutTrashed()
	 * @mixin \Eloquent
	 */
	class Comment extends \Eloquent
	{
	}
}

namespace App\Models {
	/**
	 * 
	 *
	 * @property-read \App\Models\Post|null $post
	 * @property-read User|null $user
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Like newModelQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Like newQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Like query()
	 * @mixin \Eloquent
	 */
	class Like extends \Eloquent
	{
	}
}

namespace App\Models {
	/**
	 * 
	 *
	 * @property int $id
	 * @property string $title
	 * @property string $slug
	 * @property string $content
	 * @property string|null $excerpt
	 * @property string $status
	 * @property int $user_id
	 * @property \Illuminate\Support\Carbon|null $created_at
	 * @property \Illuminate\Support\Carbon|null $updated_at
	 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
	 * @property-read int|null $categories_count
	 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Comment> $comments
	 * @property-read int|null $comments_count
	 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Like> $likes
	 * @property-read int|null $likes_count
	 * @property-read User $user
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post newModelQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post newQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post onlyTrashed()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post query()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereContent($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereCreatedAt($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereExcerpt($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereId($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereSlug($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereStatus($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereTitle($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereUpdatedAt($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post whereUserId($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post withTrashed()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|Post withoutTrashed()
	 * @mixin \Eloquent
	 */
	class Post extends \Eloquent
	{
	}
}

namespace App\Models {
	/**
	 * 
	 *
	 * @property int $id
	 * @property string $name
	 * @property string $email
	 * @property \Illuminate\Support\Carbon|null $email_verified_at
	 * @property string $password
	 * @property string|null $remember_token
	 * @property \Illuminate\Support\Carbon|null $created_at
	 * @property \Illuminate\Support\Carbon|null $updated_at
	 * @property-read \Illuminate\Database\Eloquent\Collection<int, Comment> $comments
	 * @property-read int|null $comments_count
	 * @property-read \Illuminate\Database\Eloquent\Collection<int, Like> $likes
	 * @property-read int|null $likes_count
	 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
	 * @property-read int|null $notifications_count
	 * @property-read \Illuminate\Database\Eloquent\Collection<int, Post> $posts
	 * @property-read int|null $posts_count
	 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
	 * @property-read int|null $tokens_count
	 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
	 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
	 * @mixin \Eloquent
	 */
	class User extends \Eloquent
	{
	}
}
