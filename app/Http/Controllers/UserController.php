<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\IRole;
use App\Repositories\Contracts\IUser;
use App\Repositories\Eloquent\Criteria\EagerLoad;
use App\Repositories\Eloquent\Criteria\LatestFirst;

class UserController extends Controller
{
    // public $headers = collect(
    //     [ 'text' => 'Id', 'value' => 'id'],
    //     [ 'text' => 'Name', 'value' => 'name'],
    //     [ 'text' => 'Email', 'value' => 'email' ],
    //     [ 'text' => 'Role', 'value' => 'role'],
    //     [ 'text' => 'Actions', 'value' => 'actions', 'sortable' => false]
    // );

    protected $users;
    protected $roles;

    public function __construct(IUser $users, IRole $roles)
    {
       $this->users = $users;
       $this->roles = $roles;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()    {


        $this->authorize('view', User::class);

        $result = $this->users->withCriteria([
            new EagerLoad(['role']),
            new LatestFirst()
        ])->all();

        $allRoles = $this->roles->all();

        $result = $result->map(function($user) {
            return [
                'id' => $user->id,
                'gender' => $user->gender,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'name' => $user->name,
                'email' => $user->email,
                'role_id' => $user->role_id,
                'role' => $user->role->role,
                'created_at' => Carbon::parse($user->created_at)->format('d/m/Y')
            ];
        });

        $allRoles = $allRoles->map(function($role) {
            return [
                'text' => $role->role,
                'value' => $role->id
            ];
        });

        return view('superadmin.users', ['data' => $result, 'roles' => $allRoles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->authorize('create', User::class);

        // Validate request
        $validated = $this->validateUser($request);

        // Create User with relationships
        if($validated) {
            $password = substr($request['last_name'],0,4) . date("Y");
            $newUser = $this->users->create([
                'gender' => $request['gender'],
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'name' => $request['first_name'] . " " . $request['last_name'],
                'email' => $request['email'],
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make($password),
                'role_id' => $request->role_id
            ]);
            return new UserResource($newUser);
        } else {
            return response()->json(['message', __('site.input_not_valid')], 400);
         }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('update', User::class);

        // Validate request
         $validated = $this->validateUser($request, $id);

         // Create User with relationships
         if($validated) {
            $updatedUser = $this->users->update( $id, [
                'gender' => $request['gender'],
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'name' => $request['first_name'] . " " . $request['last_name'],
                'email' => $request['email'],
                'role_id' => $request->role_id
            ]);

            return new UserResource($updatedUser);

         } else {
            return response()->json(['message', __('site.input_not_valid')], 400);
         }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', User::class);

        $this->users->delete($id);
        return response()->json(['response' => 1 ], 200);
    }


    protected function validateUser($request, $id = null)
    {

        $email = $id == null ? 'unique:users,email' : 'unique:users,email,'. $id;

        $request->validate([
            'gender' => ['required', 'string', 'max:7'],
            'first_name' => ['nullable', 'string', 'max:15'],
            'last_name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'nullable', 'email', 'max:255', $email],
        ]);
        return true;
    }
}
