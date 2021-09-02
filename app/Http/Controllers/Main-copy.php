<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Path\To\DOMDocument;
use DOMDocument;
use DOMXpath;

class Main extends Controller
{
    public function index(){
        $error = false;
        return view('login', ['error' => $error ]);
    }

    public function capturar(){
        return view('capturar');
    }

    public function auth(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ],[
            'email.required' => 'Email é obrigatório',
            'password' => 'Password é obrigatório'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('capturar');
        }

        return back()->withErrors([
            'email' => 'Email ou senha inválida.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function search(Request $request){

        $search = $request->query('search');
        $content = file_get_contents("https://www.questmultimarcas.com.br/estoque");

        $dom = new domDocument();
        @$dom->loadHTML($content);
        $xpath = new DOMXpath($dom);

        $divs = $xpath->query("//div[@class=\"list\"]");
        $values = $xpath->query(".//article", $divs->item(0));

        $selectedContent = [];

        foreach($values as $value) {
            $content = $xpath->query(".//div", $value);

            $link = trim($content->item(0)->firstChild->nextSibling->getAttribute("href"));

            $name = trim($content->item(1)->firstChild->nextSibling->textContent);

            $details = trim($content->item(3)->textContent);

            $price = trim($content->item(5)->textContent);

            $selectedContent[] = [
                "link" => $link,
                "name" => $name,
                "details" => $details,
                "price" => $price,
            ];
        }

        /* tratando os dados: Ano - Combustível - portas - quilometragem - Câmbio e cor
         que estão no Array SelectedContent em details */
        foreach($selectedContent as $select){

            // removendo exessos de espaços e quebras de linha
            $detailsCar = preg_replace("@[\s]{2,}@", " ", $select['details']);
            $detailsCar = rtrim($detailsCar);

            // selecionando os campos: Ano - Combustível - portas - quilometragem - Câmbio e cor por REGEX
            $re = '@^(\w+): ([0-9]{4}) (\w+:) (\w+.[0-9]{3}) (\w+í\w+): (\w+) (\wâ\w+): (\w+á\w+) (\w+): ([0-9]) (\w+) (\w+): (\w+)@';

            preg_match_all($re, $detailsCar, $details, PREG_SET_ORDER, 0);

            foreach ($details as $val) {
                //$val[0]; // Arry
                $ano = $val[2]; // Ano
                $combustivel = $val[6]; // Combustível
                $portas = $val[10]; // Portas
                $quilometragem = $val[4]; // Quilometragem
                $cambio = $val[8]; // Câmbio
                $val[13]; // Cor
            };

            $formatPrice = $select['price'];
            $rePrice = '/(^\w*Ç\w:\w[$] )(\w*.[0-9]{3})/m';
            preg_match_all($rePrice, $formatPrice, $formatedPrice, PREG_SET_ORDER, 0);

            // formantando o Preço, pegando só o valor numérico
            foreach ($formatedPrice as $valPrice) {
                $valPrice[2];
            };

            $cars [] = [

                    "link" => $select['link'],
                    "name" => $select['name'],
                    "ano" =>  $ano,
                    "combustivel" => $combustivel,
                    "portas" => $portas,
                    "quilometragem" => $quilometragem,
                    "cambio" => $cambio,
                    "cor" => $val[13],
                    "price" => $valPrice[2],

            ];
            $cars += $cars;

                        // echo "Link: <a href='". $cars['link'] ."'>" .$cars['link']. "</a><br>";
                        // echo "Name: ". $cars['name'] ."<br>";
                        // echo "Ano: ". $cars['ano'] ."<br>";
                        // echo "Combustivel: ". $cars['combustivel'] ."<br>";
                        // echo "Portas: ". $cars['portas'] ."<br>";
                        // echo "Quilometragem: ". $cars['quilometragem'] ."<br>";
                        // echo "Cambio: ". $cars['cambio'] ."<br>";
                        // echo "Cor: ". $cars['cor'] ."<br>";
                        // echo "Preço: ". $cars['price'] ."<br><br>";
        };
        //echo dd($cars);
        // foreach($cars as $car){
        //     echo $car['name'] ."<br>";
        // }
        //return view('capturar', $cars);

    }
    public function insertContent(Request $request){


        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $adress = $request->input('adress');
        $phone = $request->input('phone');

        $checkEmail = Content::where('nome_carro', $email)->first();

        if(!$checkEmail){
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = bcrypt($password);
            $user->adress = $adress;
            $user->phone = $phone;
            $user->save();
            if(isset($user)){
                echo response()->json(['message' => 'Inserido com sucesso.']);
            };
        }
        else {
            echo json_encode(['message' => 'Já existe um Usuário cadastrado com este email.']);
        }
    }

}
