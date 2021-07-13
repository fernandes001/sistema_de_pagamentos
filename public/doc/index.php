
<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		
		<link rel="apple-touch-icon" sizes="57x57" href="https://dyspaga.com.br/tema/landingpage/images/favicon/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="https://dyspaga.com.br/tema/landingpage/images/favicon/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="https://dyspaga.com.br/tema/landingpage/images/favicon/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="https://dyspaga.com.br/tema/landingpage/images/favicon/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="https://dyspaga.com.br/tema/landingpage/images/favicon/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="https://dyspaga.com.br/tema/landingpage/images/favicon/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="https://dyspaga.com.br/tema/landingpage/images/favicon/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="https://dyspaga.com.br/tema/landingpage/images/favicon/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="https://dyspaga.com.br/tema/landingpage/images/favicon/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192"  href="https://dyspaga.com.br/tema/landingpage/images/favicon/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="https://dyspaga.com.br/tema/landingpage/images/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="https://dyspaga.com.br/tema/landingpage/images/favicon/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="https://dyspaga.com.br/tema/landingpage/images/favicon/favicon-16x16.png">
	
	
		<title>DYS DOC</title>

		<link href="assets/css.css" rel="stylesheet" media="screen" />
		<link href="assets/screen.css" rel="stylesheet" media="screen" />
		<link href="assets/print.css" rel="stylesheet" media="print" />
		<script src="assets/all.js" type="283173bc216bfe8e739f5af6-text/javascript"></script>
	</head>

	<body class="index" data-languages="[&quot;javascript&quot;,&quot;shell&quot;]">
		<a href="#" id="nav-button">
			<span>
				NAV
				<img src="assets/navbar.png" />
			</span>
		</a>
		<div class="tocify-wrapper">
			<div class='logo-wrapper'>
				<img src="assets/logo-dsy-paga.png" />
			</div>
			<div class="lang-selector">
				<a href="#" data-language-name="Javascript">Script</a>
			</div>

			<div id="toc">
			</div>
		</div>
		<div class="page-wrapper">
			<div class="dark-box"></div>
			<div class="content">
				<h1 id="iniciando">Iniciando</h1>

				<h3 id="overview">Visão geral</h3>

				<p>Bem vindo(a) a <a href="https://dyspaga.com.br">DYS Paga</a> documentação de instalação!</p>

				<h1 id="requisitos">Requisitos</h1>

				<h3 id="overview">Requisitos</h3>

				<p>php 7.4</p>
				<p>Apache</p>
				<p>MySQL</p>
				<p>Composer</p>
				<p>Git</p>

				<h1 id="git">Clonar o repositório</h1>

				<p><code class="prettyprint">git clone https://github.com/fagnerfernandes/dyspaga</code></p>

				<blockquote>
					<p><strong>GIT</strong></p>
				</blockquote>
			
				<pre class="highlight javascript"><code>git clone https://github.com/fagnerfernandes/dyspaga</code></pre>

				<h1 id="composer">Dependências com o composer</h1>

				<p>No diretorio raiz do projeto, em seu terminal: <code>composer install</code></p>
				
				<blockquote>
					<p><strong>GIT</strong></p>
				</blockquote>
			
				<pre class="highlight javascript"><code>composer install</code></pre>
				
				<h1 id="BD">Importar o banco de dados</h1>
				
				<h3 id="overview">Importando o banco de dados</h3>
				
				<p>No diretório raiz onde foi clonado o projeto, temos um arquivo <code>.sql</code> com um backup do banco de dados. Este é um backup com toda estrutura do projeto</p>
				<p>Importe este SQL em seu banco de dados</p>
				
				<h1 id="Requisitos">Requisitos</h1>

				<h3 id="overview">Arquivo hosts</h3>

				<p>Em seu sistema operacional, adicione uma entrada para o projeto, apontando o host utilizado ao IP local</p>
				
				<blockquote>
					<p><strong>Editando arquivo hosts</strong></p>
				</blockquote>
			
				<pre class="highlight javascript"><code><span class="nt">127.0.0.1</span> <span class="s2">MEU_DOMINIO</span></code></pre>
				
				<h1 id="vhosts">Arquivo vhosts.conf</h1>

				<p>Iremos criar uma entrada em nosso apache, atrelando um domínio a um diretório em nosso projeto</p>
				<p>Também podemos definir diretórios para registrar os logs da plataforma</p>
				<p>Após criar esta entrada, precisamos reiniciar o Apache</p>
				<p>Nosso host deve apontar para o diretório <code>public</code></p>
				
				<blockquote>
					<p><strong>Apache vhosts.conf</strong></p>
				</blockquote>
			
				<pre class="highlight javascript"><code>&lt;VirtualHost *:80&gt;
	<span class="nt">ServerAdmin</span> <span class="s2">fagner.ti@gmail.com</span>
	<span class="nt">DocumentRoot </span> <span class="s2">"/var/www/dyspaga/public"</span>
	<span class="nt">ServerName</span> <span class="s2">MEU_DOMINIO</span>
	<span class="nt">ErrorLog</span> <span class="s2">"/var/logs/dyspaga-error.log"</span>
	<span class="nt">CustomLog</span> <span class="s2">"/var/logs/dyspaga-access.log" common</span>
&lt;/VirtualHost&gt;</code></pre>
				
				<h1 id="laravel">Configurando o Laravel</h1>

				<h3 id="overview">Configurando o arquivo .env do Laravel com as configurações corretas de nosso ambiente</h3>

				<p>Primeiro precisamos criar uma cópia do arquivo <code>.env.example</code> criando um arquivo com o nome <code>.env</code></p>
				<p>E Então iremos editar o novo arquivo <code>.env</code> com as nossas configurações</p>
				
				<blockquote>
					<p><strong>.env</strong></p>
				</blockquote>
			
				<pre class="highlight javascript"><code><span class="nt">APP_URL</span><span class="p">=</span><span class="s2">http://MEU_DOMINIO/</span>
<span class="nt">DB_CONNECTION</span><span class="p">=</span><span class="s2">mysql</span>
<span class="nt">DB_HOST</span><span class="p">=</span><span class="s2">HOST_DO_BANCO_DE_DADOS</span>
<span class="nt">DB_PORT</span><span class="p">=</span><span class="s2">3306</span>
<span class="nt">DB_DATABASE</span><span class="p">=</span><span class="s2">NOME_DO_BANCO_DE_DADOS</span>
<span class="nt">DB_USERNAME</span><span class="p">=</span><span class="s2">USUARIO_DO_BANCO_DE_DADOS</span>
<span class="nt">DB_PASSWORD</span><span class="p">=</span><span class="s2">SENHA_DO_BANCO_DE_DADOS</span>
				</code></pre>
				
				
				<h1 id="navegador">Acessando pelo Navegador</h1>

				<p>Acesse o projeto <code>http://MEU_DOMINIO/</code> Durante o primeiro acesso, será solicitado a geração do app key, e então a plataforma será carregada</p>

			</div>
			
			<div class="dark-box">
				<div class="lang-selector">
					<a href="#" data-language-name="javascript">Script</a>
				</div>
			</div>
		</div>

		<script data-cfasync="false" src="assets/email-decode.min.js"></script>
		<script src="assets/rocket-loader.min.js" data-cf-settings="283173bc216bfe8e739f5af6-|49" defer=""></script>

	</body>
</html>
