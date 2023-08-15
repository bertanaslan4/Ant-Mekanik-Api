<?php

namespace App\Http\Controllers\Api;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Exception;
use Illuminate\Http\JsonResponse;
use JWTAuth;
use Hash;
use App\Model\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use DB;
use Illuminate\Support\Facades\Input;

use Illuminate\Support\Facades\Mail;
use Config;

 

class AuthController extends Controller
{
    public function __construct()
    {
        $this->data = [
            'status' => false,
            'code' => 401,
            'data' => null,
            'err' => [
                'code' => 1,
                'message' => 'Unauthorized'
            ]
        ];
    }
    /**
 * Request->only metodu ile sadece parametre olarak
 * email - password ü alıyoruz ve laravel içerisinde bulunan auth
 * metoduna ait olan attempt fonksiyonu ile kullanıcı doğrulamasını yapıyoruz.
 * kullanıcı doğru ise eğer  kullanıcıya sonraki işlemlerinde kullanabilmesi için
 * bir token dönüyoruz.
 *
 * @param Request $request
 * @return JsonResponse
 */
    public function login(Request $request): JsonResponse
    {   
        //$credentials = $request->only(['email', 'password']);
        $data = $request->all();
        $email =htmlspecialchars(trim(addslashes($data['email'])));
        $password=htmlspecialchars(trim(addslashes($data['password'])));
        
     //   die(Hash::make('123456'));
        try {
            //kullanıcının aktifligi sorgulanması yapılmaktadır
            if (!$token = JWTAuth::attempt(array('email'=>$email,'password'=>$password))) {
                throw new Exception('Lütfen bilgilerinizi kontrol ediniz');
            }
            $this->data = [
                'status' => true,
                'code' => 200,
                'data' => [
                    '_token' => $token,
                    'User'=>auth()->user(),
                ],
                'err' => null
            ];
        }catch (Exception $e) {
            $this->data['err']['message'] ='Lütfen bilgilerinizi kontrol ediniz';
            $this->data['code'] = 401;
        } catch (JWTException $e) {
            $this->data['err']['message'] = 'Hata oluştu.Lütfen daha sonra tekrar deneyiniz';
            $this->data['code'] = 500;
        }
        return response()->json($this->data, $this->data['code']);
    }











    /**
 * Kullanıcı kayıt eden method burada kullanılan RegisterRequest  daha önce
 * anlatıldığı için detaylandırmıyorum.
 * @param RegisterRequest $request
 * @return JsonResponse
 */
    public function register(Request $request): JsonResponse
    {
        $data=Input::all();
        $user = User::create([
            'name' => $request->post('name'),
            'email' => $request->post('email'),
            'password' => Hash::make($request->post('password')),
            'active'=>1,
            'role'=>1,
        ]);
        
        $this->data = [
            'status' => true,
            'code' => 200,
            'data' => [
                'User' => $user
            ],
            'err' => null
        ];
        return response()->json($this->data, $this->data['code']);
        
    }
    public function Userregister(Request $request)
    {
      
        $veri = $request->all();
        $user = DB::table('kullanicilar')->insert([
        'kilit_id'=>$veri["kilit_id"],
        'role'=>0,
        'active'=>1,
    ]);
        
        
         $this->data = [
            'status' => true,
            'code' => 200,
            'data' => [
                'User' => $user
            ],
            'err' => null
        ];
         
          return response()->json($this->data, $this->data['code']); 
          
           
    }
    public function Userregisterupdate()
    {

        $veri = Input::all();
        $user =DB::table('kullanicilar')
                ->where('kilit_id',$veri["kilit_id"])
                ->update(array(  
                    'password'=>Hash::make($veri['password']),
                    'email'=>$veri['email'],
                ));
        
        $userid=$this->getUserid($veri["kilit_id"]);
        $projeadd=$this->addNewProje($userid);
        

        $this->data = [
            'status' => true,
            'code' => 200,
            'data' => [
                'User' => $user
            ],
            'err' => null
        ];

        return response()->json($this->data, $this->data['code']); 


    }
    public function getUserid($kilit_id){
        $userid=DB::table('kullanicilar')->where('kilit_id',$kilit_id)->value('id');
        return $userid;
    }
    public function addNewProje($id){
        $proje=DB::table('Projeler')
                ->insert([
                    'kullanici_id'=>$id,
        'proje_no'=>"01",
        'proje_revizyon'=>"10",
        'proje_adi'=>"Başlangıç Projesi",
    ]);
        return $proje;
    }

       public function forgotPasword(Request $request){

       
        try {
            
            $veri = $request->all();
            $email=$veri["email"];
            
            $user = DB::table('kullanicilar')->where('email',$email)->first();
            if($user){
                $parolo=1234567;
                

                $result =DB::table('kullanicilar')
                ->where('email',$veri["email"])
                ->update(array(  
                    'password'=>Hash::make($parolo),  
                ));
                return response(['success' => true, 'data' =>$result]);

            }
            else{
                return response(['success' => false, 'err' =>'Böyle bir kullanıcı  yok']);
            }
       
        } catch (\Throwable $th) {
            
            return 2;
        }

        

       }



    /**
        * Doğrulanmış olan kullanıcının detay bilgilerini getir.
        *
        * @return JsonResponse
        */
        public function detail(): JsonResponse
        {
            $this->data = [
                'status' => true,
                'code' => 200,
                'data' => [
                    'User' => auth()->user()
                ],
                'err' => null
            ];
            return response()->json($this->data);
        }
        /**
 * Kullanıcının çıkış işlemini yap ve token'ı kullanılamaz duruma getir.
 * @return JsonResponse
    */
    public function logout(): JsonResponse
    {
        auth()->logout();
        $data = [
            'status' => true,
            'code' => 200,
            'data' => [
                'message' => 'Başarılı Çıkış yapıldı'
            ],
            'err' => null
        ];
        return response()->json($data);
    }/**
    * Son kullanma tarihi geçmiş olan JWT nin tekrar kullanılır hale gelmesi
    * için yenileme işlemi.
    *
    * @return JsonResponse
    */
    public function refresh(): JsonResponse
    {
        $data = [
            'status' => true,
            'code' => 200,
            'data' => [
                '_token' => auth()->refresh()
            ],
            'err' => null
        ];
        return response()->json($data, 200);
    }
}