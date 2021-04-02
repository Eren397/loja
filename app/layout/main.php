
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="http://localhost/moobitoy/">
    <link rel="stylesheet" href="app/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
    <title>MoobiToy</title>
</head>

<body>
    <header class="header">
        <div class="header__bar">
            <div class="container flex">
                <h1 class="header__logo">MoobiToy</h1>
                    <!-- Renderização condicional -->
                <?php
                
                if(isset($_SESSION['IDUSUARIO'])) { 
                ?>
                    <form action="home/buscar" method="GET" class="flex-form">
                        <input type="text" name="pesquisa" placeholder="Buscar produto ..." class="menu__pesquisa">
                        <button class="button__pesquisa"><i class="fas fa-search"></i></button>
                    </form>
                   <i class="fas fa-bars header__toggle"></i>
                <?php
                }
                ?>     
                    <!-- Renderização condicional -->       
            </div>
        </div>
    </header>
    <!-- Renderização condicional -->
    <?php
    if(isset($_SESSION['IDUSUARIO'])) {    
    ?>
    <nav class="header__menu-nav">
       <h3 class="header__user-name"><?php echo $_SESSION['NOME'];?></h3> 
        <ul class="header__list">
            <li class="header__item"><a class="header__link" href="home">Home</a></li>
            <li class="header__item"><a class="header__link" href="revendedor">Torne-se Revendedor</a></li>
            <li class="header__item"><a class="header__link" href="meusPedidos">Meus Pedidos</a></li>
            <li class="header__item"><a class="header__link" href="home/logout">Sair</a></li>
        </ul>
    </nav>
    <?php
    }
    ?>
      <!-- Renderização condicional -->

    <div class="container content">
        <?php
        $this->renderView($name, $dadosModel);
        ?>
    </div>

    <script src="app/assets/js/main.js"></script>
</body>

</html>