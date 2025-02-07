<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function admin () {
        $admins = User::where('role', 'admin')->paginate(10);
        return view('dashboard.admin.index', ['admins'=>$admins]);
    }

    public function officer (Request $request) {
        if($request->has('search')){
        $officers = User::where('name', 'LIKE', "%{$request->search}%")->where('role', 'officer')->paginate(10);
        } else {
        $officers = User::where('role', 'officer')->paginate(10);
        }
        return view('dashboard.officer.index', ['officers'=>$officers]);
    }

    public function delete($id) {
        $user = User::findOrFail($id);
        $deletedUser = $user->delete();

        if($deletedUser){
            session()->flash('message', 'Data Successfully Delete');
            return response()->json(['message'=> 'Data Successfully Delete'],200);
        }
    }

    public function createOfficer () {
       return view('dashboard.officer.input');
    }

    public function storeOfficer (Request $request) {
        $this->validate($request, [
                'name'=>['required'],
                'email'=>['required'],
                'password'=>['required'],
                'phone' => 'nullable|regex:/^\+62[0-9]{9,13}$/'
        ]);

        $userCreated = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>'officer',
            'phone' => $request->phone
        ]);

        if($userCreated){
            return redirect('/petugas')->with('message','Data Successfully Added');
        }
    }

    public function editOfficer ($id) {
        $officer = User::findOrFail($id);
        return view('dashboard.officer.update', ['officer'=>$officer]);
    }

    public function updateOfficer (Request $request, $id) {
        $this->validate($request, [
                'name'=>['required'],
                'email'=>['required'],
                // 'password'=>['required'],
                // 'phone' => 'nullable|regex:/^\+62[0-9]{9,13}$/'
        ]);

        $officer = User::findOrFail($id);

        if($request->has('password')) {
            $password = Hash::make($request->password);
        } else {
             $password = $officer->password;
        }

        $updated = $officer->update([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$password,
            'role'=>'officer',
            'phone' => $request->phone
        ]);

        if($updated){
            return redirect('/petugas')->with('message','Data Successfully Added');
        }
    }

    public function createAdmin () {
       return view('dashboard.admin.input');
    }


    public function storeAdmin (Request $request) {
        $this->validate($request, [
                'name'=>['required'],
                'email'=>['required'],
                'password'=>['required'],
                'phone' => 'nullable|regex:/^\+62[0-9]{9,13}$/'

        ]);

        $userCreated = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'role'=>'admin',
            'phone' => $request->phone
        ]);

        if($userCreated){
            return redirect('/admin')->with('message','Data Successfully Added');
        }
    }

    public function editAdmin ($id) {
        $admin = User::findOrFail($id);
        return view('dashboard.admin.update', ['admin'=>$admin]);
    }

    public function updateAdmin (Request $request, $id) {
    $this->validate($request, [
            'name'=>['required'],
            'email'=>['required'],
    ]);

    $admin = User::findOrFail($id);

    if($request->has('password')) {
        $password = Hash::make($request->password);
    } else {
        $password = $admin->password;
    }

    $updated = $admin->update([
        'name'=>$request->name,
        'email'=>$request->email,
        'password'=>$password,
        'role'=>'admin',
        'phone' => $request->phone
    ]);

    if($updated){
        return redirect('/admin')->with('message','Data Successfully Updated');
    }
    }

}
