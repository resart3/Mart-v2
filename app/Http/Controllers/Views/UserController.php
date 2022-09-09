<?php

namespace App\Http\Controllers\Views;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Str;

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

            if($user->role == 'user'){
                dd('masuk if');
                return redirect()->back()->with('error', 'Anda Tidak Memiliki Akses');
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
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',   // required and email format validation
            'password' => 'required|min:8', // required and number field validation
            'confirm_password' => 'required|same:password',

        ]); // create the validations
        if ($validator->fails())   //check all validations are fine, if not then redirect and show error messages
        {
            $error = $validator->errors()->messages();

            if(isset($error["email"])){
                $message = "Email sudah terdaftar!";
            }elseif(isset($error["password"])){
                $message = "Password minimal 8 karakter!";
            }elseif(isset($error["confirm_password"])){
                $message = "Konfirmasi password tidak sama!";
            }

            return redirect()->route('user.index')->with('failed',$message);
        } else {
            //validations are passed, save new user in database
            $User = new User;

            if($request->role == 'user'){
                $dataMember = DB::table('family_members')
                ->select('family_members.nama')
                ->where('nik', $request->nik)
                ->get();
                
                if(isset($dataMember[0])){
                    $User->name = $dataMember[0]->nama;
                }else{
                    return redirect()->route('user.index')->with('failed','NIK Tidak Terdaftar!');
                }

            }elseif($request->role != 'user'){
                $User->name = $request->name;

                $rt = $request->rt;
                $rw = $request->rw;
                $User->rt_rw = $rt.'/'.$rw;
            }

            $User->email = $request->email;
            $User->nik = $request->nik;
            $User->role = $request->role;
            $User->password = bcrypt($request->password);
            $User->remember_token = Str::random(10);
            $User->save();
            
            return redirect()->route('user.index')->with('success','User baru berhasil ditambahkan!');
        }
    }

    /**
     * Update the specified resource in storage.
     *     
     * @return Response     
     * @param  int  $id     
     */
    public function update(Request $request, $id)
    {
        $updateData = $request->all();
        $user = User::FindOrFail($id);

        if($user->email != $updateData['email']){
            if(isset($updateData['password'])){
                $validator = Validator::make($updateData, [
                    'email' => 'required|email|unique:users,email',   // required and email format validation
                    'password' => 'required|min:8', // required and number field validation
                ]);
            }else{
                $validator = Validator::make($updateData, [
                    'email' => 'required|email|unique:users,email',   // required and email format validation
                ]);
            }
        }elseif($user->email == $updateData['email']){
            if(isset($updateData['password'])){
                $validator = Validator::make($updateData, [
                    'password' => 'required|min:8', // required and number field validation
                ]);
            }else{
                $validator = Validator::make($updateData, [
                    'email' => 'required|email',   // required and email format validation
                ]);
            }
        }

        if ($validator->fails())   //check all validations are fine, if not then redirect and show error messages
        {
            $error = $validator->errors()->messages();

            if(isset($error["email"])){
                $message = "Email sudah terdaftar!";
            }elseif(isset($error["password"])){
                $message = "Password minimal 8 karakter!";
            }elseif(isset($error["confirm_password"])){
                $message = "Konfirmasi password tidak sama!";
            }

            return response()->json($message);
        }else{
            $user->name = $updateData['name'];
            $user->email = $updateData['email'];
            $user->nik = $updateData['nik'];
            $user->role = $updateData['role'];
    
            if(isset($updateData['password'])){
                $user->password = bcrypt($updateData["password"]);
            }
    
            if(isset($updateData['rt']) AND isset($updateData['rw'])){
                $rt = $updateData['rt'];
                $rw = $updateData['rw'];
                $user->rt_rw = $rt.'/'.$rw;
            }
            $user->save();
        }


        return response()->json("Data User Berhasil Di Update!");
        // return response()->json($updateData['email']);
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