<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Mail\AdminResetPassword;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminAuth extends Controller
{

    public function login() {

        return view('admin.login');
    }
    public function index()
    {
        return view('admin.dashboard');
    }

    public function dologin() {
        $this->validate(request(),[
            'password'=>'required',
            'email' => 'required',
        ]);
        $rememberme = request('rememberme') == 1?true:false;
        if (auth()->guard('admin')->attempt(['email' => request('email'), 'password' => request('password')], $rememberme)) {
            return redirect(route('admin.home'));
        } else {
            session()->flash('error', 'Make sure the email or password is correct');
            return redirect(route('admin.login'));
        }
    }

    public function logout() {
        auth()->guard('admin')->logout();
        return redirect(route('admin.login'));
    }

    public function forgetPassword(){
        return view('admin.forgetPassword');
    }
    public function resetPassword(Request $request){
        $this->validate($request,[
            'email'=>'required',
        ]);
        $admin = User::where('email',$request->input('email'))->first();

        if (!empty($admin)){
            $token = app('auth.password.broker')->createToken($admin);
            DB::table('password_resets')->insert(
                [
                    'email'=>$admin->email,
                    'token'=>$token,
                    'created_at'=>Carbon::now()
                ]);
            Mail::to($admin->email)->send(new AdminResetPassword(['admin'=>$admin,'token'=>$token]));
            return back()->with(['success'=>'Check your email, password reset link has been sent']);
        }
        return back()->with(['error'=>'Make sure the email is correct']);
    }
    public function resetPasswordWithToken($token){

        $check_token = DB::table('password_resets')
            ->where('token', $token)
            ->where('created_at', '>', Carbon::now()->subHours(2))
            ->first();
        if (!empty($check_token)) {
            return view('admin.resetPassword', ['data' => $check_token]);
        } else {
            return redirect(route('admin.forgotPassword'));
        }
    }
    public function updatePassword($token){
        $this->validate(request(),[
            'password'=>'required|confirmed',
            'password_confirmation' => 'required',
        ]);
        $check_token = DB::table('password_resets')->where('token',$token)
            ->where('created_at','>',Carbon::now()->subHour(2))->first();
        if (!empty($check_token)) {
            $admin = User::where('email', $check_token->email)->update([
                'password' => bcrypt(request('password'))
            ]);
            DB::table('password_resets')->where('email',$check_token->email)->delete();
            auth()->guard('admin')->attempt(['email' => $check_token->email, 'password' => request('password')]);
                return redirect(route('admin.home'));
        } else {
            return redirect(route('admin.forgotPassword'));
        }
    }

    public function setting(){
        return view('admin.setting');
    }

    public function setting_email(Request $request){
        $this->validate($request,[
           'email'=> 'required|email',
           'new_email'=> 'required|email',
           'email_confirmation'=> 'required|email|same:new_email',
        ],[],[
            'email'=> 'old email',
            'new_email'=> 'new email',
            'email_confirmation'=> 'email confirmation',
        ]);
        if (Auth::user()->email == $request->email){
            Auth::user()->update(['email'=> $request->new_email]);
            return redirect()->back()->with(['success'=> 'The email updated successfully']);
        }
        return redirect()->back()->with(['error'=> 'The old email not correct']);
    }
    public function setting_password(Request $request){

        $this->validate($request,[
            'password'=> 'required|string',
            'new_password'=> 'required|string',
            'password_confirmation'=> 'required|string|same:new_password',
        ],[],[
            'password'=> 'old password',
            'new_password'=> 'new password',
            'password_confirmation'=> 'password confirmation',
        ]);
        if (Hash::check($request->password,Auth::user()->password)){
            Auth::user()->update(['password'=> Hash::make($request->new_password)]);
            return redirect()->back()->with(['success'=> 'The password updated successfully']);
        }
        return redirect()->back()->with(['error'=> 'The old password not correct']);
    }
}
