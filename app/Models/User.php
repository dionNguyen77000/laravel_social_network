<?php

namespace Social_Net\Models;

use Social_Net\Models\Status;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;


class User extends Model implements AuthenticatableContract
{
    use Authenticatable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_name', 'email', 'password', 'first_name', 'last_name', 'location',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the name of user
     */
    public function getName()
    {
        if ($this->first_name && $this->last_name){
            return "{$this->first_name} {$this->last_name}";
        }
        if($this->first_name){
            return $this->first_name;
        }
        return null;
    }

    public function getNameOrUsername()
    {
        return $this->getName() ?: $this->username;
    }

    public function getFirstNameOrUsername()
    {
        return $this->first_name ?: $this->username;
    }

    public function getAvatarUrl()
    {
        return "https://www.gravatar.com/avatar/{{md5($this->email)}}?d=mm&s=40";
    }

    public function statuses()
    {
        return $this->hasMany('Social_Net\Models\Status', 'user_id');
    }

    public function likes(){
        return $this->hasMany('Social_Net\Models\Like', 'user_id');
    }
    // who is my friends?
    public function friendsOfMine()
    {
         return $this->belongsToMany('Social_Net\Models\User', 'friends', 'user_id', 'friend_id');
    }

    // i am friend of whom?
    public function friendOf()
    {
        return $this -> belongsToMany('Social_Net\Models\User', 'friends', 'friend_id', 'user_id');
    }

    // return who is my friends and am friends of whom
    public function friends()
    {
        return $this->friendsOfMine()->wherePivot('accepted', true)->get()->merge($this->friendOf()
        ->wherePivot('accepted',true)->get());
    }

    // get all of people who requesting me as friends
    public function friendRequests()
    {
        return $this->friendsOfMine()->wherePivot('accepted',false)->get();
    }

    // get all of people who i am sending them friend request
    public function friendRequestsPending()
    {
        return $this->friendOf()->wherePivot('accepted', false)->get();
    }

    // check if $user has a friend requesting pending from me
    public function hasFriendRequestPending(User $user)
    {
        return (bool) $this->friendRequestsPending()->where('id', $user->id)->count();
    }
    // check if $user already sending request to me
    public function hasFriendRequestRecieved(User $user)
    {
        return (bool) $this->friendRequests()->where('id', $user->id)->count();
    }

    public function addFriend(User $user)
    {
        $this->friendOf()->attach($user->id);
    }

    public function acceptFriendRequest(User $user)
    {
        $this->friendRequests()->where('id',$user->id)->first()->pivot
            ->update([
               'accepted' => true,
            ]);
    }

    //return whether $user is my friend or I am friend of that $user
    public function isFriendsWith(User $user)
    {
        return (bool) $this->friends()->where('id', $user->id)->count();
    }
    /*If user already liked $status*/
    public function hasLikedStatus(Status $status)
    {
        return (bool) $status->likes
            ->where('user_id', $this->id)
            ->count();
    }

}
