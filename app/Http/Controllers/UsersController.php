<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use \App\Models\User;
use \App\Models\Department;
use App\Models\Roles;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles','department')->withCount(['login_attempts' => function($query){
            $query->where('status', 'success');
        }])->take(50)->get();

        $departments = Department::all();

        return Inertia::render('Users/Index', ['users' => $users, 'departments' => $departments]);
    }

    /**
     * Function to search for a user
     */
    public function search(Request $request){
        $users = User::when($request->input('form.user_code'), function ($query) use ($request) {
            $query->where('user_code', $request->input('form.user_code'));
        }, function ($query) use ($request) {
            $query->where(function($q) use ($request) {
                $q->when($request->input('form.name'), function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->input('form.name') . '%');
                })->when($request->input('form.phone'), function ($q) use ($request) {
                    $q->orWhere('phone', 'like', '%' . $request->input('form.phone') . '%');
                })->when($request->input('form.email'), function ($q) use ($request) {
                    $q->orWhere('email', 'like', '%' . $request->input('form.email') . '%');
                })->when($request->input('form.department_id'), function ($q) use ($request) {
                    $q->orWhere('department_id', $request->input('form.department_id'));
                });
            });
        })
        ->with('roles', 'department')
        ->withCount(['login_attempts' => function ($query) {
            $query->where('status', 'success');
        }])
        ->take(50)
        ->get();

        return response()->json([ 'users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        $roles = Roles::all();

        return Inertia::render('Users/Create', ['departments' => $departments, 'roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'form.name' => 'required',
            'form.email' => 'required|unique:users,email',
            'form.password' => 'required',
            'form.phone' => 'required',
            'form.department_id' => 'required|exists:departments,id',
            'form.status' => 'required',
            'role' => 'required'
        ],[
            'form.name.required' => 'The full name field is required',
            'form.email.required' => 'The email field is required',
            'form.email.unique' => 'There is already a user with that email',
            'form.password.required' => 'The password field is required',
            'form.phone.required' => 'The phone field is required',
            'form.department_id.required' => 'You must select a department',
            'form.status.required' => 'You must select a status',
            'role.required' => 'You must select a role'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = $request->input('form');

        $user['password'] = Hash::make($user['password']);

        User::create($user)->assignRole($request->input('role'));

        return redirect()->route('users.index')->with(['message' => 'User created successfully', 'status' => 'success']);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user->load([
            'roles',
            'login_attempts' => function ($query) {
                $query->latest()->limit(100);
            }
        ]);
        $departments = Department::all();
        $roles = Roles::all();

        return Inertia::render('Users/Edit', ['departments' => $departments, 'roles' => $roles, 'user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'form.name' => 'required',
            'form.email' => 'required|unique:users,email,'.$request->input('id'),
            'form.phone' => 'required',
            'form.department_id' => 'required|exists:departments,id',
            'form.status' => 'required',
            'role' => 'required'
        ],[
            'form.name.required' => 'The full name field is required',
            'form.email.required' => 'The email field is required',
            'form.email.unique' => 'There is already a user with that email',
            'form.password.required' => 'The password field is required',
            'form.phone.required' => 'The phone field is required',
            'form.department_id.required' => 'You must select a department',
            'form.status.required' => 'You must select a status',
            'role.required' => 'You must select a role'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $user = User::find($request->input('id'));

        $user->name = $request->input('form.name');
        $user->email = $request->input('form.email');
        $user->department_id = $request->input('form.department_id');
        $user->phone = $request->input('form.phone');
        $user->status = $request->input('form.status');
        if(!empty($request->input('form.password'))){
            $user->password = Hash::make($request->input('form.password'));
        }

        if($user->save()){
            if(!$user->hasRole($request->input('role'))){
                foreach($user->getRoleNames() as $value){
                    $user->removeRole($value);
                }
                $user->assignRole($request->input('role'));
            }
            return redirect()->route('users.index')->with(['message' => 'User successfully updated', 'status' => 'success']);
        }

        return redirect()->back()->withInput()->with(['message' => 'Ocurrio un error, vuelve a intentarlo', 'status' => 'error']);
    }

    /**
     * Function to block a user
     */
    public function block(User $user){
        $user->status = ($user->status == 'active')? 'inactive':'active';
        if($user->save()){
            return response()->json([ 'status' => true,'Ustatus' => $user->status]);
        }
        return response()->json([ 'status' => false]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([ 'status' => true]);
    }

    /**
     * Function to download a PDF with the user's login attempts
     */
    public function attemptsPdf(User $user){
        $user->load('login_attempts');

        $pdf = Pdf::loadView('pdf.userLoginAtemps', compact('user'));
        return $pdf->download('login-attempts-user-' . $user->id . '.pdf');
    }

    /**
     * Function to make a massive import of users from an Excel file
     */
    public function import(Request $request){
        $validator = Validator::make($request->all(), ['file' => 'required|file|mimes:xls,xlsx',], [
            'file.required' => 'The file is required',
            'file.file' => 'The file is not a file',
            'file.mimes' => 'The file is not an Excel file'
        ]);

        if($validator->fails()){
            return redirect()->json(['status' => false, 'errors' => $validator]);
        }

        $file = $request->file('file');

        try {
            Excel::import(new UsersImport, $file);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            return response()->json(['status' => false, 'errors' => $e->failures()]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'errors' => $e->getMessage()]);
        }

        $users = User::with('roles','department')->withCount(['login_attempts' => function($query){
            $query->where('status', 'success');
        }])->take(50)->get();

        return response()->json(['status' => true, 'users' => $users, 'menssage']);
    }
}
