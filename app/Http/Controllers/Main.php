<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DOMDocument;
use DOMXpath;
use App\Models\Car;

class Main extends Controller
{
    public function index(){
        $error = false;
        return view('login', ['error' => $error ]);
    }

    public function listCars(){
        return view('List', [
            'carsBd'=> Car::select("*")
            ->orderBy('nome_veiculo')
            ->get()
        ]);
    }

    public function DeleteCar($id){

        Car::findOrFail($id)->delete();

        return redirect()->route('list')->with('msg', 'Carro excluido com sucesso.');
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

    public function insertCar($nomeBd, $linkBd, $anoBd, $combustivelBd, $portasBd, $quilometragemBd, $cambioBd, $corBd, $precoBd)
    {
        $user_id = Auth::getUser()->id;

        $checkName = Car::where('nome_veiculo', $nomeBd)->first();
        if(!$checkName){
            $car = new Car();
            $car->user_id = $user_id;
            $car->nome_veiculo = $nomeBd;
            $car->link = $linkBd;
            $car->ano = $anoBd;
            $car->combustivel = $combustivelBd;
            $car->portas = $portasBd;
            $car->quilometragem	 = $quilometragemBd;
            $car->cambio = $cambioBd;
            $car->cor = $corBd;
            $car->preco = $precoBd;
            $car->save();
            $this->error = "false";
            $this->success = "false";
            if(isset($car)){
                return $this->success = 'Carro Inserido com sucesso.';
            };
        }
        else {
            $this->success = "false";
            return $this->error = 'Já existe um Carro cadastrado com este nome no banco de dados.';
        }
    }

    public function search(Request $request){

        $search = $request->query('search');

        if($search){
            $content = file_get_contents("https://www.questmultimarcas.com.br/estoque?termo=".$search."");
        }
        else {
            $content = file_get_contents("https://www.questmultimarcas.com.br/estoque");
        };

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
        if($selectedContent != null){

            /* tratando os dados: Ano - Combustível - portas - quilometragem - Câmbio e cor
            que estão no Array SelectedContent */
            foreach($selectedContent as $select){

                // removendo excessos de espaços e quebras de linha
                $detailsCar = preg_replace("@[\s]{2,}@", " ", $select['details']);
                $detailsCar = rtrim($detailsCar);

                // selecionando os campos: Ano - Combustível - portas - quilometragem - Câmbio e cor por REGEX
                $re = '@^(\w+): ([0-9]{4}) (\w+:) (\w+.[0-9]{3}) (\w+í\w+): (\w+) (\wâ\w+): (\w*á\w+) (\w+): ([0-9]) (\w+) (\w+): (\w+)@';
                $result = preg_match_all($re, $detailsCar, $details, PREG_SET_ORDER, 0);

                if($details == null){ // Se o Regex da linha 144 não funcionar executa este
                    $re = '@^(\w+): ([0-9]{4}) (\w+:) (\w+.[0-9]{3}) (\w+í\w+): (\w+) (\wâ\w+): (\w*\w+) (\w+): ([0-9]) (\w+) (\w+): (\w+)@';
                    $result = preg_match_all($re, $detailsCar, $details, PREG_SET_ORDER, 0);
                };

                foreach ($details as $val) { // Carrega o restante do conteudo se os Regex funcionarem
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
                    "name" => $select['name'],
                    "link" => $select['link'],
                    "ano" =>  $ano,
                    "combustivel" => $combustivel,
                    "portas" => $portas,
                    "quilometragem" => $quilometragem,
                    "cambio" => $cambio,
                    "cor" => $val[13],
                    "price" => $valPrice[2],
                ];

                $this->insertCar($select['name'], $select['link'], $ano, $combustivel, $portas, $quilometragem, $cambio, $val[13], $valPrice[2]);


            };

            return view('capturar', [
                            'cars'=> $cars,
                            'error'=> $this->error,
                            'success'=> $this->success
                        ]);

        } // if selectedContent
        else {
            return view('capturar', [
                'cars'=> '',
                'error'=> 'false',
                'success'=> 'false'
            ]);
        }
    }
}
