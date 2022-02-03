
<?php 

session_start();
set_time_limit(0);
// Iniciamos o "contador"
list($usec, $sec) = explode(' ', microtime());
$script_start = (float) $sec + (float) $usec;

$nome = null;
$contaID = null;

//ini_set('error_reporting', E_ALL); // mesmo resultado de: error_reporting(E_ALL);
//ini_set('display_errors', 1);

/*
	Request API para gerar TOKEN.
*/

$curl = curl_init();

curl_setopt_array($curl, array(
	CURLOPT_URL => "https://api.optimizebpm.com.br/oauth/token",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "POST",
	CURLOPT_POSTFIELDS => "",
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

/*
	Request API para buscar contas.
*/

$curlA = curl_init();

curl_setopt_array($curlA, array(
	CURLOPT_URL => "https://api.optimizebpm.com.br/api/v1/customers",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => array(
		"Authorization: Bearer ".$token,
		"Cache-Control: no-cache",
		"Connection: keep-alive",
		"Postman-Token: 0c7402b4-2284-4ce7-9316-9ee4ab25e89c,000989f7-d1bf-451c-b4f4-8b531a9be2fa",
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
	$workspace = $conta->bpms_workspace;
	
	//if ($contaID > 0 and $contaID <> 50 or $contaID <> 68 or $contaID <> 4 or $contaID <> 16 or $contaID <> 22) }
	if ($contaID == 6) {

	echo '<br>'.$nome.' - ID: '.$contaID.'<br>';

/*
	Request API para buscar usuário por respectiva conta.
*/

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
				"Authorization: Bearer ".$token,
				"Cache-Control: no-cache",
				"Connection: keep-alive",
				"Postman-Token: 26b988e9-12d3-4f0a-a0d1-36fbbd3413bd,4e9373bb-b1b0-4df0-81e2-068814d0fc60",
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
			if ($usuarioID == 318 or $usuarioID == 8) {

/*
	Request API para buscar tarefas por respectivo usuário.
*/

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

/*
	Request API para buscar projeto e plano de ações por respectivo usuário.
*/

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

			$_SESSION['nome'] = $nome;
			$_SESSION['email'] = $email;
/*
	Resultdo contagem tarefas No prazo e Em atraso
*/
			$_SESSION['noPrazoTarefas'] = $noPrazoTarefas = $dadosC->noPrazo;
			$_SESSION['emRiscoTarefas'] = $emRiscoTarefas = $dadosC->emRisco;
			$_SESSION['emAtrasoTarefas'] = $emAtrasoTarefas = $dadosC->emAtraso;
			$_SESSION['totalTarefas'] = $totalTarefas = $noPrazoTarefas + $emAtrasoTarefas + $emRiscoTarefas;

			echo $nome.'<br>';
			echo "Tarefas no prazo: ".$noPrazoTarefas.'<br>';
			echo "Tarefas em risco: ".$emRiscoTarefas.'<br>';
			echo "Tarefas em atraso: ".$emAtrasoTarefas.'<br>';
			echo "Tarefas total: ".$totalTarefas.'<br>';
			echo "<br>";
/*
	Resultdo contagem plano ação No prazo e Em atraso
*/
			$_SESSION['noPrazoPlanoAcao'] = $noPrazoPlanoAcao = $dadosD->noPrazo;
			$_SESSION['emRiscoPlanoAcao'] = $emRiscoPlanoAcao = $dadosD->emRisco;
			$_SESSION['emAtrasoPlanoAcao'] = $emAtrasoPlanoAcao = $dadosD->emAtraso;
			$_SESSION['totalPlanoAcao'] = $totalPlanoAcao = $noPrazoPlanoAcao + $emAtrasoPlanoAcao + $emRiscoPlanoAcao;

			echo "Ações e Projeto no prazo: ".$noPrazoPlanoAcao.'<br>';
			echo "Ações e Projeto em risco: ".$emRiscoPlanoAcao.'<br>';
			echo "Ações e Projeto em atraso: ".$emAtrasoPlanoAcao.'<br>';
			echo "Ações e Projeto total: ".$totalPlanoAcao.'<br>';
			echo "------------------------------------------------------<br>";

			require 'enviarEmail.php';
			}
			$alerta = NULL;
		}

		$nome = null;
		$email = null;
		$userID = null;
		$noPrazoTarefas = 0;
		$emRiscoTarefas = 0;
		$emAtrasoTarefas = 0;
		$totalTarefas = 0;
		$noPrazoPlanoAcao = 0;
		$emRiscoPlanoAcao = 0;
		$emAtrasoPlanoAcao = 0;
		$totalPlanoAcao = 0;

	}
}

// Terminamos o "contador" e exibimos
list($usec, $sec) = explode(' ', microtime());
$script_end = (float) $sec + (float) $usec;
$elapsed_time = round($script_end - $script_start, 5);

// Exibimos uma mensagem
echo 'Elapsed time: ', $elapsed_time, ' secs. Memory usage: ', round(((memory_get_peak_usage(true) / 1024) / 1024), 2), 'Mb';

?>
