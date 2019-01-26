<!doctype html>
<html lang="es">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/library/bootstrap/4.2.1/css/bootstrap.min.css') ?>">
        <!-- Bootstrap Scroller CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/library/scroller/3.1.5/css/jquery.mCustomScrollbar.min.css') ?>">
        <!-- JSTree CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/library/jstree/style.css') ?>">
        <!-- Billboard CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/library/billboard/billboard.min.css') ?>">
        <!-- Datatables CSS -->
        <script src="<?= base_url('assets/library/datatables/dataTables.bootstrap4.min.css') ?>"></script>        
        <!-- FontAwesome ICON -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <?= asset_css('plantilla/estilo.css') ?>
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
        <link rel="icon" type="image/ico" href="<?= base_url('assets/images/favicon.ico') ?>">

        <title>Auditor</title>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg fixed-top bg-auditor">
            <a class="navbar-brand" href="#!" style="font-weight: 600; outline: none;">
                <i class="fas fa-feather-alt" style="color: #FF8104"></i>
                Auditor
            </a>
            <div class="collapse navbar-collapse">
                <ul class="nav nav-pills" id="pills-tab" role="tablist" style="margin-left: 40px;">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-principal-tab" data-toggle="pill" href="#pills-principal" role="tab" aria-controls="pills-principal" aria-selected="true">Principal</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-datos-tab" data-toggle="pill" href="#pills-datos" role="tab" aria-controls="pills-datos" aria-selected="false">Datos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-analisis-tab" data-toggle="pill" href="#pills-analisis" role="tab" aria-controls="pills-analisis" aria-selected="false">An&aacute;lisis</a>
                    </li>
                </ul>
            </div>
            <div class="navbar-nav">
                <span class="navbar-text text-white">
                    Kevin Enriquez
                </span>
                <a class="nav-item nav-link" href="<?= base_url('auth/logout') ?>">
                    <i class="fas fa-sign-out-alt" style="margin-top: 6px;"></i>
                </a>
            </div>
        </nav>
        <?php $this->load->view('plantilla/menu'); ?>
        <!-- Sidebar -->
        <nav id="sidebar">
            <ul class="list-unstyled components" style="padding-top: 10px;">
                <p style="margin: 0px; padding: 0px 10px 0px 10px;">
                    Explorador de archivos
                </p>
                <div class="card" style="height: calc(100vh - 203px); overflow-y: auto;">
                    <div class="card-body">
                        <div id="simpleTree" obj="tree">
                            <ul obj="tree">
                                <li data-jstree='{"opened":true}' obj="tree">2018
                                    <ul obj="tree">
                                        <li obj="tree" data-jstree='{"opened":true}'>Enero
                                            <ul obj="tree">
                                                <li obj="tree" data-jstree='{"type":"file"}'>Movimientos</li>
                                                <li obj="tree" data-jstree='{"type":"file"}'>Balance de pruebas</li>
                                                <li obj="tree" data-jstree='{"type":"file"}'>CXP</li>
                                                <li obj="tree" data-jstree='{"type":"file"}'>CXC</li>
                                            </ul>
                                        </li>
                                        <li obj="tree" data-jstree='{"opened":true}'>Febrero
                                            <ul obj="tree">
                                                <li obj="tree" data-jstree='{"type":"file"}'>Movimientos</li>
                                                <li obj="tree" data-jstree='{"type":"file"}'>Balance de pruebas</li>
                                                <li obj="tree" data-jstree='{"type":"file"}'>CXP</li>
                                                <li obj="tree" data-jstree='{"type":"file"}'>CXC</li>
                                            </ul>
                                        </li>
                                        <li obj="tree" data-jstree='{"opened":true}'>Marzo
                                            <ul obj="tree">
                                                <li obj="tree" data-jstree='{"type":"file"}'>Movimientos</li>
                                                <li obj="tree" data-jstree='{"type":"file"}'>Balance de pruebas</li>
                                                <li obj="tree" data-jstree='{"type":"file"}'>CXP</li>
                                                <li obj="tree" data-jstree='{"type":"file"}'>CXC</li>
                                            </ul>
                                        </li>
                                        <li obj="tree" data-jstree='{"opened":true}'>Abril
                                            <ul obj="tree">
                                                <li obj="tree" data-jstree='{"type":"file"}'>Movimientos</li>
                                                <li obj="tree" data-jstree='{"type":"file"}'>Balance de pruebas</li>
                                                <li obj="tree" data-jstree='{"type":"file"}'>CXP</li>
                                                <li obj="tree" data-jstree='{"type":"file"}'>CXC</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li obj="tree" data-jstree='{"opened":true}'>2019
                                    <ul obj="tree">
                                        <li obj="tree" data-jstree='{"opened":true}'>Enero
                                            <ul obj="tree">
                                                <li obj="tree" data-jstree='{"type":"file"}'>Movimientos</li>
                                                <li obj="tree" data-jstree='{"type":"file"}'>Balance de pruebas</li>
                                                <li obj="tree" data-jstree='{"type":"file"}'>CXP</li>
                                                <li obj="tree" data-jstree='{"type":"file"}'>CXC</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </ul>
        </nav>
        <nav id="sidebar" class="propiedades">
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                            <button class="btn" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Resultados
                            </button>
                        </h2>
                    </div>

                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <ul>
                                <li>Araña</li>
                                <li>Ley de Benford</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                            <button class="btn collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Comentarios
                            </button>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <div id="content">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-spider-tab" data-toggle="tab" href="#nav-spider" role="tab" aria-controls="nav-spider" aria-selected="true">Araña</a>
                    <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Movimiento Enero 2019</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Base de datos Ordenada</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-spider" role="tabpanel" aria-labelledby="nav-spider-tab">
                    <?= $body ?>
                </div>
                <div class="tab-pane fade show" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div id="CombinationChart"></div>
                </div>
                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
<table id="example" class="table table-striped table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Age</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Tiger Nixon</td>
                <td>System Architect</td>
                <td>Edinburgh</td>
                <td>61</td>
                <td>2011/04/25</td>
                <td>$320,800</td>
            </tr>
            <tr>
                <td>Garrett Winters</td>
                <td>Accountant</td>
                <td>Tokyo</td>
                <td>63</td>
                <td>2011/07/25</td>
                <td>$170,750</td>
            </tr>
            <tr>
                <td>Ashton Cox</td>
                <td>Junior Technical Author</td>
                <td>San Francisco</td>
                <td>66</td>
                <td>2009/01/12</td>
                <td>$86,000</td>
            </tr>
            <tr>
                <td>Cedric Kelly</td>
                <td>Senior Javascript Developer</td>
                <td>Edinburgh</td>
                <td>22</td>
                <td>2012/03/29</td>
                <td>$433,060</td>
            </tr>
            <tr>
                <td>Airi Satou</td>
                <td>Accountant</td>
                <td>Tokyo</td>
                <td>33</td>
                <td>2008/11/28</td>
                <td>$162,700</td>
            </tr>
            <tr>
                <td>Brielle Williamson</td>
                <td>Integration Specialist</td>
                <td>New York</td>
                <td>61</td>
                <td>2012/12/02</td>
                <td>$372,000</td>
            </tr>
            <tr>
                <td>Herrod Chandler</td>
                <td>Sales Assistant</td>
                <td>San Francisco</td>
                <td>59</td>
                <td>2012/08/06</td>
                <td>$137,500</td>
            </tr>
            <tr>
                <td>Rhona Davidson</td>
                <td>Integration Specialist</td>
                <td>Tokyo</td>
                <td>55</td>
                <td>2010/10/14</td>
                <td>$327,900</td>
            </tr>
            <tr>
                <td>Colleen Hurst</td>
                <td>Javascript Developer</td>
                <td>San Francisco</td>
                <td>39</td>
                <td>2009/09/15</td>
                <td>$205,500</td>
            </tr>
            <tr>
                <td>Sonya Frost</td>
                <td>Software Engineer</td>
                <td>Edinburgh</td>
                <td>23</td>
                <td>2008/12/13</td>
                <td>$103,600</td>
            </tr>
            <tr>
                <td>Jena Gaines</td>
                <td>Office Manager</td>
                <td>London</td>
                <td>30</td>
                <td>2008/12/19</td>
                <td>$90,560</td>
            </tr>
            <tr>
                <td>Quinn Flynn</td>
                <td>Support Lead</td>
                <td>Edinburgh</td>
                <td>22</td>
                <td>2013/03/03</td>
                <td>$342,000</td>
            </tr>
            <tr>
                <td>Charde Marshall</td>
                <td>Regional Director</td>
                <td>San Francisco</td>
                <td>36</td>
                <td>2008/10/16</td>
                <td>$470,600</td>
            </tr>
            <tr>
                <td>Haley Kennedy</td>
                <td>Senior Marketing Designer</td>
                <td>London</td>
                <td>43</td>
                <td>2012/12/18</td>
                <td>$313,500</td>
            </tr>
            <tr>
                <td>Tatyana Fitzpatrick</td>
                <td>Regional Director</td>
                <td>London</td>
                <td>19</td>
                <td>2010/03/17</td>
                <td>$385,750</td>
            </tr>
            <tr>
                <td>Michael Silva</td>
                <td>Marketing Designer</td>
                <td>London</td>
                <td>66</td>
                <td>2012/11/27</td>
                <td>$198,500</td>
            </tr>
            <tr>
                <td>Paul Byrd</td>
                <td>Chief Financial Officer (CFO)</td>
                <td>New York</td>
                <td>64</td>
                <td>2010/06/09</td>
                <td>$725,000</td>
            </tr>
            <tr>
                <td>Gloria Little</td>
                <td>Systems Administrator</td>
                <td>New York</td>
                <td>59</td>
                <td>2009/04/10</td>
                <td>$237,500</td>
            </tr>
            <tr>
                <td>Bradley Greer</td>
                <td>Software Engineer</td>
                <td>London</td>
                <td>41</td>
                <td>2012/10/13</td>
                <td>$132,000</td>
            </tr>
            <tr>
                <td>Dai Rios</td>
                <td>Personnel Lead</td>
                <td>Edinburgh</td>
                <td>35</td>
                <td>2012/09/26</td>
                <td>$217,500</td>
            </tr>
            <tr>
                <td>Jenette Caldwell</td>
                <td>Development Lead</td>
                <td>New York</td>
                <td>30</td>
                <td>2011/09/03</td>
                <td>$345,000</td>
            </tr>
            <tr>
                <td>Yuri Berry</td>
                <td>Chief Marketing Officer (CMO)</td>
                <td>New York</td>
                <td>40</td>
                <td>2009/06/25</td>
                <td>$675,000</td>
            </tr>
            <tr>
                <td>Caesar Vance</td>
                <td>Pre-Sales Support</td>
                <td>New York</td>
                <td>21</td>
                <td>2011/12/12</td>
                <td>$106,450</td>
            </tr>
            <tr>
                <td>Doris Wilder</td>
                <td>Sales Assistant</td>
                <td>Sidney</td>
                <td>23</td>
                <td>2010/09/20</td>
                <td>$85,600</td>
            </tr>
            <tr>
                <td>Angelica Ramos</td>
                <td>Chief Executive Officer (CEO)</td>
                <td>London</td>
                <td>47</td>
                <td>2009/10/09</td>
                <td>$1,200,000</td>
            </tr>
            <tr>
                <td>Gavin Joyce</td>
                <td>Developer</td>
                <td>Edinburgh</td>
                <td>42</td>
                <td>2010/12/22</td>
                <td>$92,575</td>
            </tr>
            <tr>
                <td>Jennifer Chang</td>
                <td>Regional Director</td>
                <td>Singapore</td>
                <td>28</td>
                <td>2010/11/14</td>
                <td>$357,650</td>
            </tr>
            <tr>
                <td>Brenden Wagner</td>
                <td>Software Engineer</td>
                <td>San Francisco</td>
                <td>28</td>
                <td>2011/06/07</td>
                <td>$206,850</td>
            </tr>
            <tr>
                <td>Fiona Green</td>
                <td>Chief Operating Officer (COO)</td>
                <td>San Francisco</td>
                <td>48</td>
                <td>2010/03/11</td>
                <td>$850,000</td>
            </tr>
            <tr>
                <td>Shou Itou</td>
                <td>Regional Marketing</td>
                <td>Tokyo</td>
                <td>20</td>
                <td>2011/08/14</td>
                <td>$163,000</td>
            </tr>
            <tr>
                <td>Michelle House</td>
                <td>Integration Specialist</td>
                <td>Sidney</td>
                <td>37</td>
                <td>2011/06/02</td>
                <td>$95,400</td>
            </tr>
            <tr>
                <td>Suki Burks</td>
                <td>Developer</td>
                <td>London</td>
                <td>53</td>
                <td>2009/10/22</td>
                <td>$114,500</td>
            </tr>
            <tr>
                <td>Prescott Bartlett</td>
                <td>Technical Author</td>
                <td>London</td>
                <td>27</td>
                <td>2011/05/07</td>
                <td>$145,000</td>
            </tr>
            <tr>
                <td>Gavin Cortez</td>
                <td>Team Leader</td>
                <td>San Francisco</td>
                <td>22</td>
                <td>2008/10/26</td>
                <td>$235,500</td>
            </tr>
            <tr>
                <td>Martena Mccray</td>
                <td>Post-Sales support</td>
                <td>Edinburgh</td>
                <td>46</td>
                <td>2011/03/09</td>
                <td>$324,050</td>
            </tr>
            <tr>
                <td>Unity Butler</td>
                <td>Marketing Designer</td>
                <td>San Francisco</td>
                <td>47</td>
                <td>2009/12/09</td>
                <td>$85,675</td>
            </tr>
            <tr>
                <td>Howard Hatfield</td>
                <td>Office Manager</td>
                <td>San Francisco</td>
                <td>51</td>
                <td>2008/12/16</td>
                <td>$164,500</td>
            </tr>
            <tr>
                <td>Hope Fuentes</td>
                <td>Secretary</td>
                <td>San Francisco</td>
                <td>41</td>
                <td>2010/02/12</td>
                <td>$109,850</td>
            </tr>
            <tr>
                <td>Vivian Harrell</td>
                <td>Financial Controller</td>
                <td>San Francisco</td>
                <td>62</td>
                <td>2009/02/14</td>
                <td>$452,500</td>
            </tr>
            <tr>
                <td>Timothy Mooney</td>
                <td>Office Manager</td>
                <td>London</td>
                <td>37</td>
                <td>2008/12/11</td>
                <td>$136,200</td>
            </tr>
            <tr>
                <td>Jackson Bradshaw</td>
                <td>Director</td>
                <td>New York</td>
                <td>65</td>
                <td>2008/09/26</td>
                <td>$645,750</td>
            </tr>
            <tr>
                <td>Olivia Liang</td>
                <td>Support Engineer</td>
                <td>Singapore</td>
                <td>64</td>
                <td>2011/02/03</td>
                <td>$234,500</td>
            </tr>
            <tr>
                <td>Bruno Nash</td>
                <td>Software Engineer</td>
                <td>London</td>
                <td>38</td>
                <td>2011/05/03</td>
                <td>$163,500</td>
            </tr>
            <tr>
                <td>Sakura Yamamoto</td>
                <td>Support Engineer</td>
                <td>Tokyo</td>
                <td>37</td>
                <td>2009/08/19</td>
                <td>$139,575</td>
            </tr>
            <tr>
                <td>Thor Walton</td>
                <td>Developer</td>
                <td>New York</td>
                <td>61</td>
                <td>2013/08/11</td>
                <td>$98,540</td>
            </tr>
            <tr>
                <td>Finn Camacho</td>
                <td>Support Engineer</td>
                <td>San Francisco</td>
                <td>47</td>
                <td>2009/07/07</td>
                <td>$87,500</td>
            </tr>
            <tr>
                <td>Serge Baldwin</td>
                <td>Data Coordinator</td>
                <td>Singapore</td>
                <td>64</td>
                <td>2012/04/09</td>
                <td>$138,575</td>
            </tr>
            <tr>
                <td>Zenaida Frank</td>
                <td>Software Engineer</td>
                <td>New York</td>
                <td>63</td>
                <td>2010/01/04</td>
                <td>$125,250</td>
            </tr>
            <tr>
                <td>Zorita Serrano</td>
                <td>Software Engineer</td>
                <td>San Francisco</td>
                <td>56</td>
                <td>2012/06/01</td>
                <td>$115,000</td>
            </tr>
            <tr>
                <td>Jennifer Acosta</td>
                <td>Junior Javascript Developer</td>
                <td>Edinburgh</td>
                <td>43</td>
                <td>2013/02/01</td>
                <td>$75,650</td>
            </tr>
            <tr>
                <td>Cara Stevens</td>
                <td>Sales Assistant</td>
                <td>New York</td>
                <td>46</td>
                <td>2011/12/06</td>
                <td>$145,600</td>
            </tr>
            <tr>
                <td>Hermione Butler</td>
                <td>Regional Director</td>
                <td>London</td>
                <td>47</td>
                <td>2011/03/21</td>
                <td>$356,250</td>
            </tr>
            <tr>
                <td>Lael Greer</td>
                <td>Systems Administrator</td>
                <td>London</td>
                <td>21</td>
                <td>2009/02/27</td>
                <td>$103,500</td>
            </tr>
            <tr>
                <td>Jonas Alexander</td>
                <td>Developer</td>
                <td>San Francisco</td>
                <td>30</td>
                <td>2010/07/14</td>
                <td>$86,500</td>
            </tr>
            <tr>
                <td>Shad Decker</td>
                <td>Regional Director</td>
                <td>Edinburgh</td>
                <td>51</td>
                <td>2008/11/13</td>
                <td>$183,000</td>
            </tr>
            <tr>
                <td>Michael Bruce</td>
                <td>Javascript Developer</td>
                <td>Singapore</td>
                <td>29</td>
                <td>2011/06/27</td>
                <td>$183,000</td>
            </tr>
            <tr>
                <td>Donna Snider</td>
                <td>Customer Support</td>
                <td>New York</td>
                <td>27</td>
                <td>2011/01/25</td>
                <td>$112,000</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Age</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
        </tfoot>
    </table>                    
                </div>
            </div>
        </div>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="<?= base_url('assets/library/jquery/3.3.1/jquery.min.js') ?>"></script>
        <script src="<?= base_url('assets/library/popper/1.14.6/popper.min.js') ?>"></script>
        <script src="<?= base_url('assets/library/bootstrap/4.2.1/js/bootstrap.min.js') ?>"></script>
        <!-- Bootstrap Scroller JS -->
        <script src="<?= base_url('assets/library/scroller/3.1.5/js/jquery.mCustomScrollbar.concat.min.js') ?>"></script>
        <!-- JSTree JS -->
        <script src="<?= base_url('assets/library/jstree/jstree.min.js') ?>"></script>
        <!-- Datatables JS -->
        <script src="<?= base_url('assets/library/datatables/datatables.min.js') ?>"></script>
        <!-- Billboard JS -->
        <script src="https://d3js.org/d3.v5.min.js"></script>
        <script src="<?= base_url('assets/library/billboard/billboard.min.js') ?>"></script>
        <?= asset_js('plantilla/sidebar.js') ?>
        <?= asset_js('script.js') ?>
    </body>
</html>