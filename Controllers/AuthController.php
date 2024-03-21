<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
   public function register(Request $request)
   {
    //   $request = $request->all();
      $validateData =  $request->validate(
        [
            'name' => 'required|max:55',
            'email' => 'email|required|unique:users',
            'password'=>'required|confirmed',


        ]
      );
    $validateData['password']=bcrypt($request->password);
    if($request->hasFile('profile'))
    {
        $profile = $request->file('profile');
        $name = time().'.'.$profile->getClientOriginalExtension();
        $destinationPath = public_path('/profile');

        $profile->move($destinationPath,$name);
        $validateData['profile_url']= $name;
    }

    $user = User::create($validateData);
    $accessToken = $user->createToken('authToken')->accessToken;
    return response(['user'=>$user,'access_token'=>$accessToken]);
   }

   public function login(Request $request)
   {
      $credentails = $request-> validate([
         'email'=>'email|required',
         'password'=>'required',
      ]);
      if(!auth()->attempt($credentails)){
        return response([
            'message'=>'Invalide Credentails',401
        ]);
      }
      $accessToken = auth()->user()->createToken('authToken')->accessToken;
      return response([
        'user'=>auth()->user(),'accessToken'=>$accessToken,
      ]);
   }

   public function logout()
   {
     auth()->logout();
     return response(['meesage'=>'Logout out Successfull....']);
   }

   public function updateUser(Request $request,$id)
   {
       $data = $request->all();// fetch all user from server
       $user = User::find($id);// find user by id
       if(!$user)
       {
           return response([
             'message'=>'User not found.......',404

           ]);
       }
       if($user)
       {
            if($request->hasFile('profile'))
            {
                $profile = $request->file('profile');
                $name = time().'.'.$profile->getOriginalExtansion();
                $destinationPath = public_path('/profile');
                $profile->move($destinationPath,$name);
                $data['profile_url']= $name;
                $oldImage = public_path('/profile/'.$user->profile_url);
                if(file_exists($oldImage))
                {
                    @unlink($oldImage);
                }
            }
            $user->update($data);
            return response([

                'user'=>$user,'message'=>'user updated successfully....',
            ]);

       }


   }
}
