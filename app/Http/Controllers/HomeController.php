<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{   
    private $post;
    private $user;

    public function __construct(Post $post, User $user)
    {
        $this->post = $post;
        $this->user = $user;
    }
    
    public function index()
    {   
        $home_posts = $this->getHomePosts();
        $suggested_users =  $this->getSuggestedUsers();

        # $all_posts = $this->post->latest()->get();
        # SELECT * FROM posts ORDER BY created_at DESC;

        return view('users.home')
                    ->with('home_posts', $home_posts)
                    ->with('suggested_users', $suggested_users);
    }

    # Get the posts of the users that the Auth user is following
    private function getHomePosts() {
        $all_posts = $this->post->latest()->get();

        $home_posts = [];
        # Initializes an empty array variable for home page posts.

        foreach($all_posts as $post) {
            # This condition checks if the current user follows the author of the post or if the current user is the author themselves. 
            if($post->user->isFollowed() || $post->user->id === Auth::user()->id) {
                $home_posts[] = $post;
            }
        }
        return $home_posts;
    }

    # Get the users that the Auth user is not following
    private function getSuggestedUsers() {
        $all_users = $this->user->all()->except(Auth::user()->id);

        $suggested_users = [];

        foreach($all_users as $user) {
            # This condition checks if the user is not being followed. If true, assigns the user to suggested users.
            if(!$user->isFollowed()) {
                $suggested_users[] = $user;
            }
        }
        return array_slice($suggested_users, 0,5);
    }


    public function search(Request $request) {
        $users = $this->user->where('name', 'like', '%'.$request->search.'%')->get();
        # $users = $this->user: Retrieves user records.
        # ->where('name', 'like', '%'.$request->search.'%'): Filters users by name containing the search term.
        # ->get(): Retrieves filtered user records.

        return view('users.search')
                    ->with('users', $users) #  Passes user data to the view.
                    ->with('search', $request->search); # Passes search term to the view.
    }
}
