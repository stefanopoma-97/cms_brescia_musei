<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>@yield('titolo')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

        <!-- Fogli di stile -->
        <link rel="stylesheet" href="{{ url('/') }}/css/bootstrap.min.css">
        <link rel="stylesheet" href="{{ url('/') }}/css/mio_stile.css">
        <link rel="stylesheet" href="{{ url('/') }}/css/bootstrap-theme.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css">


        <!-- jQuery e plugin JavaScript -->
       
        <script src="{{ url('/') }}/js/filtro_opere.js"></script>
        

        <script type="text/javascript">@yield('javascript')</script>
        <script src="https://kit.fontawesome.com/63ae6a9696.js" crossorigin="anonymous"></script>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="{{ url('/') }}/js/bootstrap.min.js"></script>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.2.0/zxcvbn.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
        
        <!--<script type="text/javascript" class="init">

            $(document).ready(function () {
                $('#tabella_elenco_utenti').DataTable("orderCellsTop": true,"ordering": true,);
                $('#tabella_elenco_sentieri').DataTable("orderCellsTop": true,"ordering": true,);
                $('#tabella_elenco_preferiti').DataTable("orderCellsTop": true,"ordering": true,);
                $('#tabella_revisioni_sentiero').DataTable("orderCellsTop": true,"ordering": true,);
                $('#tabella_elenco_sentieri_effettuati').DataTable("orderCellsTop": true,"ordering": true,);
            });

        </script>-->

    </head>

    <body>

        
        <!-- creazione nav-bar-->
        <nav class="navbar-default navbar-fixed-top"> <!-- nav bar fissata in alto sempre -->
            <div class="container">

                <!-- crea il bottone con le 3 linee-->
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- sostituisce il bottone home -->


                <!-- barra del menu-->
                <div class="collapse navbar-collapse navbar-responsive-collapse" id="myNavbar">

                    

                    <ul class="nav navbar-nav navbar-right navbar-login pl-5 pull-right">
                        <li><a class="bordo-selezione" href="">Pagina 1</a></li>
                        <li><a class="bordo-selezione" href="">Pagina 2</a></li>
                            
                            <!-- logica per modificarla in base a $logged e $user->admin == 'y'-->
                       
                    </ul>
                </div>
            </div>
        </nav>





        @yield('sfondo')


        <!-- header-->
        <div class="container" style="margin-top: 5em;">
            <header class="header-sezione">
                @yield('header')
                @yield('breadcrumb')
            </header>
        </div>

        @yield('corpo')
        
        
        <!--
        <div style="margin-top: 5em" class="container">
            <div class="row">
                <footer class="page-footer font-small blue-grey lighten-5">
                    <div class="col-md-12">
                        <div style="background-color: rgba(53, 126, 189, 0.6);">
                            <br>
                            <br>

                        </div>
                    </div>

                    <br>
                    <div class="container text-center">

                        <div class="row dark-grey-text">

                            <div class="col-md-4 col-foot">
                                <h4 class="text-uppercase font-weight-bold">Il progetto</h4>
                                <hr class="teal accent-3 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px;">
                                <p>Il sito ha lo scopo di raccogliere tutti i sentieri italiani. Dividendoli per categorie e
                                    permettendo una ricerca sulla base di molti parametri.</p>
                            </div>

                            <div class="col-md-4 col-foot">

                                <h4 class="text-uppercase font-weight-bold">Links utili</h4>
                                <hr class="teal accent-3 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px;">

                                <div class="container">
                                    
                            </div>

                            <div class="col-md-4 col-foot">
                                <h4 class="text-uppercase font-weight-bold">Contatti</h4>
                                <hr class="teal accent-3 mb-4 mt-0 d-inline-block mx-auto" style="width: 60px;">

                                <div class="container">
                                    <div class="row">
                                        <i class="fas fa-home"></i> Brescia UNIBS
                                    </div>

                                    <div class="row">
                                        <i class="fas fa-envelope"></i> info@sentieri.com
                                    </div>

                                    <div class="row">
                                        <i class="fas fa-phone"></i> + 01 234 567 88
                                    </div>

                                    <div class="row">
                                        <i class="fas fa-print"></i> + 01 234 567 89
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                    <hr>
                    <div class="footer-copyright text-center text-black-50 py-3">© 2020 Copyright:
                    </div>
                    <br>

                </footer>
            </div>
        </div>
        -->

       

    </body>
</html>
