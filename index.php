
<?php 

set_time_limit(0);
// Iniciamos o "contador"
list($usec, $sec) = explode(' ', microtime());
$script_start = (float) $sec + (float) $usec;

$nome = null;
$contaID = null;

//ini_set('error_reporting', E_ALL); // mesmo resultado de: error_reporting(E_ALL);
//ini_set('display_errors', 1);

$curl = curl_init();

curl_setopt_array($curl, array(
	CURLOPT_URL => "https://api.optimizebpm.com.br/oauth/token",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => "client_id=9&client_secret=X7AAh0GEwNT0XkVbBmlJZXI6dbdhSlSTvPn69n7Z&username=app@plataformasintonia.com&password=NfmNXpHZT.=h&grant_type=password&scope=*",
	CURLOPT_HTTPHEADER => array(
		"Cache-Control: no-cache",
		"Content-Type: application/x-www-form-urlencoded",
		"Postman-Token: b00abd4a-0e80-9a9d-cec4-540bdddc55b7"
	),
));

$response = curl_exec($curl);
$err = curl_error($curl);
$response = json_decode($response);
curl_close($curl);

$token = $response->access_token;

$curlA = curl_init();

curl_setopt_array($curlA, array(
	CURLOPT_URL => "https://api.optimizebpm.com.br/api/v1/customers/6",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => array(
		"Accept: */*",
		"Accept-Encoding: gzip, deflate",
		"Authorization: Bearer ".$token,
		"Cache-Control: no-cache",
		"Connection: keep-alive",
		"Host: api.optimizebpm.com.br",
		"Postman-Token: 0c7402b4-2284-4ce7-9316-9ee4ab25e89c,000989f7-d1bf-451c-b4f4-8b531a9be2fa",
		"User-Agent: PostmanRuntime/7.18.0",
		"cache-control: no-cache"
	),
));

$responseA = curl_exec($curlA);
$errA = curl_error($curlA);
$dadosA = json_decode($responseA);
curl_close($curlA);

//print_r($dadosA)."<br>";
foreach ($dadosA as $conta) {
	$nome = $conta->name;
	$contaID = $conta->id;
	echo $nome.'<br>';
	echo $contaID.'<br>';
	
	if ($contaID > 0) {

		$curlB = curl_init();

		curl_setopt_array($curlB, array(
			CURLOPT_URL => "https://api.optimizebpm.com.br/api/v1/customers/users/".$contaID,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"Accept: *//*",
				"Accept-Encoding: gzip, deflate",
				"Authorization: Bearer ".$token,
				"Cache-Control: no-cache",
				"Connection: keep-alive",
				"Host: api.optimizebpm.com.br",
				"Postman-Token: 26b988e9-12d3-4f0a-a0d1-36fbbd3413bd,4e9373bb-b1b0-4df0-81e2-068814d0fc60",
				"User-Agent: PostmanRuntime/7.18.0",
				"cache-control: no-cache"
			),
		));

		$responseB = curl_exec($curlB);
		$errB = curl_error($curlB);
		$dadosB = json_decode($responseB);
		curl_close($curlB);

		foreach ($dadosB as $usuario) {
			$nome = $usuario->name;
			$usuarioID = $usuario->id;
			$email = $usuario->email;

				//Tarefas do Sintonia
			//$hoje = date();

			$curlC = curl_init();

			curl_setopt_array($curlC, array(
				CURLOPT_URL => "https://api.optimizebpm.com.br/api/v1/user-tasks-contagem/".$usuarioID,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 300,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_POSTFIELDS => "",
				CURLOPT_HTTPHEADER => array(
					"Authorization: Bearer ".$token,
					"Cache-Control: no-cache",
					"Content-Type: application/json",
					"Postman-Token: 687d14a3-e617-499d-84e4-649ce41f4752"
				),
			));

			$responseC = curl_exec($curlC);
			$errC = curl_error($curlC);
			$dadosC = json_decode($responseC);
			curl_close($curlC);

				//Resultdo contagem tarefas No prazo e Em atraso
			$noPrazoTarefas = $dadosC->noPrazo;
			$emAtrasoTarefas = $dadosC->emAtraso;
			$totalTarefas = $noPrazoTarefas + $emAtrasoTarefas;

			echo $nome.'<br>';
			echo "Tarefas no prazo: ".$noPrazoTarefas.'<br>';
			echo "Tarefas em atraso: ".$emAtrasoTarefas.'<br>';
			echo "Tarefas total: ".$totalTarefas.'<br>';
			echo "<br>";

				//Projetos e Planos de Ação
			$curlD = curl_init();

			curl_setopt_array($curlD, array(
				CURLOPT_URL => "https://api.optimizebpm.com.br/api/v1/user-action-plans-contagem/".$usuarioID,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 300,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET",
				CURLOPT_POSTFIELDS => "",
				CURLOPT_HTTPHEADER => array(
					"Authorization: Bearer ".$token,
					"Cache-Control: no-cache",
					"Content-Type: application/json",
					"Postman-Token: 687d14a3-e617-499d-84e4-649ce41f4752"
				),
			));

			$responseD = curl_exec($curlD);
			$errB = curl_error($curlD);
			$dadosD = json_decode($responseD);
			curl_close($curlD);

					//Resultdo contagem plano ação No prazo e Em atraso
			$noPrazoPlanoAcao = $dadosD->noPrazo;
			$emAtrasoPlanoAcao = $dadosD->emAtraso;
			$totalPlanoAcao = $noPrazoPlanoAcao + $emAtrasoPlanoAcao;

			echo "Ações e Projeto no prazo: ".$noPrazoPlanoAcao.'<br>';
			echo "Ações e Projeto em atraso: ".$emAtrasoPlanoAcao.'<br>';
			echo "Ações e Projeto total: ".$totalPlanoAcao.'<br>';
			echo "------------------------------------------------------<br>";

			$alerta = NULL;
		}

		$nome = null;
		$email = null;
		$userID = null;
		$atrasadas = 0;
		$noPrazo = 0;
		$totalTarefas = 0;

	}
}

// Terminamos o "contador" e exibimos
list($usec, $sec) = explode(' ', microtime());
$script_end = (float) $sec + (float) $usec;
$elapsed_time = round($script_end - $script_start, 5);

// Exibimos uma mensagem
echo 'Elapsed time: ', $elapsed_time, ' secs. Memory usage: ', round(((memory_get_peak_usage(true) / 1024) / 1024), 2), 'Mb';

?>