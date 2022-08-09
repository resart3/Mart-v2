<?php

namespace App\Http\Controllers\Views;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $user = User::get();
        $title = 'Halaman User';
        return view('user', compact('user', 'title'));        
    }

    public function view_login(){
        if (session()->get('user')){
            return redirect('dashboard');
        }

        return view('login');
    }

    public function login(Request $request)
    {        
        if(Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            $user = Auth::user();

            if($user->role != 'superuser'){
                return redirect()->back()->with('error', 'Email Atau Password Anda Salah');
            }

            request()->session()->put('user', Auth::user());
            return redirect('dashboard');
        }

        return redirect()->back()->with('error', 'Email Atau Password Anda Salah');
    }

    public function profile(){

    }

    public function logout(){
        request()->session()->forget('user');
        Auth::logout();

        return redirect('user/login')->with('success', 'Sukses Melakukan Logout');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return Response
     */
    public function store(Request $request){

        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|max:255',
        //     'email' => 'required|email|unique:users,email',
        //     'role' => 'required',
        //     'password' => 'required|min:8',
        //     'confirm password' => 'required|same:password'

        // ]); // create the validations
        // if ($validator->fails())   //check all validations are fine, if not then redirect and show error messages
        // {
        //     return redirect()->route('user.index')->with('failed','User baru gagal ditambahkan!');  
        //     // validation failed return back to form

        // } else {
        //     //validations are passed, save new user in database
        //     $User = new User;
        //     $User->name = $request->name;
        //     $User->email = $request->email;
        //     $User->nik = $request->nik;
        //     $User->password = bcrypt($request->password);
        //     $User->role = $request->role;
        //     $User->save();
            
        //     return redirect()->route('user.index')->with('success','User baru berhasil ditambahkan!');  
           
        // }

        // $validateData = $request->validate([
        //     'name' => 'required|max:255',
        //     'email' => 'required|email|unique:users,email',
        //     'role' => 'required',
        //     'nik' => 'required',
        //     'password' => 'required|min:8',
        //     'confirm_password' => 'required|same:password'
        // ]);

        // // $data = [
        // //     'name'=>$request->input('name'),
        // //     'email'=>$request->input('email'),
        // //     'nik'=>$request->input('nik'),
        // //     // 'family_card_id' => $request->input('nomor'),
        // //     'role'=>$request->input('role'),
        // //     'password'=>bcrypt($request->input('password')),
        // // ];
        // User::create($validateData);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',   // required and email format validation
            'password' => 'required|min:8', // required and number field validation
            'confirm_password' => 'required|same:password',

        ]); // create the validations
        if ($validator->fails())   //check all validations are fine, if not then redirect and show error messages
        {
            return response()->json($validator->errors(),422);  
            // validation failed return back to form

        } else {
            //validations are passed, save new user in database
            $User = new User;
            $User->name = $request->name;
            $User->email = $request->email;
            $User->nik = $request->nik;
            $User->role = $request->role;
            $User->password = bcrypt($request->password);
            $User->save();
            
            return redirect()->route('user.index')->with('success','User baru berhasil ditambahkan!');
        }
        // return redirect()->route('user.index')->with('success','User baru berhasil ditambahkan!');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @return Response     
     * @param  int  $id     
     */
    public function update(Request $request, $id)
    {
        $updateData = $request->all();
        $userId = User::FindOrFail($id);
        $userId->update($updateData);
        dd("Berhasil Terganti");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $tarif = User::where('id', $id)->delete();
        // redirect ke parentView
        return redirect()->route('user.index')->with('success','Data User berhasil dihapus!');
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
        $user = User::find($id);

        if(isset($user) == TRUE){
            return response()->json($user);
        }else{
            return response()->json("Data User Tidak Ditemukan!");
        }
    }
}