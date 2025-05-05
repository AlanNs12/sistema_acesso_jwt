<nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <a class="navbar-brand text-light" href="#"><img src="/sistema_acesso_jwt/images/logo-circle.png" width="30" height="30" class="d-inline-block align-top " alt="">  Embassy Of the Philippines</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto topnav">
                <li class="nav-item active">
                    <a class="nav-link text-light" href="/sistema_acesso_jwt/dashboard.php">Inicio <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Opções Funcionários
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="/sistema_acesso_jwt/funcionarios/registro_hora.php">&#9200; Novo Registro</a>
                    <a href="/sistema_acesso_jwt/funcionarios/listar_registros.php" class="dropdown-item">&#128269; Consulta de Registros</a>
                    <a class="dropdown-item" href="/sistema_acesso_jwt/funcionarios/listar_funcionarios.php">&#128100; Funcionários</a>
                    <a href="/sistema_acesso_jwt/funcionarios/cadastro_funcionario.php" class="dropdown-item">&#10133; Cadastrar novo funcionário</a>
                    <div class="dropdown-divider"></div>
                    <a href="/sistema_acesso_jwt/usuarios.php" class="dropdown-item">&#128736; Gerenciar Admins</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Opções Veiculos
                    </a>
                    <div class="dropdown-menu " aria-labelledby="navbarDropdown">
                        <a class="dropdown-item dropleft" href="/sistema_acesso_jwt/veiculos/registro_veiculos.php">&#9200; Novo registro</a>
                        <a class="dropdown-item" href="/sistema_acesso_jwt/veiculos/listar_registros_veiculos.php">&#128203; Lista registros</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/sistema_acesso_jwt/veiculos/gerenciar_veiculos.php">&#128736;Gerenciar Veiculos</a>
                    </div>
                </li>
                <li>
                <span class="nav-link text-muted"> | <?= $_SESSION['usuario_nome'] ?> </span>
                
                </li>
                
                <li class="nav-item">
                    <a class="nav-link btn btn-danger text-white" type="button" href="/sistema_acesso_jwt/logout.php">Sair</a>
                </li>
            </ul>
        </div>

            
    </nav>
    