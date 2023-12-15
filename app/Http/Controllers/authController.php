<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// insertamos las librerias que usaremos
use Illuminate\Support\Facades\Hash;
use App\Models\User;
//laravel nos da una libreria con el metodo auth para facilitarnos la vida en la autenticacion
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class authController extends Controller
{
    
    public function register(Request $request)
    {//obtener todos los datos del request en un json
        $data = $request->json()->all();
        // Comprobar que no este vacion
        $itExistsUserName=User::where('email',$data['email'])->first();

        if ($itExistsUserName==null) {
            $user = User::create(
                [
                    'name'=>$data['name'],
                    'email'=>$data['email'],
                    'password'=>Hash::make($data['password'])

                ]
            );
  //al usuario que creemos tenemos que asignarle un token
            $token = $user->createToken('web')->plainTextToken;


                return response()->json([
                    'data'=>$user,
                    'token'=> $token

                ],200);// tiempo de respuesta, si excede marca un error
        } else {
               return response()->json([
                'data'=>'User already exists!',
                'status'=> false
            ],404);
       }

   }

    //creacion del servicio de login, este metodo recibe un opjeto de tipo request que contiene los datos del input del front
    public function login(Request $request){

        //vamos a ocupar una condicion (laravel tiene un metodo llamado Auth (attemp, regresa un booleano) para autenticas datos, ver si si estan en la bd)
        if(!Auth::attempt($request->only('email','password')))
        {
            //si esto es falso va a retornar en un json
            return response()->json
            ([
                'message'=> 'Correo o contraseña incorrectos',// es lo que nos mostrara en la consola si no coinciden los datos
                'status'=> false
            ],404);//tiempo de espera de 400
            
        }
        else
        {
            //vamos a buscar al usuario en la bd
         $user = User::where('email',$request['email'])->firstOrFail();
         //tenemos que generarle un token de acceso que cambien en cada login
         $token = $user->createToken('web')->plainTextToken;
    
          //retornamos el data que es el usuario y tambien el token
         return response()->json
         ([
            'data'=> $user,
            'token'=>$token
         ],200);
        }
         
    
       }


       public function logout(Request $request)
       {
        $request->user()->currentAccessToken()->delete();// metodo currentAccessToken() nos optiene el token y el delete lo borra
        // returnamos un valor para ver que hizo
        return response()->json
        ([
            'status'=> true,
        ]);
    
       }

   //metodo para ver al usuario logeado con un servicio get
   public function showById($id)
   {
       //declaramos la variable que nos guarda el id del usuario con ese id
       $user = User::find($id);
       $newPassword = Str::random(6);
       //retornamos el response
       return response()->json(["data"=>$user]);
       //return response()->json(["data"=>$newPassword]);
   }

    public function updateRandomPassword(Request $request)
    {
        //verificamos que el email si le corresponda a un usuario
        $user = User::where('email', $request['email'])->first();

        //Si no encuentra ningun usuario con ese email nos dira que no existe
        if (!$user) 
        {
            return response()->json(['message' => 'El usuario no existe'], 200);
        }
        else
        {
            // Generar una contraseña aleatoria de 6 caracteres
        $newPassword = Str::random(6);
        
        // Actualizar el campo password de la tabla user
        $user->password = Hash::make($newPassword);
        //guarda los cambios en la bd
        $user->save();
        
        // Enviar respuesta un mensaje, la nueva contraseña y el usuario al que se le hizo el cambio
        return response()->json([
            'message' => 'Contraseña actualizada correctamente',
            'new_password' => $newPassword,
            'user' => $user,
            
        ], 200);
        }

        
    }       
    

    public function updateManualPassword(Request $request)
    {
        //verificamos que el id si le corresponda a un usuario
        $user = User::findOrFail($request['id']);
        
        // Actualizar el campo password del usuario
        $user->password = Hash::make($request['newPassword']);
        //guarda los cambios en la bd
        $user->save();
        
         // Enviar respuesta un mensaje, la nueva contraseña y el usuario al que se le hizo el cambio
        return response()->json([
            'message' => 'Contraseña actualizada correctamente',
        ], 200);
    }
      
}
